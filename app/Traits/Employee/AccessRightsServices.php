<?php

namespace App\Traits\Employee;

use App\Models\AccessRights;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

trait AccessRightsServices
{


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
                COUNT(roles.id) as employees
            ")
            ->join('roles', 'roles.id', '=', 'access_rights.role_id')
            ->groupBy('roles.id')
            ->get()
            ->toArray();
    }


    public function getAccessRights (int $roleId)
    {
        return (new AccessRights())->where('role_id', '=', $roleId)->get();
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
                $role = tap((new Role())
                    ->create([
                        'name' => $roleName,
                        'guard_name' => 'api'
                    ]))
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
    public function updateAccessRights (int $roleId, string $roleName, bool $back_office, bool $pos, array $permissions): mixed
    {
        try {
            DB::transaction(function () use($roleId, $roleName, $back_office, $pos, $permissions)
            {
                $role = Role::find($roleId);

                $role->update([
                    'name' => $roleName
                ]);
                    
                $role->permissions()->sync($roleId, $permissions);

                (new AccessRights())
                        ->where('role_id', '=', $roleId)
                        ->update([
                            'back_office' => $back_office,
                            'pos' => $pos
                        ]);

            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function deleteMany(array $roleIds)
    {
        return (new Role())->whereIn('id', $roleIds)->delete();
    }


}
