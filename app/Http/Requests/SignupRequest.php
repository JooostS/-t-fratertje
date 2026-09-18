<?php

namespace App\Http\Requests;

use Illuminate\Support\Arr;

/**
 * Publiek aanmeldformulier: dezelfde regels als de administratie, plus de verklaring (digitale handtekening).
 */
class SignupRequest extends MemberRequest
{
    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return Arr::except(parent::rules(), 'is_active') + [
            'agreement' => ['accepted'],
        ];
    }
}
