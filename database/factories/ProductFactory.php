<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $attributes = [];
        for ($i = 0; $i < $this->faker->numberBetween(2, 8); $i++) {
            $attributes[$this->faker->word] = $this->faker->word;
        }

        return [
            'name' => $this->faker->productName ?? $this->faker->words(3, true),
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(80),
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'attributes' => $attributes,
        ];
    }
}