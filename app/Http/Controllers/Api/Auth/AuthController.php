<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangeEmailRequest;
use App\Http\Requests\Auth\ChangeNameRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\CheckPasswordRequest;
use App\Traits\ApiResponser;
use App\Traits\Auth\AuthServices;


class AuthController extends Controller
{

    use ApiResponser, AuthServices;

    public function __construct()
    {
        $this->middleware(['auth:api']);
    }


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
     * Check if user password is correct
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkPassword(CheckPasswordRequest $request)
    {
        $result = $this->validatePassword(
            $request->password 
        );
        
        return !$result 
            ? $this->error('Incorrect password.', 400)
            : $this->success([], 'Password verified');
    }


    /**
     * Update user password
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePassword(ChangePasswordRequest $request)
    {
        $result = $this->changePassword($request->new_password);

        return !$result
            ? $this->error('Password update failed', 400)
            : $this->success([], 'Password updated successfully');
    }


    /**
     * Update user name
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateName(ChangeNameRequest $request)
    {
        $result = $this->changeName($request->new_name);

        return !$result
            ? $this->error('Name update failed', 400)
            : $this->success([], 'Name updated successfully');
    }


    /**
     * Update user email
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateEmail(ChangeEmailRequest $request)
    {
        $result = $this->changeEmail($request->new_email);

        return !$result
            ? $this->error('Email update failed', 400)
            : $this->success([], 'Email updated successfully');
    }

}
