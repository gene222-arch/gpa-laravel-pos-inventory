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
        $this->middleware(['auth:api', 'role:admin|manager']);
    }


    /**
     * * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('view', $this->supplier);

        return $this->success($this->supplier->all(),
            'Suppliers Fetched Successfully',
            200
        );
    }


   /**
     * * Get a supplier
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->supplier);

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
        $this->authorize('create', $this->supplier);

        $isSupplierCreated = $this->supplier->create($request->validated());

        return (! $isSupplierCreated )
            ? $this->serverError()
            : $this->success([],
            'Supplier created successfully',
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
        $this->authorize('update', $this->supplier);

        $isUpdated = $this->supplier
                          ->find($request->id)
                          ->update($request->validated());

        return (!$isUpdated)
            ? $this->serverError()
            : $this->success(
            [],
            'Supplier updated successfully',
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
        $this->authorize('delete', $this->supplier);

        $isDeleted = $this->supplier->deleteMany($request->id);

        return ( !$isDeleted )
            ? $this->serverError()
            : $this->success([],
            'Supplier deleted successfully',
            200
        );
    }

}
