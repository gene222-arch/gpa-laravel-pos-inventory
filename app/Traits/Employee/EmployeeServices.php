<?php

namespace App\Traits\Employee;

use App\Models\Employee;
use Illuminate\Support\Facades\DB;

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
        return Employee::all();
    }


    public function getEmployee(int $employeeId)
    {
        return Employee::find($employeeId);
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

                $employee->assignRole('cashier');
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

                $employee->assignRole('cashier');
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
