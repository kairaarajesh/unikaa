<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagemenRec extends FormRequest
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
            'product_name'=>'string|required',
            'product_code'=>'string|required',
            'Quantity'=>'string|required',
            'price'=>'required|numeric',
            // 'branch'=>'string|required',
            'date'=>'string|required',
            'category_id' => 'required|exists:categories,id',
            'branch_id' => 'nullable|exists:branches,id',
        ];
    }
}

