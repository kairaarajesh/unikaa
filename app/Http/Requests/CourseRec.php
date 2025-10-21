<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRec extends FormRequest
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
                'name' => 'string|required',
                'duration' => 'string|required',
                'fees' => 'required|numeric',
                'start_time' => 'required|date_format:h:i A',
                'end_time' => 'required|date_format:h:i A',
                'max_student' => 'string|required',
                'batch' => 'string|required',
                'staff_management_id' => 'required|exists:Staff_managements,id',
        ];
    }
}
