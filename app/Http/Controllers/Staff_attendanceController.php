<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\Staff_management;
use Illuminate\Http\Request;
use App\Models\Staff_attendance;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrainerExport;

class Staff_attendanceController extends Controller
{
     public function index()
    {
        return view('admin.staff_attendance')->with(['Staff_managements' => Staff_management::all()]);
    }

     public function CheckStore(Request $request)
    {
        if (isset($request->attd)) {
            foreach ($request->attd as $keys => $values) {
                foreach ($values as $key => $value) {
                    if ($Staff_management = Staff_management::whereId(request('staff_management__id'))->first()) {
                        if (
                            !Staff_attendance::whereAttendance_date($keys)
                                ->wherestaff_management__id($key)
                                ->whereType(0)
                                ->first()
                        ) {
                            $data = new Staff_attendance();
                            $data->staff_management__id = $key;
                            $data->attendance_date = $keys;
                            $emp_req = Staff_management::whereId($data->staff_management__id)->first();

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

    public function sheetReport()
    {

    return view('admin.staff_management_sheet_report')->with(['Staff_managements' => Staff_management::all()]);
    }

    public function report(Request $request)
{
    $month = $request->input('month') ?? now()->format('Y-m');
    $yearMonth = Carbon::createFromFormat('Y-m', $month);

    $startDate = $yearMonth->copy()->startOfMonth();
    $endDate = $yearMonth->copy()->endOfMonth();

    $Staff_management = Staff_management::withCount(['Staff_attendances as attendances_count' => function ($query) use ($startDate, $endDate) {
        $query->whereBetween('attendance_date', [$startDate, $endDate]);
    }])->get();

    return view('admin.staff_management_sheet_report', compact('Staff_management', 'yearMonth'));
}

 public function export(Request $request)
    {
        if($request->type == "xlsx"){

             $extension = "xlsx";
             $exportFormat = \Maatwebsite\Excel\Excel::XLSX;

        }
        elseif($request->type == "cvs"){

            $extension = "cvs";
            $exportFormat = \Maatwebsite\Excel\Excel::CSV;
        }
        elseif($request->type == "xls"){

            $extension = "xls";
            $exportFormat = \Maatwebsite\Excel\Excel::XLS;

        }
        else{

              $extension = "xlsx";
              $exportFormat = \Maatwebsite\Excel\Excel::XLSX;

        }
        $filename= 'Trainer-'.date('d-m-y').'.'.$exportFormat;

        return Excel::download(new TrainerExport,$filename, $exportFormat);

    }
}