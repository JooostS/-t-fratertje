<?php

use App\Mail\ContactMessage;
use Database\Seeders\MemberTypeSeeder;
use Illuminate\Support\Facades\Mail;

beforeEach(function () {
    $this->seed(MemberTypeSeeder::class);
});

it('shows the home page with birds, current tariffs and links', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Vogels houden doe je beter samen.')
        ->assertSee('images/birds/putter.jpg', false)
        ->assertSee('€ 36,00')
        ->assertSee(route('signup.create'), false);
});

it('shows the information page with the calculated examples', function () {
    $this->get(route('info'))
        ->assertOk()
        ->assertSee('NBvV en kweeknummer')
        ->assertSee('9 maanden')
        ->assertSee('8 maanden');
});

it('credits every photo in the footer', function () {
    $this->get(route('home'))
        ->assertSee('Fotoverantwoording')
        ->assertSee('MinoZig')
        ->assertSee('Mounir Neddi');
});

it('only shows phone and address when they are configured', function () {
    config(['club.phone' => null, 'club.address' => null]);
    $this->get(route('contact.create'))->assertDontSee('Telefoon');

    config(['club.phone' => '030 123 4567']);
    $this->get(route('contact.create'))->assertSee('030 123 4567');
});

it('sends a contact message to the club with the visitor as reply-to', function () {
    Mail::fake();

    $this->post(route('contact.store'), [
        'name' => 'Els Vogel',
        'email' => 'els@example.test',
        'message' => 'Kan ik een keer komen kijken bij een bijeenkomst?',
    ])->assertRedirect(route('contact.create'));

    Mail::assertSent(ContactMessage::class, fn (ContactMessage $mail) => $mail->hasTo(config('club.email'))
        && $mail->hasReplyTo('els@example.test'));
});

it('validates the contact form', function () {
    Mail::fake();

    $this->post(route('contact.store'), ['name' => '', 'email' => 'geen-email', 'message' => 'kort'])
        ->assertSessionHasErrors(['name', 'email', 'message']);

    Mail::assertNothingSent();
});
