<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'street',
        'house_number',
        'house_number_addition',
        'postal_code',
        'city',
    ];

    public function member(): HasOne
    {
        return $this->hasOne(Member::class);
    }

    protected function fullStreet(): Attribute
    {
        return Attribute::get(fn () => trim("{$this->street} {$this->house_number} {$this->house_number_addition}"));
    }
}
