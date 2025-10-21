<?php

namespace App\Http\Controllers;
use DateTime;
use App\Models\Latetime;
use App\Models\Attendance;
use App\Models\Employees;
use App\Models\Attendancelog;

use Illuminate\Http\Request;

class AttendancelogController extends Controller
{
    //show attendance
    public function index()
    {
        return view('admin.attendancelog')->with(['attendances' => Attendance::all()]);
    }

    public function indexLatetime()
    {
        return view('admin.latetime')->with(['latetimes' => Latetime::all()]);
    }

    public static function lateTimeDevice($att_dateTime, Employees $employee)
    {
        $attendance_time = new DateTime($att_dateTime);
        $checkin = new DateTime($employee->schedules->first()->time_in);
        $difference = $checkin->diff($attendance_time)->format('%H:%I:%S');

        $latetime = new Latetime();
        $latetime->emp_id = $employee->id;
        $latetime->duration = $difference;
        $latetime->latetime_date = date('Y-m-d', strtotime($att_dateTime));
        $latetime->save();
    }

}
