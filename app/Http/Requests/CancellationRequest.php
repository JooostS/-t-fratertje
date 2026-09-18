<?php

namespace App\Http\Requests;

use App\Models\Member;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class CancellationRequest extends FormRequest
{
    private ?Member $member = null;

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'birth_date' => ['required', 'date'],
            'agreement' => ['accepted'],
        ];
    }

    /**
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [function (Validator $validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $this->member = Member::query()
                ->where('email', $this->input('email'))
                ->whereDate('birth_date', $this->date('birth_date'))
                ->where('is_active', true)
                ->first();

            if ($this->member === null) {
                $validator->errors()->add('email', 'Er is geen actief lid gevonden met deze combinatie van e-mailadres en geboortedatum.');
            }
        }];
    }

    public function member(): Member
    {
        return $this->member;
    }
}
