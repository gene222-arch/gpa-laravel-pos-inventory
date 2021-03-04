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
        $this->middleware(['auth:api', 'permission:Manage Access Rights|Manage Employees']);
    }

   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pos = $this->getPermissionsOf('POS');
        $bo = $this->getPermissionsOf('Back office');

        $result = !($pos && $bo) 
            ? []
            : [
                'POS' => $pos,
                'backOffice' => $bo 
            ];

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result);
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
