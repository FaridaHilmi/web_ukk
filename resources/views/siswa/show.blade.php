@extends('layouts.app')

@section('title', 'Detail Siswa - ' . $siswa->nama)
@section('page_title', 'Profil & Riwayat Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Top Navigation & Action -->
    <div class="flex items-center justify-between pb-1">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.index') }}" 
               class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 hover:bg-slate-50 text-slate-600 flex items-center justify-center text-xs shadow-2xs transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Detail Profil Siswa</h1>
                <p class="text-xs text-slate-500">Biodata lengkap, rekapitulasi kehadiran, dan transkrip nilai.</p>
            </div>
        </div>

        @if(Auth::user()->isAdmin())
            <a href="{{ route('siswa.edit', $siswa->id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-blue-500/20 transition-all">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Edit Data Siswa</span>
            </a>
        @endif
    </div>

    <!-- Student Profile Card (Clean Modern White with Soft Blue Accents) -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs p-6 sm:p-7">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700 border-blue-100/80' : 'bg-pink-50 text-pink-700 border-pink-100/80' }} border flex items-center justify-center text-2xl font-black shrink-0 shadow-2xs">
                    {{ strtoupper(substr($siswa->nama, 0, 2)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-[11px] font-bold {{ $siswa->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                            <i class="fa-solid {{ $siswa->jenis_kelamin === 'L' ? 'fa-mars' : 'fa-venus' }} text-[10px]"></i>
                            {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                        <span class="px-2.5 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700">
                            {{ $siswa->kelas->nama_kelas ?? 'Belum ada kelas' }}
                        </span>
                    </div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ $siswa->nama }}</h2>
                    <p class="text-xs text-slate-400 font-mono mt-0.5">NISN: <span class="font-bold text-slate-700">{{ $siswa->nisn }}</span></p>
                </div>
            </div>
        </div>

        <!-- Student Meta Information Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-5">
            <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Kontak / Wali</span>
                <p class="text-xs font-bold text-slate-800 mt-1 flex items-center gap-1.5">
                    <i class="fa-solid fa-phone text-blue-600 text-[10px]"></i>
                    {{ $siswa->telepon ?? 'Tidak terdata' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Akun Portal Siswa</span>
                <p class="text-xs font-bold text-slate-800 mt-1 truncate flex items-center gap-1.5" title="{{ $siswa->user->email ?? 'Belum dibuat' }}">
                    <i class="fa-regular fa-envelope text-blue-600 text-[10px]"></i>
                    {{ $siswa->user->email ?? 'Belum dibuat' }}
                </p>
            </div>

            <div class="p-3.5 rounded-xl bg-slate-50/70 border border-slate-100">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Alamat Tinggal</span>
                <p class="text-xs font-bold text-slate-800 mt-1 truncate flex items-center gap-1.5" title="{{ $siswa->alamat ?? 'Belum diisi' }}">
                    <i class="fa-solid fa-location-dot text-blue-600 text-[10px]"></i>
                    {{ $siswa->alamat ?? 'Belum diisi' }}
                </p>
            </div>
        </div>
    </div>

    <!-- Attendance Performance Summary -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs p-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div>
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-check text-blue-600"></i> Rekapitulasi Presensi Kehadiran
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Catatan presensi harian siswa selama masa belajar.</p>
            </div>
            <div class="text-right">
                <span class="text-2xl font-black text-blue-600">{{ $persenHadir }}%</span>
                <span class="block text-[10px] text-slate-400">Tingkat Hadir</span>
            </div>
        </div>

        <!-- Attendance Progress Bar -->
        <div class="h-2 rounded-full bg-slate-100 overflow-hidden mb-5">
            <div class="h-full rounded-full bg-blue-600 transition-all duration-700" style="width: {{ $persenHadir }}%"></div>
        </div>

        <!-- 4 Grid Stat Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-100 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-emerald-800 block">Hadir</span>
                    <span class="text-lg font-black text-emerald-700 mt-0.5 block">{{ $totalHadir }}</span>
                </div>
                <span class="w-8 h-8 rounded-lg bg-emerald-100/70 text-emerald-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
            </div>

            <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-100 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-blue-800 block">Izin</span>
                    <span class="text-lg font-black text-blue-700 mt-0.5 block">{{ $totalIzin }}</span>
                </div>
                <span class="w-8 h-8 rounded-lg bg-blue-100/70 text-blue-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </span>
            </div>

            <div class="p-3.5 rounded-xl bg-amber-50/60 border border-amber-100 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-amber-800 block">Sakit</span>
                    <span class="text-lg font-black text-amber-700 mt-0.5 block">{{ $totalSakit }}</span>
                </div>
                <span class="w-8 h-8 rounded-lg bg-amber-100/70 text-amber-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-kit-medical"></i>
                </span>
            </div>

            <div class="p-3.5 rounded-xl bg-rose-50/60 border border-rose-100 flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold text-rose-800 block">Alpa</span>
                    <span class="text-lg font-black text-rose-700 mt-0.5 block">{{ $totalAlpa }}</span>
                </div>
                <span class="w-8 h-8 rounded-lg bg-rose-100/70 text-rose-700 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span>
            </div>
        </div>
    </div>

    <!-- Academic Records Table -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-award text-blue-600"></i> Riwayat Nilai & Hasil Belajar
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Daftar nilai ulangan harian, UTS, dan UAS siswa.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600">
                Total: {{ $siswa->nilai->count() }} Nilai
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-5">Mata Pelajaran</th>
                        <th class="py-3.5 px-4 text-center">Jenis Penilaian</th>
                        <th class="py-3.5 px-4 text-center">Semester</th>
                        <th class="py-3.5 px-4 text-right">Nilai Angka</th>
                        <th class="py-3.5 px-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($siswa->nilai as $n)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-5 font-semibold text-slate-900">
                                {{ $n->mataPelajaran->nama_mapel ?? '-' }}
                                <span class="block text-[10px] text-slate-400 font-mono font-normal">{{ $n->mataPelajaran->kode_mapel ?? '' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-100/70">
                                    {{ $n->jenis_nilai }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-slate-500 font-medium">
                                Semester {{ $n->semester }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-black text-sm {{ $n->nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ number_format($n->nilai, 1) }}
                            </td>
                            <td class="py-3.5 px-5 text-center">
                                @if($n->nilai >= 75)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <i class="fa-solid fa-check text-[9px]"></i> Tuntas
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                        <i class="fa-solid fa-triangle-exclamation text-[9px]"></i> Remedial
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400">
                                <i class="fa-solid fa-file-circle-question text-2xl text-slate-300 mb-2 block"></i>
                                Belum ada riwayat nilai yang diinput untuk siswa ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
