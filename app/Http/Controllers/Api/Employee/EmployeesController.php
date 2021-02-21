<?php

namespace App\Http\Controllers\Api\Employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\DeleteRequest;
use App\Http\Requests\Employee\ShowRequest;
use App\Http\Requests\Employee\StoreRequest;
use App\Http\Requests\Employee\UpdateRequest;
use App\Models\Employee;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    use ApiResponser;

    protected $employee;

    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
        $this->middleware(['auth:api', 'role:admin']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->employee);

        return $this->success($this->employee->getEmployees(),
            'Success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeAccessRights()
    {
        $this->authorize('viewAny', $this->employee);

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
        $this->authorize('create', $this->employee);

        $result = $this->employee->insertEmployee(
            $request->name,
            $request->email,
            $request->phone,
            $request->role
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Success',
                201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->employee);

        $employee = $this->employee->getEmployee($request->employee_id);

        return (!$employee)
            ? $this->serverError()
            : $this->success($employee,
                'Success');
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
        $this->authorize('update', $this->employee);

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
                'Success',
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
        $this->authorize('delete', $this->employee);

        $isDeleted = $this->employee->deleteEmployees($request->employee_ids);

        return (!$isDeleted)
            ? $this->serverError()
            : $this->success([],
                'Success');
    }
}
