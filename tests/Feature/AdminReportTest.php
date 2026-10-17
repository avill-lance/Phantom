<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reports_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/reports');

        $response->assertStatus(200);
        $response->assertSee('Inventory & Sales Reports');
    }

    public function test_admin_can_export_sales_csv()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/reports/export?type=sales');

        $response->assertStatus(200);
        $response->assertHeader('Content-type', 'text/csv; charset=utf-8');
    }

    public function test_admin_can_export_inventory_csv()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/reports/export?type=inventory');

        $response->assertStatus(200);
        $response->assertHeader('Content-type', 'text/csv; charset=utf-8');
    }
}

