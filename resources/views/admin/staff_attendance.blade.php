@extends('layouts.master')

@section('css')
    <!-- Table css -->
    <link href="{{ URL::asset('plugins/RWD-Table-Patterns/dist/css/rwd-table.min.css') }}" rel="stylesheet"
        type="text/css" media="screen">
@endsection


@section('content')

    <div class="card">

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-responsive table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Trainer Name</th>
                            <th>Subject</th>
                            {{-- @php
                                $today = today();
                                $dates = [];

                                for ($i = 1; $i < $today->daysInMonth + 1; ++$i) {
                                    $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
                                }
                            @endphp
                            @foreach ($dates as $date)
                                <th>
                                    {{ $date }}
                                </th>
                            @endforeach --}}
{{-- new --}}
                            @php
    $today = today();
    $dates = [];

    // Use day number up to today's day, not the days in month
    for ($i = 1; $i <= $today->day; ++$i) {
        $dates[] = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');
    }
@endphp

@foreach ($dates as $date)
    <th>{{ $date }}</th>
@endforeach

                        </tr>
                    </thead>
                    <tbody>
                        <form action="{{ route('staff_attendance_store') }}" method="GET">

                            <button type="submit" class="btn btn-success" style="display: flex; margin:10px">submit</button>
                            @csrf
                            @foreach ($Staff_managements as $staff_management)

                                <input type="hidden" name="staff_management__id" value="{{ $staff_management->id }}">
                                <tr>
                                    <td>{{ $staff_management->trainer }}</td>
                                    <td>{{ $staff_management->subject }}</td>
                                    {{-- <td>{{ $employee->employee_id }}</td> --}}

                                    {{-- @for ($i = 1; $i < $today->daysInMonth + 1; ++$i)
                                        @php
                                            $date_picker = \Carbon\Carbon::createFromDate($today->year, $today->month, $i)->format('Y-m-d');

                                            $check_attd = \App\Models\Attendancelog::query()
                                                ->where('emp_id', $employee->id)
                                                ->where('attendance_date', $date_picker)
                                                ->first();

                                            $check_leave = \App\Models\Leave::query()
                                                ->where('emp_id', $employee->id)
                                                ->where('leave_date', $date_picker)
                                                ->first();

                                        @endphp
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="check_box"
                                                    name="attd[{{ $date_picker }}][{{ $employee->id }}]" type="checkbox"
                                                    @if (isset($check_attd))  checked @endif id="inlineCheckbox1" value="1">

                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" id="check_box"
                                                    name="leave[{{ $date_picker }}][{{ $employee->id }}]]" type="checkbox"
                                                    @if (isset($check_leave))  checked @endif id="inlineCheckbox2" value="1">

                                            </div>

                                        </td>

                                    @endfor --}}


                                    {{-- before days and today --}}
                      {{-- @php
    $todayDate = \Carbon\Carbon::today();
@endphp

@for ($i = 1; $i <= $todayDate->day; ++$i)
    @php
        $date_check = \Carbon\Carbon::createFromDate($todayDate->year, $todayDate->month, $i);
        $date_picker = $date_check->format('Y-m-d');

        $check_attd = \App\Models\Attendancelog::where('emp_id', $employee->id)
            ->where('attendance_date', $date_picker)
            ->first();

        $check_leave = \App\Models\Leave::where('emp_id', $employee->id)
            ->where('leave_date', $date_picker)
            ->first();
    @endphp

    <td>
        <div class="form-check form-check-inline">
            <input class="form-check-input"
                name="attd[{{ $date_picker }}][{{ $employee->id }}]"
                type="checkbox"
                @if ($check_attd) checked @endif
                value="1">
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input"
                name="leave[{{ $date_picker }}][{{ $employee->id }}]"
                type="checkbox"
                @if ($check_leave) checked @endif
                value="1">
        </div>
    </td>
@endfor --}}

@php
    $todayDate = \Carbon\Carbon::today();
    $yesterdayDate = \Carbon\Carbon::yesterday();
@endphp

@for ($i = 1; $i <= $todayDate->day; ++$i)
    @php
        $date_check = \Carbon\Carbon::createFromDate($todayDate->year, $todayDate->month, $i);
        $date_picker = $date_check->format('Y-m-d');

        $check_attd = \App\Models\staff_attendance::where('staff_management__id', $staff_management->id)
            ->where('attendance_date', $date_picker)
            ->first();
 @endphp
        {{-- $check_leave = \App\Models\Leave::where('staff_management__id', $student->id)
            ->where('leave_date', $date_picker)
            ->first(); --}}
     @php
        // ✅ Make yesterday and today editable
        $is_editable = $date_check->isToday() || $date_check->isSameDay($yesterdayDate);
    @endphp

    <td>
        <div class="form-check form-check-inline">
            <input class="form-check-input"
                name="attd[{{ $date_picker }}][{{ $staff_management->id }}]"
                type="checkbox"
                @if ($check_attd) checked @endif
                @if (!$is_editable) disabled @endif
                value="1">
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input"
                name="leave[{{ $date_picker }}][{{ $staff_management->id }}]"
                type="checkbox"
                {{-- @if ($check_leave) checked @endif --}}
                @if (!$is_editable) disabled @endif
                value="1">
        </div>
    </td>
@endfor
                                </tr>
                            @endforeach
                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection