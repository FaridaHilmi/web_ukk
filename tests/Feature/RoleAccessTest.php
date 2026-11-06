<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('SIMAS AKADEMIK');
    }

    public function test_admin_can_access_all_admin_features(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $this->get('/dashboard')->assertStatus(200)->assertSee('Panel Tata Usaha');
        $this->get('/admin/users')->assertStatus(200)->assertSee('Daftar Pengguna');
        $this->get('/siswa')->assertStatus(200)->assertSee('Daftar Siswa');
        $this->get('/guru')->assertStatus(200)->assertSee('Daftar Guru');
        $this->get('/kelas')->assertStatus(200)->assertSee('Daftar Kelas');
        $this->get('/mata-pelajaran')->assertStatus(200)->assertSee('Daftar Mata Pelajaran');
        $this->get('/absensi')->assertStatus(200)->assertSee('Presensi Siswa');
        $this->get('/nilai/laporan')->assertStatus(200)->assertSee('Laporan Hasil Belajar');
    }

    public function test_guru_can_access_guru_features_and_is_blocked_from_admin_user_management(): void
    {
        $guru = User::where('role', 'guru')->first();
        $this->actingAs($guru);

        $this->get('/dashboard')->assertStatus(200)->assertSee('Panel Guru Pendidik');
        $this->get('/nilai')->assertStatus(200);
        $this->get('/absensi')->assertStatus(200);
        $this->get('/absensi/rekap')->assertStatus(200);
        $this->get('/siswa')->assertStatus(200);

        // Accessing admin-only user management must return 403 Forbidden
        $this->get('/admin/users')->assertStatus(403);
    }

    public function test_siswa_can_access_own_portal_and_is_blocked_from_admin_and_guru_management(): void
    {
        $siswa = User::where('role', 'siswa')->first();
        $this->actingAs($siswa);

        $this->get('/dashboard')->assertStatus(200)->assertSee('Portal Siswa');
        $this->get('/portal-siswa/profil')->assertStatus(200)->assertSee('Informasi Pribadi Siswa');
        $this->get('/portal-siswa/absensi')->assertStatus(200)->assertSee('Catatan Harian Kehadiran');
        $this->get('/portal-siswa/nilai')->assertStatus(200)->assertSee('Transkrip Nilai');

        // Accessing admin routes must return 403 Forbidden
        $this->get('/admin/users')->assertStatus(403);
        $this->get('/guru')->assertStatus(403);
    }
}
