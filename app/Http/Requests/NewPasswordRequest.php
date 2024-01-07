<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class NewPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'token' => [
                'required'
            ],
            'email' => [
                'required',
                'email'
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults()
            ],
        ];
    }
}
