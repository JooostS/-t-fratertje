<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Mail\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('contact');
    }

    public function store(ContactRequest $request): RedirectResponse
    {
        Mail::to(config('club.email'))->send(new ContactMessage(...$request->validated()));

        return redirect()->route('contact.create')->with('status', 'Bedankt voor je bericht! We reageren zo snel mogelijk.');
    }
}
