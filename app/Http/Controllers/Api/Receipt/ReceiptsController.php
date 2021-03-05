<?php

namespace App\Http\Controllers\Api\Receipt;

use App\Http\Controllers\Controller;
use App\Http\Requests\Receipt\FilterResourcesRequest;
use App\Http\Requests\Receipt\ShowRequest;
use App\Traits\ApiResponser;
use App\Traits\Payment\ReceiptServices;
use Illuminate\Http\Request;

class ReceiptsController extends Controller
{
    use ApiResponser, ReceiptServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'permission:View Receipts']);
    }

    /**
     * * Get resources of sales list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (FilterResourcesRequest $request)
    {
        $result = $this->getSales($request->date);
        
        return !($result)
            ? $this->noContent('No Content')
            : $this->success($result, 'Success');
    }
    
    
      /**
     * * Get resources of sales list details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show (ShowRequest $request)
    {
        $result = $this->getSalesDetails($request->receipt_id);
        
        return !($result)
            ? $this->noContent('No Content')
            : $this->success($result, 'Success');
    }

}
