<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Predefined list of brands and models
        $brands = ['Brand A', 'Brand B', 'Brand C', 'Brand D'];  // Add more as needed
        $models = ['Model 1', 'Model 2', 'Model 3', 'Model 4'];  // Add more as needed

        // Get all subcategories
        $categories = Category::all();

        // Generate 10 products for each subcategory with a maximum price of 5.00
        foreach ($categories as $category) {
            for ($i = 0; $i < 10; $i++) {
                Product::create([
                    'name' => $faker->word,
                    'desc' => $faker->sentence,
                    'price' => $faker->randomFloat(2, 1, 5), // Price between 1 and 5
                    'delivery_time' => $faker->numberBetween(1, 7), // 1 to 7 days
                    'stock' => $faker->numberBetween(1, 100), // Random stock
                    'img_url' => 'default.jpg', // Default image or dynamic
                    'category_id' => $category->id, // Associate with subcategory
                    'brand' => $faker->randomElement($brands), // Random brand from list
                    'model' => $faker->randomElement($models), // Random model from list
                    'condition' => $faker->randomElement(['New', 'Used', 'Refurbished']), // Random condition
                ]);
            }
        }
    }
}
