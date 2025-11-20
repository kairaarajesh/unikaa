<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ServiceManagementController;
use App\Http\Controllers\ServiceComboController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\FingerDevicesControlller;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\invoiceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\Staff_managementController;
use App\Http\Controllers\Student_AttendanceController;
use App\Http\Controllers\Staff_attendanceController;
use App\Http\Controllers\BillController;
use App\Models\Purchase;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/debug-customers', function () {
    $user = auth()->user();
    if (!$user) {
        return 'Not authenticated';
    }

    $isSubadmin = $user->roles()->where('slug', 'subadmin')->exists();
    $customersQuery = \App\Models\Customer::query();

    if ($isSubadmin && $user->branch_id) {
        $customersQuery->where('branch_id', $user->branch_id);
    }

    $customers = $customersQuery->with('branch')->get();
    $allCustomers = \App\Models\Customer::with('branch')->get();

    return [
        'user_id' => $user->id,
        'user_branch_id' => $user->branch_id,
        'is_subadmin' => $isSubadmin,
        'filtered_customers_count' => $customers->count(),
        'all_customers_count' => $allCustomers->count(),
        'filtered_customers' => $customers->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'branch_id' => $c->branch_id,
                'branch_name' => $c->branch ? $c->branch->name : 'No branch'
            ];
        }),
        'all_customers' => $allCustomers->map(function($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'branch_id' => $c->branch_id,
                'branch_name' => $c->branch ? $c->branch->name : 'No branch'
            ];
        })
    ];
})->middleware('auth');
Auth::routes(['register' => false, 'reset' => false]);

