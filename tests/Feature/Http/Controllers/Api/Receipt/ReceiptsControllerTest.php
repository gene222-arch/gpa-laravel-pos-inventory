<?php

namespace Tests\Feature\Http\Controllers\Api\Receipt;

use Tests\TestCase;

class ReceiptsControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_get_all_receipts()
    {
        $data = [
            'date' => '2021-3-4'
        ];

        $response = $this->post('api/receipts', $data, $this->apiHeaders());

        dd(json_decode($response->getContent()));
        
        $this->getResponse($response);
    }

}
