<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Management;
use App\Models\ServiceManagement;
use App\Models\ServiceCombo;
use App\Models\category;
use App\Http\Requests\ServiceRec;
use App\Http\Controllers\Controller;

class ServiceManagementController extends Controller
{
    public function index()
    {
        $managements = Management::with('categories')->get();
        $ServiceManagements = ServiceManagement::all();
        $ServiceCombo = ServiceCombo::all();
        $categories = category::all();
        return view('admin.service_management', compact('ServiceManagements', 'ServiceCombo', 'categories'));
        flash()->success('Success','Schedule has been created successfully !');
    }

   public function store(Request $request)
  {
    $validatedData = $request->validate([
            // 'category_id' => 'required|exists:categories,id',
            'service_name' => 'string|nullable',
            'amount' => 'string|required',
            'gender' => 'string|required',
            // 'quantity' => 'string|required',
            // 'tax' => 'string|required',
            // 'total_amount' => 'string|nullable',
        ]);

        $ServiceManagement = ServiceManagement::create($validatedData);

    if ($ServiceManagement) {
        session()->flash('success', 'Employee created successfully.');
    } else {
        session()->flash('error', 'Data storage failed. Please try again.');
    }

    return redirect()->route('service.index');
}

 public function edit($id)
    {
        $ServiceManagement=ServiceManagement::find($id);
        if(!$ServiceManagement){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_service_management')->with('serviceManagement', $ServiceManagement);
    }

      public function update(ServiceRec $request, $id)
    {
        $serviceManagement = ServiceManagement::findOrFail($id);

        $validatedData = $request->validated();

        $serviceManagement->update([
            'service_name' => $validatedData['service_name'],
            'amount' => $validatedData['amount'],
            'gender' => $validatedData['gender'],
            // 'tax' => $validatedData['tax'],
        ]);

        session()->flash('success', 'Service Record has been Updated successfully!');
        return redirect()->route('service.index');
    }

     public function destroy(ServiceManagement $service)
    {
        $service->delete();
        flash()->success('Success','Service Record has been Deleted successfully !');
        return redirect()->route('service.index');
    }

}