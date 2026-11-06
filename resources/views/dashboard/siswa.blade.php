@extends('layouts.app')

@section('title', 'Dashboard Siswa - Portal Siswa')
@section('page_title', 'Portal Siswa')

@section('content')
<div class="mx-auto max-w-xl space-y-5 pb-10">

    @if(!$siswa)
        {{-- Profil belum terhubung --}}
        <section class="rounded-2xl border border-amber-200 bg-white p-6 text-center shadow-sm">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-xl text-amber-600">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h2 class="text-sm font-bold text-slate-900">Profil Belum Ditautkan</h2>
            <p class="mx-auto mt-1 max-w-sm text-xs text-slate-500">
                Akun Anda belum terhubung dengan data siswa di database. Silakan menghubungi bagian Tata Usaha sekolah.
            </p>
            <span class="mt-4 inline-flex rounded-lg bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-800">Portal Siswa</span>
        </section>
    @else

        {{-- PROFIL --}}
        <section class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl border border-blue-100 bg-blue-600 text-lg font-extrabold text-white shadow-sm">
                {{ strtoupper(substr($siswa->nama, 0, 2)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="mb-1 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1 rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">
                        <i class="fa-solid fa-graduation-cap"></i> Portal Siswa
                    </span>
                    <span class="font-mono text-[11px] text-slate-400">NISN {{ $siswa->nisn }}</span>
                </div>
                <h2 class="truncate text-base font-extrabold text-slate-900">{{ $siswa->nama }}</h2>
                <p class="mt-0.5 text-xs text-slate-500">
                    Kelas: <strong class="text-slate-800">{{ $siswa->kelas->nama_kelas ?? '-' }}</strong>
                    <span class="mx-1 text-slate-300">&bull;</span>
                    {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                </p>
            </div>
        </section>

        {{-- RINGKASAN KEHADIRAN --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-4 flex items-start justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Ringkasan Kehadiran Bulan Ini</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Total presensi masuk sekolah</p>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-extrabold text-blue-600">{{ $persentaseKehadiran }}%</span>
                    <span class="block text-[10px] text-slate-400">Tingkat Hadir</span>
                </div>
            </div>

            <div class="mb-5 h-2.5 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-blue-600 transition-all duration-700" style="width: {{ $persentaseKehadiran }}%"></div>
            </div>

            @php
                $kehadiranTiles = [
                    ['Hadir', $totalHadir, 'emerald'],
                    ['Izin',  $totalIzin,  'blue'],
                    ['Sakit', $totalSakit, 'amber'],
                    ['Alpa',  $totalAlpa,  'rose'],
                ];
            @endphp

            <div class="grid grid-cols-2 gap-2.5">
                @foreach($kehadiranTiles as [$label, $total, $c])
                    <div class="flex items-center justify-between rounded-xl border border-{{ $c }}-100 bg-{{ $c }}-50/60 p-3 transition hover:shadow-sm">
                        <div class="flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-{{ $c }}-500"></span>
                            <span class="text-xs font-semibold text-{{ $c }}-800">{{ $label }}</span>
                        </div>
                        <span class="text-sm font-bold text-{{ $c }}-700">
                            {{ $total }} <span class="text-[10px] font-normal text-{{ $c }}-600">hari</span>
                        </span>
                    </div>
                @endforeach
            </div>

            <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3 text-xs text-slate-500">
                <span>{{ $persentaseKehadiran >= 85 ? 'Kehadiranmu sangat baik, pertahankan!' : 'Tingkatkan kehadiran di kelas ya.' }}</span>
                <a href="{{ route('siswa-portal.absensi') }}" class="shrink-0 font-semibold text-blue-600 transition hover:text-blue-700">Lihat Rekap &rarr;</a>
            </div>
        </section>

        {{-- NILAI TERAKHIR --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="mb-4 flex items-start justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Daftar Nilai Terakhir</h2>
                    <p class="mt-0.5 text-[11px] text-slate-400">Penilaian akademik terbaru</p>
                </div>
                <div class="text-right">
                    <span class="text-lg font-extrabold text-slate-900">{{ number_format($rataRataNilai, 1) }}</span>
                    <span class="block text-[10px] text-slate-400">Rata-rata</span>
                </div>
            </div>

            @if($recentNilai->isNotEmpty())
                <div class="space-y-2.5">
                    @foreach($recentNilai as $n)
                        <div class="group flex items-center justify-between gap-3 rounded-xl border border-slate-100 bg-white p-3.5 transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-sm">
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-xs font-bold text-slate-800 transition group-hover:text-blue-600">
                                    {{ $n->mataPelajaran->nama_mapel ?? '-' }}
                                </h3>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="rounded bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">{{ $n->jenis_nilai }}</span>
                                    <span class="text-[10px] text-slate-400">Semester {{ $n->semester }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-right">
                                <span class="block text-base font-black leading-tight {{ $n->nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ number_format($n->nilai, 1) }}
                                </span>
                                <span class="inline-block rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $n->nilai >= 75 ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-600' }}">
                                    {{ $n->nilai >= 75 ? 'Tuntas' : 'Remedial' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center">
                    <div class="mx-auto mb-3 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-blue-500">
                        <i class="fa-solid fa-file-circle-question"></i>
                    </div>
                    <p class="text-xs font-semibold text-slate-700">Belum ada data nilai saat ini.</p>
                    <p class="mt-0.5 text-[11px] text-slate-400">Nilai akan tampil setelah guru mempublikasikannya.</p>
                </div>
            @endif

            <div class="mt-4 border-t border-slate-100 pt-4">
                <a href="{{ route('siswa-portal.nilai') }}"
                   class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md">
                    <i class="fa-solid fa-award"></i>
                    Buka Rapor & Transkrip Lengkap
                </a>
            </div>
        </section>

        {{-- MENU CEPAT --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('siswa-portal.profil') }}"
               class="group rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-base text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <span class="block text-xs font-bold text-slate-700">Biodata Diri</span>
            </a>

            <a href="{{ route('siswa-portal.absensi') }}"
               class="group rounded-2xl border border-slate-200 bg-white p-4 text-center shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md">
                <div class="mx-auto mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-base text-emerald-600 transition group-hover:bg-emerald-600 group-hover:text-white">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <span class="block text-xs font-bold text-slate-700">Presensi Harian</span>
            </a>
        </div>

    @endif

</div>
@endsection