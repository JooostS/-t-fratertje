<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['is_nbvv_member' => $this->boolean('is_nbvv_member')]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:50', Rule::unique('member_types', 'name')->ignore($this->route('member_type'))],
            'description' => ['nullable', 'string', 'max:500'],
            'is_nbvv_member' => ['boolean'],
        ];

        // Bij het aanmaken is meteen een startbedrag nodig; daarna loopt dat via de tariefhistorie.
        if ($this->isMethod('POST')) {
            $rules['amount'] = ['required', 'numeric', 'min:0', 'max:9999.99'];
        }

        return $rules;
    }
}
