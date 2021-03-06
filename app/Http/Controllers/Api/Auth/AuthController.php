<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use phpseclib\Crypt\RC2;

class AuthController extends Controller
{

    use ApiResponser;

    /**
     * Get currently authenticated user data
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showAuthenticatedUser()
    {
        return $this->success(
            [
                'user' => auth()->user(),
                'role' => auth()->user()->roles->map->name->first(),
                'permissions' => auth()->user()->getPermissionsViaRoles()->map->name
            ],
            'Success');
    }


    /**
     * Get currently authenticated user data with their respective roles
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUserWithRoles(Request $request)
    {
        return $this->success(
            $request->user()->roles->map->name,
            'Success',
            200
        );
    }

}
