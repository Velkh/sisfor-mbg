<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions; // Tambahkan ini
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions; 

    public function test_halaman_login_bisa_diakses(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('login');
    }

    public function test_login_berhasil_sebagai_admin_kecamatan(): void
    {
        $user = User::create([
            'username' => 'adminkecamatan_test',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
        ]);

        $response = $this->post('/login', [
            'username' => 'adminkecamatan_test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('kecamatan.dashboard'));
    }

    public function test_login_berhasil_sebagai_admin_dinkes(): void
    {
        $user = User::create([
            'username' => 'admindinkes_test',
            'password' => Hash::make('password123'),
            'role' => 'admin_dinkes',
        ]);

        $response = $this->post('/login', [
            'username' => 'admindinkes_test',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_login_gagal_kredensial_salah(): void
    {
        $user = User::create([
            'username' => 'user_test_gagal',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
        ]);

        $response = $this->post('/login', [
            'username' => 'user_test_gagal',
            'password' => 'password_salah', 
        ]);

        $this->assertGuest(); 
        $response->assertSessionHasErrors(['username']); 
    }

    public function test_user_bisa_logout(): void
    {
        $user = User::create([
            'username' => 'user_test_logout',
            'password' => Hash::make('password123'),
            'role' => 'admin_kecamatan',
        ]);

        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->post('/logout'); 

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}