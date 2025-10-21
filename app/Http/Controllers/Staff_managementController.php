<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Staff_management;
use App\Http\Requests\staff_ManagementRec;

class Staff_ManagementController extends Controller
{
    public function index()
    {

        $Staff_managements = Staff_management::all();
        // $purchases = purchase::all();
        return view('admin.staff_management', compact('Staff_managements'));
        flash()->success('Success','Schedule has been created successfully !');

    }

   public function store(Request $request)
    {
        $this->validate($request, [
            'trainer'=>'string|required',
            'trainer_email' => 'required|string|email|max:255',
            'trainer_number' => 'required|numeric',
            'branch' => 'string|required',
            'joining_date' => 'required|date',
            'gender' => 'in:Male,Female',
            'dob' => 'string|required',
            'subject'=>'string|required',
            'salary'=>'string|required',
            'street' => 'string|required',
            'city' => 'string|required',
            'state' => 'string|required',
            'pin_code' => 'required|numeric',
            'emergency_name' => 'string|required',
            'emergency_number' => 'required|numeric',
            'aadhar_card' => 'required|numeric',
            'commission'=>'numeric',
        ]);

        $data=$request->all();

        $status=Staff_management::create($data);
        if ($status) {
            return redirect()->route('staff_management.index')->with('success', 'Product successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }

    public function edit($id)
    {
        $Staff_managements=Staff_management::find($id);
        if(!$Staff_managements){
            request()->session()->flash('error','management not found');
        }
        return view('includes.edit_delete_Staff_management')->with('Staff_managements',$Staff_managements);
    }

     public function update( staff_ManagementRec $request , Staff_management $staff_management)
    {
        $request->validated();

        $staff_management->trainer = $request->trainer;
        $staff_management->trainer_email = $request->trainer_email;
        $staff_management->trainer_number = $request->trainer_number;
        $staff_management->branch = $request->branch;
        $staff_management->joining_date = $request->joining_date;
        $staff_management->gender = $request->gender;
        $staff_management->dob = $request->dob;
        $staff_management->street = $request->street;
        $staff_management->city = $request->city;
        $staff_management->state = $request->state;
        $staff_management->pin_code = $request->pin_code;
        $staff_management->emergency_name = $request->emergency_name;
        $staff_management->emergency_number = $request->emergency_number;
        $staff_management->subject = $request->subject;
        $staff_management->salary = $request->salary;
        $staff_management->commission = $request->commission;
        $staff_management->save();

        flash()->success('Success','staff management Record has been Updated successfully !');

        return redirect()->route('staff_management.index')->with('success');
    }

 public function destroy(Staff_management $staff_management)
    {
        $staff_management->delete();
        flash()->success('Success','Staff management Record has been Deleted successfully !');
        return redirect()->route('staff_management.index')->with('success');
    }

}