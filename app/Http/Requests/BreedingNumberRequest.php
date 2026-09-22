<?php

namespace App\Http\Requests;

use App\Models\BreedingNumber;
use App\Models\Member;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BreedingNumberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'breeding_number' => $this->filled('breeding_number')
                ? strtoupper(trim($this->input('breeding_number')))
                : null,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var BreedingNumber|null $current */
        $current = $this->route('breeding_number');

        $rules = [
            'breeding_number' => ['required', 'string', 'size:4', 'regex:/^[A-Z0-9]{4}$/', Rule::unique('breeding_numbers', 'breeding_number')->ignore($current)],
            'issue_year' => ['required', 'integer', 'between:1900,'.now()->year],
        ];

        // Het lid ligt na het aanmaken vast; alleen bij een nieuw kweeknummer kies je een lid.
        if ($current === null) {
            $rules['member_id'] = [
                'required',
                'integer',
                Rule::exists('members', 'id')->whereNull('deleted_at')->where('is_active', true),
                Rule::unique('breeding_numbers', 'member_id'),
            ];
        }

        return $rules;
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [function (Validator $validator) {
            if ($validator->errors()->has('member_id') || ! $this->filled('member_id')) {
                return;
            }

            if (! Member::with('memberType')->find($this->input('member_id'))?->memberType->is_nbvv_member) {
                $validator->errors()->add('member_id', 'Een gastlid heeft geen kweeknummer.');
            }
        }];
    }
}
