<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NilaiController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();
        $query = Nilai::with(['siswa.kelas', 'mataPelajaran']);

        // If guru has an assigned subject, we can default or allow filter
        $guruProfile = $user->isGuru() ? Guru::where('user_id', $user->id)->first() : null;

        if ($request->filled('mata_pelajaran_id')) {
            $query->where('mata_pelajaran_id', $request->mata_pelajaran_id);
        } elseif ($user->isGuru() && $guruProfile && $guruProfile->mata_pelajaran_id && !$request->has('all_mapel')) {
            $query->where('mata_pelajaran_id', $guruProfile->mata_pelajaran_id);
        }

        if ($request->filled('kelas_id')) {
            $kelasId = $request->kelas_id;
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        if ($request->filled('jenis_nilai')) {
            $query->where('jenis_nilai', $request->jenis_nilai);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        $nilaiList = $query->latest()->paginate(15)->withQueryString();

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::all();

        return view('nilai.index', compact('nilaiList', 'kelasList', 'mataPelajaranList', 'guruProfile'));
    }

    public function create(Request $request): View
    {
        if (!Auth::user()->isGuru()) {
            abort(403, 'Hanya Guru yang dapat menginput nilai.');
        }

        $guru = Guru::where('user_id', Auth::id())->first();
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::all();

        $selectedKelasId = $request->input('kelas_id', $kelasList->first()?->id);
        $selectedMapelId = $request->input('mata_pelajaran_id', $guru?->mata_pelajaran_id ?? $mataPelajaranList->first()?->id);
        $selectedJenis = $request->input('jenis_nilai', 'UH');
        $selectedSemester = $request->input('semester', '1');

        $siswaList = collect();
        $existingNilai = collect();

        if ($selectedKelasId && $selectedMapelId) {
            $siswaList = Siswa::where('kelas_id', $selectedKelasId)->orderBy('nama')->get();
            $existingNilai = Nilai::where('mata_pelajaran_id', $selectedMapelId)
                ->where('jenis_nilai', $selectedJenis)
                ->where('semester', $selectedSemester)
                ->pluck('nilai', 'siswa_id');
        }

        return view('nilai.create', compact(
            'guru',
            'kelasList',
            'mataPelajaranList',
            'selectedKelasId',
            'selectedMapelId',
            'selectedJenis',
            'selectedSemester',
            'siswaList',
            'existingNilai'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::user()->isGuru()) {
            abort(403, 'Hanya Guru yang dapat menginput nilai.');
        }

        // Batch input for class
        if ($request->has('batch')) {
            $validated = $request->validate([
                'kelas_id' => ['required', 'exists:kelas,id'],
                'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
                'jenis_nilai' => ['required', 'in:UH,UTS,UAS'],
                'semester' => ['required', 'string', 'max:20'],
                'nilai' => ['required', 'array'],
                'nilai.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
            ], [
                'kelas_id.required' => 'Kelas wajib dipilih.',
                'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
                'jenis_nilai.required' => 'Jenis penilaian wajib dipilih.',
                'semester.required' => 'Semester wajib diisi.',
            ]);

            $savedCount = 0;
            foreach ($validated['nilai'] as $siswaId => $score) {
                if ($score !== null && $score !== '') {
                    Nilai::updateOrCreate(
                        [
                            'siswa_id' => $siswaId,
                            'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                            'jenis_nilai' => $validated['jenis_nilai'],
                            'semester' => $validated['semester'],
                        ],
                        [
                            'nilai' => $score,
                        ]
                    );
                    $savedCount++;
                }
            }

            return redirect()->route('nilai.index', [
                'kelas_id' => $validated['kelas_id'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'jenis_nilai' => $validated['jenis_nilai'],
                'semester' => $validated['semester'],
            ])->with('success', "Berhasil menyimpan {$savedCount} nilai siswa.");
        }

        // Single score store
        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajaran,id'],
            'jenis_nilai' => ['required', 'in:UH,UTS,UAS'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'semester' => ['required', 'string', 'max:20'],
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'mata_pelajaran_id.required' => 'Mata pelajaran wajib dipilih.',
            'jenis_nilai.required' => 'Jenis penilaian wajib dipilih.',
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.min' => 'Nilai minimal 0.',
            'nilai.max' => 'Nilai maksimal 100.',
        ]);

        Nilai::updateOrCreate(
            [
                'siswa_id' => $validated['siswa_id'],
                'mata_pelajaran_id' => $validated['mata_pelajaran_id'],
                'jenis_nilai' => $validated['jenis_nilai'],
                'semester' => $validated['semester'],
            ],
            [
                'nilai' => $validated['nilai'],
            ]
        );

        return redirect()->route('nilai.index')->with('success', 'Data nilai siswa berhasil disimpan.');
    }

    public function edit(Nilai $nilai): View
    {
        if (!Auth::user()->isGuru()) {
            abort(403, 'Hanya Guru yang dapat mengedit nilai.');
        }

        $nilai->load(['siswa.kelas', 'mataPelajaran']);
        $mataPelajaranList = MataPelajaran::all();

        return view('nilai.edit', compact('nilai', 'mataPelajaranList'));
    }

    public function update(Request $request, Nilai $nilai): RedirectResponse
    {
        if (!Auth::user()->isGuru()) {
            abort(403, 'Hanya Guru yang dapat mengedit nilai.');
        }

        $validated = $request->validate([
            'jenis_nilai' => ['required', 'in:UH,UTS,UAS'],
            'nilai' => ['required', 'numeric', 'min:0', 'max:100'],
            'semester' => ['required', 'string', 'max:20'],
        ], [
            'jenis_nilai.required' => 'Jenis penilaian wajib dipilih.',
            'nilai.required' => 'Nilai wajib diisi.',
            'nilai.min' => 'Nilai minimal 0.',
            'nilai.max' => 'Nilai maksimal 100.',
        ]);

        $nilai->update($validated);

        return redirect()->route('nilai.index')->with('success', 'Nilai siswa berhasil diperbarui.');
    }

    public function destroy(Nilai $nilai): RedirectResponse
    {
        if (!Auth::user()->isGuru()) {
            abort(403, 'Hanya Guru yang dapat menghapus nilai.');
        }

        $nilai->delete();

        return redirect()->route('nilai.index')->with('success', 'Data nilai berhasil dihapus.');
    }

    public function laporan(Request $request): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $mataPelajaranList = MataPelajaran::all();

        $selectedKelasId = $request->input('kelas_id', $kelasList->first()?->id);
        $selectedMapelId = $request->input('mata_pelajaran_id', $mataPelajaranList->first()?->id);
        $selectedSemester = $request->input('semester', '1');

        $rekapNilai = collect();

        if ($selectedKelasId && $selectedMapelId) {
            $siswaList = Siswa::where('kelas_id', $selectedKelasId)->orderBy('nama')->get();

            foreach ($siswaList as $s) {
                $nilaiSiswa = Nilai::where('siswa_id', $s->id)
                    ->where('mata_pelajaran_id', $selectedMapelId)
                    ->where('semester', $selectedSemester)
                    ->get();

                $nilaiUh = $nilaiSiswa->where('jenis_nilai', 'UH')->avg('nilai');
                $nilaiUts = $nilaiSiswa->where('jenis_nilai', 'UTS')->first()?->nilai;
                $nilaiUas = $nilaiSiswa->where('jenis_nilai', 'UAS')->first()?->nilai;

                // Hitung Rapor Akhir (Bobot: UH 30%, UTS 30%, UAS 40%)
                $akhir = null;
                if ($nilaiUh !== null || $nilaiUts !== null || $nilaiUas !== null) {
                    $uh = $nilaiUh ?? 0;
                    $uts = $nilaiUts ?? 0;
                    $uas = $nilaiUas ?? 0;
                    $akhir = round(($uh * 0.3) + ($uts * 0.3) + ($uas * 0.4), 2);
                }

                $rekapNilai->push([
                    'siswa' => $s,
                    'uh' => $nilaiUh !== null ? round($nilaiUh, 2) : '-',
                    'uts' => $nilaiUts !== null ? round($nilaiUts, 2) : '-',
                    'uas' => $nilaiUas !== null ? round($nilaiUas, 2) : '-',
                    'akhir' => $akhir !== null ? $akhir : '-',
                    'predikat' => $akhir !== null ? ($akhir >= 90 ? 'A' : ($akhir >= 80 ? 'B' : ($akhir >= 70 ? 'C' : 'D'))) : '-',
                ]);
            }
        }

        $activeMapel = MataPelajaran::find($selectedMapelId);
        $activeKelas = Kelas::find($selectedKelasId);

        return view('nilai.laporan', compact(
            'kelasList',
            'mataPelajaranList',
            'selectedKelasId',
            'selectedMapelId',
            'selectedSemester',
            'rekapNilai',
            'activeMapel',
            'activeKelas'
        ));
    }
}
