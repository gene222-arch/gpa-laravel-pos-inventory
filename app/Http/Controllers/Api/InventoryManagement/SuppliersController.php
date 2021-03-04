<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Models\Supplier;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\InventoryManagement\Supplier\ShowRequest;
use App\Http\Requests\InventoryManagement\Supplier\StoreRequest;
use App\Http\Requests\InventoryManagement\Supplier\DeleteRequest;
use App\Http\Requests\InventoryManagement\Supplier\UpdateRequest;

class SuppliersController extends Controller
{

    use ApiResponser;

    private $supplier;

    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->middleware(['auth:api', 'permission:Manage Suppliers']);
    }


    /**
     * * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->supplier->all();
        
        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Success', 200) ;
    }


   /**
     * * Get a supplier
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $supplier = $this->supplier->find($request->supplier_id);

        return (! $supplier )
            ? $this->serverError()
            : $this->success($supplier,
            'Success',
            201
        );
    }


    /**
     * * Create new resource supplier
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $isSupplierCreated = $this->supplier->create($request->validated());

        return (! $isSupplierCreated )
            ? $this->serverError()
            : $this->success([],
            'Supplier created successfully.',
            201
        );
    }


    /**
     * * Update a resource of supplier
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $isUpdated = $this->supplier
                          ->find($request->supplier_id)
                          ->update($request->except('supplier_id'));

        return (!$isUpdated)
            ? $this->serverError()
            : $this->success(
            [],
            'Supplier updated successfully.',
            201
        );
    }


    /**
     * * Delete resource/s of supplier/s
     *
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
        $isDeleted = $this->supplier->deleteMany($request->supplier_ids);

        return ( !$isDeleted )
            ? $this->serverError()
            : $this->success([],
            'Supplier deleted successfully.',
            200
        );
    }

}
