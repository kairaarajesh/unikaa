@extends('layouts.master')
@section('content')

    <div class="card">
        <div class="card-header bg-success text-white">
            TimeTable
            {{-- <div class=""><span>Monthly Filter </span></div> --}}
            <form action="{{ route('employee.report') }}" method="GET" class="form-inline mb-3">
                <label for="month" class="mr-2">Select Month:</label>
                <input type="month" name="month" id="month" class="form-control mr-2"
                    value="{{ request('month') ?? now()->format('Y-m') }}">
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="printTable">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Employee Position</th>
                            <th>Employee ID</th>

                            @php
                                $selectedMonth = request('month') ?? now()->format('Y-m');
                                $selectedYear = \Carbon\Carbon::parse($selectedMonth . '-01')->year;
                                $selectedMonthNum = \Carbon\Carbon::parse($selectedMonth . '-01')->month;
                                $daysInMonth = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonthNum, 1)->daysInMonth;
                                $dates = [];
                                $today = \Carbon\Carbon::today();
                                for ($i = 1; $i <= $daysInMonth; ++$i) {
                                    $currentDate = \Carbon\Carbon::createFromDate($selectedYear, $selectedMonthNum, $i);
                                    if ($currentDate->lessThanOrEqualTo($today)) {
                                        $dates[] = $currentDate->format('Y-m-d');
                                    }
                                }
                                if (!in_array($today->format('Y-m-d'), $dates)) {
                                    $dates[] = $today->format('Y-m-d');
                                }
                            @endphp
                            @foreach ($dates as $date)
                            <th style="">
                                    {{ $date }}
                        </th>

                            @endforeach
                            <th class="text-center bg-success text-white">Present</th>
                            <th class="text-center bg-danger text-white">Absent</th>
                            <th class="text-center bg-primary text-white">Total Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            @php
                                $totalPresent = 0;
                                $totalAbsent = 0;
                                $totalDays = 0;
                            @endphp
                            <input type="hidden" name="emp_id" value="{{ $employee->id }}">
                            <tr>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->employee_id }}</td>
                                @foreach ($dates as $date_picker)
                                    @php
                                        $check_attd = \App\Models\Attendance::query()
                                            ->where('emp_id', $employee->id)
                                            ->where('attendance_date', $date_picker)
                                            ->first();

                                        // If attendance record exists, employee is present
                                        $isPresent = isset($check_attd);
                                        $isAbsent = !isset($check_attd);

                                        if ($isPresent) {
                                            $totalPresent++;
                                        } else {
                                            $totalAbsent++;
                                        }
                                        $totalDays++;
                                    @endphp
                                    <td>
                                        <div class="form-check form-check-inline ">
                                            @if (isset($check_attd))
                                                 <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fas fa-times text-danger"></i>
                                            @endif
                                        </div>
                                        {{-- <div class="form-check form-check-inline">
                                            @if (isset($check_leave))
                                            @if ($check_leave->status==1)
                                            <i class="fa fa-check text-success"></i>
                                            @else
                                            <i class="fa fa-check text-danger"></i>
                                            @endif
                                       @else
                                       <i class="fas fa-times text-danger"></i>
                                       @endif
                                        </div> --}}
                                    </td>
                                {{-- <td>{{ $employee->attendances_count }}</td> --}}
                                @endforeach
                                <td class="text-center font-weight-bold text-success">{{ $totalPresent }}</td>
                                <td class="text-center font-weight-bold text-danger">{{ $totalAbsent }}</td>
                                <td class="text-center font-weight-bold text-primary">{{ $totalDays }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
   <div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <span>Employee Salary</span>

        <form action="{{ url('salary/export') }}" method="GET" class="row gx-2 align-items-end mb-0">
            <div class="col-auto">
                <label for="type" class="form-label text-white mb-0 small">Select Type</label>
                <select name="type" id="type" class="form-select form-select-sm">
                    <option value="">Select Type</option>
                    <option value="xlsx">XLSX</option>
                    <option value="csv">CSV</option>
                    <option value="xls">XLS</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-light btn-sm">Export / Report Download</button>
            </div>
        </form>
    </div>

    <div class="card-body">
    </div>
</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-sm" id="printTable">
                    <thead>
                            <tr>
                                            <th>Employee Name</th>
                                            <th>Employee Position</th>
                                            <th>Employee ID</th>
                                            <th>Salary</th>
                                            <th>Total Dates</th>
                                            <th>Total Salary</th>
                            </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            @php
                                $totalPresent = 0;
                                foreach ($dates as $date_picker) {
                                    $check_attd = \App\Models\Attendance::query()
                                        ->where('emp_id', $employee->id)
                                        ->where('attendance_date', $date_picker)
                                        ->first();
                                    if (isset($check_attd)) {
                                        $totalPresent++;
                                    }
                                }
                                $perDaySalary = $employee->salary / count($dates);
                                $totalSalary = $perDaySalary * $totalPresent;
                            @endphp
                            <input type="hidden" name="emp_id" value="{{ $employee->id }}">
                            <tr>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->position }}</td>
                                <td>{{ $employee->employee_id }}</td>
                                <td>{{ $employee->salary }}</td>
                                <td>{{ $totalPresent }}</td>
                                <td>
                                    {{ number_format($totalSalary, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
@endsection