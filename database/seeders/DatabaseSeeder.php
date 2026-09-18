<?php

namespace Database\Seeders;

use App\Models\BreedingNumber;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Test-inlogaccount voor de administratie
        User::factory()->create([
            'name' => 'Beheerder',
            'email' => 'admin@fratertje.test',
            'password' => 'password',
            'role' => 'beheerder',
        ]);

        $this->call(MemberTypeSeeder::class);

        $jeugdlid = MemberType::where('slug', MemberType::YOUTH)->first();
        $volwassenLid = MemberType::where('slug', MemberType::ADULT)->first();
        $gastlid = MemberType::where('slug', MemberType::GUEST)->first();

        // Actieve volwassen en jeugdleden krijgen een kweeknummer
        Member::factory()
            ->count(8)
            ->create(['member_type_id' => $volwassenLid->id])
            ->each(fn (Member $member) => BreedingNumber::factory()->create(['member_id' => $member->id]));

        Member::factory()
            ->count(4)
            ->youth()
            ->create(['member_type_id' => $jeugdlid->id])
            ->each(fn (Member $member) => BreedingNumber::factory()->create(['member_id' => $member->id]));

        // Gastleden krijgen bewust geen kweeknummer
        Member::factory()
            ->count(3)
            ->create(['member_type_id' => $gastlid->id]);

        // Eén oud-lid, gearchiveerd (soft-delete), zichtbaar via het archief
        $archived = Member::factory()->inactive()->create(['member_type_id' => $volwassenLid->id]);
        BreedingNumber::factory()->create(['member_id' => $archived->id]);
        $archived->delete();

        // Eén aanmelding die nog in quarantaine staat, voor demodoeleinden
        $quarantined = Member::factory()->quarantine()->create(['member_type_id' => $volwassenLid->id]);
        BreedingNumber::factory()->create(['member_id' => $quarantined->id]);

        // Contributiefactuur van het lopende jaar voor alle actieve leden
        Member::with('memberType')
            ->withStatus(Member::STATUS_ACTIVE)
            ->get()
            ->each(fn (Member $member) => Invoice::issueContribution($member, now()->year, 12));
    }
}
