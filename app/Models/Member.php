<?php

namespace App\Models;

use App\Helpers\ContributionCalculator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_INACTIVE = 'inactive';

    public const STATUS_QUARANTINE = 'quarantine';

    protected $fillable = [
        'member_type_id',
        'address_id',
        'first_name',
        'last_name',
        'email',
        'birth_date',
        'is_active',
        'is_quarantine',
        'registered_at',
        'membership_starts_on',
        'cancelled_at',
        'membership_ends_on',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'registered_at' => 'date',
        'membership_starts_on' => 'date',
        'membership_ends_on' => 'date',
        'cancelled_at' => 'datetime',
        'is_active' => 'boolean',
        'is_quarantine' => 'boolean',
    ];

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class)->withTrashed();
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class)->withTrashed();
    }

    public function breedingNumber(): HasOne
    {
        return $this->hasOne(BreedingNumber::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => "{$this->first_name} {$this->last_name}");
    }

    /**
     * Het NBvV-lidnummer is het kweeknummer; het wordt maar op één plek bewaard (breeding_numbers).
     */
    protected function nbvvNumber(): Attribute
    {
        return Attribute::get(fn () => $this->breedingNumber?->breeding_number);
    }

    protected function status(): Attribute
    {
        return Attribute::get(fn () => match (true) {
            $this->is_quarantine => self::STATUS_QUARANTINE,
            $this->is_active => self::STATUS_ACTIVE,
            default => self::STATUS_INACTIVE,
        });
    }

    /**
     * Welk contributietarief voor dit lid in het gegeven jaar geldt.
     */
    public function tariffSlugFor(int $year): string
    {
        if (! $this->memberType->is_nbvv_member) {
            return $this->memberType->slug;
        }

        return ContributionCalculator::isYouthTariff($this->birth_date, $year)
            ? MemberType::YOUTH
            : MemberType::ADULT;
    }

    public function scopeSearch(Builder $query, ?string $term): void
    {
        $term = trim((string) $term);

        if ($term === '') {
            return;
        }

        $like = '%'.addcslashes($term, '%_\\').'%';

        $query->where(function (Builder $query) use ($like) {
            $query->where('first_name', 'like', $like)
                ->orWhere('last_name', 'like', $like)
                ->orWhereRaw("concat(first_name, ' ', last_name) like ?", [$like])
                ->orWhereHas('breedingNumber', fn (Builder $number) => $number->where('breeding_number', 'like', $like));
        });
    }

    public function scopeOfType(Builder $query, ?int $memberTypeId): void
    {
        if ($memberTypeId) {
            $query->where('member_type_id', $memberTypeId);
        }
    }

    public function scopeWithStatus(Builder $query, ?string $status): void
    {
        match ($status) {
            self::STATUS_ACTIVE => $query->where('is_active', true)->where('is_quarantine', false),
            self::STATUS_INACTIVE => $query->where('is_active', false)->where('is_quarantine', false),
            self::STATUS_QUARANTINE => $query->where('is_quarantine', true),
            default => null,
        };
    }
}
