<?php

use App\Models\BreedingNumber;
use App\Models\ContributionRate;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\User;
use Database\Seeders\MemberTypeSeeder;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->seed(MemberTypeSeeder::class);
});

function signupPayload(array $overrides = []): array
{
    return array_merge(memberPayload(), ['agreement' => 1], $overrides);
}

/**
 * De administratie kent, net als in het echt, pas na de aanmelding een kweeknummer toe.
 */
function giveBreedingNumber(Member $member): void
{
    if ($member->memberType->is_nbvv_member) {
        BreedingNumber::create(['member_id' => $member->id, 'breeding_number' => '1TKY', 'issue_year' => 2020]);
    }
}

it('puts a sign-up in quarantine and alerts the administration', function () {
    Mail::fake();
    User::factory()->create();

    $this->post(route('signup.store'), signupPayload())->assertRedirect(route('home'));

    $member = Member::firstOrFail();
    expect($member->is_quarantine)->toBeTrue()
        ->and($member->is_active)->toBeFalse()
        ->and(Invoice::count())->toBe(0);

    Mail::assertSentCount(1);
});

it('requires the signature checkbox on the sign-up form', function () {
    $this->post(route('signup.store'), signupPayload(['agreement' => null]))->assertSessionHasErrors('agreement');

    expect(Member::count())->toBe(0);
});

it('does not require a breeding number on the public sign-up form, and blocks approval until one is added', function () {
    Mail::fake();
    $this->post(route('signup.store'), signupPayload(['breeding_number' => null, 'issue_year' => null]))
        ->assertSessionHasNoErrors();

    $member = Member::firstOrFail();
    expect($member->breedingNumber)->toBeNull();

    $admin = User::factory()->create();
    $this->actingAs($admin)
        ->patch(route('members.approve', $member))
        ->assertSessionHasErrors('approve');

    expect($member->refresh()->is_quarantine)->toBeTrue();

    $this->actingAs($admin)->put(route('members.update', $member), memberPayload(overrides: [
        'member_type_id' => $member->member_type_id,
    ]));

    $this->actingAs($admin)->patch(route('members.approve', $member))->assertSessionHasNoErrors();

    expect($member->refresh()->is_active)->toBeTrue()
        ->and($member->breedingNumber)->not->toBeNull();
});

it('flags a quarantined sign-up without a breeding number on the dashboard', function () {
    $this->post(route('signup.store'), signupPayload(['breeding_number' => null, 'issue_year' => null]));
    $member = Member::firstOrFail();
    $admin = User::factory()->create();

    $this->actingAs($admin)->get(route('dashboard'))
        ->assertSee('Ontbreekt nog')
        ->assertSee('Eerst kweeknummer toevoegen')
        ->assertDontSee('Goedkeuren');

    giveBreedingNumber($member);

    $this->actingAs($admin)->get(route('dashboard'))
        ->assertSee('1TKY')
        ->assertSee('Goedkeuren');
});

it('invoices pro rata for the remaining months when a sign-up is approved', function () {
    $this->travelTo('2026-03-05');
    $this->post(route('signup.store'), signupPayload());
    $member = Member::firstOrFail();
    giveBreedingNumber($member);

    $this->actingAs(User::factory()->create())
        ->patch(route('members.approve', $member))
        ->assertRedirect();

    $member->refresh();
    $invoice = $member->invoices()->sole();

    expect($member->is_active)->toBeTrue()
        ->and($member->is_quarantine)->toBeFalse()
        ->and($member->membership_starts_on->toDateString())->toBe('2026-04-01')
        ->and($invoice->months)->toBe(9)
        ->and((float) $invoice->amount)->toBe(27.00);
});

it('charges the youth tariff in the year a member turns 18, and the adult tariff after that', function (string $birthDate, float $expectedAnnualAmount) {
    $this->travelTo('2026-03-05');
    $this->post(route('signup.store'), signupPayload([
        'member_type_id' => MemberType::where('slug', $birthDate === '2008-12-01' ? MemberType::YOUTH : MemberType::ADULT)->value('id'),
        'birth_date' => $birthDate,
    ]))->assertSessionHasNoErrors();

    $member = Member::firstOrFail();
    giveBreedingNumber($member);
    $this->actingAs(User::factory()->create())->patch(route('members.approve', $member));

    expect((float) Invoice::sole()->annual_amount)->toBe($expectedAnnualAmount);
})->with([
    'jeugdlid dat dit jaar 18 wordt' => ['2008-12-01', 18.00],
    'volwassene die op 1 januari 18 werd' => ['2008-01-01', 18.00],
    'volwassene die vorig jaar 18 werd' => ['2007-06-01', 36.00],
]);

