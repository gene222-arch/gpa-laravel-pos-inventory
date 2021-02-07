<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Pos;
use Illuminate\Database\Eloquent\Factories\Factory;

class PosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pos::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cashier' => auth()->user()->name,
            'customer_id' => Customer::factory()->create()
        ];
    }
}
