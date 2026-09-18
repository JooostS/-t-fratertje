<?php

namespace App\Listeners;

use App\Events\MemberCancelled;
use App\Helpers\ContributionCalculator;
use App\Models\Invoice;

class CreateRefundInvoice
{
    public function handle(MemberCancelled $event): void
    {
        $member = $event->member;
        $year = $member->cancelled_at->year;
        $end = $member->membership_ends_on;

        $paid = $member->invoices()
            ->where('type', Invoice::CONTRIBUTION)
            ->where('year', $year)
            ->get();

        // Eindigt het lidmaatschap pas volgend jaar, dan valt er in dit jaar niets terug te geven.
        $monthsLeft = $end->year === $year ? ContributionCalculator::monthsLeftInYear($end) : 0;
        $months = min($monthsLeft, (int) $paid->sum('months'));

        if ($months <= 0) {
            return;
        }

        $annualAmount = (float) $paid->last()->annual_amount;

        $member->invoices()->create([
            'type' => Invoice::REFUND,
            'year' => $year,
            'months' => $months,
            'annual_amount' => $annualAmount,
            'amount' => -ContributionCalculator::amountForMonths($annualAmount, $months),
            'issued_on' => today(),
        ]);
    }
}
