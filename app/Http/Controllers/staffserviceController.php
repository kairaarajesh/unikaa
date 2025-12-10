<?php

namespace App\Http\Controllers;
use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\Invoice;
use Carbon\Carbon;

class staffserviceController extends Controller
{

     public function index()
    {
        $filter = request('range', 'all');

        $InvoiceQuery = Invoice::with(['employee', 'customer']);

        switch ($filter) {
            case 'today':
                $InvoiceQuery->whereDate('date', Carbon::today());
                break;
            case 'week':
                $InvoiceQuery->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $InvoiceQuery->whereBetween('date', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                break;
            case 'all':
            default:
                // no additional constraints
                break;
        }

        $Invoice = $InvoiceQuery->latest()->get();
        $Employees = Employees::all();
        $Customer = Customer::all();

        return view('admin.staffservice', compact('Employees', 'Customer', 'Invoice', 'filter'));

    }
}
