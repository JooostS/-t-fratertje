<?php

namespace App\Events;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Iemand heeft zich via het publieke formulier aangemeld; het lid staat in quarantaine.
 */
class MemberSignedUp
{
    use Dispatchable;

    public function __construct(public Member $member) {}
}