Route::group(['middleware' => ['auth', 'Role'], 'roles' => ['admin']], function () {
    Route::resource('/employees', '\App\Http\Controllers\EmployeeController');
    Route::resource('/category', '\App\Http\Controllers\CategoryController');
    Route::resource('/management', '\App\Http\Controllers\ManagementController');
    Route::resource('/purchase', '\App\Http\Controllers\PurchaseController');
    Route::resource('/branch', '\App\Http\Controllers\BranchController');
    Route::resource('/user', '\App\Http\Controllers\UserController');
    Route::resource('/changePassword', '\App\Http\Controllers\Auth\LoginController');
    Route::get('/admin', '\App\Http\Controllers\AdminController@index')->name('admin');
    Route::resource('/booking', '\App\Http\Controllers\BookingController');
    Route::resource('/customer', '\App\Http\Controllers\CustomerController');
    Route::resource('/service', '\App\Http\Controllers\ServiceManagementController');
    Route::resource('/serviceCombo', '\App\Http\Controllers\ServiceComboController');
    // Billing list/report view
    Route::get('/bill', [BillController::class, 'index'])->name('bill.index');

    Route::get('admin/customer_show/{id}', [CustomerController::class, 'generateInvoice']);
    Route::get('admin/customer/view-invoice/{id}', [CustomerController::class, 'viewInvoice'])->name('customer.view-invoice');
    // Invoice PDF by invoice id (download/stream)
    Route::get('admin/invoice/{invoiceId}/download', [CustomerController::class, 'downloadInvoiceById'])->name('invoice.download');
    Route::get('admin/invoice/{invoiceId}/view', [CustomerController::class, 'viewInvoiceById'])->name('invoice.view');
    Route::get('admin/customer/email-invoice/{id}', [CustomerController::class, 'emailInvoice'])->name('customer.email-invoice');
    Route::post('admin/customer/custom-invoice/{id}', [CustomerController::class, 'generateCustomInvoice'])->name('customer.custom-invoice');
    Route::post('admin/customer/{id}/create-invoice', [CustomerController::class, 'createInvoice'])->name('customer.create-invoice');
    Route::get('admin/customer/{id}/items-data', [CustomerController::class, 'viewItemsData'])->name('customer.items-data');
    Route::get('admin/customer/{id}/view-data', [CustomerController::class, 'viewData'])->name('customer.view-data');
    Route::get('admin/customer/{id}/bill-details', [CustomerController::class, 'billDetails'])->name('customer.bill-details');
    Route::post('admin/customer/get-by-number', [CustomerController::class, 'getCustomerByNumber'])->name('customer.get-by-number');
    Route::post('admin/customer/get-employees-by-branch', [CustomerController::class, 'getEmployeesByBranch'])->name('customer.get-employees-by-branch');
    Route::post('admin/customer/update-membership-card', [CustomerController::class, 'updateMembershipCard'])->name('customer.update-membership-card');
    Route::resource('/schedule', '\App\Http\Controllers\ScheduleController');
    // Route::resource('/customer_management', '\App\Http\Controllers\ScheduleController');
    Route::resource('/appointment', '\App\Http\Controllers\AppointmentController');

    Route::get('/employee-report', [EmployeeController::class, 'report'])->name('employee.report');

    Route::resource('/billing', '\App\Http\Controllers\BillingController');
    Route::get('/admin/billing/export', [BillingController::class, 'exportPurchases'])->name('billing.export');
    Route::get('admin/billing_show/{id}', [BillingController::class, 'generateInvoice'])->name('billing.invoice');

    Route::get('/attendancelog', '\App\Http\Controllers\AttendancelogController@index')->name('attendancelog');
    Route::get('/latetime', '\App\Http\Controllers\AttendancelogController@indexLatetime')->name('indexLatetime');

    Route::get('attendance', '\App\Http\Controllers\AttendanceController@index')->name('/attendance');
    Route::post('attendance_store','\App\Http\Controllers\AttendanceController@CheckStore')->name('attendance_store');
    Route::get('/sheet-report', '\App\Http\Controllers\AttendanceController@sheetReport')->name('sheet-report');

    Route::post('attendance/save-inline', [AttendanceController::class, 'saveInline'])->name('attendance.save-inline');
    Route::get('attendance/status-by-date', [AttendanceController::class, 'statusByDate'])->name('attendance.status-by-date');
    Route::get('attendance/statistics', [AttendanceController::class, 'getStatistics'])->name('attendance.statistics');
    Route::get('attendance/test-salary', [AttendanceController::class, 'testSalaryCalculation'])->name('attendance.test-salary');
    Route::get('attendance/debug-data', [AttendanceController::class, 'debugAttendanceData'])->name('attendance.debug-data');

    Route::get('salary/export', [AttendanceController::class, 'export'])->name('salary/export');

    Route::get('admin/booking_show/{id}', [BookingController::class, 'generateInvoice']);
    // Route::resource('/schedule', '\App\Http\Controllers\ScheduleController');

    // Route::get('/admin', '\App\Http\Controllers\AdminController@header')->name('admin');

    // notification sms get method
    // Route::get('/login', '\App\Http\Controllers\AdminController@index')->name('admin');
    // Route::get('/category', '\App\Http\Controllers\AdminController@index')->name('admin');
    // Route::get('/management', '\App\Http\Controllers\AdminController@index')->name('admin');
    // Route::get('/purchase', '\App\Http\Controllers\AdminController@index')->name('admin');
    // Route::get('/user', '\App\Http\Controllers\AdminController@index')->name('admin');

    // Route::get('/admin', '\App\Http\Controllers\AdminController@show')->name('admin');
    // Route::get('/admin', '\App\Http\Controllers\AdminController@show')->name('admin');

    Route::get('admin/booking/calendar', [BookingController::class, 'calendar'])->name('booking.calendar');
    Route::get('admin/booking/calendar-events', [App\Http\Controllers\BookingController::class, 'calendarEvents']);
    Route::get('admin/booking/get-artists', [App\Http\Controllers\BookingController::class, 'getArtists'])->name('booking.get-artists');
    Route::post('admin/booking/{id}/update-status-artist', [App\Http\Controllers\BookingController::class, 'updateStatusArtist'])->name('booking.update-status-artist');

// academy
Route::resource('/student', '\App\Http\Controllers\StudentController');
Route::resource('/staff_management', '\App\Http\Controllers\Staff_managementController');
Route::resource('/course', '\App\Http\Controllers\CourseController');
Route::get('/student_attendance', '\App\Http\Controllers\Student_AttendanceController@index')->name('/attendance');
Route::get('student_attendance_store','\App\Http\Controllers\Student_AttendanceController@CheckStore')->name('student_attendance_store');
// Route::get('/sheet-report', '\App\Http\Controllers\Student_AttendanceController@sheetReport')->name('sheet-report');
Route::get('/student_sheet_report', '\App\Http\Controllers\Student_AttendanceController@sheetReport')->name('student_sheet_report');
Route::get('/student_sheet-report', [Student_AttendanceController::class, 'report'])->name('sheet.report');
Route::get('/staff_attendance', '\App\Http\Controllers\Staff_attendanceController@index')->name('/staff_attendance');
Route::get('staff_attendance_store','\App\Http\Controllers\Staff_attendanceController@CheckStore')->name('staff_attendance_store');
Route::get('/staff_management_sheet_report', '\App\Http\Controllers\Staff_attendanceController@sheetReport')->name('staff_management_sheet_report');
// Route::get('/staff_sheet-report', [Staff_attendanceController::class, 'report'])->name('sheet.report');
Route::get('trainer/export', [Staff_AttendanceController::class, 'export'])->name('trainer/export');
Route::get('/staff_management_sheet_report', [Staff_AttendanceController::class, 'report'])->name('staff.report');

});

    // Route::get('attendance', '\App\Http\Controllers\AttendanceController@index')->name('/attendance');
    // Route::post('attendance_store','\App\Http\Controllers\AttendanceController@CheckStore')->name('attendance_store');
    // Route::get('/sheet-report', '\App\Http\Controllers\AttendanceController@sheetReport')->name('sheet-report');

// Allow POST /user/{user} to map to update for environments where
// method spoofing (hidden _method) isn't honored by the client.
// Keep it behind auth but not restricted by Role middleware to match edit forms.
Route::group(['middleware' => ['auth']], function () {
    Route::post('/user/{user}', '\App\Http\Controllers\UserController@update');

    // Route::get('/home', 'HomeController@index')->name('home');

});

// Route::get('attendance_store', function() {
//     return redirect()->back()->with('error', 'Please submit the form.');
// });
