<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\employees;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments= Appointment::with('employees')->get();
                // $managements = Management::with('categories')->get();

        $employees = employees::all();
        return view('admin.appointment',compact('appointments','employees'));
    }

    public function store(Request $request)
  {
    $validatedData = $request->validate([
        'emp_id' => 'required|exists:employees,id',
        'service' => 'string|required',
        'date' => 'string|required',
     ]);
     $appointment = Appointment::create($validatedData);
        if ($appointment) {
            session()->flash('success', 'Message sent and data stored successfully. Reminder scheduled.');
        } else {
            session()->flash('error', 'Data storage failed. Please try again.');
        }
    return redirect()->route('appointment.index')->with('success');
}

 public function edit($id)
    {
        $appointment=Appointment::find($id);
        if(!$appointment){
            request()->session()->flash('error','appointment not found');
        }
        return view('includes.edit_delete_appointment')->with('appointment',$appointment);
    }

     public function update(Request $request, Appointment $appointment)
    {
        $appointment->emp_id = $request->emp_id;
        $appointment->service = $request->service;
        $appointment->date = $request->date;
        $appointment->save();

        flash()->success('Success','Category Record has been Updated successfully !');

        return redirect()->route('appointment.index')->with('success');
    }

     public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('appointment.index')->with('success');
    }

}
