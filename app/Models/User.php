<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const ROLE_BEHEERDER = 'beheerder';

    public const ROLE_SECRETARIS = 'secretaris';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Alleen een beheerder mag lidsoorten en tarieven wijzigen; een secretaris beheert leden en kweeknummers.
     */
    public function isBeheerder(): bool
    {
        return $this->role === self::ROLE_BEHEERDER;
    }
}
