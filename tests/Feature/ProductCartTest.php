<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function test_user_can_view_product_listing_page()
    {
        // Create some test products
        Product::factory()->count(3)->create();

        $response = $this->get('/products');

        $response->assertStatus(200);
        $response->assertSee('Phantom Clothing');
        $response->assertSee('Our Products');
    }

    public function test_user_can_view_product_detail_page()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'A test product description',
            'price' => 29.99,
            'stock' => 10
        ]);

        $response = $this->get('/products/' . $product->id);

        $response->assertStatus(200);
        $response->assertSee('Test Product');
        $response->assertSee('$29.99');
        $response->assertSee('Add to Cart');
    }

    public function test_user_can_add_product_to_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 15.99,
            'stock' => 5
        ]);

        $response = $this->post('/cart/add/' . $product->id, [
            'quantity' => 2
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('success', 'Product added to cart');

        // Check that the cart contains the item
        $this->assertEquals(2, session('cart')[$product->id]['quantity']);
    }

    public function test_user_can_view_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 15.99,
            'stock' => 5
        ]);

        // Add item to cart via session
        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 2]
            ]
        ]);

        $response = $this->get('/cart');

        $response->assertStatus(200);
        $response->assertSee('Shopping Cart');
        $response->assertSee('Test Product');
        $response->assertSee('$31.98'); // 2 * 15.99
    }

    public function test_user_can_update_cart_item_quantity()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 10.00,
            'stock' => 10
        ]);

        // Add item to cart
        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 1]
            ]
        ]);

        // Update quantity
        $response = $this->patch('/cart/update/' . $product->id, [
            'quantity' => 3
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('success', 'Cart updated');
        $this->assertEquals(3, session('cart')[$product->id]['quantity']);
    }

    public function test_user_can_remove_item_from_cart()
    {
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'price' => 10.00,
            'stock' => 10
        ]);

        // Add item to cart
        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 1]
            ]
        ]);

        // Remove item
        $response = $this->delete('/cart/remove/' . $product->id);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('success', 'Product removed from cart');
        $this->assertEmpty(session('cart'));
    }

    public function test_user_cannot_add_more_items_than_available_stock()
    {
        $product = Product::factory()->create([
            'name' => 'Limited Stock Product',
            'price' => 20.00,
            'stock' => 2
        ]);

        // Try to add more than stock
        $response = $this->post('/cart/add/' . $product->id, [
            'quantity' => 5
        ]);

        $response->assertRedirectBack();
        $response->assertSessionHas('error', 'Not enough stock available');
    }

    public function test_product_listing_shows_correct_stock_status()
    {
        $inStockProduct = Product::factory()->create([
            'name' => 'In Stock Product',
            'stock' => 5
        ]);

        $outOfStockProduct = Product::factory()->create([
            'name' => 'Out of Stock Product',
            'stock' => 0
        ]);

        $response = $this->get('/products');

        $response->assertSee('In Stock Product');
        $response->assertSee('In Stock');
        $response->assertSee('Out of Stock Product');
        $response->assertSee('Out of Stock');
    }
}
