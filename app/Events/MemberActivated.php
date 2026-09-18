<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Een lid is definitief lid geworden (aanmelding verwerkt of direct actief aangemaakt).
 * De contributie wordt hierop berekend.
 */
class MemberActivated
{
    use Dispatchable;

    public function __construct(public Member $member) {}
}
