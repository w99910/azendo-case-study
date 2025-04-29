<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        
        $categories = Category::factory(20)->create();
        $brands = Brand::factory(20)->create();

        for ($i = 0; $i < 20000; $i++) {
            $attributes = [];
            for ($j = 0; $j < rand(2, 8); $j++) {
                $attributes['attr_' . $j] = 'value_' . rand(1, 10);
            }

            Product::create([
                'serial_number' => $faker->iban(),
                'name' => str_replace(' ', '', $faker->name()),
                'description' => $faker->paragraph(),
                'price' => rand(10, 1000) + (rand(0, 99) / 100),
                'stock' => rand(0, 100),
                'is_active' => rand(0, 10) > 2, // 80% will be active
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
                'attributes' => $attributes,
                'image' => "https://picsum.photos/640/480?random=" . rand(1, 20000),
            ]);
        }

        $Product = Product::all();
        for ($i=0; $i < 40000 ; $i++) { 
            Review::create([
                'product_id' => $Product->random()->id,
                'score' => rand(1, 5),
            ]);
        }



    }
}