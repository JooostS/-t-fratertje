<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MemberType extends Model
{
    use HasFactory, SoftDeletes;

    public const YOUTH = 'jeugdlid';

    public const ADULT = 'volwassen-lid';

    public const GUEST = 'gastlid';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_nbvv_member',
    ];

    protected $casts = [
        'is_nbvv_member' => 'boolean',
    ];

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }

    public function contributionRates(): HasMany
    {
        return $this->hasMany(ContributionRate::class);
    }

    /**
     * Het jaartarief dat in het gegeven jaar (standaard dit jaar) geldt, of null als er nog geen is.
     */
    public function annualContribution(?int $year = null): ?float
    {
        $amount = $this->contributionRates()
            ->where('valid_from_year', '<=', $year ?? now()->year)
            ->orderByDesc('valid_from_year')
            ->value('amount');

        return $amount === null ? null : (float) $amount;
    }
}
