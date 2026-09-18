<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Member;
use Illuminate\Console\Command;

class GenerateContributionInvoices extends Command
{
    protected $signature = 'invoices:generate {year? : Het factuurjaar, standaard het lopende jaar}';

    protected $description = 'Maakt de jaarlijkse contributiefactuur voor alle actieve leden die er dit jaar nog geen hebben';

    public function handle(): int
    {
        $year = (int) ($this->argument('year') ?? now()->year);

        $members = Member::query()
            ->with('memberType')
            ->withStatus(Member::STATUS_ACTIVE)
            ->where(fn ($query) => $query->whereNull('membership_starts_on')->orWhereDate('membership_starts_on', '<', "{$year}-01-01"))
            ->whereDoesntHave('invoices', fn ($query) => $query->where('type', Invoice::CONTRIBUTION)->where('year', $year))
            ->get();

        $members->each(fn (Member $member) => Invoice::issueContribution($member, $year, 12));

        $this->info("{$members->count()} contributiefactu(u)r(en) aangemaakt voor {$year}.");

        return self::SUCCESS;
    }
}
