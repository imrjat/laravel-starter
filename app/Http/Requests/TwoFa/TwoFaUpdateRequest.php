<?php

namespace App\Http\Requests\TwoFa;

use Illuminate\Foundation\Http\FormRequest;

class TwoFaUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'required',
                'string',
                'min:6'
            ]
        ];
    }
}
