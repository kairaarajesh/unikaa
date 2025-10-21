<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRec;
use App\Models\Student;
use App\Models\course;
use App\Models\Staff_management;
use Illuminate\Http\Request;

class StudentController extends Controller
{
     public function index()
    {
        $Students = Student::with('staff_managements','courses')->get();
        $courses = Course::all();
        $staff_managements = Staff_management::all();
        return view('admin.student', compact('Students', 'courses','staff_managements'));
        flash()->success('Success','Schedule has been created successfully !');

    }

    public function store(Request $request)
    {
        $this->validate($request, [
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
        ]);

        $Staff_management = Staff_management::find($request->staff_management_id);
        if (!$Staff_management) {
            return redirect()->back()->with('error', 'Staff_management not found.');
        }
        $Course = Course::find($request->course_id);
        if (!$Course) {
            return redirect()->back()->with('error', 'Staff_management not found.');
        }

        $data=$request->all();
        $status=Student::create($data);

        if ($status) {
            return redirect()->route('student.index')->with('success', 'student successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }

    public function edit($id)
    {
        $Students=student::find($id);
        if(!$Students){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_student')->with('Students',$Students);
    }

     public function update(StudentRec $request, Student $student)
    {
        $request->validated();
        $student->student_name = $request->student_name;
        $student->student_id = $request->student_id;
        $student->email = $request->email;
        $student->number = $request->number;
        $student->gender = $request->gender;
        $student->joining_date = $request->joining_date;
        $student->street = $request->street;
        $student->city = $request->city;
        $student->state = $request->state;
        $student->pin_code = $request->pin_code;
        $student->emergency_name = $request->emergency_name;
        $student->emergency_number = $request->emergency_number;
        $student->aadhar_card = $request->aadhar_card;
        $student->fees_status = $request->fees_status;
         $student->payment_history = $request->payment_history;
        $student->batch_timing = $request->batch_timing;
        $student->staff_management_id = $request->staff_management_id;
        $student->course_id = $request->course_id;
        $student->save();

        flash()->success('Success','student Record has been Updated successfully !');

        return redirect()->route('student.index')->with('success');
    }

     public function destroy(Student $student)
    {
        $student->delete();
        flash()->success('Success','Student Record has been Deleted successfully !');
        return redirect()->route('student.index')->with('success');
    }
}