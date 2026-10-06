<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberType;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Overzicht: aantal actieve en gearchiveerde leden, leden per lidsoort en aanmeldingen die op goedkeuring wachten.
     */
    public function __invoke(): View
    {
        return view('dashboard', [
            'activeCount' => Member::withStatus(Member::STATUS_ACTIVE)->count(),
            'archivedCount' => Member::onlyTrashed()->count(),
            'memberTypes' => MemberType::withCount(['members' => fn ($query) => $query->withStatus(Member::STATUS_ACTIVE)])->get(),
            'quarantineMembers' => Member::with(['memberType', 'breedingNumber'])->withStatus(Member::STATUS_QUARANTINE)->latest()->get(),
        ]);
    }
}
