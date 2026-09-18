<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Een lid heeft zich afgemeld; de restitutie wordt hierop berekend.
 */
class MemberCancelled
{
    use Dispatchable;

    public function __construct(public Member $member) {}
}
