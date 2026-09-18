<?php

namespace App\Http\Controllers;

use App\Helpers\ContributionCalculator;
use App\Models\MemberType;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home', ['memberTypes' => MemberType::orderBy('id')->get()]);
    }

    public function info(): View
    {
        return view('info', [
            'memberTypes' => MemberType::orderBy('id')->get(),
            'examples' => $this->registrationExamples(),
        ]);
    }

    /**
     * Rekenvoorbeelden uit de opdracht, met dezelfde helper als de echte facturering.
     *
     * @return array<int, array{requested: CarbonImmutable, start: CarbonImmutable, months: int}>
     */
    private function registrationExamples(): array
    {
        return collect([[3, 5], [3, 25]])
            ->map(function (array $monthAndDay) {
                $requested = CarbonImmutable::create(now()->year, ...$monthAndDay);
                $start = ContributionCalculator::membershipStart($requested);

                return [
                    'requested' => $requested,
                    'start' => $start,
                    'months' => ContributionCalculator::monthsLeftInYear($start),
                ];
            })
            ->all();
    }
}
