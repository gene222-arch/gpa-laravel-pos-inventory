<?php

namespace Tests\Feature\Http\Controllers\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    /**
     * @test
     */
    public function user_can_register()
    {
        $response = $this->post('/api/register',
        [
            'name' => $name = $this->faker->name,
            'email' => $email = $this->faker->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['data' => ['access_token']]);

        $this->assertDatabaseHas('users', [
            'name' => $name,
            'email' => $email,
        ]);
    }
}
