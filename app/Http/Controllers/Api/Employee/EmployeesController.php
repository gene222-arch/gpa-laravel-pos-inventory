<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\DeleteRequest;
use App\Http\Requests\Employee\ShowRequest;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Models\Employee;
use App\Traits\ApiResponser;


class EmployeesController extends Controller
{
    use ApiResponser;

    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
        $this->middleware(['auth:api', 'permission:Manage Employees']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = $this->employee->all();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeAccessRights()
    {
        return $this->success($this->employee->loadEmployeeAccessRights(),
            'Success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $result = $this->employee->create($request->validated());

        return (!$result)
            ? $this->error($result)
            : $this->success([], 'Employee created successfully.', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        $employee = $this->employee->getEmployee($request->employee_id);

        return (!$employee)
            ? $this->success([], 'No Content', 204)
            : $this->success($employee, 'Success');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request)
    {
        $result = $this->employee->updateEmployee(
            $request->employee_id,
            $request->name,
            $request->email,
            $request->phone,
            $request->role
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Employee updated successfully.',
                201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request)
    {
        $isDeleted = $this->employee->deleteEmployees($request->employee_ids);

        return (!$isDeleted)
            ? $this->serverError()
            : $this->success([], 'Employee deleted successfully.');
    }
}
