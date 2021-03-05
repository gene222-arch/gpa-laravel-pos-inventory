<?php

namespace App\Http\Controllers\Api\Auth;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ApiResponser;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:api')->except('logout');
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @return json
     */
    public function login(Request $request)
    {
        $attr = $this->validateLogin($request);

        if (!$this->guard()->attempt($attr))
        {
            return $this->error('Credentials mismatch', 401);
        }

        return $this->token(
            $this->getPersonalAccessToken($request),
            'User login successfully.',
            201,
            [
                'canViewDashboard' => auth()->user()->can('View Dashboard'),
                'permissions' => auth()->user()->permissions->map->name
            ]
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function getPersonalAccessToken($request)
    {
        if ($request->remember_me === 'true')
        {
            Passport::personalAccessTokensExpireIn(now()->addDays(15));
        }

        return $this->guard()->user()->createToken('Personal Access Token');
    }

    /**
     * Undocumented function
     *
     * @return json
     */
    public function logout()
    {
        $this->guard()
            ->user()
            ->token()
            ->revoke();

        return $this->success('User Logged Out', '', 200);
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return array
     */
    public function validateLogin($request)
    {
        return $request->validate([
            'email' => ['required', 'string', 'email', 'exists:users'],
            'password' => ['required', 'string'],
        ]);
    }

    /**
     * Undocumented function
     *
     * @return guard
     */
    public function guard()
    {
        return Auth::guard();
    }

}
