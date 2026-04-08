<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_registration_page_renders(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Welcome to');
        $response->assertSee('Admin');
        $response->assertSee('Sign up');
    }

    public function test_user_can_be_registered_from_admin_form(): void
    {
        $response = $this->post('/', [
            'email' => 'admin@example.com',
            
            'name' => 'Admin User',
            'contact_number' => '9876543210',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard', 1));
        $response->assertSessionHas('status', 'Admin account created successfully.');

        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'name' => 'Admin User',
        ]);

        $this->assertTrue(
            User::where('email', 'admin@example.com')->firstOrFail()->password !== 'password123'
        );
    }

    public function test_admin_can_update_registered_user_statuses(): void
    {
        $admin = User::factory()->create(['status' => 1, 'email_verified_at' => null]);
        $target = User::factory()->create(['status' => 1, 'email_verified_at' => now()]);

        $response = $this->put(route('admin.users.updateStatus', ['user' => $admin, 'targetUser' => $target]), [
            'account_status' => 'inactive',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'status' => 0,
        ]);

        $response = $this->put(route('admin.users.updateEmailVerification', ['user' => $admin, 'targetUser' => $target]), [
            'email_status' => 'unverified',
        ]);
        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $target->id,
            'email_verified_at' => null,
        ]);
    }
}
