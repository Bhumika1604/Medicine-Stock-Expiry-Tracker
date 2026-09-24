<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('category')?->id;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:categories,name,'.$id],
            'description' => ['nullable', 'string', 'max:1000'],
            'status' => ['sometimes', 'boolean'],
        ];
    }
}
