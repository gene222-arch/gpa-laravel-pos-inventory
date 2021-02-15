<?php

namespace Tests\Feature\Http\Controllers\Api\Products;

use Tests\TestCase;
use App\Models\User;

class CategoriesControllerTest extends TestCase
{

    /**
     * * test
     */
    public function user_can_get_categories()
    {
        $this->actingAsAdmin();

        $response = $this->get('api/categories', $this->apiHeaders());

        $this->getResponse($response);
    }


    /**
     * * test
     */
    public function user_can_store_category()
    {
        $this->actingAsAdmin();

        $data = [
            'name' => 'Pogi'
        ];

        $response = $this->post('api/categories', $data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * * test
     */
    public function user_can_update_category()
    {
        $this->actingAsAdmin();

        $data = [
            'id' => 28,
            'name' => 'Updated Category Name'
        ];

        $response = $this->put('api/categories',$data, $this->apiHeaders());

        $this->getResponse($response, 201);
    }


    /**
     * * test
     */
    public function user_can_delete_category()
    {
        $this->actingAsAdmin();

        $data = [
            'id' => [17, 18],
        ];

        $response = $this->delete('api/categories',$data, $this->apiHeaders());

        $this->getResponse($response);
    }

}
