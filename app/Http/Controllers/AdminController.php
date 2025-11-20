<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\ServiceManagement;
// use App\Models\customer;
use App\Models\Employees;
use App\Models\Customer;
use App\Models\Datetime;
use App\Models\Purchase;
use App\Models\Attendance;
use App\Models\Management;
use App\Models\Staff_management;
use App\Models\booking;
use App\Models\Student;
use App\Models\Course;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $totalEmp =  count(management::all());
        $category =  count(category::all());
        $purchase =  count(purchase::all());
        $purchaseMonth = purchase::whereMonth('created_at', Carbon::now()->month)->count();
        $purchaseLastMonth = purchase::whereMonth('created_at',Carbon::now()->subMonth()->month)->count();
        $managementBranch = management::select('branch')->count();
        $user =  count(User::all());
        $booking =  count(booking::all());
        $service =  count(ServiceManagement::all());
        $sale =  count(ServiceManagement::all());
        $managementName = management::pluck('Quantity', 'product_name');
        // $employee =  count(employee::all());
         $customer =  count(Customer::all());
         $employees =  count(Employees::all());
         $student =  count(Student::all());
        $staff_management =  count( staff_management::all());
        // $employeeName =  employee::whereDate('date',Carbon::today())->count();

        $data = [$totalEmp,$category,$purchase,$purchaseMonth,$purchaseLastMonth,$managementBranch,$user,$managementName,$customer,$booking,$employees,$student,$staff_management,$service];
            $managementName = management::pluck('Quantity', 'product_name');

            // $twentyDaysAgo = Carbon::today()->subDays(20);
            // $employeeName = Employee::whereDate('date', $twentyDaysAgo)
            //     ->select('name', 'category', 'number')
            //     ->get();

           return view('admin.index', compact('managementName'))->with(['data' => $data]);

        //    return view('admin.index', compact('managementName','employeeName'))->with(['data' => $data]);

        }

//     public function header()
// {
//     $totalEmployees = Employee::count();
//     $todayEmployees = Employee::whereDate('date', Carbon::today())->count();

//     $employees20Days = Employee::with('category')
//         ->whereDate('created_at', Carbon::today()->subDays(20))
//         ->get();

//     return view('layouts.header', compact('totalEmployees', 'todayEmployees', 'employees20Days'));
// }

}