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
        try {
            // Find the service combo
            $ServiceCombo = ServiceCombo::findOrFail($id);

            // Validate the request
            $validated = $request->validated();

            // Validate that at least one service is selected
            if (empty($request->service_combo) || !is_array($request->service_combo)) {
                return back()->with('error', 'Please select at least one service')->withInput();
            }

            // Calculate total amount from selected services
            $servicesTotal = 0;
            foreach ($request->service_combo as $serviceId) {
                $service = \App\Models\ServiceManagement::find($serviceId);
                if ($service) {
                    $servicesTotal += $service->amount;
                }
            }

            // Validate offer price doesn't exceed services total
            $offerPrice = floatval($request->quantity);
            if ($offerPrice > $servicesTotal) {
                return back()
                    ->with('error', 'Discount/Offer price cannot exceed total services amount')
                    ->withInput();
            }

            // Calculate final amount
            $finalAmount = $servicesTotal - $offerPrice;
            if ($finalAmount < 0) {
                $finalAmount = 0;
            }

            // Update the service combo
            $ServiceCombo->service_combo = json_encode($request->service_combo);
            $ServiceCombo->amount = number_format($servicesTotal, 2, '.', '');
            $ServiceCombo->quantity = number_format($offerPrice, 2, '.', '');
            $ServiceCombo->total_amount = number_format($finalAmount, 2, '.', '');
            $ServiceCombo->gender = $request->gender;

            // Save the changes
            $ServiceCombo->save();

            // Success message
            flash()->success('Success', 'Service Combo has been updated successfully!');

            return redirect()->route('service.index')->with('success', 'Service Combo updated successfully');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Service Combo Update Error: ' . $e->getMessage());

            // Return with error
            return back()
                ->with('error', 'An error occurred while updating the service combo: ' . $e->getMessage())
                ->withInput();
        }
    }

     public function destroy($id)
    {
        $ServiceCombo = ServiceCombo::findOrFail($id);
        $ServiceCombo->delete();
        flash()->success('Success','Service Record has been Deleted successfully !');
        return redirect()->route('service.index')->with('success');
    }

}
