<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

/**
 * Publiek aanmeldformulier: dezelfde regels als de administratie, plus de verklaring
 * (digitale handtekening). Het kweeknummer wordt pas door de administratie toegekend
 * bij het verwerken van de aanmelding, dus dat vraagt dit formulier niet uit.
 */
class SignupRequest extends MemberRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return Arr::except(parent::rules(), ['is_active', 'breeding_number', 'issue_year']) + [
            'agreement' => ['accepted'],
        ];
    }
}
