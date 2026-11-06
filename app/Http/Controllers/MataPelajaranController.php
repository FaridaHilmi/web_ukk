<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    public function index(Request $request): View
    {
        $query = MataPelajaran::withCount(['guru', 'nilai']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_mapel', 'like', "%{$search}%")
                  ->orWhere('kode_mapel', 'like', "%{$search}%");
        }

        $mataPelajaran = $query->orderBy('kode_mapel')->paginate(10)->withQueryString();

        return view('mata-pelajaran.index', compact('mataPelajaran'));
    }

    public function create(): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('mata-pelajaran.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', 'unique:mata_pelajaran,kode_mapel'],
            'nama_mapel' => ['required', 'string', 'max:100'],
        ], [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        MataPelajaran::create($validated);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mataPelajaran): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('mata-pelajaran.edit', compact('mataPelajaran'));
    }

    public function update(Request $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($mataPelajaran->id)],
            'nama_mapel' => ['required', 'string', 'max:100'],
        ], [
            'kode_mapel.required' => 'Kode mata pelajaran wajib diisi.',
            'kode_mapel.unique' => 'Kode mata pelajaran sudah digunakan.',
            'nama_mapel.required' => 'Nama mata pelajaran wajib diisi.',
        ]);

        $mataPelajaran->update($validated);

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $mataPelajaran->delete();

        return redirect()->route('mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
