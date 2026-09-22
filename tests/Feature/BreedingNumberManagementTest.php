<?php

use App\Models\BreedingNumber;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\User;
use Database\Seeders\MemberTypeSeeder;

beforeEach(function () {
    $this->seed(MemberTypeSeeder::class);
    $this->actingAs(User::factory()->create());
});

function memberWithoutNumber(string $slug = MemberType::ADULT, array $state = []): Member
{
    return Member::factory()->create(['member_type_id' => MemberType::where('slug', $slug)->value('id')] + $state);
}

it('registers a breeding number for an active member', function () {
    $member = memberWithoutNumber();

    $this->post(route('breeding-numbers.store'), ['member_id' => $member->id, 'breeding_number' => '1tky', 'issue_year' => 2021])
        ->assertRedirect(route('breeding-numbers.index'));

    expect($member->fresh()->nbvv_number)->toBe('1TKY');
});

it('refuses a duplicate breeding number', function () {
    BreedingNumber::factory()->create(['breeding_number' => 'FR75']);
    $member = memberWithoutNumber();

    $this->post(route('breeding-numbers.store'), ['member_id' => $member->id, 'breeding_number' => 'FR75', 'issue_year' => 2021])
        ->assertSessionHasErrors('breeding_number');
});

it('refuses a breeding number that is not exactly 4 letters and digits', function () {
    $member = memberWithoutNumber();

    $this->post(route('breeding-numbers.store'), ['member_id' => $member->id, 'breeding_number' => 'FR7', 'issue_year' => 2021])
        ->assertSessionHasErrors('breeding_number');
    $this->post(route('breeding-numbers.store'), ['member_id' => $member->id, 'breeding_number' => 'FR755', 'issue_year' => 2021])
        ->assertSessionHasErrors('breeding_number');
});

it('gives a member at most one breeding number', function () {
    $member = memberWithoutNumber();
    BreedingNumber::factory()->create(['member_id' => $member->id]);

    $this->post(route('breeding-numbers.store'), ['member_id' => $member->id, 'breeding_number' => 'FR99', 'issue_year' => 2021])
        ->assertSessionHasErrors('member_id');
});

it('refuses to link a breeding number to a guest or inactive member', function () {
    $guest = memberWithoutNumber(MemberType::GUEST);
    $inactive = memberWithoutNumber(state: ['is_active' => false]);

    $this->post(route('breeding-numbers.store'), ['member_id' => $guest->id, 'breeding_number' => 'FR01', 'issue_year' => 2021])
        ->assertSessionHasErrors('member_id');
    $this->post(route('breeding-numbers.store'), ['member_id' => $inactive->id, 'breeding_number' => 'FR02', 'issue_year' => 2021])
        ->assertSessionHasErrors('member_id');
});

it('updates a breeding number and keeps it unique', function () {
    $mine = BreedingNumber::factory()->create(['breeding_number' => 'FR01']);
    BreedingNumber::factory()->create(['breeding_number' => 'FR02']);

    $this->put(route('breeding-numbers.update', $mine), ['breeding_number' => 'FR01', 'issue_year' => 2019])
        ->assertSessionHasNoErrors();
    $this->put(route('breeding-numbers.update', $mine), ['breeding_number' => 'FR02', 'issue_year' => 2019])
        ->assertSessionHasErrors('breeding_number');
});

it('does not archive the breeding number of an active member', function () {
    $number = BreedingNumber::factory()->create();

    $this->delete(route('breeding-numbers.destroy', $number))->assertSessionHasErrors('delete');

    expect(BreedingNumber::count())->toBe(1);
});

it('soft deletes the breeding number of an inactive member and restores it from the archive', function () {
    $number = BreedingNumber::factory()->create();
    $number->member->update(['is_active' => false]);

    $this->delete(route('breeding-numbers.destroy', $number))->assertRedirect(route('breeding-numbers.index'));
    expect(BreedingNumber::count())->toBe(0);

    $this->get(route('breeding-numbers.index', ['archived' => 1]))->assertSee($number->breeding_number);

    $this->patch(route('breeding-numbers.restore', $number));
    expect(BreedingNumber::count())->toBe(1);
});

it('manages member types and blocks removing one that is in use', function () {
    $this->post(route('member-types.store'), ['name' => 'Ere-lid', 'description' => 'Voor verdienste', 'is_nbvv_member' => 0, 'amount' => 0])
        ->assertSessionHasNoErrors();

    $honorary = MemberType::where('name', 'Ere-lid')->firstOrFail();
    expect($honorary->contributionRates)->toHaveCount(1);

    $this->post(route('member-types.store'), ['name' => 'Ere-lid', 'amount' => 0])->assertSessionHasErrors('name');

    $this->put(route('member-types.update', $honorary), ['name' => 'Erelid', 'is_nbvv_member' => 0])->assertSessionHasNoErrors();

    $volwassen = MemberType::where('slug', MemberType::ADULT)->firstOrFail();
    Member::factory()->create(['member_type_id' => $volwassen->id]);
    $this->delete(route('member-types.destroy', $volwassen))->assertSessionHasErrors('delete');

    $this->delete(route('member-types.destroy', $honorary));
    expect(MemberType::where('name', 'Erelid')->exists())->toBeFalse();
});
