<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRec;
use App\Models\User;
use App\Models\Branch;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Ensure the authenticated user has access to subadmin management.
     *
     * @param  string|null  $access
     * @return void
     */
    protected function ensureSubadminPermission(?string $access = null): void
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $userPermissions = checkUserPermissions($user);

        if ($userPermissions['hasFullAccess']) {
            return;
        }

        $permissions = $userPermissions['permissions'];

        if (!hasPermission($permissions, 'subadmin')) {
            abort(403, 'Unauthorized.');
        }

        if ($access !== null) {
            $details = $permissions['subadmin_detail'] ?? null;

            if ($access === 'write') {
                if (empty($details['write'])) {
                    abort(403, 'Unauthorized.');
                }
            } elseif ($access === 'read') {
                $hasReadAccess = $details === null || !empty($details['read']) || !empty($details['write']);

                if (!$hasReadAccess) {
                    abort(403, 'Unauthorized.');
                }
            }
        }
    }

    public function index()
    {
        $this->ensureSubadminPermission('read');

        $users = User::with('roles')->get();
        $Branch = Branch::all();
        return view('admin.user', compact('users','Branch'));
    }

    public function store(Request $request)
    {
        $this->ensureSubadminPermission('write');

        $this->validate($request, [
            // 'name' => 'string|required',
            'branch_id' => 'required|exists:branches,id',
            'email' => 'required|string|unique:users',
            'password' => 'string|required|min:6',
            'place' => 'string|required',
            // 'role' => 'string|required|in:subadmin',
            'permissions' => 'array',
            'permissions_detail' => 'array'
        ]);

        // Derive place from selected branch id (use branch address, fallback to submitted place)
        $branch = Branch::find($request->branch_id);
        $derivedPlace = $branch && isset($branch->address) ? $branch->address : $request->place;

        $user = User::create([
            // 'name' => $request->name,
            'branch_id' => $request->branch_id,
            'place' => $derivedPlace,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Get or create subadmin role
        $role = Role::firstOrCreate(
            ['slug' => 'subadmin'],
            ['name' => 'Subadmin']
        );

        // Attach role to user
        $user->roles()->attach($role->id);

        // Store permissions
        if ($request->has('permissions')) {
            $permissions = [];
            foreach ($request->permissions as $permission) {
                $permissions[$permission] = true;

                // Handle detailed permissions for all modules
                if ($request->has('permissions_detail.' . $permission)) {
                    $permissions[$permission . '_detail'] = $request->permissions_detail[$permission];
                }
            }
            $user->permissions = json_encode($permissions);
            $user->save();
        }

        if ($user) {
            return redirect()->route('user.index')->with('success', 'Subadmin successfully created');
        } else {
            return redirect()->back()->with('error', 'Error, Please try again');
        }
    }

    public function edit($id)
    {
        $this->ensureSubadminPermission('read');

        $user = User::find($id);

        if (!$user) {
            request()->session()->flash('error', 'Employee not found');
            return redirect()->route('user.index');
        }
    }

    public function update(UserRec $request, int $id)
    {
        $this->ensureSubadminPermission('write');

        // Validate request
        $validated = $request->validated();

        // Fetch user or fail
        $user = User::findOrFail($id);

        // Derive place from selected branch id (use branch address, fallback to submitted place)
        $derivedPlace = $user->place;
        if ($request->filled('branch_id')) {
            $branch = Branch::find($request->branch_id);
            $derivedPlace = $branch && isset($branch->address) ? $branch->address : ($request->place ?? $user->place);
        } elseif ($request->filled('place')) {
            $derivedPlace = $request->place;
        }

        // Update basic fields
        if ($request->filled('name')) {
            $user->name = $request->name;
        }
        if ($request->filled('branch_id')) {
            $user->branch_id = $request->branch_id;
        }
        $user->place = $derivedPlace;
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Permissions
        $permissions = [];
        if ($request->has('permissions') && is_array($request->permissions)) {
            foreach ($request->permissions as $permission) {
                $permissions[$permission] = true;
                if ($request->has('permissions_detail.' . $permission)) {
                    $permissions[$permission . '_detail'] = $request->permissions_detail[$permission];
                }
            }
        }
        $user->permissions = !empty($permissions) ? json_encode($permissions) : null;

        $user->save();

        flash()->success('Success', 'Subadmin record has been updated successfully!');
        return redirect()->route('user.index');
    }

    public function destroy($id)
    {
        $this->ensureSubadminPermission('write');

        $user = User::findOrFail($id);
        $user->delete();

        flash()->success('Success', 'Subadmin record has been deleted successfully!');
        return redirect()->route('user.index');
    }



    //   public function destroy($id)
    // {
    //     $management = Management::findOrFail($id);
    //     $management->delete();
    //     return redirect()->route('management.index')->with('success', 'Product has been deleted successfully!');
    // }
}
