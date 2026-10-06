<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Bij het aanmaken is een wachtwoord verplicht; bij wijzigen laat je het leeg om het te behouden.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'role' => ['required', Rule::in([User::ROLE_BEHEERDER, User::ROLE_SECRETARIS])],
            'password' => [$this->isMethod('POST') ? 'required' : 'nullable', 'confirmed', Password::min(8)],
        ];
    }

    /**
     * Je kunt je eigen rol niet verlagen: zo blijft er altijd minstens één beheerder over.
     *
     * @return array<int, callable(Validator): void>
     */
    public function after(): array
    {
        return [function (Validator $validator) {
            $user = $this->route('user');

            if ($user instanceof User && $user->is($this->user()) && $this->input('role') !== User::ROLE_BEHEERDER) {
                $validator->errors()->add('role', 'Je kunt je eigen beheerdersrol niet afnemen.');
            }
        }];
    }
}
