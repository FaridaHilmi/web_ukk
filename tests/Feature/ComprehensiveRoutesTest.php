<?php

namespace Tests\Feature;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ComprehensiveRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_all_admin_get_routes(): void
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $siswa = Siswa::first();
        $guru = Guru::first();
        $kelas = Kelas::first();
        $mapel = MataPelajaran::first();
        $absensi = Absensi::first();

        $routes = [
            '/dashboard',
            '/admin/users',
            '/admin/users/create',
            "/admin/users/{$admin->id}/edit",
            '/siswa',
            '/siswa/create',
            "/siswa/{$siswa->id}",
            "/siswa/{$siswa->id}/edit",
            '/guru',
            '/guru/create',
            "/guru/{$guru->id}",
            "/guru/{$guru->id}/edit",
            '/kelas',
            '/kelas/create',
            "/kelas/{$kelas->id}/edit",
            '/mata-pelajaran',
            '/mata-pelajaran/create',
            "/mata-pelajaran/{$mapel->id}/edit",
            '/absensi',
            '/absensi/create',
            "/absensi/{$absensi->id}/edit",
            '/absensi/rekap',
            '/nilai',
            '/nilai/laporan',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_all_guru_get_routes(): void
    {
        $guruUser = User::where('role', 'guru')->first();
        $this->actingAs($guruUser);

        $absensi = Absensi::first();
        $nilai = Nilai::first();
        $siswa = Siswa::first();

        $routes = [
            '/dashboard',
            '/siswa',
            "/siswa/{$siswa->id}",
            '/kelas',
            '/mata-pelajaran',
            '/absensi',
            '/absensi/create',
            "/absensi/{$absensi->id}/edit",
            '/absensi/rekap',
            '/nilai',
            '/nilai/create',
            "/nilai/{$nilai->id}/edit",
            '/nilai/laporan',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_all_siswa_get_routes(): void
    {
        $siswaUser = User::where('role', 'siswa')->first();
        $this->actingAs($siswaUser);

        $routes = [
            '/dashboard',
            '/portal-siswa/profil',
            '/portal-siswa/absensi',
            '/portal-siswa/nilai',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }
}
