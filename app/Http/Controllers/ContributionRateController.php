<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContributionRateRequest;
use App\Models\MemberType;
use Illuminate\Http\RedirectResponse;

class ContributionRateController extends Controller
{
    /**
     * Legt een nieuw jaartarief vast; bestaat er al een tarief voor dat jaar, dan wordt het bedrag overschreven.
     */
    public function store(ContributionRateRequest $request, MemberType $memberType): RedirectResponse
    {
        $memberType->contributionRates()->updateOrCreate(
            ['valid_from_year' => $request->validated('valid_from_year')],
            ['amount' => $request->validated('amount')],
        );

        return back()->with('status', 'Nieuw tarief is vastgelegd.');
    }
}
