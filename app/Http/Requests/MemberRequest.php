<?php

namespace App\Http\Requests;

use App\Models\BreedingNumber;
use App\Models\Member;
use App\Models\MemberType;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MemberRequest extends FormRequest
{
    private ?MemberType $memberType = null;

    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $postalCode = strtoupper(preg_replace('/\s+/', '', (string) $this->input('postal_code')));

        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'postal_code' => preg_match('/^\d{4}[A-Z]{2}$/', $postalCode)
                ? substr($postalCode, 0, 4).' '.substr($postalCode, 4)
                : $this->input('postal_code'),
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
        $needsBreedingNumber = (bool) $this->memberType()?->is_nbvv_member;

        return [
            'member_type_id' => ['required', 'integer', Rule::exists('member_types', 'id')->whereNull('deleted_at')],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'birth_date' => ['required', 'date', 'after:1900-01-01', 'before:tomorrow'],
            'street' => ['required', 'string', 'max:100'],
            'house_number' => ['required', 'regex:/^\d{1,5}$/'],
            'house_number_addition' => ['nullable', 'string', 'max:10'],
            'postal_code' => ['required', 'regex:/^[1-9]\d{3} [A-Z]{2}$/'],
            'city' => ['required', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'breeding_number' => $needsBreedingNumber
                ? ['required', 'string', 'max:20', 'regex:/^[A-Z0-9-]+$/', Rule::unique('breeding_numbers', 'breeding_number')->ignore($this->currentBreedingNumberId())]
                : ['prohibited'],
            'issue_year' => $needsBreedingNumber
                ? ['required', 'integer', 'between:1900,'.now()->year]
                : ['nullable'],
        ];
    }

    /**
     * Leeftijdsgrenzen van de lidsoorten worden ook server-side afgedwongen.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [function (Validator $validator) {
            if ($validator->errors()->hasAny(['member_type_id', 'birth_date'])) {
                return;
            }

            $slug = $this->memberType()->slug;
            $age = $this->date('birth_date')->age;

            if ($slug === MemberType::YOUTH && $age >= 18) {
                $validator->errors()->add('member_type_id', 'Een jeugdlid moet jonger zijn dan 18 jaar. Kies “Volwassen lid”.');
            }

            if ($slug === MemberType::ADULT && $age < 18) {
                $validator->errors()->add('member_type_id', 'Een volwassen lid moet minimaal 18 jaar zijn. Kies “Jeugdlid”.');
            }
        }];
    }

    protected function memberType(): ?MemberType
    {
        return $this->memberType ??= MemberType::find($this->input('member_type_id'));
    }

    private function currentBreedingNumberId(): ?int
    {
        $member = $this->route('member');

        return $member instanceof Member
            ? BreedingNumber::withTrashed()->where('member_id', $member->id)->value('id')
            : null;
    }
}
