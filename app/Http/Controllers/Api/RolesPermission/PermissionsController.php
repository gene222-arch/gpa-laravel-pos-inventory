<?php

namespace App\Http\Controllers\Api\RolesPermission;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\Permissions\PermissionServices;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    use ApiResponser, PermissionServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:Super admin']);
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(
            $this->getPermissions(),
            'Success'
        );
    }


    /**
     * Display a listing of the resource of a user.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexNames(User $user)
    {
        return $this->success(
            $user->permissions->map->name,
            'Success'
        );
    }
}
