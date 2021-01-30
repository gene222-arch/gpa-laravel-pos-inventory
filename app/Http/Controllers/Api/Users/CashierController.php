<?php

namespace App\Http\Controllers\Api\Users;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{

    use ApiResponser;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Return the authenticated cashier information
     *
     * @param Request $request
     * @return json
     */
    public function cashier(Request $request)
    {
        return $this->success(
            $this->user->cashier($request),
            'Success',
            200
        );
    }

}
