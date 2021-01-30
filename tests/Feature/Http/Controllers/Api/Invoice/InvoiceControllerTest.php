<?php

namespace Tests\Feature\Http\Controllers\Api\Invoice;

use Tests\TestCase;

class InvoiceControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_invoices()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/invoices', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_invoice()
    {
        $this->actingAsAdmin();

        $data = [
            'invoice_id' => 1
        ];

        $response = $this->post('api/invoices/details', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function user_can_update_invoice_status()
    {
        $this->actingAsAdmin();

        $data = [
            'invoice_ids' => [
                2,
                6
            ]
        ];

        $response = $this->put('api/invoices', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_delete_invoice()
    {
        $this->actingAsAdmin();

        $data = [
            'invoice_ids' => [
                10
            ]
        ];

        $response = $this->delete('api/invoices', $data, $this->apiHeaders());

        $this->getResponse($response);
    }

}
