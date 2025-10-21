<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRec extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // Use route model/parameter to ignore current user on unique email validation
        $routeUser = $this->route('user');
        $userId = is_object($routeUser) ? ($routeUser->id ?? null) : $routeUser;

        return [
            'name' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'place' => 'nullable|string',
            'email' => 'required|string|unique:users,email,' . ($userId ?? 'NULL') . ',id',
            'password' => 'nullable|string|min:6',
            'permissions' => 'nullable|array',
            'permissions_detail' => 'nullable|array',
        ];
    }
}