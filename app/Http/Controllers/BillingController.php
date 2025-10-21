<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Http\Requests\PurchaseRec;
use App\Models\Management;
use App\Exports\PurchasesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class BillingController extends Controller
{
     public function index()
    {
        $managements = Management::all();

        $user = auth()->user();
        $userBranch = $user ? $user->place : null; // assuming user's branch stored in `place`

        $purchasesQuery = Purchase::query();
        // Restrict by branch only for subadmin role
        if ($user && $user->roles()->where('slug', 'subadmin')->exists() && $userBranch) {
            $purchasesQuery->where('branch', $userBranch);
        }
        $purchases = $purchasesQuery->get();

        $totalSales = $purchases->sum('total_amount');
        $totalQuantity = $purchases->sum('Quantity');
        $totalTax = $purchases->sum('tax');
        $totalDiscount = $purchases->sum('discount');
        $totalCalculations = $purchases->sum('total_calculation');

        $todaySales = $purchases->where('created_at', '>=', now()->startOfDay())->sum('total_amount');
        $monthlySales = $purchases->where('created_at', '>=', now()->startOfMonth())->sum('total_amount');
        $yearlySales = $purchases->where('created_at', '>=', now()->startOfYear())->sum('total_amount');

        return view('admin.billing', compact(
            'managements',
            'purchases',
            'totalSales',
            'totalQuantity',
            'totalTax',
            'totalDiscount',
            'totalCalculations',
            'todaySales',
            'monthlySales',
            'yearlySales'
        ));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'management_id' => 'required|exists:management,id',
            'customer_name'=>'string|required',
            'customer_number'=>'required|numeric',
            'Quantity'=>'string|required',
            'price'=>'required|numeric',
            'total_amount'=>'required|numeric',
            'branch'=>'string',
            'payment'=>'string|required',
            'product_code'=>'string|required',
            'tax'=>'numeric',
            'total_calculation'=>'numeric',
            'discount'=>'nullable|numeric',
        ]);
        $data=$request->all();

        // enforce branch from logged-in user
        $user = auth()->user();
        if ($user && isset($user->place)) {
            $data['branch'] = $user->place; // assuming user's branch stored in `place`
        }

        $management = Management::findOrFail($validatedData['management_id']);

        if ($management->Quantity < $validatedData['Quantity']) {
            return redirect()->back()->withInput()->with('error', 'Not enough packets available.');
        }
        $management->decrement('Quantity', $validatedData['Quantity']);
        $status=Purchase::create($data);

        if ($status) {
            return redirect()->route('billing.index')->with('success', 'Product successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }

    public function edit($id)
    {
        $purchase = Purchase::findOrFail($id);
        // Subadmin can only access their branch
        $user = auth()->user();
        if ($user && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($purchase->branch !== $user->place) {
                abort(403, 'Unauthorized');
            }
        }
        $managements = Management::all();
        return view('includes.edit_delete_billing', compact('purchase', 'managements'));
    }

   public function update(PurchaseRec $request, $id)
    {
        $purchase = Purchase::findOrFail($id);
        // Subadmin can only modify their branch
        $user = auth()->user();
        if ($user && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($purchase->branch !== $user->place) {
                abort(403, 'Unauthorized');
            }
        }

        $validatedData = $request->validated();

        // Update the purchase record
        $purchase->customer_name = $validatedData['customer_name'];
        $purchase->customer_number = $validatedData['customer_number'];
        $purchase->Quantity = $validatedData['Quantity'];
        $purchase->price = $validatedData['price'];
        $purchase->total_amount = $validatedData['total_amount'];
        // Keep branch unchanged or enforce user's branch if subadmin
        if ($user && $user->roles()->where('slug', 'subadmin')->exists()) {
            $purchase->branch = $user->place;
        } else {
            $purchase->branch = $request->branch;
        }
        $purchase->payment = $validatedData['payment'];
        $purchase->product_code = $validatedData['product_code'];
        $purchase->tax = $request->tax;
        $purchase->total_calculation = $request->total_calculation;
        $purchase->discount = $request->discount;
        $purchase->management_id = $validatedData['management_id'];
        $purchase->save();

        flash()->success('Success','Purchase Record has been Updated successfully !');

        return redirect()->route('billing.index')->with('success');
    }

public function destroy($id)
    {
        $purchase = Purchase::findOrFail($id);
        // Subadmin can only delete within their branch
        $user = auth()->user();
        if ($user && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($purchase->branch !== $user->place) {
                abort(403, 'Unauthorized');
            }
        }
        $purchase->delete();
        flash()->success('Success','Billing Record has been Deleted successfully !');
        return redirect()->route('billing.index')->with('success');
    }

    public function exportPurchases(Request $request)
{
    $type = $request->input('type');
    $date = $request->input('date');

    if ($type === 'daily') {
        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';
        $filename = 'daily_Sales_' . $date . '.xlsx';
    } elseif ($type === 'monthly') {
        $startDate = $date . '-01 00:00:00';
        $endDate = date("Y-m-t 23:59:59", strtotime($date . '-01'));
        $filename = 'monthly_Sales_' . $date . '.xlsx';
    } elseif ($type === 'yearly') {
        $startDate = $date . '-01-01 00:00:00';
        $endDate = $date . '-12-31 23:59:59';
        $filename = 'yearly_Sales_' . $date . '.xlsx';
    } else {
        $startDate = null;
        $endDate = null;
        $filename = 'all_Sales.xlsx';
    }

    $branch = optional(auth()->user())->place;
    return Excel::download(new PurchasesExport($startDate, $endDate, $branch), $filename);
}

     public function generateInvoice($id)
    {
        $purchase = Purchase::findOrFail($id);
        // Subadmin can only download invoices within their branch
        $user = auth()->user();
        if ($user && $user->roles()->where('slug', 'subadmin')->exists()) {
            if ($purchase->branch !== $user->place) {
                abort(403, 'Unauthorized');
            }
        }
        $data = ['purchase' => $purchase];
        $pdf = Pdf::loadView('admin/billing_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');

        return $pdf->download('invoice_' . $purchase->id . '_' . $todayDate . '.pdf');
    }

}