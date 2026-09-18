<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContributionRateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prijswijzigingen gelden alleen voor een komend jaar, nooit voor het lopende jaar of de historie.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        $nextYear = now()->year + 1;

        return [
            'valid_from_year' => ['required', 'integer', "between:{$nextYear},".($nextYear + 10)],
            'amount' => ['required', 'numeric', 'min:0', 'max:9999.99'],
        ];
    }
}
