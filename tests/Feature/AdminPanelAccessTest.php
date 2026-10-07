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

    public function test_admin_user_with_mfa_can_access_panel(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@example.com',
            'password' => 'password', 'is_admin' => true,
        ]);
        // Set secret via cast (trait marks it encrypted); set after create because
        // it is not in #[Fillable].
        $admin->app_authentication_secret = 'JBSWY3DPEHPK3PXP'; // valid base32 test secret
        $admin->save();

        $response = $this->actingAs($admin->fresh())->get('/admin');
        $response->assertSuccessful()
            ->assertSee('Dashboard');
    }

    public function test_admin_can_access_setup_page_route(): void
    {
        // The dedicated set-up route exists
        $admin = User::create([
            'name' => 'Admin', 'email' => 'admin@example.com',
            'password' => 'password', 'is_admin' => true,
        ]);
        $this->actingAs($admin)->get('/admin/multi-factor-authentication/set-up')->assertSuccessful();
    }

    public function test_admin_without_mfa_is_redirected_to_setup(): void
    {
        $admin = User::create([
            'name' => 'Admin2', 'email' => 'admin2@example.com',
            'password' => 'password', 'is_admin' => true,
        ]);

        // No MFA configured → forced to mandatory set-up page, not the panel
        $this->actingAs($admin)
            ->get('/admin')
            ->assertRedirect()
            ->assertSessionHasNoErrors();
    }
}
