<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_checkout_page_can_be_rendered_with_items_in_cart()
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'stock' => 10
        ]);

        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 2]
            ]
        ]);

        $response = $this->get('/checkout');

        $response->assertStatus(200);
        $response->assertSee($product->name);
        $response->assertSee('100.00');
    }

    public function test_user_can_place_order_successfully()
    {
        Mail::fake();

        $product = Product::factory()->create([
            'price' => 25.00,
            'stock' => 5
        ]);

        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 2]
            ]
        ]);

        $response = $this->post('/checkout', [
            'customer_name' => 'John Doe',
            'customer_email' => 'john@example.com',
            'customer_phone' => '1234567890',
            'customer_address' => '123 Main St',
            'payment_method' => 'test'
        ]);

        $this->assertDatabaseHas('orders', [
            'customer_email' => 'john@example.com',
            'total_amount' => 50.00,
            'payment_status' => 'paid',
            'status' => 'processing'
        ]);

        $order = Order::first();
        $response->assertRedirect('/order-confirmation/' . $order->id);

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'subtotal' => 50.00
        ]);

        // Check stock reduced
        $this->assertEquals(3, $product->fresh()->stock);

        // Check email sent
        Mail::assertSent(OrderConfirmationMail::class, function ($mail) use ($order) {
            return $mail->order->id === $order->id;
        });
    }

    public function test_checkout_redirects_if_cart_is_empty()
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/cart');
    }

    public function test_checkout_validation_errors()
    {
        $product = Product::factory()->create([
            'price' => 50.00,
            'stock' => 10
        ]);

        $this->withSession([
            'cart' => [
                $product->id => ['quantity' => 1]
            ]
        ]);

        $response = $this->post('/checkout', []);
        $response->assertSessionHasErrors(['customer_name', 'customer_email', 'customer_phone', 'customer_address']);
    }
}
