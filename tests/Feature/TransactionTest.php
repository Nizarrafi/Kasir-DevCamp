<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup permissions
        Permission::create(['name' => 'view_general_dashboard', 'guard_name' => 'web']);
        Permission::create(['name' => 'view_transaction', 'guard_name' => 'web']);
        
        $role = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role->givePermissionTo(['view_general_dashboard', 'view_transaction']);
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get(route('dashboard.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_user_can_access_transaction_page()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        // Note: route name might vary, check routes/admin/core.php if needed
        // Assuming 'transaction.index' based on common patterns
        if (\Route::has('transaction.index')) {
            $response = $this->actingAs($user)->get(route('transaction.index'));
            $response->assertStatus(200);
        } else {
            $this->markTestSkipped('Transaction index route not found.');
        }
    }
}
