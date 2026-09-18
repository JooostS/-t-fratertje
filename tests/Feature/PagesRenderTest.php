<?php

use App\Models\BreedingNumber;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\User;

it('renders every administration page for a seeded database', function () {
    $this->seed();
    $this->actingAs(User::factory()->create());
    $member = Member::firstOrFail();

    foreach ([
        route('dashboard'),
        route('members.index'),
        route('members.create'),
        route('members.show', $member),
        route('members.edit', $member),
        route('member-types.index'),
        route('member-types.create'),
        route('member-types.edit', MemberType::firstOrFail()),
        route('breeding-numbers.index'),
        route('breeding-numbers.create'),
        route('breeding-numbers.edit', BreedingNumber::firstOrFail()),
        route('invoices.index'),
    ] as $url) {
        $this->get($url)->assertOk();
    }
});

it('renders the public pages for guests', function () {
    $this->seed();

    foreach ([route('home'), route('login'), route('signup.create'), route('cancellation.create')] as $url) {
        $this->get($url)->assertOk();
    }
});
