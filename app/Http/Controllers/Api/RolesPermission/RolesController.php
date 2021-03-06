<?php

namespace App\Http\Controllers\Api\RolesPermission;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use ApiResponser;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:Manage Access Rights|Manage Employees']);
    }


    public function index ()
    {
        return $this->success(
            Role::where('id', '!=', 1)->get(),
            'Success'
        );
    }

}
