<?php

namespace App\Http\Controllers\Api\Products;

use App\Models\User;
use App\Models\Category;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Category\StoreRequest;
use App\Http\Requests\Products\Category\DeleteRequest;
use App\Http\Requests\Products\Category\UpdateRequest;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    use ApiResponser;

    protected $category;
    protected $user;

    public function __construct(User $user, Category $category)
    {
        $this->user = $user;
        $this->category = $category;
        $this->middleware(['auth:api', 'role:admin|manager']);
    }

    /**
     * * Get resources of categories
     *
     * @return void
     */
    public function index()
    {
        $this->authorize('view', $this->category);

        return $this->success($this->category->all(),
            'Fetched successfully',
            200
        );
    }


    /**
     * * Create new resource of category
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $this->authorize('create', $this->category);

        $isCategoryStored = $this->category->create($request->validated());

        return ( !$isCategoryStored )
            ? $this->serverError()
            : $this->success([],
            'Category created successfully',
            201
        );
    }


    /**
     * * Update a resource category
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $this->authorize('update', $this->category);

        $isCategoryUpdated = $this->category
                            ->find($request->id)
                            ->update($request->validated());

        return ( !$isCategoryUpdated )
            ? $this->serverError()
            : $this->success(
            [],
            'Category updated successfully',
            201
        );
    }


    /**
     * Delete resource/s category/ies
     *
     * @param DeleteRequest $request
     * @return void
     */
    public function destroy(DeleteRequest $request)
    {
        $this->authorize('delete', $this->category);

        $isCategoriesDeleted = $this->category->deleteMany($request->id);

        return ( !$isCategoriesDeleted )
            ? $this->serverError()
            : $this->success([],
            'Product/s deleted successfully',
            200
        );
    }

}
