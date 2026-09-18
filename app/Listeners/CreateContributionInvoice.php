<?php

namespace App\Listeners;

use App\Events\MemberActivated;
use App\Helpers\ContributionCalculator;
use App\Models\Invoice;

class CreateContributionInvoice
{
    public function handle(MemberActivated $event): void
    {
        $member = $event->member;
        $start = ContributionCalculator::membershipStart($member->registered_at);

        $member->update(['membership_starts_on' => $start]);

        Invoice::issueContribution($member, $start->year, ContributionCalculator::monthsLeftInYear($start));
    }
}
