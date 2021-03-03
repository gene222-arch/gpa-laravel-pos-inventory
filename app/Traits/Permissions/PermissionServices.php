<?php

namespace App\Traits\Permissions;

use Spatie\Permission\Models\Permission;

trait PermissionServices
{

    public function getPermissions()
    {
        return Permission::all([
            'id',
            'name'
        ]);
    }

}
