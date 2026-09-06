<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin')->assertRedirect();
    }

    public function test_non_admin_user_cannot_access_the_panel(): void
    {
        // Regression: every authenticated user used to reach the panel.
        $user = User::create([
            'name' => 'Biasa', 'email' => 'biasa@example.com',
            'password' => 'password', 'is_admin' => false,
        ]);

        $this->actingAs($user)->get('/admin')->assertForbidden();
    }

    public function test_admin_user_can_access_the_panel(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@example.com',
            'password' => 'password', 'is_admin' => true,
        ]);

        $this->actingAs($admin)->get('/admin')->assertSuccessful();
    }
}
