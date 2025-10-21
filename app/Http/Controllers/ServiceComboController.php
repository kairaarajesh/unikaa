<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceManagement;
use App\Models\ServiceCombo;
use App\Http\Requests\ServiceRec;

class ServiceComboController extends Controller
{

    public function index()
    {
        $ServiceCombo = ServiceCombo::all();
        $ServiceManagements = ServiceManagement::all();
        return view('admin.service_management', compact('ServiceManagements', 'ServiceCombo'));
        flash()->success('Success','Schedule has been created successfully !');
    }

   public function store(Request $request)
  {
    $validatedData = $request->validate([
            // 'service_name' => 'array|required',
            'service_combo' => 'array|required',
            'service_combo.*' => 'string|required',
            'amount' => 'string|required',
            'gender' => 'string|required',
            'quantity' => 'string|required',
            'total_amount' => 'string|nullable',
        ]);

        $validatedData['service_combo'] = json_encode($validatedData['service_combo']);

        $ServiceCombo = ServiceCombo::create($validatedData);

    if ($ServiceCombo) {
        session()->flash('success', 'Service created successfully.');
    } else {
        session()->flash('error', 'Data storage failed. Please try again.');
    }

    return redirect()->route('service.index');
}

  public function edit($id)
    {
        $ServiceCombo = ServiceCombo::findOrFail($id);
        $ServiceManagements = ServiceManagement::all();
        return view('includes.edit_delete_service_combo', compact('ServiceCombo', 'ServiceManagements'));
    }

      public function update(ServiceRec $request, $id)
    {
        $ServiceCombo = ServiceCombo::findOrFail($id);
        $request->validated();
        $ServiceCombo->service_combo = json_encode($request->service_combo);
        $ServiceCombo->amount = $request->amount;
        $ServiceCombo->quantity = $request->quantity;
        $ServiceCombo->gender = $request->gender;
        // $ServiceCombo->tax = $request->tax;
        // $ServiceCombo->total_amount = $request->total_amount;
        $ServiceCombo->save();
        flash()->success('Success','Service Record has been Updated successfully !');

        return redirect()->route('service.index')->with('success');
    }

     public function destroy($id)
    {
        $ServiceCombo = ServiceCombo::findOrFail($id);
        $ServiceCombo->delete();
        flash()->success('Success','Service Record has been Deleted successfully !');
        return redirect()->route('service.index')->with('success');
    }

}