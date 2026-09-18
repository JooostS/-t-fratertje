<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemberTypeRequest;
use App\Models\MemberType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MemberTypeController extends Controller
{
    public function index(): View
    {
        return view('member-types.index', [
            'memberTypes' => MemberType::withCount('members')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('member-types.create');
    }

    public function store(MemberTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $memberType = MemberType::create([
            'slug' => Str::slug($data['name']),
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'is_nbvv_member' => $data['is_nbvv_member'],
        ]);

        $memberType->contributionRates()->create([
            'valid_from_year' => now()->year,
            'amount' => $data['amount'],
        ]);

        return redirect()->route('member-types.edit', $memberType)->with('status', 'Lidsoort is toegevoegd.');
    }

    public function edit(MemberType $memberType): View
    {
        return view('member-types.edit', [
            'memberType' => $memberType,
            'rates' => $memberType->contributionRates()->orderByDesc('valid_from_year')->get(),
        ]);
    }

    public function update(MemberTypeRequest $request, MemberType $memberType): RedirectResponse
    {
        $memberType->update($request->validated());

        return redirect()->route('member-types.index')->with('status', 'Lidsoort is gewijzigd.');
    }

    public function destroy(MemberType $memberType): RedirectResponse
    {
        if ($memberType->members()->withTrashed()->exists()) {
            return back()->withErrors(['delete' => "“{$memberType->name}” heeft nog (gearchiveerde) leden en kan niet worden verwijderd."]);
        }

        $memberType->delete();

        return redirect()->route('member-types.index')->with('status', 'Lidsoort is verwijderd.');
    }
}
