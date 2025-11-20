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
use App\Models\Booking;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

    {
   View::composer('layouts.header', function ($view) {
        try {
            // Fetch bookings from Node.js API
            $response = Http::timeout(10)->get('https://back.unikaabeauty.com/api/admin/bookings');

            $bookings = collect([]);

            if ($response->successful()) {
                $data = $response->json();

                // Handle structure: either ["data"] or ["bookings"] or root array
                if (isset($data['bookings']) && is_array($data['bookings'])) {
                    $bookings = collect($data['bookings']);
                } elseif (isset($data['data']) && is_array($data['data'])) {
                    $bookings = collect($data['data']);
                } elseif (is_array($data)) {
                    $bookings = collect($data);
                }

                // Filter for today's and tomorrow's bookings
                $today = Carbon::now()->startOfDay();
                $tomorrow = Carbon::now()->addDay()->endOfDay();

                $bookings = $bookings->filter(function ($booking) use ($today, $tomorrow) {
                    if (empty($booking['date'])) return false;
                    $date = Carbon::parse($booking['date']);
                    return $date->between($today, $tomorrow);
                });
            } else {
                Log::error('Booking API request failed', ['status' => $response->status()]);
            }

            $view->with('bookings', $bookings);
        } catch (\Exception $e) {
            Log::error('Booking API error: ' . $e->getMessage());
            $view->with('bookings', collect([]));
        }
    });
}

}
}
