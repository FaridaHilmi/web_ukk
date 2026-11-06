@extends('layouts.app')

@section('title', 'Dashboard Guru - Panel Guru Pendidik')
@section('page_title', 'Dashboard Guru')

@section('content')
<div class="space-y-6">

    {{-- HERO --}}
    <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-600"></div>
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Panel Guru Pendidik
                </span>
                <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    Halo, {{ $guru->nama ?? Auth::user()->name }}
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Guru Pengampu:
                    <strong class="text-slate-800">{{ $guru?->mataPelajaran?->nama_mapel ?? 'Mata Pelajaran Belum Ditentukan' }}</strong>
                    <span class="mx-1 text-slate-300">&bull;</span>
                    NIP: {{ $guru?->nip ?? '-' }}
                </p>
            </div>

            <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('nilai.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-md">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Input Nilai Baru
                </a>
                <a href="{{ route('absensi.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:text-blue-600 hover:shadow-sm">
                    <i class="fa-solid fa-clipboard-check"></i>
                    Catat Presensi
                </a>
            </div>
        </div>
    </section>

    {{-- STAT CARDS --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Mata Pelajaran', 'value' => $guru?->mataPelajaran?->nama_mapel ?? 'Belum Diatur', 'note' => 'Kode: ' . ($guru?->mataPelajaran?->kode_mapel ?? '-'), 'icon' => 'fa-book-bookmark', 'text' => true],
                ['label' => 'Total Siswa',    'value' => number_format($totalSiswa), 'note' => 'Siswa di semua rombel', 'icon' => 'fa-user-graduate'],
                ['label' => 'Total Kelas',    'value' => number_format($totalKelas), 'note' => 'Rombongan belajar',      'icon' => 'fa-school'],
                ['label' => 'Nilai Diinput',  'value' => number_format($totalNilaiDiinput), 'note' => 'Entri nilai mapel ini', 'icon' => 'fa-award'],
            ];
        @endphp

        @foreach($stats as $s)
            <div class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $s['label'] }}</p>
                    <h3 class="mt-1 {{ !empty($s['text']) ? 'truncate text-base' : 'text-2xl' }} font-bold text-slate-900">{{ $s['value'] }}</h3>
                    <p class="mt-1 text-[11px] font-medium text-blue-600">{{ $s['note'] }}</p>
                </div>
                <div class="ml-3 flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-lg text-blue-600 transition group-hover:scale-105">
                    <i class="fa-solid {{ $s['icon'] }}"></i>
                </div>
            </div>
        @endforeach
    </section>

    {{-- PINTASAN --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-4">
            <h2 class="text-sm font-bold text-slate-900">Aksi & Pintasan Akademik</h2>
            <p class="mt-0.5 text-xs text-slate-400">Akses cepat tugas penilaian dan presensi siswa</p>
        </div>

        @php
            $shortcuts = [
                ['nilai.create',   'Input Nilai',    'UH, UTS, UAS',     'fa-pen-to-square'],
                ['nilai.index',    'Kelola Nilai',   'Edit & Hapus',     'fa-table-list'],
                ['absensi.create', 'Catat Presensi', 'Kehadiran Kelas',  'fa-clipboard-user'],
                ['absensi.rekap',  'Rekap Presensi', 'Bulanan/Semester', 'fa-calendar-days'],
                ['nilai.laporan',  'Laporan Rapor',  'Hasil Belajar',    'fa-chart-line'],
                ['siswa.index',    'Daftar Siswa',   'Data Lengkap',     'fa-users'],
            ];
        @endphp

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($shortcuts as [$routeName, $title, $sub, $icon])
                <a href="{{ route($routeName) }}"
                   class="group flex flex-col items-center rounded-xl border border-slate-100 p-4 text-center transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:bg-blue-50/50 hover:shadow-sm">
                    <div class="mb-2 flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-sm text-slate-600 transition group-hover:bg-blue-600 group-hover:text-white">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-800 transition group-hover:text-blue-600">{{ $title }}</p>
                    <span class="mt-0.5 text-[10px] text-slate-400">{{ $sub }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- KELAS & NILAI TERKINI --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Daftar Rombel --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Daftar Rombongan Belajar</h2>
                    <p class="mt-0.5 text-xs text-slate-400">Pilih kelas untuk mencatat presensi atau nilai</p>
                </div>
                <a href="{{ route('kelas.index') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">Lihat Kelas</a>
            </div>

            @if($kelasList->isNotEmpty())
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                    @foreach($kelasList as $k)
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-slate-50/50 p-4 transition hover:border-blue-300 hover:bg-white hover:shadow-sm">
                            <div class="min-w-0">
                                <span class="block truncate text-xs font-bold text-slate-800">{{ $k->nama_kelas }}</span>
                                <span class="text-[11px] text-slate-500">Tingkat {{ $k->tingkat }} &bull; {{ $k->siswa_count }} siswa</span>
                            </div>
                            <div class="flex shrink-0 items-center gap-1.5">
                                <a href="{{ route('absensi.create', ['kelas_id' => $k->id]) }}" title="Presensi Kelas"
                                   class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-50 text-xs text-blue-700 transition hover:bg-blue-600 hover:text-white">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                </a>
                                <a href="{{ route('nilai.create', ['kelas_id' => $k->id]) }}" title="Input Nilai"
                                   class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-50 text-xs text-emerald-700 transition hover:bg-emerald-600 hover:text-white">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 text-center text-xs text-slate-400">
                    <i class="fa-solid fa-school mb-2 block text-3xl text-slate-300"></i>
                    Belum ada kelas terdaftar.
                </div>
            @endif
        </section>

        {{-- Nilai Terkini --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-wrap items-start justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Nilai Siswa Terkini</h2>
                    <p class="mt-0.5 text-xs text-slate-400">Entri nilai terbaru pada mata pelajaran Anda</p>
                </div>
                <a href="{{ route('nilai.index') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">Semua Nilai</a>
            </div>

            @if($recentNilai->isNotEmpty())
                <div class="relative mb-3">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input type="search" data-table-search="tbl-nilai-guru" placeholder="Cari nama siswa atau kelas..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs text-slate-700 placeholder-slate-400 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100">
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table id="tbl-nilai-guru" class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Siswa</th>
                                <th class="px-4 py-3 font-semibold">Kelas</th>
                                <th class="px-4 py-3 text-center font-semibold">Jenis</th>
                                <th class="px-4 py-3 text-right font-semibold">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentNilai as $n)
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $n->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $n->siswa->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="rounded-md bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700">{{ $n->jenis_nilai }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold {{ $n->nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($n->nilai, 1) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 text-center text-xs text-slate-400">
                    <i class="fa-solid fa-clipboard-list mb-2 block text-3xl text-slate-300"></i>
                    Belum ada nilai yang diinput untuk mata pelajaran ini.
                </div>
            @endif
        </section>
    </div>
</div>

<script>
    document.querySelectorAll('[data-table-search]').forEach(input => {
        const table = document.getElementById(input.dataset.tableSearch);
        if (!table) return;
        input.addEventListener('input', () => {
            const q = input.value.toLowerCase().trim();
            table.querySelectorAll('tbody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    });
</script>
@endsection