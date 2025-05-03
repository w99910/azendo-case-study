<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Devices and gadgets such as phones, laptops, and cameras.'],
            ['name' => 'Home Appliances', 'description' => 'Appliances for home use including refrigerators, washing machines, and microwaves.'],
            ['name' => 'Books', 'description' => 'Printed and digital books across various genres and topics.'],
            ['name' => 'Clothing', 'description' => 'Apparel for men, women, and children.'],
            ['name' => 'Shoes', 'description' => 'Footwear for all occasions and activities.'],
            ['name' => 'Sports & Outdoors', 'description' => 'Equipment and gear for sports and outdoor activities.'],
            ['name' => 'Beauty & Personal Care', 'description' => 'Cosmetics, skincare, and personal hygiene products.'],
            ['name' => 'Toys & Games', 'description' => 'Toys, board games, and entertainment for children and adults.'],
            ['name' => 'Automotive', 'description' => 'Car accessories, parts, and tools.'],
            ['name' => 'Health & Wellness', 'description' => 'Health products, supplements, and wellness equipment.'],
            ['name' => 'Jewelry', 'description' => 'Rings, necklaces, bracelets, and other jewelry items.'],
            ['name' => 'Watches', 'description' => 'Wristwatches and smartwatches for all styles.'],
            ['name' => 'Office Supplies', 'description' => 'Stationery, office equipment, and organization tools.'],
            ['name' => 'Pet Supplies', 'description' => 'Products for pets including food, toys, and accessories.'],
            ['name' => 'Garden & Outdoor', 'description' => 'Gardening tools, plants, and outdoor furniture.'],
            ['name' => 'Musical Instruments', 'description' => 'Instruments and accessories for music lovers.'],
            ['name' => 'Baby Products', 'description' => 'Products for babies including clothing, toys, and care items.'],
            ['name' => 'Groceries', 'description' => 'Food, beverages, and everyday grocery items.'],
            ['name' => 'Furniture', 'description' => 'Home and office furniture for all spaces.'],
            ['name' => 'Art & Craft', 'description' => 'Art supplies, craft materials, and DIY kits.'],
            ['name' => 'Cameras & Photography', 'description' => 'Cameras, lenses, and photography accessories.'],
            ['name' => 'Computers & Accessories', 'description' => 'Desktops, laptops, and computer peripherals.'],
            ['name' => 'Mobile Phones', 'description' => 'Smartphones and mobile accessories.'],
            ['name' => 'Tablets & E-Readers', 'description' => 'Tablets, e-readers, and related accessories.'],
            ['name' => 'Video Games', 'description' => 'Consoles, games, and gaming accessories.'],
            ['name' => 'Home Decor', 'description' => 'Decorative items for home and office.'],
            ['name' => 'Lighting', 'description' => 'Indoor and outdoor lighting solutions.'],
            ['name' => 'Kitchenware', 'description' => 'Cookware, utensils, and kitchen gadgets.'],
            ['name' => 'Cleaning Supplies', 'description' => 'Cleaning products and tools for home and office.'],
            ['name' => 'Luggage & Travel', 'description' => 'Travel bags, suitcases, and travel accessories.'],
            ['name' => 'Bags & Wallets', 'description' => 'Handbags, backpacks, and wallets.'],
            ['name' => 'Eyewear', 'description' => 'Glasses, sunglasses, and optical accessories.'],
            ['name' => 'Fitness Equipment', 'description' => 'Exercise machines, weights, and fitness gear.'],
            ['name' => 'Bedding & Bath', 'description' => 'Bedding sets, towels, and bath accessories.'],
            ['name' => 'Stationery', 'description' => 'Notebooks, pens, and other stationery items.'],
            ['name' => 'Hardware & Tools', 'description' => 'Hand tools, power tools, and hardware supplies.'],
            ['name' => 'Safety & Security', 'description' => 'Home security systems and safety products.'],
            ['name' => 'Wines & Spirits', 'description' => 'Alcoholic beverages including wines and spirits.'],
            ['name' => 'Dairy & Eggs', 'description' => 'Milk, cheese, eggs, and other dairy products.'],
            ['name' => 'Meat & Seafood', 'description' => 'Fresh and frozen meat and seafood.'],
            ['name' => 'Bakery', 'description' => 'Bread, cakes, and baked goods.'],
            ['name' => 'Snacks & Sweets', 'description' => 'Chips, chocolates, and sweet treats.'],
            ['name' => 'Energy & Utilities', 'description' => 'Batteries, power strips, and utility products.'],
            ['name' => 'Craft Beverages', 'description' => 'Craft beers, specialty drinks, and mixers.'],
            ['name' => 'Seasonal', 'description' => 'Seasonal decorations and products.'],
            ['name' => 'Costumes & Party', 'description' => 'Costumes, party supplies, and decorations.'],
            ['name' => 'Medical Supplies', 'description' => 'Medical equipment and health supplies.'],
            ['name' => 'Collectibles', 'description' => 'Collectible items, memorabilia, and antiques.'],
            ['name' => 'Magazines & Newspapers', 'description' => 'Print and digital magazines and newspapers.'],
            ['name' => 'Luxury Goods', 'description' => 'High-end luxury products and designer items.'],
        ];
        $category = $this->faker->randomElement($categories);
        return [
            'name' => $category['name'],
            'description' => $category['description'],
        ];
    }
}