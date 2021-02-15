<?php

namespace App\Http\Controllers\Api\InventoryManagement;

use App\Http\Controllers\Controller;
use App\Models\ReceivedStock;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ReceivedStocksController extends Controller
{
    use ApiResponser;
    protected $receivedStocks;

    public function __construct(ReceivedStock $receivedStocks)
    {
        $this->receivedStocks = $receivedStocks;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', $this->receivedStocks);

        return $this->success($this->receivedStocks->getAllReceivedStocks());
    }
}
