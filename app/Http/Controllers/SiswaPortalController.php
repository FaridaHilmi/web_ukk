<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SiswaPortalController extends Controller
{
    private function getAuthSiswa(): Siswa
    {
        $siswa = Siswa::with(['user', 'kelas'])->where('user_id', Auth::id())->first();

        if (!$siswa) {
            abort(404, 'Profil data siswa untuk akun ini belum dihubungkan oleh Admin.');
        }

        return $siswa;
    }

    public function profil(): View
    {
        $siswa = $this->getAuthSiswa();
        return view('siswa-portal.profil', compact('siswa'));
    }

    public function absensi(Request $request): View
    {
        $siswa = $this->getAuthSiswa();

        $query = Absensi::where('siswa_id', $siswa->id);

        if ($request->filled('month')) {
            $month = $request->month; // format 'YYYY-MM'
            $query->where('tanggal', 'like', "{$month}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absensiList = $query->orderBy('tanggal', 'desc')->paginate(15)->withQueryString();

        // All attendance statistics
        $allAbsensi = Absensi::where('siswa_id', $siswa->id)->get();
        $total = $allAbsensi->count();
        $hadir = $allAbsensi->where('status', 'hadir')->count();
        $sakit = $allAbsensi->where('status', 'sakit')->count();
        $izin  = $allAbsensi->where('status', 'izin')->count();
        $alpa  = $allAbsensi->where('status', 'alpa')->count();
        $persenHadir = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;

        return view('siswa-portal.absensi', compact('siswa', 'absensiList', 'total', 'hadir', 'sakit', 'izin', 'alpa', 'persenHadir'));
    }

    public function nilai(Request $request): View
    {
        $siswa = $this->getAuthSiswa();
        $selectedSemester = $request->input('semester', '1');

        $mapelList = MataPelajaran::all();
        $transkrip = collect();

        foreach ($mapelList as $mapel) {
            $nilaiRecords = Nilai::where('siswa_id', $siswa->id)
                ->where('mata_pelajaran_id', $mapel->id)
                ->where('semester', $selectedSemester)
                ->get();

            $uh = $nilaiRecords->where('jenis_nilai', 'UH')->avg('nilai');
            $uts = $nilaiRecords->where('jenis_nilai', 'UTS')->first()?->nilai;
            $uas = $nilaiRecords->where('jenis_nilai', 'UAS')->first()?->nilai;

            $akhir = null;
            if ($uh !== null || $uts !== null || $uas !== null) {
                $uhVal = $uh ?? 0;
                $utsVal = $uts ?? 0;
                $uasVal = $uas ?? 0;
                $akhir = round(($uhVal * 0.3) + ($utsVal * 0.3) + ($uasVal * 0.4), 2);
            }

            $transkrip->push([
                'mapel' => $mapel,
                'uh' => $uh !== null ? round($uh, 1) : null,
                'uts' => $uts !== null ? round($uts, 1) : null,
                'uas' => $uas !== null ? round($uas, 1) : null,
                'akhir' => $akhir,
                'predikat' => $akhir !== null ? ($akhir >= 90 ? 'A' : ($akhir >= 80 ? 'B' : ($akhir >= 70 ? 'C' : 'D'))) : '-',
                'status_lulus' => $akhir !== null ? ($akhir >= 75 ? 'Tuntas' : 'Remedial') : '-',
            ]);
        }

        $validGrades = $transkrip->whereNotNull('akhir');
        $ipk = $validGrades->count() > 0 ? round($validGrades->avg('akhir'), 2) : 0;

        return view('siswa-portal.nilai', compact('siswa', 'transkrip', 'selectedSemester', 'ipk'));
    }
}
