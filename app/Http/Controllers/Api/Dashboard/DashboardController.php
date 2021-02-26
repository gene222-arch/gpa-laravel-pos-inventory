<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardDataRequest;
use App\Traits\ApiResponser;
use App\Traits\Dashboard\DashboardServices;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    use ApiResponser, DashboardServices;

    public function __construct()
    {
        $this->middleware(['auth:api', 'role:admin|manager']);
    }


    public function index(DashboardDataRequest $request)
    {

        return $this->success([
            'salesSummary' => $this->getSalesSummary(),
            'monthlySales' => $this->getMonthlySales($request->year),
            'pendingInvoices' => $this->getPendingInvoices(),
            'inProcessPurchaseOrders' => $this->getInProcessPurchaseOrders()
        ],'Success');
    }

}
