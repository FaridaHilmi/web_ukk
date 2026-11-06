<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AbsensiController extends Controller
{
    public function index(Request $request): View
    {
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));
        $kelasId = $request->input('kelas_id');

        $query = Absensi::with(['siswa', 'kelas'])->where('tanggal', $tanggal);

        if (!empty($kelasId)) {
            $query->where('kelas_id', $kelasId);
        }

        $absensiList = $query->latest()->paginate(15)->withQueryString();

        // Calculate summary for this date and filter
        $summaryQuery = Absensi::where('tanggal', $tanggal);
        if (!empty($kelasId)) {
            $summaryQuery->where('kelas_id', $kelasId);
        }
        $summaryData = $summaryQuery->get();
        $summary = [
            'total' => $summaryData->count(),
            'hadir' => $summaryData->where('status', 'hadir')->count(),
            'sakit' => $summaryData->where('status', 'sakit')->count(),
            'izin'  => $summaryData->where('status', 'izin')->count(),
            'alpa'  => $summaryData->where('status', 'alpa')->count(),
        ];

        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('absensi.index', compact('absensiList', 'kelasList', 'tanggal', 'kelasId', 'summary'));
    }

    public function create(Request $request): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $selectedKelasId = $request->input('kelas_id', $kelasList->first()?->id);
        $tanggal = $request->input('tanggal', Carbon::today()->format('Y-m-d'));

        $siswaList = collect();
        $existingAbsensi = collect();

        if ($selectedKelasId) {
            $siswaList = Siswa::where('kelas_id', $selectedKelasId)->orderBy('nama')->get();
            $existingAbsensi = Absensi::where('kelas_id', $selectedKelasId)
                ->where('tanggal', $tanggal)
                ->pluck('status', 'siswa_id');
        }

        return view('absensi.create', compact('kelasList', 'selectedKelasId', 'tanggal', 'siswaList', 'existingAbsensi'));
    }

    public function store(Request $request): RedirectResponse
    {
        // Batch store: when taking attendance for whole class
        if ($request->has('batch')) {
            $validated = $request->validate([
                'kelas_id' => ['required', 'exists:kelas,id'],
                'tanggal' => ['required', 'date'],
                'absensi' => ['required', 'array'],
                'absensi.*.status' => ['required', 'in:hadir,sakit,izin,alpa'],
                'absensi.*.keterangan' => ['nullable', 'string', 'max:255'],
            ], [
                'kelas_id.required' => 'Kelas wajib dipilih.',
                'tanggal.required' => 'Tanggal absensi wajib diisi.',
                'absensi.required' => 'Data kehadiran siswa tidak boleh kosong.',
            ]);

            foreach ($validated['absensi'] as $siswaId => $data) {
                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'tanggal' => $validated['tanggal'],
                    ],
                    [
                        'kelas_id' => $validated['kelas_id'],
                        'status' => $data['status'],
                        'keterangan' => $data['keterangan'] ?? null,
                    ]
                );
            }

            return redirect()->route('absensi.index', [
                'kelas_id' => $validated['kelas_id'],
                'tanggal' => $validated['tanggal'],
            ])->with('success', 'Presensi kelas berhasil disimpan!');
        }

        // Single store
        $validated = $request->validate([
            'siswa_id' => ['required', 'exists:siswa,id'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:hadir,sakit,izin,alpa'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'siswa_id.required' => 'Siswa wajib dipilih.',
            'tanggal.required' => 'Tanggal absensi wajib diisi.',
            'status.required' => 'Status kehadiran wajib dipilih.',
        ]);

        $siswa = Siswa::findOrFail($validated['siswa_id']);

        Absensi::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tanggal' => $validated['tanggal'],
            ],
            [
                'kelas_id' => $siswa->kelas_id,
                'status' => $validated['status'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]
        );

        return redirect()->route('absensi.index', [
            'kelas_id' => $siswa->kelas_id,
            'tanggal' => $validated['tanggal'],
        ])->with('success', 'Data absensi siswa berhasil disimpan.');
    }

    public function edit(Absensi $absensi): View
    {
        $absensi->load(['siswa.kelas']);
        return view('absensi.edit', compact('absensi'));
    }

    public function update(Request $request, Absensi $absensi): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:hadir,sakit,izin,alpa'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ], [
            'status.required' => 'Status kehadiran wajib dipilih.',
        ]);

        $absensi->update($validated);

        return redirect()->route('absensi.index', [
            'kelas_id' => $absensi->kelas_id,
            'tanggal' => $absensi->tanggal->format('Y-m-d'),
        ])->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function destroy(Absensi $absensi): RedirectResponse
    {
        $kelasId = $absensi->kelas_id;
        $tanggal = $absensi->tanggal->format('Y-m-d');
        $absensi->delete();

        return redirect()->route('absensi.index', [
            'kelas_id' => $kelasId,
            'tanggal' => $tanggal,
        ])->with('success', 'Catatan absensi berhasil dihapus.');
    }

    public function rekap(Request $request): View
    {
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        $selectedKelasId = $request->input('kelas_id', $kelasList->first()?->id);
        $startDate = $request->input('start_date', Carbon::today()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::today()->format('Y-m-d'));

        $rekapData = collect();

        if ($selectedKelasId) {
            $siswaList = Siswa::where('kelas_id', $selectedKelasId)->orderBy('nama')->get();

            foreach ($siswaList as $s) {
                $absensiSiswa = Absensi::where('siswa_id', $s->id)
                    ->whereBetween('tanggal', [$startDate, $endDate])
                    ->get();

                $total = $absensiSiswa->count();
                $hadir = $absensiSiswa->where('status', 'hadir')->count();
                $sakit = $absensiSiswa->where('status', 'sakit')->count();
                $izin  = $absensiSiswa->where('status', 'izin')->count();
                $alpa  = $absensiSiswa->where('status', 'alpa')->count();
                $persen = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

                $rekapData->push([
                    'siswa' => $s,
                    'total' => $total,
                    'hadir' => $hadir,
                    'sakit' => $sakit,
                    'izin'  => $izin,
                    'alpa'  => $alpa,
                    'persen' => $persen,
                ]);
            }
        }

        return view('absensi.rekap', compact('kelasList', 'selectedKelasId', 'startDate', 'endDate', 'rekapData'));
    }
}
