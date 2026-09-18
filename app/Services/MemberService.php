<?php

namespace App\Services;

use App\Events\MemberActivated;
use App\Events\MemberCancelled;
use App\Events\MemberSignedUp;
use App\Helpers\ContributionCalculator;
use App\Models\Address;
use App\Models\BreedingNumber;
use App\Models\Member;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

/**
 * Bewaart lid, adres en kweeknummer altijd samen in één transactie.
 */
class MemberService
{
    private const ADDRESS_FIELDS = ['street', 'house_number', 'house_number_addition', 'postal_code', 'city'];

    private const MEMBER_FIELDS = ['member_type_id', 'first_name', 'last_name', 'email', 'birth_date'];

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Member
    {
        $member = $this->store($data, quarantine: false);

        if ($member->is_active) {
            MemberActivated::dispatch($member);
        }

        return $member;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function signUp(array $data): Member
    {
        $member = $this->store($data, quarantine: true);

        MemberSignedUp::dispatch($member);

        return $member;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Member $member, array $data): Member
    {
        return DB::transaction(function () use ($member, $data) {
            $member->address->update(Arr::only($data, self::ADDRESS_FIELDS));
            // Een lid in quarantaine wordt alleen via "goedkeuren" actief.
            $member->update(Arr::only($data, self::MEMBER_FIELDS) + [
                'is_active' => ! $member->is_quarantine && ($data['is_active'] ?? $member->is_active),
            ]);
            $member->load('memberType');
            $this->syncBreedingNumber($member, $data);

            return $member;
        });
    }

    public function approve(Member $member): void
    {
        $member->update(['is_quarantine' => false, 'is_active' => true]);

        MemberActivated::dispatch($member);
    }

    public function cancel(Member $member): void
    {
        $member->update([
            'is_active' => false,
            'cancelled_at' => now(),
            'membership_ends_on' => ContributionCalculator::membershipStart(now()),
        ]);

        MemberCancelled::dispatch($member);
    }

    /**
     * Ook het adres wordt niet definitief verwijderd; het kweeknummer blijft gereserveerd.
     */
    public function archive(Member $member): void
    {
        $member->update(['is_active' => false]);
        $member->delete();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function store(array $data, bool $quarantine): Member
    {
        return DB::transaction(function () use ($data, $quarantine) {
            $address = Address::create(Arr::only($data, self::ADDRESS_FIELDS));

            $member = Member::create(Arr::only($data, self::MEMBER_FIELDS) + [
                'address_id' => $address->id,
                'registered_at' => today(),
                'is_quarantine' => $quarantine,
                'is_active' => ! $quarantine && ($data['is_active'] ?? true),
            ]);

            $member->load('memberType');
            $this->syncBreedingNumber($member, $data);

            return $member;
        });
    }

    /**
     * Een NBvV-lid heeft precies één kweeknummer; een gastlid heeft er geen.
     *
     * @param  array<string, mixed>  $data
     */
    private function syncBreedingNumber(Member $member, array $data): void
    {
        $existing = BreedingNumber::withTrashed()->where('member_id', $member->id)->first();

        if (! $member->memberType->is_nbvv_member) {
            $existing?->delete();

            return;
        }

        $number = $existing ?? new BreedingNumber(['member_id' => $member->id]);
        $number->fill(Arr::only($data, ['breeding_number', 'issue_year']))->save();

        if ($number->trashed()) {
            $number->restore();
        }

        $member->unsetRelation('breedingNumber');
    }
}
