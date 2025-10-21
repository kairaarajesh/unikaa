<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Student_attendance;

class Student_AttendanceController extends Controller
{
    public function index()
    {
        return view('admin.student_attendance')->with(['Students' => Student::all()]);
    }

    public function CheckStore(Request $request)
    {
        if (isset($request->attd)) {
            foreach ($request->attd as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($student = Student::whereId(request('student_id'))->first()) {
                        if (
                            !Student_attendance::whereAttendance_date($keys)
                                ->whereStudent_id($key)
                                ->whereType(0)
                                ->first()
                        ) {
                            $data = new Student_attendance();
                            $data->student_id = $key;
                            $data->attendance_date = $keys;
                            $emp_req = Student::whereId($data->student_id)->first();

                            if ($emp_req && $emp_req->schedules && $emp_req->schedules->first()) {
                                $data->attendance_time = date('H:i:s', strtotime($emp_req->schedules->first()->time_in));
                            } else {
                                $data->attendance_time = date('H:i:s');
                            }

                            $data->type = 0;
                            $data->save();
                        }
                    }
                }
            }
        }
         flash()->success('Success', 'You have successfully submitted the attendance!');
        return back();
    }

    // public function sheetReport()
    // {
    //     $students = student::withCount('student_attendance')->get();

    //     return view('admin.student_sheet_report')->with(['students' => $students]);
    // }

      public function sheetReport()
    {

    return view('admin.student_sheet_report')->with(['students' => Student::all()]);
    }

 public function report(Request $request)
{
    $month = $request->input('month') ?? now()->format('Y-m');
    $yearMonth = Carbon::createFromFormat('Y-m', $month);

    $startDate = $yearMonth->copy()->startOfMonth();
    $endDate = $yearMonth->copy()->endOfMonth();

    $students = Student::withCount(['Student_attendances as attendances_count' => function ($query) use ($startDate, $endDate) {
        $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }])->get();

    return view('admin.student_sheet_report', compact('students', 'yearMonth'));
}

}