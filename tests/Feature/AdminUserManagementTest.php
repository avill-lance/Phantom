<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_users_listing()
    {
        $admin = User::factory()->create();
        $otherUser = User::factory()->create(['name' => 'Charlie Brown']);

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Charlie Brown');
    }

    public function test_admin_can_create_user()
    {
        $admin = User::factory()->create();
        $role = Role::where('name', 'staff')->first() ?? Role::factory()->create(['name' => 'staff']);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name' => 'New Staff',
            'email' => 'staff@example.com',
            'password' => 'password123',
            'role_id' => $role->id,
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'email' => 'staff@example.com',
            'name' => 'New Staff',
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_update_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(['name' => 'Old Name']);
        $role = Role::where('name', 'admin')->first() ?? Role::factory()->create(['name' => 'admin']);

        $response = $this->actingAs($admin)->put("/admin/users/{$user->id}", [
            'name' => 'Updated Name',
            'email' => $user->email,
            'role_id' => $role->id,
        ]);

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'role_id' => $role->id,
        ]);
    }

    public function test_admin_can_delete_user()
    {
        $admin = User::factory()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}
