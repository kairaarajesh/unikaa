<?php

namespace App\Http\Controllers;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRec;
use App\Models\Management;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PurchaseController extends Controller
{
    public function index()
    {

        $managements = Management::all();
        $purchases = Purchase::all();
        return view('admin.purchase', compact('managements', 'purchases'));
        flash()->success('Success','Schedule has been created successfully !');

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
        ]);
        $data=$request->all();

        $management = Management::findOrFail($validatedData['management_id']);

        if ($management->Quantity < $validatedData['Quantity']) {
            return redirect()->back()->withInput()->with('error', 'Not enough packets available.');
        }
        $management->decrement('Quantity', $validatedData['Quantity']);

        $status=Purchase::create($data);
        if ($status) {
            return redirect()->route('purchase.index')->with('success', 'Product successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }

    public function edit($id)
    {
        $purchase=Purchase::find($id);
        if(!$purchase){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_purchase')->with('purchase',$purchase);
    }

     public function update(PurchaseRec $request, Purchase $purchase)
    {
        $request->validated();
        $purchase->customer_name = $request->customer_name;
        $purchase->customer_number = $request->customer_number;
        $purchase->Quantity = $request->Quantity;
        $purchase->price = $request->price;
        $purchase->total_amount = $request->total_amount;
        $purchase->branch = $request->branch;
        $purchase->payment = $request->payment;
        $purchase->product_code = $request->product_code;
        $purchase->tax = $request->tax;
        $purchase->total_calculation = $request->total_calculation;
        $purchase->discount = $request->discount;
        $purchase->management_id = $request->management_id;
        $purchase->save();

        flash()->success('Success','purchase Record has been Updated successfully !');

        return redirect()->route('billing.index')->with('success');
    }

    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('billing.index')->with('success');
    }

    public function generateInvoice($id)
    {
        $purchase = Purchase::findOrFail($id);
        $data = ['billing' => $purchase];
        $pdf = Pdf::loadView('admin/billing_show', $data);
        $todayDate = Carbon::now()->format('d-m-y');

        return $pdf->download('invoice_' . $purchase->id . '_' . $todayDate . '.pdf');
    }
}