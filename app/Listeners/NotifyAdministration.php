<?php

namespace App\Listeners;

use App\Events\MemberCancelled;
use App\Events\MemberSignedUp;
use App\Mail\AdministrationNotice;
use App\Models\Member;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

/**
 * Signaleert aan- en afmeldingen per e-mail aan alle gebruikers van de administratie.
 */
class NotifyAdministration
{
    /**
     * Mailt de administratie bij een nieuwe aanmelding.
     */
    public function handleMemberSignedUp(MemberSignedUp $event): void
    {
        $this->notify(
            'Nieuwe aanmelding in quarantaine',
            "{$event->member->full_name} heeft zich aangemeld en wacht op verwerking.",
            $event->member,
        );
    }

    /**
     * Mailt de administratie bij een afmelding.
     */
    public function handleMemberCancelled(MemberCancelled $event): void
    {
        $this->notify(
            'Afmelding ontvangen',
            "{$event->member->full_name} heeft zich afgemeld per {$event->member->membership_ends_on->format('d-m-Y')}.",
            $event->member,
        );
    }

    /**
     * Verstuurt de melding naar alle gebruikers, met een link naar het lid.
     */
    private function notify(string $subject, string $text, Member $member): void
    {
        Mail::to(User::pluck('email')->all())
            ->send(new AdministrationNotice($subject, $text, route('members.show', $member)));
    }
}
