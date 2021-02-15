<?php

namespace App\Http\Controllers\Api\Reports;

use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;
use App\Traits\Reports\ReportsSummaryServices;
use App\Http\Requests\Reports\SalesByCategoryRequest;
use App\Http\Requests\Reports\SalesByEmployeeRequest;
use App\Http\Requests\Reports\SalesByPaymentTypeRequest;
use App\Http\Requests\Reports\SalesByItemReportRequest;
use App\Http\Requests\Reports\TopFiveSalesByItemRequest;

class ReportsController extends Controller
{
    use ApiResponser, ReportsSummaryServices;


    public function __construct()
    {
        $this->middleware(['auth:api', 'role:admin|manager']);
    }


    /**
     * Undocumented function
     *
     * @return \Illuminate\Http\Response
     */
    public function generalAnalytics()
    {
        return $this->success($this->salesSummary(), 'Success');
    }


    /**
     * Undocumented function
     *
     * @param TopFiveSalesByItemRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getTopFiveSalesByItem(TopFiveSalesByItemRequest $request)
    {
        $topFiveMonthlySales = $this->topFiveSalesByItem(
            $request->year,
    $request->monthNumber
        );

        return (!$topFiveMonthlySales)
            ? $this->serverError()
            : $this->success( $topFiveMonthlySales,
            'Success');
    }


    /**
     * Undocumented function
     *
     * @param SalesByItemReportRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getSalesByItemReports(SalesByItemReportRequest $request)
    {
        $topFiveSales = $this->salesByItemReports(
            $request->startDate,
            $request->endDate
        );

        return (!$topFiveSales)
            ? $this->serverError()
            : $this->success($topFiveSales,
            'Success');
    }


    /**
     * Undocumented function
     *
     * @param SalesByCategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getSalesByCategory(SalesByCategoryRequest $request)
    {
        $topFiveSales = $this->salesByCategory(
            $request->startDate,
            $request->endDate
        );

        return (!true)
            ? $this->serverError()
            : $this->success($topFiveSales,
            'Success');
    }



   /**
     * Undocumented function
     *
     * @param SalesByPaymentTypeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getSalesByPaymentType(SalesByPaymentTypeRequest $request)
    {
        $salesByPaymentType = $this->salesByPamentType(
            $request->startDate,
            $request->endDate
        );

        return (!$salesByPaymentType)
            ? $this->serverError()
            : $this->success($salesByPaymentType,
            'Success');
    }


   /**
     * Undocumented function
     *
     * @param SalesByEmployeeRequest $request
     * @return \Illuminate\Http\Response
     */
    public function getSalesByEmployee(SalesByEmployeeRequest $request)
    {
        $salesByEmployee = $this->salesByEmployee(
            $request->startDate,
            $request->endDate
        );

        return (!$salesByEmployee)
            ? $this->serverError()
            : $this->success($salesByEmployee,
            'Success');
    }



}
