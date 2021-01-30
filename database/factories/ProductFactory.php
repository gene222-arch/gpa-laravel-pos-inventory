<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $soldBy = [
            'each',
            'weight/volume'
        ];

        return [
            'sku' => Str::limit($this->faker->uuid, 13, ''),
            'barcode' => $this->faker->ean13,
            'product_name' => $this->faker->name,
            'image' => 'no_image.png',
            'category' => Category::factory(),
            'sold_by' => $soldBy[rand(0, 1)],
            'price' => rand(10, 150),
            'cost' => rand(10, 100),
        ];
    }
}
