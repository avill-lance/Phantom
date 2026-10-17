<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminOrderManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_orders_listing()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'customer_name' => 'Jane Smith',
            'status' => 'processing'
        ]);

        $response = $this->actingAs($user)->get('/admin/orders');

        $response->assertStatus(200);
        $response->assertSee('Jane Smith');
        $response->assertSee('Processing');
    }

    public function test_admin_can_view_order_details()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'customer_name' => 'Alice Johnson'
        ]);

        $response = $this->actingAs($user)->get("/admin/orders/{$order->id}");

        $response->assertStatus(200);
        $response->assertSee('Alice Johnson');
    }

    public function test_admin_can_update_order_status()
    {
        $user = User::factory()->create();
        $order = Order::factory()->create([
            'status' => 'processing',
            'customer_email' => 'alice@example.com'
        ]);

        $response = $this->actingAs($user)->patch("/admin/orders/{$order->id}/status", [
            'status' => 'shipped'
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped'
        ]);
    }
}

