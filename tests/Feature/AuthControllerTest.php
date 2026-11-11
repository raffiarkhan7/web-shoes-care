<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_order_now_buttons_open_the_login_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $this->assertSame(
            2,
            substr_count((string) $response->getContent(), 'href="'.route('login').'"'),
        );

        $this->get(route('login'))
            ->assertOk()
            ->assertViewIs('auth.login');
    }

    public function test_guest_is_redirected_to_login_from_buyer_pages(): void
    {
        $this->get(route('pembeli.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_buyer_registration_creates_and_signs_in_a_buyer_account(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Pembeli Baru',
            'email' => 'pembeli-baru@example.test',
            'phone' => '081234567890',
            'role' => User::ROLE_PEMBELI,
            'address' => 'Jl. Mawar No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('pembeli.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'pembeli-baru@example.test',
            'role' => User::ROLE_PEMBELI,
        ]);
        $this->assertAuthenticated();
    }

    public function test_seller_registration_creates_a_seller_account(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Penjual Baru',
            'email' => 'penjual-baru@example.test',
            'phone' => '081234567890',
            'role' => User::ROLE_PENJUAL,
            'shop_name' => 'Sepatu Bersih',
            'address' => 'Jl. Mawar No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('penjual.dashboard'));
        $this->assertDatabaseHas('users', [
            'email' => 'penjual-baru@example.test',
            'role' => User::ROLE_PENJUAL,
            'shop_name' => 'Sepatu Bersih',
        ]);
    }

    public function test_buyer_can_sign_in_and_open_their_dashboard(): void
    {
        $buyer = User::factory()->create([
            'role' => User::ROLE_PEMBELI,
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $buyer->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('pembeli.dashboard'));
        $this->assertAuthenticatedAs($buyer);
        $this->get(route('pembeli.dashboard'))->assertOk();
    }

    public function test_non_buyer_cannot_access_buyer_pages(): void
    {
        $seller = User::factory()->create([
            'role' => User::ROLE_PENJUAL,
        ]);

        $this->actingAs($seller)
            ->get(route('pembeli.dashboard'))
            ->assertForbidden();
    }

    public function test_registration_rejects_admin_role_assignment(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Akun Tidak Sah',
            'email' => 'admin-palsu@example.test',
            'phone' => '081234567890',
            'role' => User::ROLE_ADMIN,
            'address' => 'Jl. Mawar No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('role');
        $this->assertDatabaseMissing('users', [
            'email' => 'admin-palsu@example.test',
        ]);
        $this->assertGuest();
    }

    public function test_disabled_buyer_cannot_sign_in(): void
    {
        $buyer = User::factory()->create([
            'role' => User::ROLE_PEMBELI,
            'is_active' => false,
        ]);

        $response = $this->post(route('login.store'), [
            'email' => $buyer->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'Akun Anda sedang dinonaktifkan. Hubungi admin Shaos Care.',
        ]);
        $this->assertGuest();
    }
}
