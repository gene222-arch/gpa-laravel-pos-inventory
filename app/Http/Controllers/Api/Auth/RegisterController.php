<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use App\Traits\ApiResponser;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class RegisterController extends Controller
{

    use ApiResponser;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create($data)
    {
        $user = new User();

        try {
            DB::transaction(function () use ($user, $data)
            {
                $user = $user->create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
        
                $employeeRole = Employee::where('email', '=', $data['email'])->first()->role;
        
                $user->assignRole($employeeRole);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return $user;
    }

    /**
     * Undocumented function
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $this->create($request->validated());

        if (!$this->guard()->attempt($request->only('email', 'password')))
        {
            return $this->error('Credentials mismatch', 401);
        }

        return $this->token(
            $this->getPersonalAccessToken($request),
            'Successful Registration',
            201
        );
    }

    /**
     * Undocumented function
     *
     * @return string token
     */
    public function getPersonalAccessToken($request)
    {
        if ($request->remember_me === 'true')
        {
            Passport::personalAccessTokensExpireIn(now()->addDays(15));
        }

        $user = $this->guard()->user();

        return $user->createToken('Personal Access Token');
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
