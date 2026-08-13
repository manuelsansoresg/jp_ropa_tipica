<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isOwner() === true;
    }

    public function rules(): array
    {
        $managedUser = $this->route('user');

        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($managedUser)],
            'password' => [$managedUser ? 'nullable' : 'required', 'string', 'min:10', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.min' => 'La contraseña debe tener al menos 10 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ];
    }
}
