<?php

use App\Models\User;

it('redirects guests away from every administration page', function (string $url) {
    $this->get($url)->assertRedirect(route('login'));
})->with([
    'dashboard' => '/dashboard',
    'leden' => '/leden',
    'lid toevoegen' => '/leden/create',
    'lidsoorten' => '/lidsoorten',
    'kweeknummers' => '/kweeknummers',
    'facturen' => '/facturen',
]);

it('does not let guests change data', function () {
    $this->post('/leden', [])->assertRedirect(route('login'));
    $this->post('/kweeknummers', [])->assertRedirect(route('login'));
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create(['password' => 'geheim-wachtwoord']);

    $this->post(route('login.store'), ['email' => $user->email, 'password' => 'geheim-wachtwoord'])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejects a wrong password', function () {
    $user = User::factory()->create();

    $this->from(route('login'))
        ->post(route('login.store'), ['email' => $user->email, 'password' => 'fout'])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs out', function () {
    $this->actingAs(User::factory()->create())
        ->post(route('logout'))
        ->assertRedirect(route('home'));

    $this->assertGuest();
});

it('stores passwords hashed', function () {
    $user = User::factory()->create(['password' => 'geheim-wachtwoord']);

    expect($user->password)->not->toBe('geheim-wachtwoord')
        ->and(Hash::check('geheim-wachtwoord', $user->password))->toBeTrue();
});
