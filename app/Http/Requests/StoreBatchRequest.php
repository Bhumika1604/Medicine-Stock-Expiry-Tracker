<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $medicine = $this->route('medicine');
        $medicineId = $medicine?->id ?? $this->input('medicine_id');
        $batchId = $this->route('batch')?->id;

        return [
            'batch_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('batches', 'batch_number')
                    ->where(fn ($query) => $query->where('medicine_id', $medicineId))
                    ->ignore($batchId),
            ],

            'manufacturing_date' => [
                'nullable',
                'date',
                'before_or_equal:expiry_date',
            ],

            'expiry_date' => [
                'required',
                'date',
                'after_or_equal:manufacturing_date',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'minimum_stock_level' => [
                'required',
                'integer',
                'min:0',
            ],

            'purchase_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'selling_price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'supplier_name' => [
                'nullable',
                'string',
                'max:150',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'manufacturing_date.before_or_equal' =>
                'Manufacturing date cannot be after the expiry date.',

            'expiry_date.after_or_equal' =>
                'Expiry date must be on or after the manufacturing date.',

            'batch_number.unique' =>
                'This batch number already exists for this medicine.',
        ];
    }
}