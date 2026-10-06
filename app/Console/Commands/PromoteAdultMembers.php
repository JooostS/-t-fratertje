<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\MemberType;
use Illuminate\Console\Command;

class PromoteAdultMembers extends Command
{
    protected $signature = 'members:promote-adults';

    protected $description = 'Zet jeugdleden die 18 zijn geworden om naar volwassen lid';

    /**
     * Zet alle jeugdleden van 18 jaar of ouder om naar volwassen lid.
     */
    public function handle(): int
    {
        $adultType = MemberType::where('slug', MemberType::ADULT)->firstOrFail();

        $promoted = Member::query()
            ->whereHas('memberType', fn ($query) => $query->where('slug', MemberType::YOUTH))
            ->whereDate('birth_date', '<=', now()->subYears(18)->toDateString())
            ->update(['member_type_id' => $adultType->id]);

        $this->info("{$promoted} jeugdlid(leden) omgezet naar volwassen lid.");

        return self::SUCCESS;
    }
}
