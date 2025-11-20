@extends('layouts.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h4 class="mb-3">Employee Attendance</h4>

        <style>
            .attd-pills input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
            .attd-pills .pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border: 1px solid #e1e6ea; border-radius: 14px; background: #fff; color: #6c757d; cursor: pointer; font-size: 12px; }
            .attd-pills .pill:hover { background: #f8f9fa; border-color: #cfd6dc; }
            .attd-pills input[type="radio"]:checked + .pill.present { background: #e8f7ee; border-color: #198754; color: #198754; }
            .attd-pills input[type="radio"]:checked + .pill.absent { background: #fdeaea; border-color: #dc3545; color: #dc3545; }
            .attd-pills input[type="radio"]:checked + .pill.half { background: #fff7e6; border-color: #ffc107; color: #b58100; }
            /* Styles for read-only list view pills (without radio buttons) */
            .attendance-list-view .attd-pills .pill { cursor: default; }
            .attendance-list-view .attd-pills .pill:hover { background: inherit; border-color: inherit; }
            .attendance-list-view .attd-pills .pill.present { background: #e8f7ee; border-color: #198754; color: #198754; }
            .attendance-list-view .attd-pills .pill.absent { background: #fdeaea; border-color: #dc3545; color: #dc3545; }
            .attendance-list-view .attd-pills .pill.half { background: #fff7e6; border-color: #ffc107; color: #b58100; }
            .attd-pills .pill.saved-success { box-shadow: 0 0 0 2px rgba(25,135,84,0.25) inset; }
            .attd-pills .pill.today-success { border-color: #198754 !important; background: #e8f7ee !important; color: #198754 !important; }
            /* Hide colors when no date is selected */
            .no-date-selected .attd-pills input[type="radio"]:checked + .pill { background: #fff !important; border-color: #e1e6ea !important; color: #6c757d !important; }
            .no-date-selected .attd-pills .pill.saved-success { box-shadow: none !important; }
            .no-date-selected .attd-pills .pill.today-success { border-color: #e1e6ea !important; background: #fff !important; color: #6c757d !important; }
            /* Disable radio buttons when no date selected */
            .no-date-selected .attd-pills input[type="radio"] { pointer-events: none; }
            .no-date-selected .attd-pills .pill { cursor: not-allowed; opacity: 0.6; }

            /* Filter Section Styling */
            .filter-section { background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 20px; }
            .filter-section .form-label { font-weight: 600; color: #495057; margin-bottom: 8px; }
            .filter-section .form-select { border: 1px solid #ced4da; border-radius: 6px; }
            .filter-section .form-select:focus { border-color: #80bdff; box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25); }
            .filter-section .btn { border-radius: 6px; font-weight: 500; }
            .filter-section .btn-primary { background-color: #007bff; border-color: #007bff; }
            .filter-section .btn-primary:hover { background-color: #0056b3; border-color: #0056b3; }
            .filter-section .btn-secondary { background-color: #6c757d; border-color: #6c757d; }
            .filter-section .btn-secondary:hover { background-color: #545b62; border-color: #545b62; }

            /* Custom Date Range Styling */
            #customDateRange {
                background: #e9ecef;
                border-radius: 6px;
                padding: 15px;
                margin-top: 10px;
                border: 1px solid #ced4da;
            }
            #customDateRange .form-control {
                border: 1px solid #ced4da;
                border-radius: 6px;
            }
            #customDateRange .form-control:focus {
                border-color: #80bdff;
                box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
            }

            /* Statistics Table Enhancement */
            #attendanceStatsTable { box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
            #attendanceStatsTable thead th { background: #f8f9fa; color: #495057; border: 1px solid #dee2e6; font-weight: 600; }
            #attendanceStatsTable tbody tr:hover { background-color: #f8f9fa; }

            /* Enhanced Table Styling */
            #attendanceStatsTable td, #attendanceStatsTable th {
                padding: 12px 8px;
                vertical-align: middle;
                border-color: #dee2e6;
            }
            #attendanceStatsTable tbody tr {
                transition: all 0.2s ease-in-out;
            }
            #attendanceStatsTable tbody tr:hover {
                transform: translateY(-1px);
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            }

            /* Salary Column Styling */
            #attendanceStatsTable td:nth-child(3),
            #attendanceStatsTable td:nth-child(9) {
                font-weight: 600;
                color: #28a745;
                text-align: right;
            }
            #attendanceStatsTable th:nth-child(3),
            #attendanceStatsTable th:nth-child(9) {
                text-align: center;
            }

            /* Notification Styling */
            .alert {
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                border: none;
                border-radius: 8px;
            }
            .alert-success {
                background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                color: #155724;
            }
            .alert-danger {
                background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
                color: #721c24;
            }

            /* List View Styling for Past Dates */
            .attendance-list-view {
                display: none;
            }
            .attendance-list-view.active {
                display: block;
            }
            .attendance-list-item {
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: 8px;
                padding: 15px;
                margin-bottom: 12px;
                transition: all 0.2s ease;
            }
            .attendance-list-item:hover {
                box-shadow: 0 2px 8px rgba(0,0,0,0.1);
                transform: translateY(-1px);
            }
            .attendance-list-item .emp-info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
                gap: 10px;
            }
            .attendance-list-item .emp-details {
                flex: 1;
                min-width: 200px;
            }
            .attendance-list-item .emp-id {
                font-weight: 600;
                color: #495057;
                font-size: 14px;
            }
            .attendance-list-item .emp-name {
                color: #6c757d;
                font-size: 13px;
                margin-top: 4px;
            }
            .attendance-list-item .emp-branch {
                color: #868e96;
                font-size: 12px;
                margin-top: 2px;
            }
            .attendance-list-item .attendance-status {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .attendance-list-item .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }
            .status-badge.present {
                background: #e8f7ee;
                color: #198754;
                border: 1px solid #198754;
            }
            .status-badge.absent {
                background: #fdeaea;
                color: #dc3545;
                border: 1px solid #dc3545;
            }
            .status-badge.half-day {
                background: #fff7e6;
                color: #b58100;
                border: 1px solid #ffc107;
            }
            .status-badge:not(.present):not(.absent):not(.half-day) {
                background: #e9ecef;
                color: #6c757d;
                border: 1px solid #ced4da;
            }
            .attendance-list-item .time-info {
                font-size: 11px;
                color: #6c757d;
                margin-top: 8px;
                padding-top: 8px;
                border-top: 1px solid #e9ecef;
            }
            .attendance-table-view {
                display: block;
            }
            .attendance-table-view.hidden {
                display: none !important;
            }
            /* Ensure radio buttons are disabled and hidden for past dates */
            .attendance-table-view.hidden .attendance-radio {
                pointer-events: none;
                opacity: 0.5;
            }
        </style>

        {{-- Attendance Statistics --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Attendance Statistics</h5>
                    </div>
                    <div class="card-body">
                        {{-- Employee Filter Dropdown --}}
                        <div class="row mb-3 filter-section">
                            <div class="col-md-4">
                                <label for="employeeFilter" class="form-label fw-bold">Filter by Employee:</label>
                                <select class="form-select" id="employeeFilter">
                                    <option value="">All Employees</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->employee_id }} - {{ $employee->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="periodFilter" class="form-label fw-bold">Filter by Period:</label>
                                <select class="form-select" id="periodFilter">
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="this_week">This Week</option>
                                    <option value="last_week">Last Week</option>
                                    <option value="this_month" selected>This Month</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="button" class="btn btn-primary" id="applyFilter">
                                    <i class="fas fa-filter"></i> Apply Filter
                                </button>
                                <button type="button" class="btn btn-secondary ms-2" id="resetFilter">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                                <div class="spinner-border spinner-border-sm text-primary ms-2" id="filterSpinner" style="display: none;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        {{-- Custom Date Range (Hidden by default) --}}
                        <div class="row mb-3" id="customDateRange" style="display: none;">
                            <div class="col-md-6">
                                <label for="startDate" class="form-label fw-bold">Start Date:</label>
                                <input type="date" class="form-control" id="startDate">
                            </div>
                            <div class="col-md-6">
                                <label for="endDate" class="form-label fw-bold">End Date:</label>
                                <input type="date" class="form-control" id="endDate">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered" id="attendanceStatsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Emp Id</th>
                                        <th>Emp Name</th>
                                        <th>Emp Salary</th>
                                        <th>Present Days</th>
                                        <th>Casual Leaves</th>
                                        <th>LOP Days</th>
                                        <th>Half Days</th>
                                        <th>Paid Days</th>
                                        <th>Total Salary</th>
                                    </tr>
                                </thead>
                                <tbody id="attendanceStatsBody">
                                    @foreach ($employees as $employee)
                                    @php
                                        // Count full present days (half_type is NULL)
                                        $presentCount = \App\Models\Attendance::where('emp_id', $employee->id)
                                            ->where('type', 0)
                                            ->whereNull('half_type')
                                            ->whereMonth('attendance_date', now()->month)
                                            ->whereYear('attendance_date', now()->year)
                                            ->count();

                                        // Count half days (half_type has value)
                                        $halfDayCount = \App\Models\Attendance::where('emp_id', $employee->id)
                                            ->where('type', 0)
                                            ->whereNotNull('half_type')
                                            ->whereMonth('attendance_date', now()->month)
                                            ->whereYear('attendance_date', now()->year)
                                            ->count();

                                        if (\Illuminate\Support\Facades\Schema::hasColumn('leaves', 'category')) {
                                            // Categorized leaves
                                            $casualLeaveCount = \App\Models\Leave::where('emp_id', $employee->id)
                                                ->where('type', 0)
                                                ->where('category', 'casual')
                                                ->whereMonth('leave_date', now()->month)
                                                ->whereYear('leave_date', now()->year)
                                                ->count();

                                            $lopCount = \App\Models\Leave::where('emp_id', $employee->id)
                                                ->where('type', 0)
                                                ->where('category', 'lop')
                                                ->whereMonth('leave_date', now()->month)
                                                ->whereYear('leave_date', now()->year)
                                                ->count();

                                            $totalDays = $presentCount + $casualLeaveCount + $lopCount + $halfDayCount;
                                        } else {
                                            // Legacy schema without category column
                                            // Count all type=1 leaves (no category info) to include in total
                                            $legacyLeaveCount = \App\Models\Leave::where('emp_id', $employee->id)
                                                ->where('type', 0)
                                                ->whereMonth('leave_date', now()->month)
                                                ->whereYear('leave_date', now()->year)
                                                ->count();
                                            $casualLeaveCount = 0;
                                            $lopCount = 0;
                                            $totalDays = $presentCount + $legacyLeaveCount + $halfDayCount;
                                        }

                                        // Calculate total salary for current month (standardized daily rate)
                                        $monthlySalary = $employee->salary;
                                        $currentMonth = now()->month;
                                        $currentYear = now()->year;
                                        $startDate = now()->startOfMonth()->format('Y-m-d');
                                        $endDate = now()->endOfMonth()->format('Y-m-d');

                                        // Use fixed working days per month for consistency (30)
                                        $workingDays = 30;

                                        // Calculate daily salary rate
                                        $dailySalary = $workingDays > 0 ? $monthlySalary / $workingDays : 0;

                                        // Calculate total salary based on attendance
                                        $presentSalary = $presentCount * $dailySalary;
                                        $halfDaySalary = $halfDayCount * ($dailySalary * 0.5);
                                        $casualLeaveSalary = $casualLeaveCount * $dailySalary;
                                        $lopSalary = $lopCount * 0; // No salary for LOP days

                                        $totalSalary = round($presentSalary + $halfDaySalary + $casualLeaveSalary + $lopSalary, 2);
                                        // Calculate paid days (exclude LOP, half-day = 0.5)
                                        $paidDays = $presentCount + $casualLeaveCount + ($halfDayCount * 0.5);
                                    @endphp
                                    <tr data-emp-id="{{ $employee->id }}">
                                        <td>{{ $employee->employee_id }}</td>
                                        <td>{{ $employee->employee_name }}</td>
                                        <td>{{ number_format($employee->salary , 2) }}</td>
                                        <td>{{ $presentCount }}</td>
                                        <td>{{ $casualLeaveCount }}</td>
                                        <td>{{ $lopCount }}</td>
                                        <td>{{ $halfDayCount }}</td>
                                        <td>{{ rtrim(rtrim(number_format($paidDays, 2, '.', ''), '0'), '.') }}</td>
                                        <td>{{ number_format($totalSalary, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Date Picker --}}
        <form action="{{ route('attendance_store') }}" method="POST" id="attendanceForm">
            @csrf
            <div class="d-flex mb-3">
                <input type="date" name="attendance_date" class="form-control w-auto me-2" value="{{ now()->format('Y-m-d') }}" id="attendanceDateInput">
                <button type="submit" class="btn btn-primary me-2" id="saveAttendanceBtn">Save Attendance</button>
                {{-- <a href="" class="btn btn-warning me-2">Get Attendance Details</a> --}}
                {{-- <a href="" class="btn btn-success">Export to Excel</a> --}}
            </div>

            {{-- List View for Past Dates --}}
            <div class="attendance-list-view" id="attendanceListView">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Attendance List (Read-Only)</h5>
                    </div>
                    <div class="card-body" id="attendanceListBody">
                        <p class="text-muted text-center">Loading attendance data...</p>
                    </div>
                </div>
            </div>

            {{-- Employee Table for Current/Future Dates --}}
            <div class="attendance-table-view" id="attendanceTableView">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>Emp Id</th>
                                <th>Emp Name</th>
                                <th>Branch</th>
                                <th>Attendance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->employee_id }}</td>
                                <td>{{ $employee->employee_name }}</td>
                                <td>{{ $employee->branch?->name ?? 'N/A' }}</td>
                                <td>
                                    <ul class="list-inline m-0 attd-pills">
                                        <li class="list-inline-item me-2">
                                            <label>
                                                <input type="radio" name="attendance[{{ $employee->id }}]" value="present" class="attendance-radio" data-emp-id="{{ $employee->id }}" data-type="present" {{ old('attendance.' . $employee->id) == 'present' ? 'checked' : '' }}>
                                                <span class="pill present">✓ Present</span>
                                            </label>
                                        </li>
                                        <li class="list-inline-item me-2">
                                            <label>
                                                <input type="radio" name="attendance[{{ $employee->id }}]" value="absent" class="attendance-radio" data-emp-id="{{ $employee->id }}" data-type="absent" {{ old('attendance.' . $employee->id) == 'absent' ? 'checked' : '' }}>
                                                <span class="pill absent">✗ Absent</span>
                                            </label>
                                        </li>
                                        <li class="list-inline-item">
                                            <label>
                                                <input type="radio" name="attendance[{{ $employee->id }}]" value="half_day" class="attendance-radio" data-emp-id="{{ $employee->id }}" data-type="half_day" {{ old('attendance.' . $employee->id) == 'half_day' ? 'checked' : '' }}>
                                                <span class="pill half">✗ Half Day</span>
                                            </label>
                                        </li>
                                    </ul>
                                    <input type="hidden" name="login_time[{{ $employee->id }}]" id="login_time_{{ $employee->id }}" value="{{ old('login_time.' . $employee->id) }}">
                                    <input type="hidden" name="logout_time[{{ $employee->id }}]" id="logout_time_{{ $employee->id }}" value="{{ old('logout_time.' . $employee->id) }}">
                                    <input type="hidden" name="half_type[{{ $employee->id }}]" id="half_type_{{ $employee->id }}" value="{{ old('half_type.' . $employee->id) }}">
                                    <input type="hidden" name="permission_taken[{{ $employee->id }}]" id="permission_taken_{{ $employee->id }}" value="{{ old('permission_taken.' . $employee->id) }}">
                                    <input type="hidden" name="permission_reason[{{ $employee->id }}]" id="permission_reason_{{ $employee->id }}" value="{{ old('permission_reason.' . $employee->id) }}">
                                    <input type="hidden" name="permission_from[{{ $employee->id }}]" id="permission_from_{{ $employee->id }}" value="{{ old('permission_from.' . $employee->id) }}">
                                    <input type="hidden" name="permission_to[{{ $employee->id }}]" id="permission_to_{{ $employee->id }}" value="{{ old('permission_to.' . $employee->id) }}">
                                    <input type="hidden" name="casual_leave[{{ $employee->id }}]" id="casual_leave_{{ $employee->id }}" value="{{ old('casual_leave.' . $employee->id) }}">
                                    <input type="hidden" name="lop[{{ $employee->id }}]" id="lop_{{ $employee->id }}" value="{{ old('lop.' . $employee->id) }}">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>

        <!-- Absent Modal with Casual Leave and LOP -->
        <div class="modal fade" id="absentModal" tabindex="-1" aria-labelledby="absentModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="absentModalLabel">Mark Absent</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" name="category" type="checkbox" id="casual_leave_chk">
                            <label class="form-check-label" for="casual_leave_chk">
                                <strong>Mark as Casual Leave</strong>
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" name="category" type="checkbox" id="lop_chk">
                            <label class="form-check-label" for="lop_chk">
                                <strong>Mark as LOP (Loss of Pay)</strong>
                            </label>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong>
                            <ul class="mb-0 mt-2">
                                <li>If casual leave is checked, this will be recorded as a casual leave.</li>
                                <li>If LOP is checked, this will be recorded as a loss of pay leave.</li>
                                <li>If neither is checked, this will be recorded as a regular absent.</li>
                            </ul>
                        </div>
                        <div class="mt-3 text-danger small" id="absent_errors"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="absentCancelBtn">Cancel</button>
                        <button type="button" class="btn btn-danger" id="absentSubmitBtn">Mark Absent</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permission Modal (for Present) -->
        <div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permissionModalLabel">Enter Employee Login/Logout Time</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Enter Employee Login Time</label>
                            <input type="time" name="login_time" class="form-control" id="perm_login_time" step="60" placeholder="--:-- --" title="Format: HH:MM">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Enter Employee LogOut Time</label>
                            <input type="time" name="logout_time" class="form-control" id="perm_logout_time" step="60" placeholder="--:-- --" title="Format: HH:MM">
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" name="permission_taken" type="checkbox" id="perm_taken_chk">
                            <label class="form-check-label" for="perm_taken_chk">Permission Taken?</label>
                        </div>
                        <div class="mb-3" id="permission_details" style="display: none;">
                            <input type="text" class="form-control"name="permission_reason" id="perm_reason" placeholder="Enter permission reason">
                        </div>
                        <div class="row g-2" id="permission_times" style="display: none;">
                            <div class="col-6">
                                <label class="form-label">Permission From</label>
                                <input type="time" name="permission_from" class="form-control" id="perm_from" step="60">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Permission To</label>
                                <input type="time" name="permission_to" class="form-control" id="perm_to" step="60">
                            </div>
                        </div>
                        <div class="mt-3 text-danger small" id="perm_errors"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="permCancelBtn">Cancel</button>
                        <button type="button" class="btn btn-primary" id="permSubmitBtn">Submit</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Half Day Modal -->
        <div class="modal fade" id="halfDayModal" tabindex="-1" aria-labelledby="halfDayModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="halfDayModalLabel">Mark Half Day Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label d-block">Select Half:</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="half_selection" id="firstHalf" value="first">
                                <label class="form-check-label" for="firstHalf">First Half</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="half_selection" id="secondHalf" value="second">
                                <label class="form-check-label" for="secondHalf">Second Half</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Login Time</label>
                            <input type="time" name="half_login_time" class="form-control" id="half_login_time" step="60">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logout Time</label>
                            <input type="time" name="half_logout_time" class="form-control" id="half_logout_time" step="60">
                        </div>
                        <div class="mt-1 text-danger small" id="half_errors"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" id="halfCancelBtn">Cancel</button>
                        <button type="button" class="btn btn-warning" id="halfSubmitBtn">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
(function(){
    let activeEmployeeId = null;
    const canEditPastDates = @json($canEditPastDates ?? false);
    const permissionModalEl = document.getElementById('permissionModal');
    const halfDayModalEl = document.getElementById('halfDayModal');
    const absentModalEl = document.getElementById('absentModal');
    const csrfToken = (function(){
        const meta = document.querySelector('meta[name="csrf-token"]');
        if(meta && typeof meta.getAttribute === 'function'){
            return meta.getAttribute('content') || '';
        }
        const hidden = document.querySelector('input[name="_token"]');
        if(hidden && 'value' in hidden){
            return hidden.value || '';
        }
        return '';
    })();

    function showModal(el){
        try{
            if(window.bootstrap && bootstrap.Modal){
                bootstrap.Modal.getOrCreateInstance(el).show();
                return;
            }
        }catch(e){}
        if(window.jQuery){ jQuery(el).modal('show'); }
    }
    function hideModal(el){
        try{
            if(window.bootstrap && bootstrap.Modal){
                const i = bootstrap.Modal.getInstance(el); if(i){ i.hide(); }
                return;
            }
        }catch(e){}
        if(window.jQuery){ jQuery(el).modal('hide'); }
    }

    function uncheck(empId){
        document.querySelectorAll('input[name="attendance['+empId+']"]').forEach(r=> r.checked = false);
    }

    function applyStatusToRow(empId, data, selectedDate){
        const name = 'attendance['+empId+']';
        const radios = document.querySelectorAll('input[name="'+name+'"]');
        if(!radios || !radios.length) return;

        // Reset pill visuals for this row first
        const presentRadio = document.querySelector('input[name="'+name+'"][value="present"]');
        const absentRadio = document.querySelector('input[name="'+name+'"][value="absent"]');
        const halfRadio = document.querySelector('input[name="'+name+'"][value="half_day"]');

        // Reset all pills to default state
        if(presentRadio && presentRadio.nextElementSibling){
            const pill = presentRadio.nextElementSibling;
            pill.innerHTML = '✓ Present';
            pill.style.backgroundColor = '';
            pill.style.borderColor = '';
            pill.style.color = '';
            pill.classList.remove('saved-success','today-success');
        }
        if(absentRadio && absentRadio.nextElementSibling){
            const pill = absentRadio.nextElementSibling;
            pill.innerHTML = '✗ Absent';
            pill.style.backgroundColor = '';
            pill.style.borderColor = '';
            pill.style.color = '';
        }
        if(halfRadio && halfRadio.nextElementSibling){
            const pill = halfRadio.nextElementSibling;
            pill.innerHTML = '✗ Half Day';
            pill.style.backgroundColor = '';
            pill.style.borderColor = '';
            pill.style.color = '';
        }

        // Fill hidden fields
        if(typeof data.login_time !== 'undefined' && document.getElementById('login_time_'+empId)){
            document.getElementById('login_time_'+empId).value = data.login_time || '';
        }
        if(typeof data.logout_time !== 'undefined' && document.getElementById('logout_time_'+empId)){
            document.getElementById('logout_time_'+empId).value = data.logout_time || '';
        }
        if(typeof data.permission_taken !== 'undefined' && document.getElementById('permission_taken_'+empId)){
            document.getElementById('permission_taken_'+empId).value = data.permission_taken || '0';
        }
        if(typeof data.permission_reason !== 'undefined' && document.getElementById('permission_reason_'+empId)){
            document.getElementById('permission_reason_'+empId).value = data.permission_reason || '';
        }
        if(typeof data.permission_from !== 'undefined' && document.getElementById('permission_from_'+empId)){
            document.getElementById('permission_from_'+empId).value = data.permission_from || '';
        }
        if(typeof data.permission_to !== 'undefined' && document.getElementById('permission_to_'+empId)){
            document.getElementById('permission_to_'+empId).value = data.permission_to || '';
        }
        if(typeof data.half_type !== 'undefined' && document.getElementById('half_type_'+empId)){
            document.getElementById('half_type_'+empId).value = data.half_type || '';
        }
        if(typeof data.casual_leave !== 'undefined' && document.getElementById('casual_leave_'+empId)){
            document.getElementById('casual_leave_'+empId).value = data.casual_leave || '0';
        }
        if(typeof data.lop !== 'undefined' && document.getElementById('lop_'+empId)){
            document.getElementById('lop_'+empId).value = data.lop || '0';
        }

        // Select radio and style
        const status = data.status;
        if(status === 'present' && presentRadio){
            presentRadio.checked = true;
            const pill = presentRadio.nextElementSibling;
            if(pill){
                pill.classList.add('saved-success');
                try{
                    const today = (new Date()).toISOString().slice(0,10);
                    if(selectedDate === today){ pill.classList.add('today-success'); }
                }catch(e){}
                setTimeout(()=>{ pill.classList.remove('saved-success'); }, 1500);
            }
        } else if(status === 'absent' && absentRadio){
            absentRadio.checked = true;
            const pill = absentRadio.nextElementSibling;
            if(pill){
                const isCasual = data.casual_leave === '1';
                const isLop = data.lop === '1';
                if(isCasual){
                    pill.innerHTML = '✗ Absent (Casual Leave)';
                    pill.style.backgroundColor = '#fff3cd';
                    pill.style.borderColor = '#ffc107';
                    pill.style.color = '#856404';
                }else if(isLop){
                    pill.innerHTML = '✗ Absent (LOP)';
                    pill.style.backgroundColor = '#f8d7da';
                    pill.style.borderColor = '#dc3545';
                    pill.style.color = '#721c24';
                }else{
                    pill.innerHTML = '✗ Absent';
                    pill.style.backgroundColor = '';
                    pill.style.borderColor = '';
                    pill.style.color = '';
                }
            }
        } else if(status === 'half_day' && halfRadio){
            halfRadio.checked = true;
        } else {
            // nothing selected
            radios.forEach(r=> r.checked = false);
        }
    }

    function isPastDate(dateString) {
        if (!dateString) return false;
        const selectedDate = new Date(dateString);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        selectedDate.setHours(0, 0, 0, 0);
        return selectedDate < today;
    }

    function toggleViewMode(isPast) {
        const listView = document.getElementById('attendanceListView');
        const tableView = document.getElementById('attendanceTableView');
        const saveBtn = document.getElementById('saveAttendanceBtn');

        if (isPast && !canEditPastDates) {
            listView.classList.add('active');
            tableView.classList.add('hidden');
            if (saveBtn) {
                saveBtn.style.display = 'none';
            }
        } else {
            listView.classList.remove('active');
            tableView.classList.remove('hidden');
            if (saveBtn) {
                saveBtn.style.display = 'inline-block';
            }
        }
    }

    async function loadAttendanceListView(dateVal) {
        const listBody = document.getElementById('attendanceListBody');
        if (!listBody) return;

        listBody.innerHTML = '<p class="text-muted text-center">Loading attendance data...</p>';

        try {
            const url = "{{ route('attendance.status-by-date') }}" + '?date=' + encodeURIComponent(dateVal);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json();

            if (!json || !json.success) {
                listBody.innerHTML = '<p class="text-muted text-center">No attendance data found for this date.</p>';
                return;
            }

            const data = json.data || {};
            const employees = @json($employees);

            let html = '<div class="table-responsive"><table class="table table-bordered align-middle"><thead><tr><th>Emp Id</th><th>Emp Name</th><th>Branch</th><th>Attendance</th></tr></thead><tbody>';

            employees.forEach(employee => {
                const empId = employee.id.toString();
                const attendanceData = data[empId] || {};
                const status = attendanceData.status || 'not_marked';

                // Determine which option is selected and style accordingly
                // Only the selected status will have a class (present/absent/half) for coloring
                // Unselected options will remain in default neutral format (no class)
                let presentClass = '';
                let absentClass = '';
                let halfDayClass = '';
                let absentText = '✗ Absent';

                if (status === 'present') {
                    presentClass = 'present'; // Only Present gets colored
                } else if (status === 'absent') {
                    absentClass = 'absent'; // Only Absent gets colored
                    if (attendanceData.casual_leave === '1') {
                        absentText = '✗ Absent (Casual Leave)';
                    } else if (attendanceData.lop === '1') {
                        absentText = '✗ Absent (LOP)';
                    }
                } else if (status === 'half_day') {
                    halfDayClass = 'half'; // Only Half Day gets colored
                }
                // If status is 'not_marked', all three will show in default neutral format (no classes)

                html += `
                    <tr>
                        <td>${employee.employee_id}</td>
                        <td>${employee.employee_name}</td>
                        <td>${employee.branch?.name || 'N/A'}</td>
                        <td>
                            <ul class="list-inline m-0 attd-pills">
                                <li class="list-inline-item me-2">
                                    <span class="pill ${presentClass}">✓ Present</span>
                                </li>
                                <li class="list-inline-item me-2">
                                    <span class="pill ${absentClass}">${absentText}</span>
                                </li>
                                <li class="list-inline-item">
                                    <span class="pill ${halfDayClass}">✗ Half Day</span>
                                </li>
                            </ul>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
            listBody.innerHTML = html || '<p class="text-muted text-center">No attendance records found for this date.</p>';
        } catch (e) {
            console.error('Error loading attendance list:', e);
            listBody.innerHTML = '<p class="text-danger text-center">Error loading attendance data. Please try again.</p>';
        }
    }

    async function fetchAndApplyStatuses(){
        const dateInput = document.querySelector('input[name="attendance_date"]');
        if(!dateInput) return;
        const dateVal = dateInput.value;

        // Clear all selections and colors when no date is selected
        if(!dateVal) {
            clearAllSelections();
            toggleViewMode(false);
            return;
        }

        // Check if date is in the past
        const isPast = isPastDate(dateVal);
        toggleViewMode(isPast);

        if (isPast && !canEditPastDates) {
            // Load list view for past dates
            await loadAttendanceListView(dateVal);
            return;
        }

        // Reset UI to the default state before applying the fetched status
        clearAllSelections();

        try{
            const url = "{{ route('attendance.status-by-date') }}" + '?date=' + encodeURIComponent(dateVal);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json();
            if(!json || !json.success) return;
            const data = json.data || {};
            Object.keys(data).forEach(empId=>{
                applyStatusToRow(empId, data[empId], dateVal);
            });
        }catch(e){ /* silent */ }
    }

    // Load initial statistics when page loads
    async function loadInitialStatistics() {
        try {
            const response = await fetch("{{ route('attendance.statistics') }}", {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                updateStatisticsTable(data.data);
            }
        } catch (error) {
            console.error('Error loading initial statistics:', error);
        }
    }

        function clearAllSelections(){
        // Uncheck all radio buttons
        document.querySelectorAll('.attendance-radio').forEach(radio => {
            radio.checked = false;
        });

        // Reset all pill styles to default neutral state
        document.querySelectorAll('.attd-pills .pill').forEach(pill => {
            pill.style.backgroundColor = '';
            pill.style.borderColor = '';
            pill.style.color = '';
            pill.classList.remove('saved-success', 'today-success');

            // Reset pill text content to default
            const radio = pill.previousElementSibling;
            if(radio) {
                if(radio.value === 'present') {
                    pill.innerHTML = '✓ Present';
                } else if(radio.value === 'absent') {
                    pill.innerHTML = '✗ Absent';
                } else if(radio.value === 'half_day') {
                    pill.innerHTML = '✗ Half Day';
                }
            }
        });

        // Clear all hidden fields
        document.querySelectorAll('input[id^="login_time_"], input[id^="logout_time_"], input[id^="logout_time_"], input[id^="half_type_"], input[id^="permission_taken_"], input[id^="permission_reason_"], input[id^="permission_from_"], input[id^="permission_to_"], input[id^="casual_leave_"], input[id^="lop_"]').forEach(input => {
            input.value = '';
        });
    }

    function openAttendanceModal(radio){
        if(!radio){ return; }
        const type = radio.getAttribute('data-type');
        activeEmployeeId = radio.getAttribute('data-emp-id');
        if(!activeEmployeeId){ return; }

        if(type === 'present'){
            // Prefill permission modal for present
            document.getElementById('perm_login_time').value = document.getElementById('login_time_'+activeEmployeeId)?.value || '';
            document.getElementById('perm_logout_time').value = document.getElementById('logout_time_'+activeEmployeeId)?.value || '';
            const permissionTaken = (document.getElementById('permission_taken_'+activeEmployeeId)?.value === '1');
            document.getElementById('perm_taken_chk').checked = permissionTaken;
            document.getElementById('perm_reason').value = document.getElementById('permission_reason_'+activeEmployeeId)?.value || '';
            document.getElementById('perm_from').value = document.getElementById('permission_from_'+activeEmployeeId)?.value || '';
            document.getElementById('perm_to').value = document.getElementById('permission_to_'+activeEmployeeId)?.value || '';

            // Show/hide permission details based on existing value
            const permissionDetails = document.getElementById('permission_details');
            const permissionTimes = document.getElementById('permission_times');
            if(permissionTaken) {
                permissionDetails.style.display = 'block';
                permissionTimes.style.display = 'flex';
            } else {
                permissionDetails.style.display = 'none';
                permissionTimes.style.display = 'none';
            }

            document.getElementById('perm_errors').innerHTML = '';
            showModal(permissionModalEl);
        } else if(type === 'absent'){
            // Show absent modal with casual leave and LOP options
            document.getElementById('casual_leave_chk').checked = (document.getElementById('casual_leave_'+activeEmployeeId)?.value === '1');
            document.getElementById('lop_chk').checked = (document.getElementById('lop_'+activeEmployeeId)?.value === '1');
            document.getElementById('absent_errors').innerHTML = '';
            showModal(absentModalEl);
        } else if(type === 'half_day'){
            // Prefill half day modal
            document.getElementById('half_login_time').value = document.getElementById('login_time_'+activeEmployeeId)?.value || '';
            document.getElementById('half_logout_time').value = document.getElementById('logout_time_'+activeEmployeeId)?.value || '';
            const selectedHalf = document.getElementById('half_type_'+activeEmployeeId)?.value || '';
            document.getElementById('firstHalf').checked = selectedHalf === 'first';
            document.getElementById('secondHalf').checked = selectedHalf === 'second';
            document.getElementById('half_errors').innerHTML = '';
            showModal(halfDayModalEl);
        }
    }

    // Open modals based on attendance type (new selection and re-open existing)
    document.querySelectorAll('.attendance-radio').forEach(function(radio){
        radio.addEventListener('change', function(){
            openAttendanceModal(this);
        });
        radio.addEventListener('click', function(){
            if(this.checked){
                openAttendanceModal(this);
            }
        });
    });

    // Absent modal submit/cancel
    document.getElementById('absentSubmitBtn').addEventListener('click', async function(){
        if(!activeEmployeeId) return;
        const casualLeave = document.getElementById('casual_leave_chk').checked ? '1' : '0';
        const lop = document.getElementById('lop_chk').checked ? '1' : '0';

        document.getElementById('casual_leave_'+activeEmployeeId).value = casualLeave;
        document.getElementById('lop_'+activeEmployeeId).value = lop;

        const dateInput = document.querySelector('input[name="attendance_date"]');
        const payload = {
            employee_id: activeEmployeeId,
            attendance_date: dateInput ? dateInput.value : '',
            status: 'absent',
            casual_leave: casualLeave,
            lop: lop
        };

        try{
            if(!csrfToken){
                document.getElementById('absent_errors').innerHTML = 'Security token missing. Please refresh the page.';
                return;
            }
            const res = await fetch("{{ route('attendance.save-inline') }}",{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(!data.success){ throw new Error(data.message||'Failed'); }

            // Add visual indicator for absent type
            const absentRadio = document.querySelector(`input[name="attendance[${activeEmployeeId}]"][value="absent"]`);
            const absentPill = absentRadio.nextElementSibling;
            if (casualLeave === '1') {
                absentPill.innerHTML = '✗ Absent (Casual Leave)';
                absentPill.style.backgroundColor = '#fff3cd';
                absentPill.style.borderColor = '#ffc107';
                absentPill.style.color = '#856404';
            } else if (lop === '1') {
                absentPill.innerHTML = '✗ Absent (LOP)';
                absentPill.style.backgroundColor = '#f8d7da';
                absentPill.style.borderColor = '#dc3545';
                absentPill.style.color = '#721c24';
            } else {
                absentPill.innerHTML = '✗ Absent';
                absentPill.style.backgroundColor = '';
                absentPill.style.borderColor = '';
                absentPill.style.color = '';
            }
            hideModal(absentModalEl);
        }catch(err){
            document.getElementById('absent_errors').innerHTML = (err&&err.message)? err.message : 'Error saving attendance.';
        }
    });

    // Ensure only one checkbox can be selected at a time
    document.getElementById('casual_leave_chk').addEventListener('change', function(){
        if(this.checked) {
            document.getElementById('lop_chk').checked = false;
        }
    });

    document.getElementById('lop_chk').addEventListener('change', function(){
        if(this.checked) {
            document.getElementById('casual_leave_chk').checked = false;
        }
    });

    document.getElementById('absentCancelBtn').addEventListener('click', function(){
        if(activeEmployeeId){ uncheck(activeEmployeeId); }
        hideModal(absentModalEl);
    });

    // Permission modal submit/cancel
    document.getElementById('permSubmitBtn').addEventListener('click', async function(){
        if(!activeEmployeeId) return;
        const login = document.getElementById('perm_login_time').value;
        const logout = document.getElementById('perm_logout_time').value;
        const taken = document.getElementById('perm_taken_chk').checked ? '1' : '0';
        const reason = document.getElementById('perm_reason').value.trim();
        const from = document.getElementById('perm_from').value;
        const to = document.getElementById('perm_to').value;

        const errors = [];
        if(!login) errors.push('Login time is required.');
        if(!logout) errors.push('Logout time is required.');
        if(taken === '1'){
            if(!reason) errors.push('Permission reason is required.');
            if(!from) errors.push('Permission from time is required.');
            if(!to) errors.push('Permission to time is required.');
        }
        const errorBox = document.getElementById('perm_errors');
        if(errors.length){ errorBox.innerHTML = errors.map(e=>'<div>'+e+'</div>').join(''); return; }

        document.getElementById('login_time_'+activeEmployeeId).value = login;
        document.getElementById('logout_time_'+activeEmployeeId).value = logout;
        document.getElementById('permission_taken_'+activeEmployeeId).value = taken;
        document.getElementById('permission_reason_'+activeEmployeeId).value = reason;
        document.getElementById('permission_from_'+activeEmployeeId).value = from;
        document.getElementById('permission_to_'+activeEmployeeId).value = to;

        const dateInput = document.querySelector('input[name="attendance_date"]');
        const payload = {
            employee_id: activeEmployeeId,
            attendance_date: dateInput ? dateInput.value : '',
            status: 'present',
            login_time: login,
            logout_time: logout,
            permission_taken: taken,
            permission_reason: reason,
            permission_from: from,
            permission_to: to
        };

        try{
            if(!csrfToken){
                document.getElementById('perm_errors').innerHTML = 'Security token missing. Please refresh the page.';
                return;
            }
            const res = await fetch("{{ route('attendance.save-inline') }}",{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(!data.success){ throw new Error(data.message||'Failed'); }
            // Ensure the Present radio remains selected and visibly marked
            const presentRadio = document.querySelector(`input[name="attendance[${activeEmployeeId}]"][value="present"]`);
            if(presentRadio){
                presentRadio.checked = true;
                const pill = presentRadio.nextElementSibling;
                if(pill){
                    pill.classList.add('saved-success');
                    // Stronger highlight when selected date is today
                    try{
                        const dateInput = document.querySelector('input[name="attendance_date"]');
                        const today = (new Date()).toISOString().slice(0,10);
                        if(dateInput && dateInput.value === today){
                            pill.classList.add('today-success');
                        }
                    }catch(e){}
                    setTimeout(()=>{ pill.classList.remove('saved-success'); }, 2000);
                }
            }
            hideModal(permissionModalEl);
        }catch(err){
            document.getElementById('perm_errors').innerHTML = (err&&err.message)? err.message : 'Error saving attendance.';
        }
    });

    document.getElementById('permCancelBtn').addEventListener('click', function(){
        if(activeEmployeeId){ uncheck(activeEmployeeId); }
        hideModal(permissionModalEl);
    });

    // Show/hide permission details based on checkbox
    document.getElementById('perm_taken_chk').addEventListener('change', function(){
        const permissionDetails = document.getElementById('permission_details');
        const permissionTimes = document.getElementById('permission_times');

        if(this.checked) {
            permissionDetails.style.display = 'block';
            permissionTimes.style.display = 'flex';
        } else {
            permissionDetails.style.display = 'none';
            permissionTimes.style.display = 'none';
            // Clear the fields when unchecked
            document.getElementById('perm_reason').value = '';
            document.getElementById('perm_from').value = '';
            document.getElementById('perm_to').value = '';
        }
    });

    // Half day submit/cancel
    document.getElementById('halfSubmitBtn').addEventListener('click', async function(){
        if(!activeEmployeeId) return;
        const half = document.querySelector('input[name="half_selection"]:checked');
        const login = document.getElementById('half_login_time').value;
        const logout = document.getElementById('half_logout_time').value;
        const errors = [];
        if(!half){ errors.push('Please select First Half or Second Half.'); }
        if(!login){ errors.push('Login time is required.'); }
        if(!logout){ errors.push('Logout time is required.'); }
        if(errors.length){ document.getElementById('half_errors').innerHTML = errors.map(e=>'<div>'+e+'</div>').join(''); return; }

        document.getElementById('half_type_'+activeEmployeeId).value = half.value;
        document.getElementById('login_time_'+activeEmployeeId).value = login;
        document.getElementById('logout_time_'+activeEmployeeId).value = logout;
        const dateInput = document.querySelector('input[name="attendance_date"]');
        const payload = {
            employee_id: activeEmployeeId,
            attendance_date: dateInput ? dateInput.value : '',
            status: 'half_day',
            half_type: half.value,
            login_time: login,
            logout_time: logout
        };

        try{
            if(!csrfToken){
                document.getElementById('half_errors').innerHTML = 'Security token missing. Please refresh the page.';
                return;
            }
            const res = await fetch("{{ route('attendance.save-inline') }}",{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });
            const data = await res.json();
            if(!data.success){ throw new Error(data.message||'Failed'); }
            hideModal(halfDayModalEl);
        }catch(err){
            document.getElementById('half_errors').innerHTML = (err&&err.message)? err.message : 'Error saving attendance.';
        }
    });

    document.getElementById('halfCancelBtn').addEventListener('click', function(){
        if(activeEmployeeId){ uncheck(activeEmployeeId); }
        hideModal(halfDayModalEl);
    });

        const dateInputEl = document.querySelector('input[name="attendance_date"]');
    if(dateInputEl){
        dateInputEl.addEventListener('change', fetchAndApplyStatuses);
        dateInputEl.addEventListener('input', async function() {
            // Add/remove class based on whether date is selected
            const form = document.getElementById('attendanceForm');
                if(this.value) {
                form.classList.remove('no-date-selected');
                const isPast = isPastDate(this.value);
                    toggleViewMode(isPast);
                    const readOnlyPast = isPast && !canEditPastDates;

                    if (readOnlyPast) {
                        document.querySelectorAll('.attendance-radio').forEach(radio => {
                            radio.disabled = true;
                        });
                        await loadAttendanceListView(this.value);
                    } else {
                        document.querySelectorAll('.attendance-radio').forEach(radio => {
                            radio.disabled = false;
                        });
                    }
                } else {
                form.classList.add('no-date-selected');
                // Disable radio buttons
                document.querySelectorAll('.attendance-radio').forEach(radio => {
                    radio.disabled = true;
                });
                clearAllSelections();
                toggleViewMode(false);
            }
        });

        // Set initial state
        if(!dateInputEl.value) {
            document.getElementById('attendanceForm').classList.add('no-date-selected');
            // Disable radio buttons initially if no date
            document.querySelectorAll('.attendance-radio').forEach(radio => {
                radio.disabled = true;
            });
        } else {
            // Check initial date
            const isPast = isPastDate(dateInputEl.value);
            toggleViewMode(isPast);
            const readOnlyPast = isPast && !canEditPastDates;
            document.querySelectorAll('.attendance-radio').forEach(radio => {
                radio.disabled = readOnlyPast ? true : false;
            });
        }
    }

    // Prevent form submission for past dates
    document.getElementById('attendanceForm').addEventListener('submit', function(e) {
        const dateInput = document.querySelector('input[name="attendance_date"]');
        if (dateInput && isPastDate(dateInput.value) && !canEditPastDates) {
            e.preventDefault();
            alert('Cannot save attendance for past dates. Past dates are read-only.');
            return false;
        }
    });

    // initial load
    fetchAndApplyStatuses();
    loadInitialStatistics(); // Load initial statistics on page load

    // Employee and Month Filter Functionality
    document.getElementById('applyFilter').addEventListener('click', function() {
        const employeeId = document.getElementById('employeeFilter').value;
        const periodFilter = document.getElementById('periodFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        let urlParams = '';
        if (periodFilter === 'custom') {
            if (!startDate || !endDate) {
                alert('Please select both start and end dates for custom range.');
                return;
            }
            urlParams += 'start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);
        } else {
            urlParams += 'period=' + encodeURIComponent(periodFilter);
        }

        if (employeeId) {
            urlParams += (urlParams ? '&' : '') + 'employee_id=' + encodeURIComponent(employeeId);
        }

        if (!urlParams) {
            alert('Please select a filter option.');
            return;
        }

        fetchFilteredStatistics(employeeId, periodFilter, startDate, endDate);
    });

    document.getElementById('resetFilter').addEventListener('click', function() {
        document.getElementById('employeeFilter').value = '';
        document.getElementById('periodFilter').value = 'this_month';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('customDateRange').style.display = 'none';
        fetchFilteredStatistics('', 'this_month', '', '');
    });

    async function fetchFilteredStatistics(employeeId, periodFilter, startDate, endDate) {
        const spinner = document.getElementById('filterSpinner');
        const applyBtn = document.getElementById('applyFilter');

        try {
            // Show loading state
            spinner.style.display = 'inline-block';
            applyBtn.disabled = true;
            applyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

            let url = "{{ route('attendance.statistics') }}";
            let params = '';

            if (periodFilter === 'custom') {
                if (startDate && endDate) {
                    params += 'start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate);
                }
            } else {
                params += 'period=' + encodeURIComponent(periodFilter);
            }

            if (employeeId) {
                params += (params ? '&' : '') + 'employee_id=' + encodeURIComponent(employeeId);
            }

            if (params) {
                url += '?' + params;
            }

            const response = await fetch(url, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                updateStatisticsTable(data.data);
                // Show success message
                showNotification('Statistics updated successfully!', 'success');
            } else {
                console.error('Failed to fetch statistics:', data.message);
                showNotification('Failed to fetch statistics: ' + (data.message || 'Unknown error'), 'error');
            }
        } catch (error) {
            console.error('Error fetching statistics:', error);
            showNotification('Error fetching statistics. Please try again.', 'error');
        } finally {
            // Hide loading state
            spinner.style.display = 'none';
            applyBtn.disabled = false;
            applyBtn.innerHTML = '<i class="fas fa-filter"></i> Apply Filter';
        }
    }

    function updateStatisticsTable(statistics) {
        const tbody = document.getElementById('attendanceStatsBody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (statistics.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="text-center text-muted">No data found for the selected criteria</td></tr>';
            return;
        }

        statistics.forEach(stat => {
            const row = document.createElement('tr');
            row.setAttribute('data-emp-id', stat.employee_id);

            row.innerHTML = `
                <td>${stat.employee_id}</td>
                <td>${stat.employee_name}</td>
                <td>${parseFloat(stat.emp_salary).toFixed(2)}</td>
                <td>${stat.present_days}</td>
                <td>${stat.casual_leaves}</td>
                <td>${stat.lop_days}</td>
                <td>${stat.half_days}</td>
                <td>${parseFloat(stat.paid_days).toFixed(2)}</td>
                <td>${parseFloat(stat.total_salary).toFixed(2)}</td>
            `;

            tbody.appendChild(row);
        });
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

        document.body.appendChild(notification);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 5000);
    }

    // Auto-filter when month changes
    document.getElementById('periodFilter').addEventListener('change', function() {
        const employeeId = document.getElementById('employeeFilter').value;
        const periodFilter = this.value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Show/hide custom date range
        const customRange = document.getElementById('customDateRange');
        if (periodFilter === 'custom') {
            customRange.style.display = 'flex';
        } else {
            customRange.style.display = 'none';
        }

        fetchFilteredStatistics(employeeId, periodFilter, startDate, endDate);
    });

    // Auto-filter when employee changes
    document.getElementById('employeeFilter').addEventListener('change', function() {
        const employeeId = this.value;
        const periodFilter = document.getElementById('periodFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        fetchFilteredStatistics(employeeId, periodFilter, startDate, endDate);
    });

    // Auto-filter when custom dates change
    document.getElementById('startDate').addEventListener('change', function() {
        const employeeId = document.getElementById('employeeFilter').value;
        const periodFilter = document.getElementById('periodFilter').value;
        const startDate = this.value;
        const endDate = document.getElementById('endDate').value;
        if (startDate && endDate) {
            fetchFilteredStatistics(employeeId, periodFilter, startDate, endDate);
        }
    });

    document.getElementById('endDate').addEventListener('change', function() {
        const employeeId = document.getElementById('employeeFilter').value;
        const periodFilter = document.getElementById('periodFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = this.value;
        if (startDate && endDate) {
            fetchFilteredStatistics(employeeId, periodFilter, startDate, endDate);
        }
    });
})();
</script>
@endsection

