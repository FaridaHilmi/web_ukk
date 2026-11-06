<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KelasController extends Controller
{
    public function index(Request $request): View
    {
        $query = Kelas::withCount('siswa');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_kelas', 'like', "%{$search}%")
                  ->orWhere('tingkat', 'like', "%{$search}%");
        }

        $kelas = $query->orderBy('tingkat')->orderBy('nama_kelas')->paginate(10)->withQueryString();

        return view('kelas.index', compact('kelas'));
    }

    public function create(): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('kelas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'string', 'max:20'],
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib diisi (misal: 10, 11, 12).',
        ]);

        Kelas::create($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kela): View
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $kelas = $kela;
        return view('kelas.edit', compact('kelas'));
    }

    public function update(Request $request, Kelas $kela): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_kelas' => ['required', 'string', 'max:50'],
            'tingkat' => ['required', 'string', 'max:20'],
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'tingkat.required' => 'Tingkat kelas wajib diisi.',
        ]);

        $kela->update($validated);

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kela): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $kela->delete();

        return redirect()->route('kelas.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
