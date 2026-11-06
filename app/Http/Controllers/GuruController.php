<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(Request $request): View
    {
        $query = Guru::with(['user', 'mataPelajaran']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        }

        $guru = $query->latest()->paginate(10)->withQueryString();
        $mataPelajaranList = MataPelajaran::all();

        return view('guru.index', compact('guru', 'mataPelajaranList'));
    }

    public function create(): View
    {
        $mataPelajaranList = MataPelajaran::all();
        // Users with role 'guru' that do not have a guru profile yet
        $availableUsers = User::where('role', 'guru')
            ->whereDoesntHave('guru')
            ->get();

        return view('guru.create', compact('mataPelajaranList', 'availableUsers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30', 'unique:guru,nip'],
            'nama' => ['required', 'string', 'max:255'],
            'mata_pelajaran_id' => ['nullable', 'exists:mata_pelajaran,id'],
            'telepon' => ['nullable', 'string', 'max:20'],
            // Account creation options: either link existing or create new
            'user_option' => ['required', 'in:existing,new'],
            'user_id' => ['required_if:user_option,existing', 'nullable', 'exists:users,id'],
            'email' => ['required_if:user_option,new', 'nullable', 'email', 'unique:users,email'],
            'password' => ['required_if:user_option,new', 'nullable', 'min:6'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'nama.required' => 'Nama guru wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        if ($request->user_option === 'new') {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'guru',
            ]);
            $userId = $user->id;
        } else {
            $userId = $validated['user_id'];
        }

        Guru::create([
            'user_id' => $userId,
            'nip' => $validated['nip'],
            'nama' => $validated['nama'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
        ]);

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru): View
    {
        $guru->load(['user', 'mataPelajaran']);
        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru): View
    {
        $guru->load('user');
        $mataPelajaranList = MataPelajaran::all();

        return view('guru.edit', compact('guru', 'mataPelajaranList'));
    }

    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['required', 'string', 'max:30', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'nama' => ['required', 'string', 'max:255'],
            'mata_pelajaran_id' => ['nullable', 'exists:mata_pelajaran,id'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($guru->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
        ], [
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP ini sudah terdaftar.',
            'nama.required' => 'Nama guru wajib diisi.',
            'email.required' => 'Email pengguna wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        $guru->update([
            'nip' => $validated['nip'],
            'nama' => $validated['nama'],
            'mata_pelajaran_id' => $validated['mata_pelajaran_id'] ?? null,
            'telepon' => $validated['telepon'] ?? null,
        ]);

        // Update linked user account
        if ($guru->user) {
            $userData = [
                'name' => $validated['nama'],
                'email' => $validated['email'],
            ];
            if (!empty($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }
            $guru->user->update($userData);
        }

        return redirect()->route('guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        // Deleting guru will delete user or guru record
        $user = $guru->user;
        $guru->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('guru.index')->with('success', 'Data guru dan akun terkait berhasil dihapus.');
    }
}
