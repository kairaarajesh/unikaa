<?php

function flash($title=null, $message=null)
{
    $flash = app('App\Http\Flash');
    if (func_num_args()==0) {
        return $flash;
    }
    return $flash->info($title, $message);
}


if (!function_exists('hasPermission')) {
    /**
     * Check if user has permission for a specific module
     *
     * @param array|null $permissions User permissions array
     * @param string $module Module name to check
     * @param string|null $access Specific access level (read/write)
     * @return bool
     */
    function hasPermission($permissions, $module, $access = null) {
        if (is_null($permissions)) return true; // Full access
        if (!isset($permissions[$module])) return false;

        if ($access) {
            return isset($permissions[$module . '_detail'][$access]) &&
                   $permissions[$module . '_detail'][$access];
        }

        return $permissions[$module] === true;
    }
}

if (!function_exists('checkUserPermissions')) {
    /**
     * Get user permissions and access status
     *
     * @param \App\Models\User $user
     * @return array
     */
    function checkUserPermissions($user) {
        $permissions = $user->permissions ? json_decode($user->permissions, true) : null;
        $hasFullAccess = is_null($permissions);

        return [
            'permissions' => $permissions,
            'hasFullAccess' => $hasFullAccess
        ];
    }
}

?>