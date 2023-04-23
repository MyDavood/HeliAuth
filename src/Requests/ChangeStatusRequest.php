<?php

namespace Heli\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangeStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hash' => 'required',
            'telegramId' => 'required',
            'status' => 'required|numeric|in:1,2',
        ];
    }
}
