<?php

use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\User;
use Database\Seeders\MemberTypeSeeder;

beforeEach(function () {
    $this->seed(MemberTypeSeeder::class);
    $this->actingAs(User::factory()->create());
});

it('filters invoices by member name and type, and downloads a pdf', function () {
    $this->post(route('members.store'), memberPayload())->assertSessionHasNoErrors();
    $member = Member::firstOrFail();
    $contribution = Invoice::issueContribution($member, 2026, 12);
    Invoice::create(['member_id' => $member->id, 'type' => Invoice::REFUND, 'year' => 2026, 'months' => 3, 'annual_amount' => 10, 'amount' => -2.5, 'issued_on' => today()]);

    $this->get(route('invoices.index', ['type' => 'refund']))->assertOk()->assertDontSee('Contributie</td>', false);
    $this->get(route('invoices.index', ['q' => 'Onbekendnaam']))->assertOk()->assertSee('Nog geen facturen.');

    $this->get(route('invoices.pdf', $contribution))
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});
