<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SiswaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Siswa::with(['user', 'kelas']);

        if ($request->filled('kelas_id')) {
            $query->where('kelas_id', $request->kelas_id);
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $siswa = $query->orderBy('nama')->paginate(10)->withQueryString();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('siswa.index', compact('siswa', 'kelasList'));
    }

    public function create(): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $availableUsers = User::where('role', 'siswa')
            ->whereDoesntHave('siswa')
            ->get();

        return view('siswa.create', compact('kelasList', 'availableUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nisn' => ['required', 'string', 'max:20', 'unique:siswa,nisn'],
            'nama' => ['required', 'string', 'max:255'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            // User account options
            'user_option' => ['required', 'in:existing,new'],
            'user_id' => ['required_if:user_option,existing', 'nullable', 'exists:users,id'],
            'email' => ['required_if:user_option,new', 'nullable', 'email', 'unique:users,email'],
            'password' => ['required_if:user_option,new', 'nullable', 'min:6'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN ini sudah terdaftar.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($request->user_option === 'new') {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'siswa',
            ]);
            $userId = $user->id;
        } else {
            $userId = $validated['user_id'];
        }

        Siswa::create([
            'user_id' => $userId,
            'nisn' => $validated['nisn'],
            'nama' => $validated['nama'],
            'kelas_id' => $validated['kelas_id'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa): View
    {
        $siswa->load(['user', 'kelas', 'absensi', 'nilai.mataPelajaran']);
        
        $totalAbsen = $siswa->absensi->count();
        $totalHadir = $siswa->absensi->where('status', 'hadir')->count();
        $totalIzin = $siswa->absensi->where('status', 'izin')->count();
        $totalSakit = $siswa->absensi->where('status', 'sakit')->count();
        $totalAlpa = $siswa->absensi->where('status', 'alpa')->count();
        $persenHadir = $totalAbsen > 0 ? round(($totalHadir / $totalAbsen) * 100, 1) : 0;

        return view('siswa.show', compact('siswa', 'totalAbsen', 'totalHadir', 'totalIzin', 'totalSakit', 'totalAlpa', 'persenHadir'));
    }

    public function edit(Siswa $siswa): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $siswa->load('user');
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('siswa.edit', compact('siswa', 'kelasList'));
    }

    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nisn' => ['required', 'string', 'max:20', Rule::unique('siswa', 'nisn')->ignore($siswa->id)],
            'nama' => ['required', 'string', 'max:255'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($siswa->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nisn.required' => 'NISN wajib diisi.',
            'nisn.unique' => 'NISN sudah digunakan oleh siswa lain.',
            'nama.required' => 'Nama siswa wajib diisi.',
            'kelas_id.required' => 'Kelas wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'email.required' => 'Email akun siswa wajib diisi.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
        ]);

        $siswa->update([
            'nisn' => $validated['nisn'],
            'nama' => $validated['nama'],
            'kelas_id' => $validated['kelas_id'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
        ]);

        // Update linked user
        if ($siswa->user) {
            $userData = [
                'name' => $validated['nama'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $siswa->user->update($userData);
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $user = $siswa->user;
        $siswa->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa dan akun terkait berhasil dihapus.');
    }
}
