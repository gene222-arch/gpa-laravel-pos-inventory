<?php

namespace Tests\Feature\Http\Controllers\Api\Reports;

use Tests\TestCase;

class ReportsControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_general_analytics()
    {
        $response = $this->get('api/report/general', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_top_five_sales_by_item()
    {
        $data = [
            'monthNumber' => 2
        ];

        $response = $this->post('api/report/sales-by-item/top-5', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_sales_by_item_reports()
    {
        $data = [
            'startDate' => '2021-01-01',
            'endDate' => '2021-05-01'
        ];

        $response = $this->post('api/report/sales-by-item/reports', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_sales_by_category()
    {
        $data = [
            'startDate' => '2021-01-01',
            'endDate' => '2021-05-01'
        ];

        $response = $this->post('api/report/sales-by-category', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_sales_by_payment_type()
    {
        $data = [
            'startDate' => '2021-01-01',
            'endDate' => '2021-05-01'
        ];

        $response = $this->post('api/report/sales-by-payment-type', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_sales_by_employee()
    {
        $data = [
            'startDate' => '2021-01-01',
            'endDate' => '2021-05-01'
        ];

        $response = $this->post('api/report/sales-by-employee', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


}
