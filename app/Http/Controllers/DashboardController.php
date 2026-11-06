<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $today = Carbon::today()->format('Y-m-d');

        // 1. ADMIN / TU DASHBOARD
        if ($user->isAdmin()) {
            $totalSiswa = Siswa::count();
            $totalGuru = Guru::count();
            $totalKelas = Kelas::count();
            $totalMapel = MataPelajaran::count();
            $totalUsers = User::count();

            $absensiToday = Absensi::where('tanggal', $today)->get();
            $hadirCount = $absensiToday->where('status', 'hadir')->count();
            $sakitCount = $absensiToday->where('status', 'sakit')->count();
            $izinCount = $absensiToday->where('status', 'izin')->count();
            $alpaCount = $absensiToday->where('status', 'alpa')->count();

            $totalAbsensiToday = $absensiToday->count();
            $persenHadirHariIni = $totalAbsensiToday > 0
                ? round(($hadirCount / $totalAbsensiToday) * 100, 1)
                : 0;

            $recentAbsensi = Absensi::with(['siswa', 'kelas'])
                ->latest()
                ->take(6)
                ->get();

            $recentNilai = Nilai::with(['siswa', 'mataPelajaran'])
                ->latest()
                ->take(6)
                ->get();

            $kelasStats = Kelas::withCount('siswa')->orderBy('tingkat')->orderBy('nama_kelas')->get();
            $siswaTerbaru = Siswa::with('kelas')->latest()->take(5)->get();

            return view('dashboard.admin', compact(
                'totalSiswa',
                'totalGuru',
                'totalKelas',
                'totalMapel',
                'totalUsers',
                'hadirCount',
                'sakitCount',
                'izinCount',
                'alpaCount',
                'persenHadirHariIni',
                'recentAbsensi',
                'recentNilai',
                'kelasStats',
                'siswaTerbaru',
                'today'
            ));
        }

        // 2. GURU DASHBOARD
        if ($user->isGuru()) {
            $guru = Guru::with('mataPelajaran')->where('user_id', $user->id)->first();
            $totalSiswa = Siswa::count();
            $totalKelas = Kelas::count();

            $mapelId = $guru?->mata_pelajaran_id;
            $totalNilaiDiinput = $mapelId ? Nilai::where('mata_pelajaran_id', $mapelId)->count() : 0;
            $absensiTodayCount = Absensi::where('tanggal', $today)->count();

            $recentNilai = $mapelId
                ? Nilai::with(['siswa.kelas', 'mataPelajaran'])
                    ->where('mata_pelajaran_id', $mapelId)
                    ->latest()
                    ->take(8)
                    ->get()
                : collect();

            $kelasList = Kelas::withCount('siswa')->orderBy('tingkat')->orderBy('nama_kelas')->get();

            return view('dashboard.guru', compact(
                'guru',
                'totalSiswa',
                'totalKelas',
                'totalNilaiDiinput',
                'absensiTodayCount',
                'recentNilai',
                'kelasList',
                'today'
            ));
        }

        // 3. SISWA DASHBOARD
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        if (!$siswa) {
            return view('dashboard.siswa', [
                'siswa' => null,
                'totalHadir' => 0,
                'totalIzin' => 0,
                'totalSakit' => 0,
                'totalAlpa' => 0,
                'totalAbsen' => 0,
                'persentaseKehadiran' => 0,
                'rataRataNilai' => 0,
                'recentNilai' => collect(),
            ]);
        }

        $absensi = Absensi::where('siswa_id', $siswa->id)->get();
        $totalAbsen = $absensi->count();
        $totalHadir = $absensi->where('status', 'hadir')->count();
        $totalIzin = $absensi->where('status', 'izin')->count();
        $totalSakit = $absensi->where('status', 'sakit')->count();
        $totalAlpa = $absensi->where('status', 'alpa')->count();

        $persentaseKehadiran = $totalAbsen > 0 ? round(($totalHadir / $totalAbsen) * 100, 1) : 0;

        $nilaiList = Nilai::with('mataPelajaran')->where('siswa_id', $siswa->id)->get();
        $rataRataNilai = $nilaiList->count() > 0 ? round($nilaiList->avg('nilai'), 2) : 0;
        $recentNilai = $nilaiList->take(6);

        return view('dashboard.siswa', compact(
            'siswa',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpa',
            'totalAbsen',
            'persentaseKehadiran',
            'rataRataNilai',
            'recentNilai'
        ));
    }
}
