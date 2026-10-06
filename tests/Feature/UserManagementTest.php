<?php

use App\Models\User;

it('lets a beheerder add a secretaris who can then log in, and a secretaris cannot manage users', function () {
    $beheerder = User::factory()->create(['role' => User::ROLE_BEHEERDER]);

    $this->actingAs($beheerder)->post(route('users.store'), [
        'name' => 'Piet Penning', 'email' => 'piet@example.nl', 'role' => 'secretaris',
        'password' => 'geheim1234', 'password_confirmation' => 'geheim1234',
    ])->assertRedirect(route('users.index'));

    $piet = User::where('email', 'piet@example.nl')->firstOrFail();
    expect($piet->role)->toBe('secretaris')->and($piet->isBeheerder())->toBeFalse();

    auth()->logout();
    $this->actingAs($piet)->get(route('users.index'))->assertForbidden();
});

it('keeps the password when left empty and protects the own account', function () {
    $beheerder = User::factory()->create(['role' => User::ROLE_BEHEERDER]);
    $other = User::factory()->create(['role' => User::ROLE_SECRETARIS]);
    $hash = $other->password;

    $this->actingAs($beheerder)->put(route('users.update', $other), [
        'name' => 'Nieuwe Naam', 'email' => $other->email, 'role' => 'beheerder', 'password' => '',
    ])->assertSessionHasNoErrors();
    expect($other->fresh()->password)->toBe($hash)->and($other->fresh()->role)->toBe('beheerder');

    $this->put(route('users.update', $beheerder), [
        'name' => $beheerder->name, 'email' => $beheerder->email, 'role' => 'secretaris',
    ])->assertSessionHasErrors('role');

    $this->delete(route('users.destroy', $beheerder))->assertSessionHasErrors('delete');
    $this->delete(route('users.destroy', $other))->assertRedirect(route('users.index'));
    expect(User::count())->toBe(1);
});