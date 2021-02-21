<?php

namespace App\Traits\Employee;

use App\Models\Employee;
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

    public function getEmployees()
    {
        return DB::table('employees')
            ->selectRaw('
                employees.id as id,
                employees.name,
                employees.email,
                employees.phone,
                roles.name as role
            ')
            ->join('model_has_roles', 'model_has_roles.model_id', '=', 'employees.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->get()
            ->toArray();
    }


    public function getEmployee(int $employeeId)
    {
        $emp = Employee::find($employeeId);
        $role = $emp->roles->first()->name;

        return [
            'employee' => $emp,
            'role' => $role
        ];
    }


    public function insertEmployee(string $name, string $email, string $phone, string $role)
    {
        try {
            DB::transaction(function () use ($name, $email, $phone, $role)
            {
                $employee = Employee::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone
                ]);

                $employee->assignRole($role);
            });
        } catch (\Throwable $th) {
            return $th->getMessage();
        }

        return true;
    }


    public function updateEmployee(int $employeeId, string $name, string $email, string $phone, string $role)
    {
        try {
            DB::transaction(function () use ($employeeId, $name, $email, $phone, $role)
            {
                $employee = tap(Employee::where('id', '=', $employeeId))
                    ->update([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone
                    ])
                    ->first();

                $role = Role::where('name', '=', $role)->first();

                DB::table('model_has_roles')
                    ->where('model_id', '=', $employeeId)
                    ->updateTs([
                        'role_id' => $role->id
                    ]);
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
