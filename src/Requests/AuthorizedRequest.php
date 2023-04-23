<?php

namespace Heli\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'وارد کردن نام کاربری الزامی می باشد.',
        ];
    }
}
