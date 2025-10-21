<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRec extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'name' => 'string|required',
            'branch_id' => 'nullable|exists:branches,id',
            // 'customer_id' => 'string|required',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $this->customer->id,
            'number' => 'required|numeric',
            'place' => 'string|required',
            // 'category' => 'string|required',
            'date' => 'string',
            // 'amount' => 'required|numeric',
            // 'total_amount' => 'string|required',
            // 'discount' => 'string|required',
            'gender' => 'in:Male,Female',
            'subtotal' => 'nullable|numeric',
            'service_tax' => 'nullable|numeric',
            'service_tax_amount' => 'nullable|numeric',
            'service_total_calculation' => 'nullable|numeric',
            'service_items' => 'required',
            'purchase_items' => 'nullable',
            'purchase_total_amount' => 'nullable|numeric',
            'membership_card' => 'nullable|string|max:255',

        ];
    }
}
