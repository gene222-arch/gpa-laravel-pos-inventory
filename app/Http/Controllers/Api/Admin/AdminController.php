<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    use ApiResponser;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Return the authenticated admin information
     *
     * @param Request $request
     * @return json
     */
    public function authenticatedAdminInfo(Request $request)
    {
        return $this->success(
            $this->user->admin($request),
            'Success',
            200
        );
    }

}
