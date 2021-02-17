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


        $response = $this->get('api/pos/order-lists', $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_customer_cart_details()
    {
        $data = [
            'customer_id' => 1
        ];

        $response = $this->post('api/pos/cart-details', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_get_customer_amount_to_pay()
    {
        $data = [
            'customer_id' => 1
        ];

        $response = $this->post('api/pos/to-pay', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }


    /**
     * test
     */
    public function user_can_add_to_cart_in_customers_orders_in_pos()
    {
        $data = [
            'customer_id' => 2,
            'product_id' => 23
        ];

        $response = $this->post('api/pos/add-to-cart', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }

    /**
     * test
     */
    public function user_can_update_customer_orders_quantities_in_pos()
    {
        $data = [
            'customer_id' => 2,
            'product_id' => 23,
            'quantity' => 1000,
        ];

        $response = $this->put('api/pos/item-qty', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_increment_customer_orders_quantity()
    {


        $data = [
            'customer_id' => 1,
            'product_id' => 19,
        ];

        $response = $this->put('api/pos/increase-item-qty', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_decrement_customer_orders_quantity()
    {
        $data = [
            'customer_id' => 1,
            'product_id' => 19,
        ];

        $response = $this->put('api/pos/decrease-item-qty', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_assign_a_discount_to_customers_order()
    {
        $data = [
            'customer_id' => 1,
            'product_id' => 19,
            'discount_id' => 1
        ];

        $response = $this->put('api/pos/discount', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_assign_a_discount_to_all_customers_order()
    {


        $data = [
            'customer_id' => 1,
            'discount_id' => 1
        ];

        $response = $this->put('api/pos/discount-all', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_remove_a_discount_to_customers_order()
    {
        $data = [
            'customer_id' => 1,
            'product_id' => 19,
        ];

        $response = $this->delete('api/pos/discount', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 200);
    }


    /**
     * test
     */
    public function user_can_remove_a_discount_to_all_customers_order()
    {
        $data = [
            'customer_id' => 3
        ];

        $response = $this->delete('api/pos/discount-all', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_remove_customer_ordered_items_in_pos()
    {
        $data = [
            'customer_id' => 1,
            'product_id' => 19
        ];

        $response = $this->delete('api/pos/item', $data, $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * test
     */
   public function user_can_apply_discount_add_quantity()
   {
       $data = [
           'customer_id' => 2,
           'discount_id' => 1,
           'product_id' => 19,
           'quantity' => 10,
       ];

       $response = $this->put('api/pos/discount/item-quantity', $data, $this->apiHeaders());

       dd(json_decode($response->getContent()));

       $this->getResponse($response, 201);
   }


    /**
     * @test
     */
    public function user_can_process_customer_payment()
    {
        $data = [
            'customer_id' => 1,
            'payment_method' => 'invoice',
            'should_mail' => false
        ];

        $response = $this->post('api/pos/process-payment', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_cancel_customer_orders_in_pos()
    {
        $data = [
            'customer_id' => 1,
        ];

        $response = $this->delete('api/pos/cancel-orders', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response);
    }
}
