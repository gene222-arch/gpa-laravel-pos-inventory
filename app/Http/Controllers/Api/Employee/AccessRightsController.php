<?php

namespace App\Http\Controllers\Api\Employee;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\AccessRights\DeleteRequest;
use App\Http\Requests\AccessRights\ShowRequest;
use App\Http\Requests\AccessRights\StoreRequest;
use App\Http\Requests\AccessRights\UpdateRequest;
use App\Models\AccessRights;

class AccessRightsController extends Controller
{
    use ApiResponser;
    protected $accessRights;

    public function __construct(AccessRights $accessRights)
    {
        $this->accessRights = $accessRights;
        $this->middleware(['auth:api', 'role:Super admin']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->success($this->accessRights->getAllAccessRights(),'Success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\AccessRights\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $isAccessRightsCreated = $this->accessRights->createAccessRights(
            $request->role_name,
            $request->back_office,
            $request->pos,
            $request->permissions
        );

        return (!$isAccessRightsCreated)
            ? $this->serverError()
            : $this->success([],
                'Success',
                201);
    }

    /**
     * Undocumented function
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $getAccessRights = $this->accessRights->find($request->role_id);

        return $this->success($getAccessRights,
        'Success',
        200);
    }

   /**
    * Undocumented function
    *
    * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function update(UpdateRequest $request)
    {
        $result = $this->accessRights->updateAccessRights(
            $request->role_id,
            $request->role_name,
            $request->back_office,
            $request->pos,
            $request->permissions
        );

        return ($result !== true)
            ? $this->error($request)
            : $this->success([],
                'Success',
                201);
    }

    /**
     * Remove the resources from storage.
     *
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
    */
    public function destroy(DeleteRequest $request)
    {
        $this->accessRights->deleteMany(
            $request->role_ids
        );

        return $this->success([],
                'Success',
                200);
    }
}
