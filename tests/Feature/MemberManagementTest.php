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

it('creates a member together with address and breeding number', function () {
    $this->post(route('members.store'), memberPayload())->assertSessionHasNoErrors();

    $member = Member::with(['address', 'breedingNumber'])->firstOrFail();

    expect($member->address->city)->toBe('Utrecht')
        ->and($member->nbvv_number)->toBe('1TKY')
        ->and($member->is_active)->toBeTrue();
});

it('refuses an adult or youth member without breeding number', function (string $slug, string $birthDate) {
    $this->post(route('members.store'), memberPayload($slug, ['birth_date' => $birthDate, 'breeding_number' => null]))
        ->assertSessionHasErrors('breeding_number');

    expect(Member::count())->toBe(0);
})->with([
    'volwassen lid' => [MemberType::ADULT, '1985-04-12'],
    'jeugdlid' => [MemberType::YOUTH, '2015-04-12'],
]);

it('lets a guest member exist without breeding number', function () {
    $this->post(route('members.store'), memberPayload(MemberType::GUEST))->assertSessionHasNoErrors();

    $member = Member::firstOrFail();
    expect($member->nbvv_number)->toBeNull()
        ->and(BreedingNumber::count())->toBe(0);
});

it('refuses a breeding number for a guest member', function () {
    $this->post(route('members.store'), memberPayload(MemberType::GUEST, ['breeding_number' => 'FR01']))
        ->assertSessionHasErrors('breeding_number');
});

it('refuses a duplicate breeding number, also one that is archived', function () {
    $this->post(route('members.store'), memberPayload());
    $this->post(route('members.store'), memberPayload(overrides: ['email' => 'ander@example.test']))
        ->assertSessionHasErrors('breeding_number');

    Member::firstOrFail()->delete();

    $this->post(route('members.store'), memberPayload())->assertSessionHasErrors('breeding_number');
    expect(Member::count())->toBe(0);
});

it('enforces the age limits of the member types', function () {
    $this->post(route('members.store'), memberPayload(MemberType::YOUTH, ['birth_date' => '1985-04-12']))
        ->assertSessionHasErrors('member_type_id');

    $this->post(route('members.store'), memberPayload(MemberType::ADULT, ['birth_date' => now()->subYears(10)->toDateString()]))
        ->assertSessionHasErrors('member_type_id');
});

it('validates required fields, formats and lengths', function () {
    $this->post(route('members.store'), memberPayload(overrides: [
        'first_name' => '',
        'email' => 'geen-email',
        'postal_code' => '12',
        'house_number' => 'abc',
        'birth_date' => now()->addDay()->toDateString(),
        'city' => str_repeat('x', 101),
    ]))->assertSessionHasErrors(['first_name', 'email', 'postal_code', 'house_number', 'birth_date', 'city']);
});

it('normalises the postal code', function () {
    $this->post(route('members.store'), memberPayload(overrides: ['postal_code' => '1234ab']));

    expect(Member::firstOrFail()->address->postal_code)->toBe('1234 AB');
});

it('updates a member and removes the breeding number when switching to guest', function () {
    $this->post(route('members.store'), memberPayload());
    $member = Member::firstOrFail();

    $this->put(route('members.update', $member), memberPayload(MemberType::GUEST, ['first_name' => 'Anneke']))
        ->assertSessionHasNoErrors();

    expect($member->fresh()->first_name)->toBe('Anneke')
        ->and($member->fresh()->breedingNumber)->toBeNull();

    $this->put(route('members.update', $member), memberPayload(MemberType::ADULT))->assertSessionHasNoErrors();

    expect($member->fresh()->nbvv_number)->toBe('1TKY');
});

it('soft deletes a member and shows it in the archive until restored', function () {
    $member = Member::factory()->create(['last_name' => 'Verdwenen']);

    $this->delete(route('members.destroy', $member))->assertRedirect(route('members.index'));

    expect(Member::count())->toBe(0)
        ->and(Member::onlyTrashed()->count())->toBe(1);

    $this->get(route('members.index'))->assertDontSee(route('members.show', $member));
    $this->get(route('members.index', ['archived' => 1]))->assertSee('Verdwenen');
    $this->get(route('members.show', $member))->assertOk()->assertSee('Gearchiveerd');

    $this->patch(route('members.restore', $member));

    expect(Member::count())->toBe(1);
});

it('searches and filters members', function () {
    $adult = MemberType::where('slug', MemberType::ADULT)->first();
    $guest = MemberType::where('slug', MemberType::GUEST)->first();

    $jansen = Member::factory()->create(['member_type_id' => $adult->id, 'first_name' => 'Piet', 'last_name' => 'Jansen']);
    BreedingNumber::factory()->create(['member_id' => $jansen->id, 'breeding_number' => 'ZZ77']);
    Member::factory()->create(['member_type_id' => $guest->id, 'first_name' => 'Klaas', 'last_name' => 'Bakker']);
    Member::factory()->inactive()->create(['member_type_id' => $adult->id, 'first_name' => 'Marie', 'last_name' => 'Smit']);

    $this->get(route('members.index', ['q' => 'Jansen']))->assertSee('Jansen')->assertDontSee('Bakker');
    $this->get(route('members.index', ['q' => 'ZZ77']))->assertSee('Jansen')->assertDontSee('Bakker');
    $this->get(route('members.index', ['member_type_id' => $guest->id]))->assertSee('Bakker')->assertDontSee('Jansen');
    $this->get(route('members.index', ['status' => 'inactive']))->assertSee('Smit')->assertDontSee('Jansen');
    $this->get(route('members.index', ['status' => 'active']))->assertSee('Jansen')->assertDontSee('Smit');
});

it('shows the detail page with all data', function () {
    $this->post(route('members.store'), memberPayload());

    $this->get(route('members.show', Member::firstOrFail()))
        ->assertOk()
        ->assertSee('Vinkenlaan 12 A')
        ->assertSee('1TKY');
});
