<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Branch;
use App\Models\ServiceManagement;
use App\Models\Management;
use App\Models\Employees;
use App\Models\Invoice;
use App\Models\ServiceCombo;

class BillController extends Controller
{
     public function index(Request $request)
    {
        // Filters: default to today's date
         $Branch = Branch::all();
         $services = ServiceManagement::all();
         $managements = Management::all();
         $employees = Employees::all();
         $customer = Customer::all();
         $invoice = Invoice::all();
         $serviceCombos = ServiceCombo::all();
        //  Duplicate fetch removed
        $date = $request->input('date');
        $number = $request->input('number');

            $query = Invoice::with(['customer']);

        if ($date) {
            $query->whereDate('date', $date);
        } else {
            $query->whereDate('date', now()->toDateString());
        }

        if ($number) {
            $query->whereHas('customer', function($q) use ($number) {
                $q->where('number', 'like', '%' . $number . '%');
            });
        }

        $bills = $query->orderByDesc('id')->get();

            // Customers list (for "Customers" table)
            $customersQuery = Customer::query();
            if ($date) {
                $customersQuery->whereDate('date', $date);
            } else {
                $customersQuery->whereDate('date', now()->toDateString());
            }
            if ($number) {
                $customersQuery->where('number', 'like', '%' . $number . '%');
            }
            $customers = $customersQuery
                ->select(['id', 'customer_id', 'name', 'number', 'date'])
                ->orderByDesc('date')
                ->limit(100)
                ->get();

        $selectedCustomer = null;
        $visitsCount = 0;
        $totalBillsAmount = 0;
        if ($number) {
            $selectedCustomer = Customer::where('number', 'like', '%' . $number . '%')->orderByDesc('date')->first();
            $visitsCount = Customer::where('number', 'like', '%' . $number . '%')->count();
            $totalBillsAmount = (float) Invoice::whereHas('customer', function($q) use ($number) {
                $q->where('number', 'like', '%' . $number . '%');
            })->sum('total_amount');
        }

        $baseData = [
            'bills' => $bills,
            'filters' => [
                'date' => $date ?: now()->toDateString(),
                'number' => $number,
            ],
            'selectedCustomer' => $selectedCustomer,
            'visitsCount' => $visitsCount,
            'totalBillsAmount' => $totalBillsAmount,
            'customers' => $customers,
        ];

        $extraData = compact('employees', 'services', 'managements', 'Branch','customer', 'invoice', 'serviceCombos');

        return view('admin.bill', array_merge($baseData, $extraData));
    }
}
