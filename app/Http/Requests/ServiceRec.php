<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRec extends FormRequest
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
            'service_name' => 'string|nullable',
            'amount' => 'string|required',
            'gender' => 'string|required',
            // 'quantity' => 'string|required',
            // 'tax' => 'string|required',
            'total_amount' => 'string|nullable',
        ];
    }
}