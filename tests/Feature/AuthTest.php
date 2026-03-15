<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup basic role and permission for dashboard access
        Permission::create(['name' => 'view_general_dashboard', 'guard_name' => 'web']);
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo('view_general_dashboard');
    }

    /** @test */
    public function user_can_view_login_page()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->postJson('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 200,
                'message' => 'Success',
            ]);

        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/admin/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_login_if_no_dashboard_access()
    {
        $user = User::factory()->create();
        // User has no roles/permissions

        $response = $this->postJson('/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(403);
        $this->assertGuest();
    }
}
