@extends('layouts.app')

@section('title', 'Transkrip Nilai Saya')
@section('page_title', 'Transkrip & Rapor Nilai Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header & Print Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Transkrip Nilai & Rapor Hasil Belajar</h2>
            <p class="text-xs text-slate-500">Evaluasi pencapaian hasil belajar berkala per mata pelajaran</p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                <i class="fa-solid fa-print"></i> Cetak Rapor Pribadi
            </button>
        </div>
    </div>

    <!-- Semester Switcher & Summary Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Student Info -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-sm text-slate-900">{{ $siswa->nama }}</h3>
                    <p class="text-xs text-slate-400 font-mono">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                    Semester {{ $selectedSemester }}
                </span>
            </div>
            <!-- Semester Filter Radio Buttons -->
            <form method="GET" action="{{ route('siswa-portal.nilai') }}" class="mt-4 flex items-center gap-3 print:hidden">
                <span class="text-xs font-semibold text-slate-500">Pilih Semester:</span>
                <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border cursor-pointer text-xs font-bold transition-colors {{ $selectedSemester == '1' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600' }}">
                    <input type="radio" name="semester" value="1" {{ $selectedSemester == '1' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden">
                    <span>Semester 1 (Ganjil)</span>
                </label>
                <label class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border cursor-pointer text-xs font-bold transition-colors {{ $selectedSemester == '2' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-600' }}">
                    <input type="radio" name="semester" value="2" {{ $selectedSemester == '2' ? 'checked' : '' }} onchange="this.form.submit()" class="hidden">
                    <span>Semester 2 (Genap)</span>
                </label>
            </form>
        </div>

        <!-- GPA Card -->
        <div class="bg-gradient-to-tr from-blue-700 to-indigo-600 rounded-2xl p-5 text-white shadow-md shadow-blue-500/20 flex flex-col justify-between">
            <div>
                <span class="text-xs text-blue-100 font-medium">Rata-Rata Nilai Rapor (IPK)</span>
                <h3 class="text-3xl font-extrabold mt-1">{{ number_format($ipk, 2) }}</h3>
            </div>
            <p class="text-[11px] text-blue-200 mt-2">
                Kriteria Ketuntasan Minimal (KKM): <strong class="text-white">75.0</strong>
            </p>
        </div>
    </div>

    <!-- Official Print Header -->
    <div class="hidden print:block text-center pb-4 border-b border-slate-800 mb-4">
        <h1 class="text-xl font-bold uppercase tracking-tight">RAPOR CAPAIAN KOMPETENSI SISWA</h1>
        <p class="text-xs mt-1">Nama: {{ $siswa->nama }} | NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }} | Semester {{ $selectedSemester }}</p>
    </div>

    <!-- Transcript Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Mata Pelajaran</th>
                        <th class="py-3 px-4 text-right">Ulangan Harian (30%)</th>
                        <th class="py-3 px-4 text-right">Nilai UTS (30%)</th>
                        <th class="py-3 px-4 text-right">Nilai UAS (40%)</th>
                        <th class="py-3 px-4 text-right font-bold text-slate-900">Nilai Akhir Rapor</th>
                        <th class="py-3 px-4 text-center">Predikat</th>
                        <th class="py-3 px-4 text-center">Capaian Kompetensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($transkrip as $idx => $t)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-slate-900">{{ $t['mapel']->nama_mapel }}</p>
                                <p class="text-[10px] text-slate-400 font-mono">{{ $t['mapel']->kode_mapel }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-right font-medium text-slate-600">
                                {{ $t['uh'] ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-medium text-slate-600">
                                {{ $t['uts'] ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-medium text-slate-600">
                                {{ $t['uas'] ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-sm {{ $t['akhir'] && $t['akhir'] >= 75 ? 'text-blue-600' : ($t['akhir'] ? 'text-rose-600' : 'text-slate-400') }}">
                                {{ $t['akhir'] ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-slate-800">
                                {{ $t['predikat'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($t['status_lulus'] === 'Tuntas')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        Tuntas
                                    </span>
                                @elseif($t['status_lulus'] === 'Remedial')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800">
                                        Remedial
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum Lengkap</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-400">Belum ada mata pelajaran dalam kurikulum.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Official Signature Footnote for Printing -->
    <div class="hidden print:grid grid-cols-3 gap-6 pt-12 text-center text-xs">
        <div>
            <p>Orang Tua / Wali Siswa</p>
            <div class="h-20"></div>
            <p class="font-bold underline">( ........................................ )</p>
        </div>
        <div></div>
        <div>
            <p>Jakarta, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <p>Wali Kelas</p>
            <div class="h-20"></div>
            <p class="font-bold underline">( ........................................ )</p>
        </div>
    </div>

</div>
@endsection
