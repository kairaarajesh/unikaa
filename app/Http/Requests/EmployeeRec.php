<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRec extends FormRequest
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
        'employee_id' => 'string|required',
        'employee_name' => 'string|required',
        'employee_email' => 'required|string|email|max:255',
        'employee_number' => 'required|numeric',
        // 'password' => 'string|required',
        'position' => 'string|required',
        'address' => 'string|required',
        'employee_status' => 'string|required',
        'team' => 'string|nullable',
        'place' => 'string|nullable',
        // 'branch' => 'string|required',
        'branch_id' => 'required|exists:branches,id',
        'joining_date' => 'string|nullable',
        'salary' => 'required|numeric',
        'gender' => 'in:Male,Female',
        //  'unit' => 'in:kg,ml,gram,liter,no',
        'age' => 'string|required',
        'dob' => 'string|required',
        // 'street' => 'string|required',
        // 'city' => 'string|required',
        // 'state' => 'string|required',
        // 'pin_code' => 'required|numeric',
        'emergency_name' => 'string|required',
        'emergency_number' => 'required|numeric',
        'aadhar_card' => 'required|numeric',
        'qualification' => 'string|nullable',
        'certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        'company' => 'string|nullable',
        'experience' => 'string|nullable',
        'role' => 'string|nullable',
        'old_salary' => 'string|nullable',
        ];
    }
}