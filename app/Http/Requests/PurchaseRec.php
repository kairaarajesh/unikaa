<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRec extends FormRequest
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
              'management_id' => 'required|exists:management,id',
            'customer_name' => 'required|string',
            'customer_number' => 'required|string',
            'Quantity' => 'required|string',
            'price' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'branch' => 'nullable|string',
            'payment' => 'required|string',
            'product_code' => 'required|string',
            'tax' => 'nullable|numeric',
            'total_calculation' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
        ];
    }
}