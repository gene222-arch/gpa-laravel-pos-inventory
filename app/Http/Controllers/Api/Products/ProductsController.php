<?php

namespace App\Http\Controllers\Api\Products;

use App\Models\Stock;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Product\StoreRequest;
use App\Http\Requests\Products\Product\DeleteRequest;
use App\Http\Requests\Products\Product\ImageUploadRequest;
use App\Http\Requests\Products\Product\ShowRequest;
use App\Http\Requests\Products\Product\UpdateRequest;


class ProductsController extends Controller
{

    use ApiResponser;

    private $product;
    private $stock;


    public function __construct(Product $product, Stock $stock)
    {
        $this->product = $product;
        $this->stock = $stock;
        $this->middleware(['auth:api', 'permission:Manage Products']);
    }

    /**
     * * Get resources from products and stocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $result = $this->product->getAll();

        return !$result
            ? $this->success([], 'No Content', 204)
            : $this->success($result, 'Fetched Successfully');
    }



    /**
     * * Get resources from products and stocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $product = $this->product
            ->where('id', '=', $request->product_id)
            ->with('stock')
            ->first();

        return !$product
            ? $this->success([], 'No Content', 204)
            : $this->success($product, 'Fetched Successfully', 200);
    }


    /**
     * * Create new resource of products and stocks
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        try {
            DB::transaction(function () use($request)
            {
                $productId = $this->product->createProduct($request->product);

                $this->stock->createStock($productId, $request->stock);

            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success([],
        'Product created successfully.',
        201
        );
    }


    /**
     * * Update a resource of `products` and `stocks` tables
     *
     * @param UpdateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request)
    {
        try {
            DB::transaction(function () use($request)
            {
                $this->product
                    ->updateProduct(
                        $request->product['product_id'],
                        $request->product['data']
                    );

                $this->stock
                    ->updateStock(
                    $request->product['product_id'],
                        $request->stock['data']
                    );
            });
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }

        return $this->success([],
        'Product updated successfully.',
        201
        );
    }


    /**
     * * Create new resource of products and stocks
     *
     * @param StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(ImageUploadRequest $request)
    {
        $file = $this->product->uploadImage($request);

        return $this->success($file, 'Success');
    }



    /**
     * * Delete resource/s of products and stocks
     *
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(DeleteRequest $request)
    {
        $isProductsDeleted = $this->product->deleteMany($request->product_ids);

        return ( !$isProductsDeleted )
            ? $this->serverError()
            : $this->success([],
            'Product deleted successfully.',
            200
        );
    }

}
