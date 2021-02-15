<?php

namespace App\Traits\Employee;

use App\Models\AccessRights;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

trait AccessRightsServices
{


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
    public function createAccessRights (string $roleName, bool $back_office, bool $pos): mixed
    {
        try {
            DB::transaction(function () use($roleName, $back_office, $pos)
            {
                $role = (new Role())->create([
                    'name' => $roleName
                ]);

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
    public function updateAccessRights (int $roleId, string $roleName, bool $back_office, bool $pos): mixed
    {
        try {
            DB::transaction(function () use($roleId, $roleName, $back_office, $pos)
            {
                (new Role())->where('id', '=', $roleId)
                    ->update([
                        'name' => $roleName
                    ]);

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
