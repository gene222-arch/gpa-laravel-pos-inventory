<?php

namespace App\Http\Controllers\Api\Products;

use App\Models\Stock;
use App\Models\Product;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Product\StoreRequest;
use App\Http\Requests\Products\Product\DeleteRequest;
use App\Http\Requests\Products\Product\FilterProductsRequest;
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
        $this->middleware(['auth:api', 'role:admin|manager']);
    }

    /**
     * * Get resources from products and stocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $this->authorize('viewAny', $this->product);

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
    public function showFilteredProducts(FilterProductsRequest $request)
    {
        $this->authorize('viewAny', $this->product);

        $result = $this->product
            ->getAllForPos(
                $request->category_id,
                $request->productName
            );

        return !$result
            ? $this->success([], 'No Content',204)
            : $this->success($result, 'Success');
    }


    /**
     * * Get resources from products and stocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function showProductToPurchase(ShowRequest $request)
    {
        $this->authorize('view', $this->product);

        $product = $this->product->getProductForPurchaseOrder(
            $request->product_id
        );

        return !$product
            ? $this->success([], 'No Content', 204)
            : $this->success($product, 'Fetched Successfully', 200);
    }


    /**
     * * Get resources from products and stocks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $this->authorize('view', $this->product);

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
        $this->authorize('create', $this->product);

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
        $this->authorize('update', $this->product);

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
        $this->authorize('create', $this->product);

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
        $this->authorize('delete', $this->product);

        $isProductsDeleted = $this->product->deleteMany($request->product_ids);

        return ( !$isProductsDeleted )
            ? $this->serverError()
            : $this->success([],
            'Product deleted successfully.',
            200
        );
    }

}
