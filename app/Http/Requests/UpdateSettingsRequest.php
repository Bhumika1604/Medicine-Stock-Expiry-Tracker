<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pharmacy_name' => ['required', 'string', 'max:150'],
            'near_expiry_days' => ['required', 'integer', 'min:1', 'max:365'],
            'default_min_stock' => ['required', 'integer', 'min:0'],
            'currency_symbol' => ['required', 'string', 'max:5'],
        ];
    }
}
