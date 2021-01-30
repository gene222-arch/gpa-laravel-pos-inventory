<?php

namespace Tests\Feature\Http\Controllers\Api\Pos;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PosControllerTest extends TestCase
{
    /**
     * test
     */
    public function user_can_get_all_orders_in_pos()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/pos/order-lists', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_customer_pos_details()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1
        ];

        $response = $this->post('api/pos/cart', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }

    /**
     * test
     */
    public function user_can_get_customer_amount_to_pay()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1
        ];

        $response = $this->post('api/pos/to-pay', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * @test
     */
    public function user_can_create_customer_orders_in_pos()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 2,
            'product_id' => 27
        ];

        $response = $this->post('api/pos/add-to-cart', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_customer_orders_quantities_in_pos()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1,
            'product_id' => 20,
            'quantity' => 10,
        ];

        $response = $this->put('api/pos/item-qty', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_increment_customer_orders_quantity()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1,
            'product_id' => 20,
        ];

        $response = $this->put('api/pos/increase-item-qty', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_decrement_customer_orders_quantity()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1,
            'product_id' => 20,
        ];

        $response = $this->put('api/pos/decrease-item-qty', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_remove_customer_ordered_items_in_pos()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1,
            'product_id' => 20
        ];

        $response = $this->delete('api/pos/item', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_process_customer_payment()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 1,
            'payment_method' => 'invoice',
            'numberOfDays' => 10,
        ];

        $response = $this->post('api/pos/process-payment', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_cancel_customer_orders_in_pos()
    {
        $this->actingAsAdmin();

        $data = [
            'customer_id' => 2,
        ];

        $response = $this->delete('api/pos/cancel-orders', $data, $this->apiHeaders());

        $this->getResponse($response);
    }
}
