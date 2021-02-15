<?php

namespace Tests\Feature\Http\Controllers\Api\InventoryManagement;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class SuppliersControllerTest extends TestCase
{

    /**
     * * test
     */
    public function user_can_get_suppliers()
    {
        $response = $this->get('/api/supplierss', $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * * test
     */
    public function user_can_get_supplier()
    {
        $data = [
            'supplier_id' => 3
        ];

        $response = $this->post('/api/suppliers/supplier-details', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * * test
     */
    public function user_can_create_suppliers()
    {
        $data = [
            'name' => 'Kenkeen',
            'contact' => '1234567891',
            'email' => 'kengmail.com',
            'phone' => '1234567891',
            'website' => 'https://www.facebook.com/',
            'main_address' => 'Brgy. 134 Daisy St.',
            'optional_address' => 'Brgy. 134 Daisy St.',
            'city' => 'Calamba',
            'zipcode' => '4027',
            'country' => 'Philippines',
            'province' => 'Laguna',
        ];

        $response = $this->post('api/suppliers', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * *test
     */
    public function user_can_update_suppliers()
    {
        $data = [
            'id' => 4,
            'name' => 'New Supplier',
            'contact' => '32323232323',
            'email' => 'genephillip222gmail.com',
            'phone' => '32323232323',
            'website' => 'https://www.facebook.com/',
            'main_address' => 'Brgy. 134 Daisy St.',
            'optional_address' => 'Brgy. 134 Daisy St.',
            'city' => 'Calamba',
            'zipcode' => '4027',
            'country' => 'Philippines',
            'province' => 'Laguna',
        ];

        $response = $this->put('api/suppliers', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * test
     */
    public function user_can_delete_suppliers()
    {


        $data = [
            'id' => [2]
        ];

        $response = $this->delete('api/suppliers', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));

        $this->getResponse($response, 200);
    }

}
