<?php

namespace App\Models;

use App\Helpers\ContributionCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    public const CONTRIBUTION = 'contribution';

    public const REFUND = 'refund';

    protected $fillable = [
        'member_id',
        'type',
        'year',
        'months',
        'annual_amount',
        'amount',
        'issued_on',
    ];

    protected $casts = [
        'year' => 'integer',
        'months' => 'integer',
        'annual_amount' => 'decimal:2',
        'amount' => 'decimal:2',
        'issued_on' => 'date',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class)->withTrashed();
    }

    /**
     * Maakt een contributiefactuur voor het aantal opgegeven maanden van een jaar.
     */
    public static function issueContribution(Member $member, int $year, int $months): self
    {
        $annualAmount = ContributionRate::annualAmountFor($member->tariffSlugFor($year), $year);

        return $member->invoices()->create([
            'type' => self::CONTRIBUTION,
            'year' => $year,
            'months' => $months,
            'annual_amount' => $annualAmount,
            'amount' => ContributionCalculator::amountForMonths($annualAmount, $months),
            'issued_on' => today(),
        ]);
    }
}
