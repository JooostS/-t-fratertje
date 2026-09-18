<?php

namespace App\Http\Controllers;

use App\Http\Requests\CancellationRequest;
use App\Services\MemberService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CancellationController extends Controller
{
    public function create(): View
    {
        return view('cancellation.create');
    }

    public function store(CancellationRequest $request, MemberService $members): RedirectResponse
    {
        $member = $request->member();
        $members->cancel($member);

        return redirect()->route('home')->with('status', "Je afmelding is verwerkt. Je lidmaatschap eindigt per {$member->membership_ends_on->format('d-m-Y')}.");
    }
}
