<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRec;
use App\Models\course;
use App\Models\Staff_management;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CourseController extends Controller
{
     public function index()
    {
        $Courses = Course::with('Staff_managements')->get();
        $Staff_managements = Staff_management::all();
        return view('admin.course', compact('Courses', 'Staff_managements'));
        flash()->success('Success','Schedule has been created successfully !');

    }

      public function store(Request $request)
{
    // Step 1: Validate common/global fields
    $this->validate($request, [
        'name' => 'required|string',
        'type' => 'required|array|min:1',
        'type.*' => 'in:BASIC,ADVANCE',
        'start_time' => 'required|date_format:h:i A',
        // 'end_time' => 'required|date_format:h:i A',
        'max_student' => 'required|string',
        'batch' => 'required|string',
        'staff_management_id' => 'required|exists:staff_managements,id',
    ]);

    $type = $request->input('type');
    $errors = [];

    if (in_array('BASIC', $type)) {
        if (!$request->filled('fees_basic')) {
            $errors['fees_basic'] = 'The BASIC fees field is required.';
        }
        if (!$request->filled('duration_basic')) {
            $errors['duration_basic'] = 'The BASIC duration field is required.';
        }
        if (!$request->has('course_basic')) {
            $errors['course_basic'] = 'At least one BASIC course must be selected.';
        }
    }

    if (in_array('ADVANCE', $type)) {
        if (!$request->filled('fees_advance')) {
            $errors['fees_advance'] = 'The ADVANCE fees field is required.';
        }
        if (!$request->filled('duration_advance')) {
            $errors['duration_advance'] = 'The ADVANCE duration field is required.';
        }
        if (!$request->has('course_advance')) {
            $errors['course_advance'] = 'At least one ADVANCE course must be selected.';
        }
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    // Step 3: Prepare data for saving
    $data = $request->only([
        'name',
        'type',
        'max_student',
        'batch',
        'staff_management_id',
    ]);

    $data['type'] = json_encode($request->type);

    // Combine courses from both BASIC and ADVANCE
    $allCourses = [];

    if (in_array('BASIC', $type)) {
        $allCourses = array_merge($allCourses, $request->course_basic);
    }

    if (in_array('ADVANCE', $type)) {
        $allCourses = array_merge($allCourses, $request->course_advance);
    }

    $data['course'] = json_encode($allCourses);

    if (in_array('BASIC', $type) && !in_array('ADVANCE', $type)) {
        $data['fees'] = $request->fees_basic;
        $data['duration'] = $request->duration_basic;
    } elseif (in_array('ADVANCE', $type) && !in_array('BASIC', $type)) {
        $data['fees'] = $request->fees_advance;
        $data['duration'] = $request->duration_advance;
    } else {
        $data['fees'] = json_encode([
            'basic' => $request->fees_basic,
            'advance' => $request->fees_advance,
        ]);

        $data['duration'] = json_encode([
            'basic' => $request->duration_basic,
            'advance' => $request->duration_advance,
        ]);
    }

    // Time conversion
    $data['start_time'] = Carbon::createFromFormat('h:i A', $request->start_time)->format('H:i:s');
    $data['end_time'] = Carbon::createFromFormat('h:i A', $request->end_time)->format('H:i:s');

    // Save
    $status = Course::create($data);

    if ($status) {
        return redirect()->route('course.index')->with('success', 'Course successfully created');
    } else {
        return redirect()->back()->with('error', 'Error, Please try again');
    }
}

    public function edit($id)
    {
        $course=Course::find($id);
        if(!$course){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_course')->with('course',$course);
    }

    public function update(CourseRec $request, Course $course)
{
    // Validate the request
    $validated = $request->validated();

    // Convert times to proper format
    $course->start_time = \Carbon\Carbon::createFromFormat('h:i A', $validated['start_time'])->format('H:i:s');
    // $course->end_time = \Carbon\Carbon::createFromFormat('h:i A', $validated['end_time'])->format('H:i:s');

    // Assign common fields
    $course->name = $validated['name'];
    $course->staff_management_id = $validated['staff_management_id'];
    $course->max_student = $validated['max_student'];
    $course->batch = $validated['batch'];

    $course->type = json_encode($request->type ?? []);

    $courses = [];
    if ($request->has('course_basic')) {
        $courses = array_merge($courses, $request->course_basic);
    }
    if ($request->has('course_advance')) {
        $courses = array_merge($courses, $request->course_advance);
    }
    $course->course = json_encode($courses);

    if (in_array('BASIC', $request->type ?? []) && in_array('ADVANCE', $request->type ?? [])) {
        $course->fees = json_encode([
            'basic' => $request->fees_basic,
            'advance' => $request->fees_advance,
        ]);
        $course->duration = json_encode([
            'basic' => $request->duration_basic,
            'advance' => $request->duration_advance,
        ]);
    } elseif (in_array('BASIC', $request->type ?? [])) {
        $course->fees = $request->fees_basic;
        $course->duration = $request->duration_basic;
    } elseif (in_array('ADVANCE', $request->type ?? [])) {
        $course->fees = $request->fees_advance;
        $course->duration = $request->duration_advance;
    }

    // Save
    $course->save();

    flash()->success('Success', 'Course record has been updated successfully!');
    return redirect()->route('course.index');
}


    public function destroy(Course $course)
    {
        $course->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('course.index')->with('success');
    }
}