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
        $this->middleware(['auth:api', 'permission:Manage Access Rights']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->accessRights->getAllAccessRights();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result,'Success');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\AccessRights\StoreRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $result = $this->accessRights->createAccessRights(
            $request->role,
            $request->back_office,
            $request->pos,
            $request->permissions
        );

        return ($result !== true) 
            ? $this->error($result)
            : $this->success([],
                'Role created successfully.',
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
        $result = $this->accessRights
            ->getAccessRight($request->access_right_id);

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success');
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
            $request->access_right_id,
            $request->role,
            $request->back_office,
            $request->pos,
            $request->permissions
        );

        return ($result !== true)
            ? $this->error($result)
            : $this->success([],
                'Access rights updated successfully.',
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
            $request->access_right_ids
        );

        return $this->success([], 'Access rights deleted successfully.');
    }
}
