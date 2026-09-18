<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignupRequest;
use App\Models\MemberType;
use App\Services\MemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SignupController extends Controller
{
    public function create(): View
    {
        return view('signup.create', ['memberTypes' => MemberType::orderBy('name')->get()]);
    }

    public function store(SignupRequest $request, MemberService $members): RedirectResponse
    {
        $members->signUp($request->validated());

        return redirect()->route('home')->with('status', 'Bedankt voor je aanmelding! De administratie verwerkt je aanmelding zo snel mogelijk.');
    }
}
