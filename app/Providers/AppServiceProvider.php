<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Management;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\User;
use App\Models\Employee;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Check if the current route is not 'login'
        if (!request()->is('login')) {
            View::composer('layouts.header', function ($view) {
                $totalEmp = Management::count();
                $category = Category::count();
                $purchase = Purchase::count();
                $purchaseMonth = Purchase::whereMonth('created_at', Carbon::now()->month)->count();
                $purchaseLastMonth = Purchase::whereMonth('created_at', Carbon::now()->subMonth()->month)->count();
                $managementBranch = Management::select('branch')->count();
                $user = User::count();
                $managementName = Management::pluck('Quantity', 'product_name');
                // $employee = Employee::count();
                // $employeeNameToday = Employee::whereDate('date', Carbon::today())->count();

                $data = [
                    $totalEmp, $category, $purchase, $purchaseMonth,
                    $purchaseLastMonth, $managementBranch, $user,
                    $managementName
                ];

                // $twentyDaysAgo = Carbon::today()->subDays(20);
                // $employeeName = Employee::whereDate('date', $twentyDaysAgo)
                //     ->select('name', 'category', 'number')
                //     ->get();

                // $view->with([
                //     'data' => $data,
                //     'employeeName' => $employeeName
                // ]);
            });
        }
    }
}

