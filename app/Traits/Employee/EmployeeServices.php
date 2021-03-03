<?php

namespace App\Traits\Employee;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

trait EmployeeServices
{

    public function loadEmployeeAccessRights()
    {
        DB::statement('Set sql_mode = "" ');

        return DB::table('roles')
            ->selectRaw("
                CONCAT(UCASE(LEFT(roles.name, 1)), SUBSTRING(roles.name, 2)) as role,
                CASE
                    WHEN roles.name = 'admin' || roles.name = 'manager'
                    THEN
                        'Back Office and POS'
                    ELSE
                        'POS'
                END as access_rights,
                COUNT(roles.id) as employees
            ")
            ->join('model_has_roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->groupBy('roles.id')
            ->get();
    }


    public function getEmployee(int $employeeId)
    {
        $emp = Employee::find($employeeId);

        return [
            'employee_id' => $emp->id,
            'name' => $emp->name,
            'email' => $emp->email,
            'phone' => $emp->phone,
            'role' => $emp->role
        ];
    }



    public function updateEmployee(int $employeeId, string $name, string $email, string $phone, string $role)
    {
        try {
            DB::transaction(function () use ($employeeId, $name, $email, $phone, $role)
            {
                $user = new User();

                tap(Employee::where('id', '=', $employeeId))
                    ->update([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'role' => $role
                    ])
                    ->first();

                $user->where('email', '=', $email)
                    ->syncRoles([$role]);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function deleteEmployees(array $employeeIds)
    {
        return Employee::whereIn('id', $employeeIds)->delete();
    }

}
