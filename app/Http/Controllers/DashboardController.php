<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberType;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'activeCount' => Member::withStatus(Member::STATUS_ACTIVE)->count(),
            'archivedCount' => Member::onlyTrashed()->count(),
            'memberTypes' => MemberType::withCount(['members' => fn ($query) => $query->withStatus(Member::STATUS_ACTIVE)])->get(),
            'quarantineMembers' => Member::with('memberType')->withStatus(Member::STATUS_QUARANTINE)->latest()->get(),
        ]);
    }
}
