<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Models\Employees;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\EmployeeRec;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use RealRashid\SweetAlert\Facades\Alert;
use App\Console\Commands\SendReminderEmails;
use Illuminate\Support\Facades\Notification;
use App\Notifications\EmployeeFormSmsNotification;
use Cloudinary\Cloudinary;
use App\Models\Branch;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeController extends Controller
{
    /**
     * Display a listing of employees
     */
    public function index()
    {
        $user = auth()->user();

        // If the logged-in user is a subadmin, restrict employees to their branch
        if ($user && $user->roles()->where('slug', 'subadmin')->exists()) {
            $employees = employees::where('branch_id', $user->branch_id)->get();
        } else {
            $employees = employees::all();
        }

        $Branch = Branch::all();
        $schedules = Schedule::all();

        return view('admin.employee', compact('employees', 'schedules', 'Branch', 'user'));
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $isSubadmin = $user && $user->roles()->where('slug', 'subadmin')->exists();

        // Check if user has write permission for employees
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'employees', 'write')) {
            abort(403, 'You do not have permission to create employees.');
        }

        // Define validation rules
        $validationRules = [
            'employee_name' => 'string|required',
            'employee_email' => 'required|string|email|max:255',
            'employee_number' => 'required|numeric',
            'position' => 'string|required',
            'address' => 'string|required',
            'team' => 'string|nullable',
            'place' => 'string|nullable',
            'joining_date' => 'required|date',
            'salary' => 'required|numeric',
            'gender' => 'in:Male,Female',
            'age' => 'string|required',
            'dob' => 'string|required',
            'emergency_name' => 'string|required',
            'emergency_number' => 'required|numeric',
            'aadhar_card' => 'required|numeric',
            'qualification' => 'string|nullable',
            'certificate' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'company' => 'string|nullable',
            'experience' => 'string|nullable',
            'role' => 'string|nullable',
            'old_salary' => 'string|nullable',
        ];

        // Add branch_id validation only for non-subadmin users
        if (!$isSubadmin) {
            $validationRules['branch_id'] = 'required|exists:branches,id';
        }

        $validatedData = $request->validate($validationRules);

        // Automatically set branch_id and place for subadmin users
        if ($isSubadmin) {
            $validatedData['branch_id'] = $user->branch_id;
            // Get branch information for automatic place assignment
            $branch = Branch::find($user->branch_id);
            if ($branch) {
                $validatedData['place'] = $branch->address ?? $branch->place ?? $validatedData['place'];
            }
        }

        // Generate unique employee ID
        $lastEmployee = employees::orderBy('id', 'desc')->first();
        $nextNumber = $lastEmployee ? $lastEmployee->id + 1 : 1;
        $employeeId = 'EMP' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        $validatedData['employee_id'] = $employeeId;

        // Handle certificate upload to Cloudinary
        if ($request->hasFile('certificate')) {
            $cloudinary = new Cloudinary();
            $uploadResult = $cloudinary->uploadApi()->upload($request->file('certificate')->getRealPath());
            $validatedData['certificate'] = $uploadResult['secure_url'];
        }

        // Store employee in database
        $employee = employees::create($validatedData);

        if ($employee) {
            try {

                $branch = Branch::find($request->branch_id);
                $derivedPlace = $branch && isset($branch->address) ? $branch->address : $request->place;

                // Create user account
                $newUser = User::create([
                    'name' => $validatedData['employee_name'],
                    'email' => $validatedData['employee_email'],
                    'password' => Hash::make('password123'), // Default password
                    'place' => $derivedPlace,
                    'branch_id' => $validatedData['branch_id'],
                ]);

                // Assign employee role to the new user
                $employeeRole = Role::where('slug', 'employee')->first();
                if (!$employeeRole) {
                    $employeeRole = Role::create([
                        'slug' => 'employee',
                        'name' => 'Employee'
                    ]);
                }
                $newUser->roles()->attach($employeeRole->id);

            } catch (\Exception $e) {
                Log::error('Failed to create user account for employee: ' . $e->getMessage());
                session()->flash('warning', 'Employee created successfully, but failed to create user account. Please create the user account manually.');
            }
        } else {
            session()->flash('error', 'Data storage failed. Please try again.');
        }

        return redirect()->route('employees.index');
    }

    /**
     * Show the form for editing the specified employee
     */
    public function edit($id)
    {
        $employee = employees::find($id);

        if (!$employee) {
            request()->session()->flash('error', 'Employee not found');
            return redirect()->route('employees.index');
        }

        $Branch = Branch::all();
        $employees = employees::all(); // For the view loop

        return view('includes.edit_delete_employee', compact('employee', 'Branch', 'employees'));
    }

    /**
     * Update the specified employee
     */
    public function update(EmployeeRec $request, employees $employee)
    {
        $user = auth()->user();

        // Check if user has write permission for employees
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'employees', 'write')) {
            abort(403, 'You do not have permission to update employees.');
        }

        $request->validated();

        // Update basic employee information
        $employee->employee_id = $request->employee_id;
        $employee->employee_name = $request->employee_name;
        $employee->employee_email = $request->employee_email;
        $employee->employee_number = $request->employee_number;
        $employee->address = $request->address;
        $employee->team = $request->team;
        $employee->branch_id = $request->branch_id;
        $employee->place = $request->place;
        $employee->joining_date = $request->joining_date;
        $employee->salary = $request->salary;
        $employee->gender = $request->gender;
        $employee->age = $request->age;
        $employee->dob = $request->dob;
        $employee->emergency_name = $request->emergency_name;
        $employee->emergency_number = $request->emergency_number;
        $employee->aadhar_card = $request->aadhar_card;
        $employee->qualification = $request->qualification;
        $employee->company = $request->company;
        $employee->experience = $request->experience;
        $employee->role = $request->role;
        $employee->old_salary = $request->old_salary;

        // Handle certificate upload
        if ($request->hasFile('certificate')) {
            $cloudinary = new Cloudinary();
            $uploadResult = $cloudinary->uploadApi()->upload($request->file('certificate')->getRealPath());
            $employee->certificate = $uploadResult['secure_url'];
        }

        $employee->save();
        flash()->success('Success', 'Employee Record has been Updated successfully !');

        return redirect()->route('employees.index')->with('success');
    }

    /**
     * Remove the specified employee
     */
    public function destroy(employees $employee)
    {
        $user = auth()->user();

        // Check if user has write permission for employees
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'employees', 'write')) {
            abort(403, 'You do not have permission to delete employees.');
        }

        $employee->delete();
        flash()->success('Success', 'Employee Record has been Deleted successfully !');

        return redirect()->route('employees.index')->with('success');
    }

    /**
     * Generate attendance report for employees
     */
    public function report(Request $request)
    {
        $month = $request->input('month') ?? now()->format('Y-m');
        $yearMonth = Carbon::createFromFormat('Y-m', $month);

        // Get start and end of the selected month
        $startDate = $yearMonth->copy()->startOfMonth();
        $endDate = $yearMonth->copy()->endOfMonth();

        // Load employees with filtered attendance count
        $employees = employees::withCount(['attendances as attendances_count' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('attendance_date', [$startDate, $endDate]);
        }])->get();

        return view('admin.sheet-report', compact('employees', 'yearMonth'));
    }
}
