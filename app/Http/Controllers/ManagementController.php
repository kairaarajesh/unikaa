<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManagemenRec;
use App\Models\Management;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Role;
use App\Models\Branch;

class ManagementController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Resolve permission payload (read/write on branches, etc.)
        $permissionsPayload = $user
            ? checkUserPermissions($user)
            : ['permissions' => [], 'hasFullAccess' => false];

        $permissions = $permissionsPayload['permissions'];
        $hasFullAccess = $permissionsPayload['hasFullAccess'];

        $isSubadmin = $user
            && method_exists($user, 'roles')
            && $user->roles()->where('slug', 'subadmin')->exists();

        // Can this user see/select branches and auto place?
        $canViewBranchDetails = $hasFullAccess
            || hasPermission($permissions, 'branches', 'read')
            || hasPermission($permissions, 'branches')
            || $isSubadmin;

        // Base query with relations
        $managementsQuery = Management::with(['categories', 'branch']);

        // Subadmin: restrict to own branch
        $userBranch = null;
        if ($isSubadmin && isset($user->branch_id)) {
            $managementsQuery->where('branch_id', $user->branch_id);
            $userBranch = Branch::find($user->branch_id);
        }

        $managements = $managementsQuery->get();

        $categories = Category::all();

        // Branch options for the form: subadmin only sees their branch
        if ($isSubadmin && isset($user->branch_id)) {
            $Branch = Branch::where('id', $user->branch_id)->get();
        } else {
            $Branch = Branch::all();
        }

        return view(
            'admin.management',
            compact('managements', 'categories', 'Branch', 'user', 'userBranch', 'canViewBranchDetails')
        );
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $isSubadmin = $user && $user->roles()->where('slug', 'subadmin')->exists();

        // Check if user has write permission for branches (controls branch & place fields)
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        if (!$hasFullAccess && !hasPermission($permissions, 'branches', 'write')) {
            abort(403, 'You do not have permission to create products.');
        }

        // Prepare validation rules
        $validationRules = [
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'string|required',
            'product_code' => 'string|required',
            'Quantity' => 'required|numeric',
            'price' => 'required|numeric',
            'place' => 'string|nullable',
            'date' => 'string|required',
        ];

        // Add branch_id validation only for non-subadmin users
        // and only when they can actually see/select branches
        $canViewBranchDetails = $hasFullAccess
            || hasPermission($permissions, 'branches', 'read')
            || hasPermission($permissions, 'branches')
            || $isSubadmin;

        if (!$isSubadmin && $canViewBranchDetails) {
            $validationRules['branch_id'] = 'required|exists:branches,id';
        }

        $validatedData = $request->validate($validationRules);

        $category = Category::find($request->category_id);
        if (!$category) {
            return redirect()->back()->with('error', 'Category not found.');
        }

       // Automatically set branch_id and place for subadmin users
        if ($isSubadmin) {
            $validatedData['branch_id'] = $user->branch_id;
            // Get branch information for automatic place assignment
            $branch = Branch::find($user->branch_id);
            if ($branch) {
                $validatedData['place'] = $branch->address ?? $branch->place ?? $validatedData['place'];
            }
        }

        $status = Management::create($validatedData);

        if ($status) {
            return redirect()->route('management.index')->with('success', 'Product successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }


    public function edit($id)
    {
        $user = auth()->user();
        $isSubadmin = $user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists();

        // Check if user has permission to edit
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];

        $canViewBranchDetails = $hasFullAccess
            || hasPermission($permissions, 'branches', 'read')
            || hasPermission($permissions, 'branches')
            || $isSubadmin;

        $management = Management::find($id);
        if(!$management){
            request()->session()->flash('error','management not found');
            return redirect()->route('management.index');
        }

        // Subadmin can only edit managements from their branch
        if ($isSubadmin && isset($user->branch_id)) {
            if ($management->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $categories = Category::all();
        $Branch = Branch::all();

        // Get user branch for subadmin users
        $userBranch = null;
        if ($isSubadmin && isset($user->branch_id)) {
            $userBranch = Branch::find($user->branch_id);
        }

        return view('includes.edit_delete_management', compact('management', 'categories', 'Branch', 'user', 'userBranch', 'canViewBranchDetails'));
    }

    public function update(ManagemenRec $request, Management $management)
    {
        $user = auth()->user();
        $isSubadmin = $user && method_exists($user, 'roles') && $user->roles()->where('slug', 'subadmin')->exists();

        // Get user permissions to check branch access
        $userPermissions = checkUserPermissions($user);
        $permissions = $userPermissions['permissions'];
        $hasFullAccess = $userPermissions['hasFullAccess'];
        $canViewBranchDetails = $hasFullAccess
            || hasPermission($permissions, 'branches', 'read')
            || hasPermission($permissions, 'branches')
            || $isSubadmin;

        // Subadmin can only modify managements within their branch
        if ($isSubadmin && isset($user->branch_id)) {
            if ($management->branch_id !== $user->branch_id) {
                abort(403, 'Unauthorized');
            }
        }

        $request->validated();
        $management->product_name = $request->product_name;
        $management->product_code = $request->product_code;
        $management->Quantity = $request->Quantity;
        $management->price = $request->price;
        $management->category_id = $request->category_id;
        $management->date = $request->date;

        // Handle branch_id and place automatically based on permissions
        if ($isSubadmin && isset($user->branch_id)) {
            // Enforce branch for subadmin
            $management->branch_id = $user->branch_id;
            // Get place from branch automatically
            $branch = Branch::find($user->branch_id);
            if ($branch) {
                $management->place = $branch->address ?? $branch->place ?? $request->place;
            }
        } elseif ($canViewBranchDetails && $request->has('branch_id') && !empty($request->branch_id)) {
            // Users with branch permission: automatically get place from selected branch
            $management->branch_id = $request->branch_id;
            $branch = Branch::find($request->branch_id);
            if ($branch) {
                // Automatically set place from branch (prefer branch data)
                $management->place = $branch->address ?? $branch->place ?? ($request->place ?? null);
            } else {
                $management->place = $request->place;
            }
        } else {
            // For users without branch permission, just update place if provided
            if ($request->has('place')) {
                $management->place = $request->place;
            }
        }

        $management->save();

        flash()->success('Success','management Record has been Updated successfully !');

        return redirect()->route('management.index')->with('success');
    }

    public function destroy(Management $management)
    {
        $management->delete();
        flash()->success('Success','Employee Record has been Deleted successfully !');
        return redirect()->route('management.index')->with('success');
    }
}
