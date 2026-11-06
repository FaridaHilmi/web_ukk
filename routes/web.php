<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaPortalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Absensi & Nilai Siswa
|--------------------------------------------------------------------------
*/

// Redirect root to dashboard (or login)
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Multi-role Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | A. ROLE ADMIN / TU (Tata Usaha)
    |--------------------------------------------------------------------------
    | CRUD Siswa, Guru, Kelas, Mapel, User Management, Absensi
    | Read Only Laporan Nilai
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::resource('users', UserController::class);
    });

    // Admin-only CRUD actions
    Route::middleware('role:admin')->group(function () {
        // Guru CRUD
        Route::resource('guru', GuruController::class);

        // Siswa Write Actions (Admin only)
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

        // Kelas Write Actions (Admin only)
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kela}/edit', [KelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kela}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{kela}', [KelasController::class, 'destroy'])->name('kelas.destroy');

        // Mata Pelajaran Write Actions (Admin only)
        Route::get('/mata-pelajaran/create', [MataPelajaranController::class, 'create'])->name('mata-pelajaran.create');
        Route::post('/mata-pelajaran', [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
        Route::get('/mata-pelajaran/{mata_pelajaran}/edit', [MataPelajaranController::class, 'edit'])->name('mata-pelajaran.edit');
        Route::put('/mata-pelajaran/{mata_pelajaran}', [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
        Route::delete('/mata-pelajaran/{mata_pelajaran}', [MataPelajaranController::class, 'destroy'])->name('mata-pelajaran.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | B. ROLE GURU & ADMIN (Shared Access for Read / Operations)
    |--------------------------------------------------------------------------
    | - Read Data Siswa, Kelas, Mapel (Accessible by Admin and Guru)
    | - Absensi Siswa CRUD (Accessible by Admin and Guru)
    */
    Route::middleware('role:admin,guru')->group(function () {
        // Siswa Read Only for Guru, full read for Admin
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');

        // Kelas Read Only for Guru, view for Admin
        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');

        // Mata Pelajaran Read Only for Guru, view for Admin
        Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran.index');

        // Absensi Siswa CRUD & Rekap (Admin & Guru)
        Route::get('/absensi/rekap', [AbsensiController::class, 'rekap'])->name('absensi.rekap');
        Route::resource('absensi', AbsensiController::class);

        // Laporan Nilai Siswa (Read only for Admin, accessible by Guru)
        Route::get('/nilai/laporan', [NilaiController::class, 'laporan'])->name('nilai.laporan');
        Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    });

    /*
    |--------------------------------------------------------------------------
    | B. ROLE GURU (Write Access for Nilai)
    |--------------------------------------------------------------------------
    | - CRUD Data Nilai Siswa (UH, UTS, UAS per Mata Pelajaran)
    */
    Route::middleware('role:guru')->group(function () {
        Route::get('/nilai/create', [NilaiController::class, 'create'])->name('nilai.create');
        Route::post('/nilai', [NilaiController::class, 'store'])->name('nilai.store');
        Route::get('/nilai/{nilai}/edit', [NilaiController::class, 'edit'])->name('nilai.edit');
        Route::put('/nilai/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
        Route::delete('/nilai/{nilai}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | C. ROLE SISWA (Read Only - Personal Data Only)
    |--------------------------------------------------------------------------
    | - Profil Pribadi
    | - Rekapitulasi Absensi Pribadi
    | - Transkrip / Rapor Nilai Pribadi
    */
    Route::middleware('role:siswa')->prefix('portal-siswa')->name('siswa-portal.')->group(function () {
        Route::get('/profil', [SiswaPortalController::class, 'profil'])->name('profil');
        Route::get('/absensi', [SiswaPortalController::class, 'absensi'])->name('absensi');
        Route::get('/nilai', [SiswaPortalController::class, 'nilai'])->name('nilai');
    });
});
