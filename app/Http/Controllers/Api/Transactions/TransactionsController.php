<?php

namespace App\Http\Controllers\Api\Transactions;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponser;
use App\Traits\Transactions\TransactionServices;
use Illuminate\Http\Request;

class TransactionsController extends Controller
{
    use ApiResponser, TransactionServices;

    public function __construct()
    {
        $this->middleware(['role:admin|manager']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerOrderTransactions()
    {
        return $this->success($this->customerOrders(), 'Success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoiceTransactions()
    {
        return $this->success($this->invoices(), 'Success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function purchaseOrderTransactions()
    {
        return $this->success($this->purchaseOrders(), 'Success');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function receivedStocksTransactions()
    {
        return $this->success($this->receivedStocks(), 'Success');
    }

}
