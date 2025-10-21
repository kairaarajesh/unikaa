<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Attendance;
use App\Models\Attendancelog;
use App\Models\Leave;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalaryExport;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        return view('admin.attendance')->with(['employees' => Employees::all()]);
    }

    public function CheckStore(Request $request)
    {
        // New handler for simplified attendance form with modals
        $request->validate([
            'attendance_date' => 'required|date',
            'attendance' => 'sometimes|array'
        ]);

        $date = $request->input('attendance_date');
        $attendanceSelections = (array) $request->input('attendance', []);
        $loginTimes = (array) $request->input('login_time', []);
        $logoutTimes = (array) $request->input('logout_time', []);
        $halfTypes = (array) $request->input('half_type', []);
        $casualLeaves = (array) $request->input('casual_leave', []);
        $lopLeaves = (array) $request->input('lop', []);
        $permissionTaken = (array) $request->input('permission_taken', []);
        $permissionReasons = (array) $request->input('permission_reason', []);
        $permissionFrom = (array) $request->input('permission_from', []);
        $permissionTo = (array) $request->input('permission_to', []);

        foreach ($attendanceSelections as $employeeId => $status) {
            $employee = Employees::whereId($employeeId)->first();
            if (!$employee) {
                continue;
            }

            $loginTime = isset($loginTimes[$employeeId]) && $loginTimes[$employeeId] !== ''
                ? $loginTimes[$employeeId] . ':00'
                : null;
            $logoutTime = isset($logoutTimes[$employeeId]) && $logoutTimes[$employeeId] !== ''
                ? $logoutTimes[$employeeId] . ':00'
                : null;

            // Fallback to schedule times if available and inputs are missing
            if ((!$loginTime || !$logoutTime) && $employee->schedules && $employee->schedules->first()) {
                $loginTime = $loginTime ?: date('H:i:s', strtotime($employee->schedules->first()->time_in));
                $logoutTime = $logoutTime ?: date('H:i:s', strtotime($employee->schedules->first()->time_out));
            }

            switch ($status) {
                case 'present':
                    // Check if permission was taken
                    $hasPermission = isset($permissionTaken[$employeeId]) && $permissionTaken[$employeeId] === '1';
                    $permissionReason = isset($permissionReasons[$employeeId]) ? $permissionReasons[$employeeId] : null;
                    $permissionFromTime = isset($permissionFrom[$employeeId]) ? $permissionFrom[$employeeId] : null;
                    $permissionToTime = isset($permissionTo[$employeeId]) ? $permissionTo[$employeeId] : null;

                    // Record attendance
                    $exists = Attendance::where('emp_id', $employeeId)
                        ->where('attendance_date', $date)
                        ->where('type', 0)
                        ->first();
                    if (!$exists) {
                        $record = new Attendance();
                    } else {
                        $record = $exists;
                    }

                    $record->emp_id = $employeeId;
                    $record->attendance_date = $date;
                    $record->attendance_time = $loginTime ?: date('H:i:s');
                    $record->leave_time = $logoutTime; // optional
                    $record->type = 0; // present
                    // Persist explicit present modal fields if columns exist
                    try {
                        $record->login_time = $loginTime ?: null;
                        $record->logout_time = $logoutTime ?: null;
                        $record->permission_taken = $hasPermission ? '1' : '0';
                        $record->permission_reason = $hasPermission ? ($permissionReason ?: null) : null;
                        $record->permission_from = ($hasPermission && $permissionFromTime) ? ($permissionFromTime . ':00') : null;
                        $record->permission_to = ($hasPermission && $permissionToTime) ? ($permissionToTime . ':00') : null;
                    } catch (\Exception $e) {
                        // Columns may not exist; ignore
                    }
                    try {
                        $record->save();
                    } catch (\Throwable $e) {
                        // Fallback: some schemas use DATETIME for these fields
                        try {
                            if (isset($record->login_time)) {
                                $record->login_time = $loginTime ? ($date . ' ' . $loginTime) : null;
                            }
                            if (isset($record->logout_time)) {
                                $record->logout_time = $logoutTime ? ($date . ' ' . $logoutTime) : null;
                            }
                            if (isset($record->permission_from) && $permissionFromTime) {
                                $record->permission_from = $date . ' ' . ($permissionFromTime . ':00');
                            }
                            if (isset($record->permission_to) && $permissionToTime) {
                                $record->permission_to = $date . ' ' . ($permissionToTime . ':00');
                            }
                            $record->save();
                        } catch (\Throwable $e2) {
                            throw $e; // bubble original
                        }
                    }

                    // If permission was taken, also record it as a leave entry
                    if ($hasPermission && $permissionReason) {
                        $permissionExists = Leave::where('emp_id', $employeeId)
                            ->where('leave_date', $date)
                            ->where('type', 2) // permission type
                            ->first();
                        if (!$permissionExists) {
                            $permission = new Leave();
                            $permission->emp_id = (int) $employeeId;
                            $permission->leave_date = $date;
                            $permission->leave_time = $permissionToTime ?: date('H:i:s');
                            $permission->type = 2; // permission
                            if (Schema::hasColumn('leaves', 'category')) {
                                $permission->category = 'permission';
                            }
                            $permission->save();
                        }
                    }
                    break;

                case 'absent':
                    // Check if it's marked as casual leave or LOP
                    $isCasualLeave = isset($casualLeaves[$employeeId]) && $casualLeaves[$employeeId] === '1';
                    $isLOP = isset($lopLeaves[$employeeId]) && $lopLeaves[$employeeId] === '1';

                    // Record as leave
                    $exists = Leave::where('emp_id', $employeeId)
                        ->where('leave_date', $date)
                        ->where('type', 1)
                        ->first();
                    if (!$exists) {
                        $leave = new Leave();
                        $leave->emp_id = (int) $employeeId;
                        $leave->leave_date = $date;
                        $leave->leave_time = $logoutTime ?: date('H:i:s');
                        $leave->type = 1; // leave

                        // Set category only if column exists
                        if (Schema::hasColumn('leaves', 'category')) {
                            if ($isCasualLeave) {
                                $leave->category = 'casual';
                            } elseif ($isLOP) {
                                $leave->category = 'lop';
                            } else {
                                $leave->category = null; // regular absent
                            }
                        }

                        $leave->save();
                    }
                    break;

                case 'half_day':
                    $halfCategory = isset($halfTypes[$employeeId]) ? $halfTypes[$employeeId] : null; // 'first' | 'second'

                    // Record attendance for the half worked
                    $attExists = Attendance::where('emp_id', $employeeId)
                        ->where('attendance_date', $date)
                        ->where('type', 0)
                        ->first();
                    if (!$attExists) {
                        $att = new Attendance();
                    } else {
                        $att = $attExists;
                    }
                    $att->emp_id = $employeeId;
                    $att->attendance_date = $date;
                    $att->attendance_time = $loginTime ?: date('H:i:s');
                    $att->leave_time = $logoutTime; // optional
                    $att->type = 0; // presence part
                    try {
                        $att->half_type = $halfCategory;
                        $att->half_login_time = $loginTime ?: null;
                        $att->half_logout_time = $logoutTime ?: null;
                    } catch (\Exception $e) {
                        // ignore missing columns
                    }
                    try {
                        $att->save();
                    } catch (\Throwable $e) {
                        // Fallback for DATETIME schemas
                        try {
                            if (isset($att->half_login_time)) {
                                $att->half_login_time = $loginTime ? ($date . ' ' . $loginTime) : null;
                            }
                            if (isset($att->half_logout_time)) {
                                $att->half_logout_time = $logoutTime ? ($date . ' ' . $logoutTime) : null;
                            }
                            $att->save();
                        } catch (\Throwable $e2) {
                            throw $e;
                        }
                    }

                    // Do not create a leave record for half day; store only in attendances
                    break;
            }
        }

        // Count different types of absents for the message
        $casualLeaveCount = 0;
        $lopCount = 0;
        $regularAbsentCount = 0;
        foreach ($attendanceSelections as $employeeId => $status) {
            if ($status === 'absent') {
                if (isset($casualLeaves[$employeeId]) && $casualLeaves[$employeeId] === '1') {
                    $casualLeaveCount++;
                } elseif (isset($lopLeaves[$employeeId]) && $lopLeaves[$employeeId] === '1') {
                    $lopCount++;
                } else {
                    $regularAbsentCount++;
                }
            }
        }

        $message = 'You have successfully submitted the attendance!';
        if ($casualLeaveCount > 0) {
            $message .= " ($casualLeaveCount casual leave(s) marked)";
        }
        if ($lopCount > 0) {
            $message .= " ($lopCount LOP day(s) marked)";
        }
        if ($regularAbsentCount > 0) {
            $message .= " ($regularAbsentCount regular absent(s) marked)";
        }

        flash()->success('Success', $message);
        return back();
    }
    public function sheetReport()
    {

    return view('admin.sheet-report')->with(['employees' => Employees::all()]);
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
        $filename= 'Product-'.date('d-m-y').'.'.$exportFormat;

        return Excel::download(new SalaryExport,$filename, $exportFormat);

    }

    public function saveInline(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|integer|exists:employees,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,half_day',
            'login_time' => 'nullable|date_format:H:i',
            'logout_time' => 'nullable|date_format:H:i',
            'permission_taken' => 'nullable|in:0,1',
            'permission_reason' => 'nullable|string',
            'permission_from' => 'nullable|date_format:H:i',
            'permission_to' => 'nullable|date_format:H:i',
            'casual_leave' => 'nullable|in:0,1',
            'lop' => 'nullable|in:0,1',
            'half_type' => 'nullable|in:first,second',
            'category'=> 'nullable|string',
        ]);

        try {
            $employeeId = (int) $validated['employee_id'];
            $date = $validated['attendance_date'];
            $status = $validated['status'];

            $loginTime = isset($validated['login_time']) && $validated['login_time'] !== ''
                ? $validated['login_time'] . ':00'
                : null;
            $logoutTime = isset($validated['logout_time']) && $validated['logout_time'] !== ''
                ? $validated['logout_time'] . ':00'
                : null;

            $employee = Employees::whereId($employeeId)->first();
            if (!$employee) {
                return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
            }

            // Fallback to schedule times if available and inputs are missing
            if ((!$loginTime || !$logoutTime) && $employee->schedules && $employee->schedules->first()) {
                $loginTime = $loginTime ?: date('H:i:s', strtotime($employee->schedules->first()->time_in));
                $logoutTime = $logoutTime ?: date('H:i:s', strtotime($employee->schedules->first()->time_out));
            }

            switch ($status) {
            case 'present':
                $hasPermission = ($validated['permission_taken'] ?? '0') === '1';
                $permissionReason = $validated['permission_reason'] ?? null;
                $permissionFromTime = $validated['permission_from'] ?? null;
                $permissionToTime = $validated['permission_to'] ?? null;

                $exists = Attendance::where('emp_id', $employeeId)
                    ->where('attendance_date', $date)
                    ->where('type', 0)
                    ->first();
                if (!$exists) {
                    $record = new Attendance();
                } else {
                    $record = $exists;
                }
                $record->emp_id = $employeeId;
                $record->attendance_date = $date;
                $record->attendance_time = $loginTime ?: date('H:i:s');
                $record->leave_time = $logoutTime;
                $record->type = 0;
                try {
                    $record->login_time = $loginTime ?: null;
                    $record->logout_time = $logoutTime ?: null;
                    $record->permission_taken = $hasPermission ? '1' : '0';
                    $record->permission_reason = $hasPermission ? ($permissionReason ?: null) : null;
                    $record->permission_from = ($hasPermission && $permissionFromTime) ? ($permissionFromTime . ':00') : null;
                    $record->permission_to = ($hasPermission && $permissionToTime) ? ($permissionToTime . ':00') : null;
                } catch (\Exception $e) {
                    // ignore if columns absent
                }
                try {
                    $record->save();
                } catch (\Throwable $e) {
                    // Fallback: some schemas use DATETIME for these fields
                    try {
                        if (isset($record->login_time)) {
                            $record->login_time = $loginTime ? ($date . ' ' . $loginTime) : null;
                        }
                        if (isset($record->logout_time)) {
                            $record->logout_time = $logoutTime ? ($date . ' ' . $logoutTime) : null;
                        }
                        if (isset($record->permission_from) && $permissionFromTime) {
                            $record->permission_from = $date . ' ' . ($permissionFromTime . ':00');
                        }
                        if (isset($record->permission_to) && $permissionToTime) {
                            $record->permission_to = $date . ' ' . ($permissionToTime . ':00');
                        }
                        $record->save();
                    } catch (\Throwable $e2) {
                        throw $e; // bubble original
                    }
                }

                if ($hasPermission && $permissionReason) {
                    $permissionExists = Leave::where('emp_id', $employeeId)
                        ->where('leave_date', $date)
                        ->where('type', 2)
                        ->first();
                    if (!$permissionExists) {
                        $permission = new Leave();
                        $permission->emp_id = $employeeId;
                        $permission->leave_date = $date;
                        $permission->leave_time = ($permissionToTime ? $permissionToTime . ':00' : date('H:i:s'));
                        $permission->type = 2;
                        if (Schema::hasColumn('leaves', 'category')) {
                            $permission->category = 'permission';
                        }
                        $permission->save();
                    }
                }
                break;

            case 'absent':
                $isCasualLeave = ($validated['casual_leave'] ?? '0') === '1';
                $isLOP = ($validated['lop'] ?? '0') === '1';

                $exists = Leave::where('emp_id', $employeeId)
                    ->where('leave_date', $date)
                    ->where('type', 1)
                    ->first();
                if (!$exists) {
                    $leave = new Leave();
                    $leave->emp_id = $employeeId;
                    $leave->leave_date = $date;
                    $leave->leave_time = $logoutTime ?: date('H:i:s');
                    $leave->type = 1;
                    if (Schema::hasColumn('leaves', 'category')) {
                        if ($isCasualLeave) {
                            $leave->category = 'casual';
                        } elseif ($isLOP) {
                            $leave->category = 'lop';
                        } else {
                            $leave->category = null;
                        }
                    }
                    $leave->save();
                }
                break;

            case 'half_day':
                $halfCategory = $validated['half_type'] ?? null; // 'first' | 'second'

                $attExists = Attendance::where('emp_id', $employeeId)
                    ->where('attendance_date', $date)
                    ->where('type', 0)
                    ->first();
                if (!$attExists) {
                    $att = new Attendance();
                } else {
                    $att = $attExists;
                }
                $att->emp_id = $employeeId;
                $att->attendance_date = $date;
                $att->attendance_time = $loginTime ?: date('H:i:s');
                $att->leave_time = $logoutTime;
                $att->type = 0;
                try {
                    $att->half_type = $halfCategory;
                    $att->half_login_time = $loginTime ?: null;
                    $att->half_logout_time = $logoutTime ?: null;
                } catch (\Exception $e) {
                    // ignore if columns absent
                }
                try {
                    $att->save();
                } catch (\Throwable $e) {
                    // Fallback for DATETIME schemas
                    try {
                        if (isset($att->half_login_time)) {
                            $att->half_login_time = $loginTime ? ($date . ' ' . $loginTime) : null;
                        }
                        if (isset($att->half_logout_time)) {
                            $att->half_logout_time = $logoutTime ? ($date . ' ' . $logoutTime) : null;
                        }
                        $att->save();
                    } catch (\Throwable $e2) {
                        throw $e;
                    }
                }

                // Do not create a leave record for half day; store only in attendances
                break;
            }

            return response()->json(['success' => true, 'message' => 'Attendance saved']);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function statusByDate(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date',
        ]);

        $date = $request->query('date');

        // If no date provided, return empty result
        if (!$date) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $trimTime = function ($time) {
            if (!$time) { return null; }
            // Expect formats like HH:MM:SS or YYYY-MM-DD HH:MM:SS
            if (preg_match('/^\d{2}:\d{2}/', $time)) {
                return substr($time, 0, 5);
            }
            if (preg_match('/\s(\d{2}:\d{2})/', $time, $m)) {
                return $m[1];
            }
            return $time;
        };

        $result = [];
        $employees = Employees::all(['id']);
        foreach ($employees as $emp) {
            $empId = (int) $emp->id;

            // Prefer half day if explicitly recorded
            $attendance = Attendance::where('emp_id', $empId)
                ->where('attendance_date', $date)
                ->first();

            $leave = Leave::where('emp_id', $empId)
                ->where('leave_date', $date)
                ->where('type', 1)
                ->first();

            $status = null;
            $payload = [
                'status' => null,
                'login_time' => null,
                'logout_time' => null,
                'permission_taken' => '0',
                'permission_reason' => null,
                'permission_from' => null,
                'permission_to' => null,
                'half_type' => null,
                'casual_leave' => '0',
                'lop' => '0',
            ];

            if ($attendance) {
                // Determine if this is half day or full present
                $halfType = null;
                try { $halfType = $attendance->half_type ?? null; } catch (\Throwable $e) { $halfType = null; }
                if ($halfType) {
                    $status = 'half_day';
                    $payload['half_type'] = $halfType;
                } else if ((int)($attendance->type ?? 0) === 0) {
                    $status = 'present';
                }

                // Times and permission info if available
                try { $payload['login_time'] = $trimTime($attendance->login_time ?? $attendance->attendance_time ?? null); } catch (\Throwable $e) {}
                try { $payload['logout_time'] = $trimTime($attendance->logout_time ?? $attendance->leave_time ?? null); } catch (\Throwable $e) {}
                try { $payload['permission_taken'] = ($attendance->permission_taken ?? '0') ? '1' : '0'; } catch (\Throwable $e) {}
                try { $payload['permission_reason'] = $attendance->permission_reason ?? null; } catch (\Throwable $e) {}
                try { $payload['permission_from'] = $trimTime($attendance->permission_from ?? null); } catch (\Throwable $e) {}
                try { $payload['permission_to'] = $trimTime($attendance->permission_to ?? null); } catch (\Throwable $e) {}
            }

            if (!$status && $leave) {
                $status = 'absent';
                // category may not exist
                $category = null;
                try { $category = $leave->category ?? null; } catch (\Throwable $e) { $category = null; }
                if ($category === 'casual') { $payload['casual_leave'] = '1'; }
                if ($category === 'lop') { $payload['lop'] = '1'; }
            }

            if ($status) {
                $payload['status'] = $status;
                $result[$empId] = $payload;
            }
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function getStatistics(Request $request)
    {
        $request->validate([
            'period' => 'nullable|in:today,yesterday,this_week,last_week,this_month,last_month',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'employee_id' => 'nullable|integer|exists:employees,id'
        ]);

        $period = $request->input('period');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $employeeId = $request->input('employee_id');

        // Calculate date range based on period
        $dateRange = $this->calculateDateRange($period, $startDate, $endDate);

        // Add debugging
        \Log::info('Attendance Statistics Request', [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'employee_id' => $employeeId,
            'calculated_date_range' => $dateRange
        ]);

        if (!$dateRange) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid date range'
            ], 400);
        }

        $query = Employees::query();

        if ($employeeId) {
            $query->where('id', $employeeId);
        }

        $employees = $query->get();
        $statistics = [];

        foreach ($employees as $employee) {
            // Count full present days (half_type is NULL)
            $presentCount = Attendance::where('emp_id', $employee->id)
                ->where('type', 0)
                ->whereNull('half_type')
                ->whereBetween('attendance_date', [$dateRange['start'], $dateRange['end']])
                ->count();

            // Count half days (half_type has value)
            $halfDayCount = Attendance::where('emp_id', $employee->id)
                ->where('type', 0)
                ->whereNotNull('half_type')
                ->whereBetween('attendance_date', [$dateRange['start'], $dateRange['end']])
                ->count();

            $casualLeaveCount = 0;
            $lopCount = 0;

            if (Schema::hasColumn('leaves', 'category')) {
                // Categorized leaves
                $casualLeaveCount = Leave::where('emp_id', $employee->id)
                    ->where('type', 1)
                    ->where('category', 'casual')
                    ->whereBetween('leave_date', [$dateRange['start'], $dateRange['end']])
                    ->count();

                $lopCount = Leave::where('emp_id', $employee->id)
                    ->where('type', 1)
                    ->where('category', 'lop')
                    ->whereBetween('leave_date', [$dateRange['start'], $dateRange['end']])
                    ->count();
            } else {
                // Legacy schema - count all type=1 leaves
                $legacyLeaveCount = Leave::where('emp_id', $employee->id)
                    ->where('type', 1)
                    ->whereBetween('leave_date', [$dateRange['start'], $dateRange['end']])
                    ->count();
                // For legacy, we'll show this as a general leave count
                $casualLeaveCount = $legacyLeaveCount;
            }

            $totalDays = $presentCount + $casualLeaveCount + $lopCount + $halfDayCount;

            // Calculate total salary based on attendance
            $totalSalary = $this->calculateTotalSalary(
                $employee->salary,
                $presentCount,
                $halfDayCount,
                $casualLeaveCount,
                $lopCount,
                $dateRange
            );

            // Add debugging for salary calculation
            \Log::info('Salary Calculation Debug', [
                'employee_id' => $employee->id,
                'employee_name' => $employee->employee_name,
                'monthly_salary' => $employee->salary,
                'present_count' => $presentCount,
                'half_day_count' => $halfDayCount,
                'casual_leave_count' => $casualLeaveCount,
                'lop_count' => $lopCount,
                'total_days' => $totalDays,
                'calculated_total_salary' => $totalSalary,
                'date_range' => $dateRange,
                'salary_type' => gettype($employee->salary),
                'present_count_type' => gettype($presentCount),
                'half_day_count_type' => gettype($halfDayCount),
                'casual_leave_count_type' => gettype($casualLeaveCount),
                'lop_count_type' => gettype($lopCount)
            ]);

            $statistics[] = [
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->employee_name,
                'emp_salary' => $employee->salary,
                'present_days' => $presentCount,
                'casual_leaves' => $casualLeaveCount,
                'lop_days' => $lopCount,
                'half_days' => $halfDayCount,
                'total_days' => $totalDays,
                'total_salary' => $totalSalary
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $statistics,
            'period' => $period,
            'date_range' => $dateRange,
            'total_employees' => count($statistics)
        ]);
    }

    /**
     * Test method to verify salary calculation
     */
    public function testSalaryCalculation()
    {
        // Test with sample data
        $monthlySalary = 15000.00;
        $presentDays = 1;
        $halfDays = 0;
        $casualLeaves = 0;
        $lopDays = 0;
        $dateRange = [
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d')
        ];

        $totalSalary = $this->calculateTotalSalary(
            $monthlySalary,
            $presentDays,
            $halfDays,
            $casualLeaves,
            $lopDays,
            $dateRange
        );

        return response()->json([
            'test_data' => [
                'monthly_salary' => $monthlySalary,
                'present_days' => $presentDays,
                'half_days' => $halfDays,
                'casual_leaves' => $casualLeaves,
                'lop_days' => $lopDays,
                'date_range' => $dateRange
            ],
            'calculated_salary' => $totalSalary,
            'expected_daily_rate' => $monthlySalary / 22,
            'expected_salary' => ($monthlySalary / 22) * $presentDays
        ]);
    }

    /**
     * Debug method to check current attendance data
     */
    public function debugAttendanceData()
    {
        $today = date('Y-m-d');

        // Get all attendance records for today
        $todayAttendance = Attendance::where('attendance_date', $today)->get();

        // Get all employees
        $employees = Employees::all(['id', 'employee_id', 'employee_name', 'salary']);

        $debugData = [];

        foreach ($employees as $employee) {
            $attendance = $todayAttendance->where('emp_id', $employee->id)->first();

            $debugData[] = [
                'employee_id' => $employee->employee_id,
                'employee_name' => $employee->employee_name,
                'salary' => $employee->salary,
                'has_attendance_today' => $attendance ? true : false,
                'attendance_type' => $attendance ? $attendance->type : null,
                'half_type' => $attendance ? $attendance->half_type : null,
                'attendance_time' => $attendance ? $attendance->attendance_time : null
            ];
        }

        return response()->json([
            'today_date' => $today,
            'total_employees' => count($employees),
            'total_attendance_today' => count($todayAttendance),
            'employee_data' => $debugData
        ]);
    }

    /**
     * Calculate total salary based on attendance data
     */
    private function calculateTotalSalary($monthlySalary, $presentDays, $halfDays, $casualLeaves, $lopDays, $dateRange)
    {
        // Validate inputs
        if (!is_numeric($monthlySalary) || $monthlySalary <= 0) {
            \Log::error('Invalid monthly salary', ['salary' => $monthlySalary]);
            return 0;
        }

        if (!is_numeric($presentDays) || !is_numeric($halfDays) || !is_numeric($casualLeaves) || !is_numeric($lopDays)) {
            \Log::error('Invalid attendance counts', [
                'present_days' => $presentDays,
                'half_days' => $halfDays,
                'casual_leaves' => $casualLeaves,
                'lop_days' => $lopDays
            ]);
            return 0;
        }

        // For single day periods (today, yesterday), use a standard daily rate
        if ($dateRange['start'] === $dateRange['end']) {
            // Use standard 22 working days per month for daily calculation
            $dailySalary = $monthlySalary / 22;
        } else {
            // For multi-day periods, calculate based on actual working days
            $totalWorkingDays = $this->getTotalWorkingDays($dateRange);
            if ($totalWorkingDays == 0) {
                return 0;
            }
            $dailySalary = $monthlySalary / $totalWorkingDays;
        }

        // Ensure daily salary is not zero
        if ($dailySalary <= 0) {
            // Fallback to standard calculation
            $dailySalary = $monthlySalary / 22; // Assume 22 working days per month
        }

        // Calculate salary for different types of days
        $presentSalary = $presentDays * $dailySalary; // Full salary for present days
        $halfDaySalary = $halfDays * ($dailySalary * 0.5); // Half salary for half days
        $casualLeaveSalary = $casualLeaves * $dailySalary; // Full salary for casual leaves
        $lopSalary = $lopDays * 0; // No salary for LOP days

        // Total calculated salary
        $totalSalary = $presentSalary + $halfDaySalary + $casualLeaveSalary + $lopSalary;

        // Add debugging
        \Log::info('Salary Calculation Details', [
            'monthly_salary' => $monthlySalary,
            'present_days' => $presentDays,
            'half_days' => $halfDays,
            'casual_leaves' => $casualLeaves,
            'lop_days' => $lopDays,
            'date_range' => $dateRange,
            'is_single_day' => ($dateRange['start'] === $dateRange['end']),
            'daily_salary' => $dailySalary,
            'present_salary' => $presentSalary,
            'half_day_salary' => $halfDaySalary,
            'casual_leave_salary' => $casualLeaveSalary,
            'lop_salary' => $lopSalary,
            'total_calculated_salary' => $totalSalary
        ]);

        // Round to 2 decimal places
        return round($totalSalary, 2);
    }

    /**
     * Get total working days in the given date range (excluding weekends)
     */
    private function getTotalWorkingDays($dateRange)
    {
        $startDate = Carbon::parse($dateRange['start']);
        $endDate = Carbon::parse($dateRange['end']);

        $workingDays = 0;
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            // Count only weekdays (Monday = 1, Tuesday = 2, ..., Friday = 5)
            // Carbon dayOfWeek returns: 0 = Sunday, 1 = Monday, ..., 6 = Saturday
            if ($currentDate->dayOfWeek >= 1 && $currentDate->dayOfWeek <= 5) {
                $workingDays++;
            }
            $currentDate->addDay();
        }

        return $workingDays;
    }

    private function calculateDateRange($period, $startDate = null, $endDate = null)
    {
        $now = Carbon::now();

        if ($period === 'custom' && $startDate && $endDate) {
            return [
                'start' => $startDate,
                'end' => $endDate
            ];
        }

        switch ($period) {
            case 'today':
                return [
                    'start' => $now->format('Y-m-d'),
                    'end' => $now->format('Y-m-d')
                ];

            case 'yesterday':
                $yesterday = $now->copy()->subDay();
                return [
                    'start' => $yesterday->format('Y-m-d'),
                    'end' => $yesterday->format('Y-m-d')
                ];

            case 'this_week':
                return [
                    'start' => $now->copy()->startOfWeek()->format('Y-m-d'),
                    'end' => $now->copy()->endOfWeek()->format('Y-m-d')
                ];

            case 'last_week':
                $lastWeek = $now->copy()->subWeek();
                return [
                    'start' => $lastWeek->startOfWeek()->format('Y-m-d'),
                    'end' => $lastWeek->endOfWeek()->format('Y-m-d')
                ];

            case 'this_month':
                return [
                    'start' => $now->copy()->startOfMonth()->format('Y-m-d'),
                    'end' => $now->copy()->endOfMonth()->format('Y-m-d')
                ];

            case 'last_month':
                $lastMonth = $now->copy()->subMonth();
                return [
                    'start' => $lastMonth->startOfMonth()->format('Y-m-d'),
                    'end' => $lastMonth->endOfMonth()->format('Y-m-d')
                ];

            default:
                // Default to current month if no valid period
                return [
                    'start' => $now->copy()->startOfMonth()->format('Y-m-d'),
                    'end' => $now->copy()->endOfMonth()->format('Y-m-d')
                ];
        }
    }
}