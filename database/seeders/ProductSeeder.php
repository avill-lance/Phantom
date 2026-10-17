<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Classic Cotton T-Shirt',
            'description' => 'A comfortable, everyday t-shirt made from premium cotton.',
            'price' => 25.99,
            'stock' => 50,
            'image_url' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
        ]);

        Product::create([
            'name' => 'Premium Denim Jeans',
            'description' => 'Stylish and durable jeans perfect for casual wear.',
            'price' => 79.99,
            'stock' => 30,
            'image_url' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
        ]);

        Product::create([
            'name' => 'Sports Hoodie',
            'description' => 'Warm and comfortable hoodie for workouts or casual wear.',
            'price' => 45.99,
            'stock' => 25,
            'image_url' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
        ]);

        Product::create([
            'name' => 'Running Shoes',
            'description' => 'Lightweight and supportive shoes for running and training.',
            'price' => 89.99,
            'stock' => 20,
            'image_url' => 'https://images.unsplash.com/photo-1542291026-7y9f929078f9?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
        ]);

        Product::create([
            'name' => 'Leather Jacket',
            'description' => 'Genuine leather jacket for a timeless, stylish look.',
            'price' => 199.99,
            'stock' => 10,
            'image_url' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=800&q=80'
        ]);
    }
}