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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser(Request $request)
    {
        return $this->success(
            $request->user(),
            'Success',
            200
        );
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
