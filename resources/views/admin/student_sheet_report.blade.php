@extends('layouts.master')
@section('content')

    <div class="card">
        <div class="card-header bg-success text-white">
            TimeTable
            {{-- <div class=""><span>Monthly Filter </span></div> --}}
            <form action="{{ route('sheet.report') }}" method="GET" class="form-inline mb-3">
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
                            <th>Student Name</th>
                            <th>Student ID</th>
                            <th>Gender</th>
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
                                // Ensure today is included if it's in the selected month and not already in the array
                                if (
                                    $today->year == $selectedYear &&
                                    $today->month == $selectedMonthNum &&
                                    !in_array($today->format('Y-m-d'), $dates)
                                ) {
                                    $dates[] = $today->format('Y-m-d');
                                }
                            @endphp
                            @foreach ($dates as $date)
                            <th
                                @if ($date == $today->format('Y-m-d'))
                                    style="background: #ffe082; font-weight: bold;"
                                @endif
                            >
                                    {{ $date }}
                        </th>
                            @endforeach
                            <th class="text-center bg-success text-white">Present</th>
                            <th class="text-center bg-danger text-white">Absent</th>
                            <th class="text-center bg-primary text-white">Total Days</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                          @php
                                $totalPresent = 0;
                                $totalAbsent = 0;
                                $totalDays = 0;
                            @endphp
                            <input type="hidden" name="student_id" value="{{ $student->id }}">
                            <tr>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->gender }}</td>
                                @foreach ($dates as $date_picker)
                                    @php
                                        $check_attd = \App\Models\Student_attendance::query()
                                            ->where('student_id', $student->id)
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
                                    <td
                                        @if ($date_picker == $today->format('Y-m-d'))
                                            style="background: #ffe082; font-weight: bold;"
                                        @endif
                                    >
                                        <div class="form-check form-check-inline ">
                                            @if (isset($check_attd))
                                                 @if ($check_attd->status==1)
                                                 <i class="fa fa-check text-success"></i>
                                                 @else
                                                 <i class="fa fa-check text-danger"></i>
                                                 @endif
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

    {{-- <div class="card">
   <div class="card">
    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <span>Employee Salary</span>
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
                            </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <input type="hidden" name="emp_id" value="{{ $student->id }}">
                            <tr>
                                <td>{{ $student->student_name }}</td>
                                <td>{{ $student->student_id }}</td>
                                <td>{{ $student->gender }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}
  </div>
@endsection