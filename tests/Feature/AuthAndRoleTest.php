<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthAndRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed users
        User::create([
            'name' => 'Admin Kampus',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Mahasiswa Kampus',
            'email' => 'mahasiswa@kampus.ac.id',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dosen');
        $response->assertRedirect('/login');

        $responseHome = $this->get('/');
        $responseHome->assertRedirect('/login');
    }

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Login Sistem');
        $response->assertSee('Email');
        $response->assertSee('Password');
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@kampus.ac.id',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_can_login_and_is_redirected_to_dosen(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@kampus.ac.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dosen');
    }

    public function test_mahasiswa_can_login_and_is_redirected_to_dosen(): void
    {
        $response = $this->post('/login', [
            'email' => 'mahasiswa@kampus.ac.id',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/dosen');
    }

    public function test_admin_can_view_dosen_page_with_admin_controls(): void
    {
        $admin = User::where('email', 'admin@kampus.ac.id')->first();

        $response = $this->actingAs($admin)->get('/dosen');
        $response->assertStatus(200);
        $response->assertSee('Tambah Dosen');
        $response->assertSee('Logout');
        $response->assertSee('admin');
    }

    public function test_mahasiswa_can_view_dosen_page_without_admin_controls(): void
    {
        $mahasiswa = User::where('email', 'mahasiswa@kampus.ac.id')->first();

        $response = $this->actingAs($mahasiswa)->get('/dosen');
        $response->assertStatus(200);
        $response->assertDontSee('Tambah Dosen');
        $response->assertSee('Logout');
        $response->assertSee('mahasiswa');
    }

    public function test_admin_can_access_create_and_store_dosen(): void
    {
        $admin = User::where('email', 'admin@kampus.ac.id')->first();

        $createResponse = $this->actingAs($admin)->get('/dosen/create');
        $createResponse->assertStatus(200);

        $storeResponse = $this->actingAs($admin)->post('/dosen', [
            'nidn' => '1234567890',
            'nama' => 'Dr. Budi Santoso',
            'jabatan' => 'Lektor Kepala',
            'kepakaran' => 'Jaringan Komputer',
        ]);

        $storeResponse->assertRedirect('/');
        $this->assertDatabaseHas('dosens', [
            'nidn' => '1234567890',
            'nama' => 'Dr. Budi Santoso',
        ]);
    }

    public function test_mahasiswa_cannot_access_create_and_gets_403(): void
    {
        $mahasiswa = User::where('email', 'mahasiswa@kampus.ac.id')->first();

        $createResponse = $this->actingAs($mahasiswa)->get('/dosen/create');
        $createResponse->assertStatus(403);

        $storeResponse = $this->actingAs($mahasiswa)->post('/dosen', [
            'nidn' => '9999999999',
            'nama' => 'Hacker',
            'jabatan' => 'Unknown',
            'kepakaran' => 'Security',
        ]);
        $storeResponse->assertStatus(403);
    }

    public function test_user_can_logout(): void
    {
        $admin = User::where('email', 'admin@kampus.ac.id')->first();

        $response = $this->actingAs($admin)->post('/logout');
        $this->assertGuest();
        $response->assertRedirect('/login');
    }
}
