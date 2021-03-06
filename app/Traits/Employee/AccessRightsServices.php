<?php

namespace App\Traits\Employee;

use App\Models\AccessRights;
use App\Traits\Permissions\PermissionServices;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

trait AccessRightsServices
{

    use PermissionServices;

    public function getAllAccessRights()
    {
        DB::statement('SET sql_mode="" ');

        return DB::table('access_rights')
            ->selectRaw("
                access_rights.id,
                roles.name as role,
                CASE
                    WHEN
                        access_rights.back_office = 1 AND access_rights.pos = 1
                    THEN
                        'Back office and POS'
                    WHEN
                        access_rights.back_office = 1 AND access_rights.pos = 0
                    THEN
                        'Back office'
                    WHEN
                        access_rights.back_office = 0 AND access_rights.pos = 1
                    THEN
                        'POS'
                END as access,
                COUNT(model_has_roles.model_id) as employees
            ")
            ->join('roles', 'roles.id', '=', 'access_rights.role_id')
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'access_rights.role_id')
            ->groupBy('model_has_roles.role_id')
            ->get()
            ->toArray();
    }


    public function getAccessRights (int $roleId)
    {
        return (new AccessRights())->where('role_id', '=', $roleId)->get();
    }
    


    public function getAccessRight (int $accessRightId)
    {
        $accessRight = AccessRights::find($accessRightId);

        $role = Role::findById($accessRight->role_id, 'api');

        $rolePermissions = $role->permissions->map->name;

        return ($accessRight || $rolePermissions)
        ? [
            'role' => $role->name,
            'back_office' => boolval($accessRight->back_office),
            'pos' => boolval($accessRight->pos),
            'permissions' => $rolePermissions
        ]
        : [
            'role' => '',
            'back_office' => false,
            'pos' => false,
            'permissions' => []
        ];
    }



    /**
     * Undocumented function
     *
     * @param string $roleName
     * @param boolean $back_office
     * @param boolean $pos
     * @return mixed
     */
    public function createAccessRights (string $roleName, bool $back_office, bool $pos, array $permissions): mixed
    {
        try {
            DB::transaction(function () use($roleName, $back_office, $pos, $permissions)
            {
                $role = (new Role())
                    ->create([
                        'name' => $roleName,
                        'guard_name' => 'api'
                    ])
                    ->givePermissionTo(...$permissions);


                (new AccessRights())
                    ->create([
                        'role_id' => $role->id,
                        'back_office' => $back_office,
                        'pos' => $pos
                    ]);

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    /**
     * Undocumented function
     *
     * @param integer $roleId
     * @param string $roleName
     * @param boolean $back_office
     * @param boolean $pos
     * @return mixed
     */
    public function updateAccessRights (int $accessRightId, string $roleName, bool $back_office, bool $pos, array $permissions): mixed
    {
        try {
            DB::transaction(function () use($accessRightId, $roleName, $back_office, $pos, $permissions)
            {
                $accessRight = AccessRights::find($accessRightId);
                $roleId = $accessRight->role_id;

                $accessRight
                    ->update([
                        'back_office' => $back_office,
                        'pos' => $pos
                    ]);

                $role = Role::find($roleId);

                $role->update([
                    'name' => $roleName
                ]);
                    
                $role->permissions()->detach();
                $role->givePermissionTo(...$permissions);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function deleteMany(array $accessRightIds)
    {
        $roleIds = AccessRights::whereIn('id', $accessRightIds)->pluck('role_id');

        return (new Role())->whereIn('id', $roleIds)->delete();
    }


}
