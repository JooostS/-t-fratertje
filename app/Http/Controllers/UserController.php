<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Lijst van beheerders en secretarissen.
     */
    public function index(): View
    {
        return view('users.index', ['users' => User::orderBy('name')->get()]);
    }

    /**
     * Formulier voor een nieuwe beheerder of secretaris.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Maakt een gebruiker aan; het wachtwoord wordt door het model gehasht.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('users.index')->with('status', 'Gebruiker is toegevoegd.');
    }

    /**
     * Formulier om naam, e-mail, rol of wachtwoord te wijzigen.
     */
    public function edit(User $user): View
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Wijzigt een gebruiker; een leeg wachtwoord laat het huidige ongemoeid.
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $user->update(array_filter($request->validated(), fn ($value) => $value !== null));

        return redirect()->route('users.index')->with('status', 'Gebruiker is gewijzigd.');
    }

    /**
     * Archiveert een gebruiker (soft delete); jezelf verwijderen is niet toegestaan.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['delete' => 'Je kunt je eigen account niet verwijderen.']);
        }

        $user->delete();

        return redirect()->route('users.index')->with('status', 'Gebruiker is verwijderd.');
    }
}