it('refunds the remaining months when a member cancels', function () {
    $this->travelTo('2026-03-05');
    $this->post(route('signup.store'), signupPayload());
    $member = Member::firstOrFail();
    giveBreedingNumber($member);
    $this->actingAs(User::factory()->create())->patch(route('members.approve', $member));
    $this->app['auth']->logout();

    $this->travelTo('2026-06-10');
    $this->post(route('cancellation.store'), [
        'email' => 'anna@example.test',
        'birth_date' => '1985-04-12',
        'agreement' => 1,
    ])->assertRedirect(route('home'));

    $member = Member::firstOrFail();
    $refund = $member->invoices()->where('type', Invoice::REFUND)->sole();

    expect($member->is_active)->toBeFalse()
        ->and($member->membership_ends_on->toDateString())->toBe('2026-07-01')
        ->and($refund->months)->toBe(6)
        ->and((float) $refund->amount)->toBe(-18.00);
});

it('does not cancel a member with unknown details', function () {
    Member::factory()->create(['email' => 'anna@example.test', 'birth_date' => '1985-04-12']);

    $this->post(route('cancellation.store'), [
        'email' => 'anna@example.test',
        'birth_date' => '1990-01-01',
        'agreement' => 1,
    ])->assertSessionHasErrors('email');

    expect(Member::firstOrFail()->is_active)->toBeTrue();
});

it('only accepts price changes for a coming year and leaves the current year untouched', function () {
    $this->actingAs(User::factory()->create());
    $adult = MemberType::where('slug', MemberType::ADULT)->firstOrFail();
    $year = now()->year;

    $this->post(route('contribution-rates.store', $adult), ['valid_from_year' => $year, 'amount' => 50])
        ->assertSessionHasErrors('valid_from_year');

    $this->post(route('contribution-rates.store', $adult), ['valid_from_year' => $year + 1, 'amount' => 40])
        ->assertSessionHasNoErrors();

    expect(ContributionRate::annualAmountFor(MemberType::ADULT, $year))->toBe(36.00)
        ->and(ContributionRate::annualAmountFor(MemberType::ADULT, $year + 1))->toBe(40.00);
});

it('generates yearly invoices only for active members without an invoice for that year', function () {
    $adult = MemberType::where('slug', MemberType::ADULT)->firstOrFail();
    $withInvoice = Member::factory()->create(['member_type_id' => $adult->id, 'membership_starts_on' => '2024-01-01']);
    Invoice::issueContribution($withInvoice, 2026, 12);
    Member::factory()->create(['member_type_id' => $adult->id, 'membership_starts_on' => '2024-01-01']);
    Member::factory()->inactive()->create(['member_type_id' => $adult->id]);

    $this->artisan('invoices:generate', ['year' => 2026])->assertSuccessful();

    expect(Invoice::where('year', 2026)->count())->toBe(2);
});

it('shows a yearly totals overview on the invoices page', function () {
    $adult = MemberType::where('slug', MemberType::ADULT)->firstOrFail();
    $member = Member::factory()->create(['member_type_id' => $adult->id]);
    Invoice::issueContribution($member, 2025, 12);
    Invoice::issueContribution($member, 2026, 12);
    $member->invoices()->create([
        'type' => Invoice::REFUND, 'year' => 2026, 'months' => 3,
        'annual_amount' => 36, 'amount' => -9.00, 'issued_on' => today(),
    ]);

    $this->actingAs(User::factory()->create())->get(route('invoices.index'))
        ->assertOk()
        ->assertSeeInOrder(['2026', '€ 36,00', '€ -9,00', '€ 27,00'])
        ->assertSee('2025');
});

it('promotes a youth member to adult once they turn 18, and leaves the rest alone', function () {
    $this->travelTo('2026-06-15');

    $justTurned18 = Member::factory()->create(['member_type_id' => MemberType::where('slug', MemberType::YOUTH)->value('id'), 'birth_date' => '2008-06-15']);
    $stillYouth = Member::factory()->youth()->create(['member_type_id' => MemberType::where('slug', MemberType::YOUTH)->value('id'), 'birth_date' => '2010-01-01']);
    $alreadyAdult = Member::factory()->create(['member_type_id' => MemberType::where('slug', MemberType::ADULT)->value('id'), 'birth_date' => '1985-04-12']);

    $this->artisan('members:promote-adults')->assertSuccessful();

    expect($justTurned18->refresh()->memberType->slug)->toBe(MemberType::ADULT)
        ->and($stillYouth->refresh()->memberType->slug)->toBe(MemberType::YOUTH)
        ->and($alreadyAdult->refresh()->memberType->slug)->toBe(MemberType::ADULT);
});
