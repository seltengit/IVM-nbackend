<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Smartphone X',
            'hsincode' => '123456',
            'category' => 'Electronics',
            'sub_category' => 'Mobile Phones',
            'description' => 'A high-end smartphone with great features.',
            'brand' => 'TechBrand',
            'design' => 'Sleek',
            'price' => 699.99,
            'stocks' => 100,
            'unit' => 'piece',
            'varient' => 'Black',
            'status' => true,
            'test1' => 'Sample test data 1',
            'test2' => 'Sample test data 2',
        ]);

        // Generate 50 random products
        Product::factory()->count(50)->create();
    }
}
