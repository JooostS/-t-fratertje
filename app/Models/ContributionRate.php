<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RuntimeException;

class ContributionRate extends Model
{
    protected $fillable = [
        'member_type_id',
        'valid_from_year',
        'amount',
    ];

    protected $casts = [
        'valid_from_year' => 'integer',
        'amount' => 'decimal:2',
    ];

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    /**
     * Het jaartarief dat voor een lidsoort in een bepaald jaar geldt: het laatst ingegane tarief.
     */
    public static function annualAmountFor(string $memberTypeSlug, int $year): float
    {
        $amount = static::query()
            ->whereHas('memberType', fn ($query) => $query->where('slug', $memberTypeSlug))
            ->where('valid_from_year', '<=', $year)
            ->orderByDesc('valid_from_year')
            ->value('amount');

        return $amount !== null
            ? (float) $amount
            : throw new RuntimeException("Geen contributietarief gevonden voor '{$memberTypeSlug}' in {$year}.");
    }
}
