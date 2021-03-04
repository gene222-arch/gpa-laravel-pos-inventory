<?php

namespace App\Traits\Permissions;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

trait PermissionServices
{

    public function getPermissionsOf(string $systemType)
    {
        return DB::table('system_permission')
            ->selectRaw('
                system_permission.id,
                permissions.name as permission
            ')
            ->join('permissions', 'permissions.id', '=', 'system_permission.permission_id')
            ->where('system_permission.name', '=', $systemType)
            ->get()
            ->toArray();
    }

}
