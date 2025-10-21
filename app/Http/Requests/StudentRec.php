<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRec extends FormRequest
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
            'student_name' => 'string|required',
            'student_id' => 'string|required',
            'email' => 'string|required',
            'number' => 'required|numeric',
            'gender' => 'in:Male,Female',
            'dob' => 'string|required',
            'joining_date' => 'required|date',
            'street' => 'string|required',
            'city' => 'string|required',
            'state' => 'string|required',
            'pin_code' => 'required|numeric',
            'emergency_name' => 'string|required',
            'emergency_number' => 'required|numeric',
            'aadhar_card' => 'required|numeric',
            'fees_status' => 'string|required',
            'payment_history' => 'string|required',
            'batch_timing' => 'string|required',
            'staff_management_id' => 'required|exists:staff_managements,id',
            'course_id' => 'required|exists:courses,id',
        ];
    }
}
