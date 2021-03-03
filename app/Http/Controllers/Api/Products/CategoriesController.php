<?php

namespace App\Http\Controllers\Api\Products;

use App\Models\User;
use App\Models\Category;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Category\ShowRequest;
use App\Http\Requests\Products\Category\StoreRequest;
use App\Http\Requests\Products\Category\DeleteRequest;
use App\Http\Requests\Products\Category\UpdateRequest;

class CategoriesController extends Controller
{

    use ApiResponser;

    protected $category;
    protected $user;

    public function __construct(User $user, Category $category)
    {
        $this->user = $user;
        $this->category = $category;
        $this->middleware(['auth:api', 'permission:Manage Categories']);
    }

    /**
     * * Get resources of categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->category->latest()->get();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Fetched successfully', 200);
    }

    /**
     * Undocumented function
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $result = $this->category->find($request->category_id);

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Fetched successfully', 200);
    }


    /**
     * * Create new resource of category
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $isCategoryStored = $this->category->create($request->validated());

        return !$isCategoryStored
            ? $this->error('Unable to create category')
            : $this->success([], 'Category created successfully', 201);
    }



    /**
     * * Update a resource category
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        $isCategoryUpdated = $this->category
                            ->find($request->id)
                            ->update($request->validated());

        return !$isCategoryUpdated
            ? $this->error('Unable to update a category')
            : $this->success([], 'Category updated successfully', 201);
    }


    /**
     * Delete resource/s category/ies
     *
     * @param DeleteRequest $request
     * @return void
     */
    public function destroy(DeleteRequest $request)
    {
        $isCategoriesDeleted = $this->category->deleteMany($request->category_ids);

        return ( !$isCategoriesDeleted )
            ? $this->serverError()
            : $this->success([],
            'Categories deleted successfully',
            200
        );
    }

}
