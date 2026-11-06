@extends('layouts.app')

@section('title', 'Dashboard Admin - Panel Tata Usaha')
@section('page_title', 'Dashboard')

@section('content')
@php
    $tanggalHariIni   = \Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y');
    $totalPresensi    = $hadirCount + $izinCount + $sakitCount + $alpaCount;
    $statusBadge = [
        'hadir' => ['Hadir', 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'],
        'izin'  => ['Izin',  'bg-blue-50 text-blue-700 ring-blue-600/20'],
        'sakit' => ['Sakit', 'bg-amber-50 text-amber-700 ring-amber-600/20'],
        'alpa'  => ['Alpa',  'bg-rose-50 text-rose-700 ring-rose-600/20'],
    ];
@endphp

<div class="space-y-6">

    {{-- HERO / WELCOME --}}
    <section class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm">
        <div class="absolute inset-y-0 left-0 w-1.5 bg-blue-600"></div>
        <div class="flex flex-col gap-5 md:flex-row md:items-center md:justify-between">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                    <i class="fa-solid fa-shield-halved"></i>
                    Panel Tata Usaha
                </span>
                <h1 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">
                    Selamat Datang, {{ Auth::user()->name }}
                </h1>
                <p class="mt-1 max-w-xl text-sm text-slate-500">
                    Pusat administrasi data siswa, tenaga pendidik, presensi harian, dan rekapitulasi nilai sekolah.
                </p>
            </div>
            <div class="inline-flex w-fit items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-medium text-slate-600">
                <i class="fa-regular fa-calendar-check text-blue-600"></i>
                {{ $tanggalHariIni }}
            </div>
        </div>
    </section>

    {{-- STAT CARDS --}}
    <section class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @php
            $stats = [
                ['label' => 'Total Siswa',     'value' => number_format($totalSiswa), 'note' => 'Siswa terdaftar aktif',  'icon' => 'fa-user-graduate',   'color' => 'blue'],
                ['label' => 'Total Guru',      'value' => number_format($totalGuru),  'note' => 'Tenaga pendidik',        'icon' => 'fa-chalkboard-user', 'color' => 'emerald'],
                ['label' => 'Rombel Kelas',    'value' => number_format($totalKelas), 'note' => 'Kelas aktif',            'icon' => 'fa-school',          'color' => 'indigo'],
                ['label' => 'Mata Pelajaran',  'value' => number_format($totalMapel), 'note' => 'Kurikulum aktif',        'icon' => 'fa-book-bookmark',   'color' => 'violet'],
            ];
            $colorMap = [
                'blue'    => 'bg-blue-50 text-blue-600 text-blue-600',
                'emerald' => 'bg-emerald-50 text-emerald-600 text-emerald-600',
                'indigo'  => 'bg-indigo-50 text-indigo-600 text-indigo-600',
                'violet'  => 'bg-violet-50 text-violet-600 text-violet-600',
            ];
        @endphp

        @foreach($stats as $s)
            <div class="group flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">{{ $s['label'] }}</p>
                    <h3 class="mt-1 text-2xl font-bold text-slate-900">{{ $s['value'] }}</h3>
                    <p class="mt-1 text-[11px] font-medium text-slate-500">{{ $s['note'] }}</p>
                </div>
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl text-lg transition group-hover:scale-105 {{ explode(' ', $colorMap[$s['color']])[0] }} {{ explode(' ', $colorMap[$s['color']])[1] }}">
                    <i class="fa-solid {{ $s['icon'] }}"></i>
                </div>
            </div>
        @endforeach
    </section>

    {{-- PRESENSI HARI INI --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-3 border-b border-slate-100 pb-4">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Status Presensi Hari Ini</h2>
                <p class="mt-0.5 text-xs text-slate-400">{{ $tanggalHariIni }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3 py-1 text-xs font-bold text-white">
                <i class="fa-solid fa-chart-pie text-[10px]"></i>
                {{ $persenHadirHariIni }}% Hadir
            </span>
        </div>

        <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
            @php
                $presensiTiles = [
                    ['Hadir', $hadirCount, 'emerald', 'fa-circle-check'],
                    ['Izin',  $izinCount,  'blue',    'fa-envelope-open-text'],
                    ['Sakit', $sakitCount, 'amber',   'fa-kit-medical'],
                    ['Alpa',  $alpaCount,  'rose',    'fa-circle-xmark'],
                ];
            @endphp

            @foreach($presensiTiles as [$label, $count, $c, $icon])
                <div class="rounded-xl border border-{{ $c }}-100 bg-{{ $c }}-50/60 p-4 text-center transition hover:shadow-sm">
                    <div class="mx-auto mb-2 flex h-9 w-9 items-center justify-center rounded-lg bg-{{ $c }}-100 text-{{ $c }}-700">
                        <i class="fa-solid {{ $icon }}"></i>
                    </div>
                    <p class="text-xs font-semibold text-{{ $c }}-800">{{ $label }}</p>
                    <p class="text-2xl font-bold text-{{ $c }}-700">{{ $count }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-5 flex flex-col gap-3 border-t border-slate-100 pt-4 text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between">
            <span>Total presensi terinput hari ini: <strong class="text-slate-800">{{ $totalPresensi }} data</strong></span>
            <a href="{{ route('absensi.index') }}" class="inline-flex items-center gap-1.5 font-semibold text-blue-600 transition hover:gap-2.5 hover:text-blue-700">
                Buka Data Presensi <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>
    </section>

    {{-- DISTRIBUSI SISWA PER KELAS --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Distribusi Siswa per Kelas</h2>
                <p class="mt-0.5 text-xs text-slate-400">Jumlah siswa aktif di tiap rombongan belajar</p>
            </div>
            <a href="{{ route('kelas.index') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">
                Lihat Semua Kelas
            </a>
        </div>

        @if($kelasStats->isNotEmpty())
            @php $maxSiswa = max(1, $kelasStats->max('siswa_count')); @endphp
            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                @foreach($kelasStats as $k)
                    <div class="rounded-xl border border-slate-100 bg-slate-50/50 p-4 transition hover:border-blue-200 hover:bg-white">
                        <div class="mb-2 flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-800">{{ $k->nama_kelas }} <span class="font-normal text-slate-400">(Tingkat {{ $k->tingkat }})</span></span>
                            <span class="font-semibold text-slate-600">{{ $k->siswa_count }} siswa</span>
                        </div>
                        <div class="h-2 overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-blue-600 transition-all duration-700" style="width: {{ round(($k->siswa_count / $maxSiswa) * 100) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-10 text-center text-xs text-slate-400">
                <i class="fa-solid fa-school mb-2 block text-3xl text-slate-300"></i>
                Belum ada rombel kelas yang terdaftar.
            </div>
        @endif
    </section>

    {{-- TABEL RIWAYAT --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">

        {{-- Catatan Presensi Terkini --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-sm font-bold text-slate-900">Catatan Presensi Terkini</h2>
                <a href="{{ route('absensi.index') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">Semua Presensi</a>
            </div>

            @if($recentAbsensi->isNotEmpty())
                <div class="relative mb-3">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input type="search" data-table-search="tbl-absensi" placeholder="Cari siswa, kelas, atau tanggal..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs text-slate-700 placeholder-slate-400 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100">
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table id="tbl-absensi" class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Siswa</th>
                                <th class="px-4 py-3 font-semibold">Kelas</th>
                                <th class="px-4 py-3 font-semibold">Tanggal</th>
                                <th class="px-4 py-3 text-center font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentAbsensi as $ab)
                                @php $b = $statusBadge[$ab->status] ?? $statusBadge['alpa']; @endphp
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $ab->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $ab->kelas->nama_kelas ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-400">{{ \Carbon\Carbon::parse($ab->tanggal)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex rounded-md px-2 py-0.5 text-[10px] font-bold ring-1 ring-inset {{ $b[1] }}">{{ $b[0] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 text-center text-xs text-slate-400">Belum ada data absensi tercatat.</div>
            @endif
        </section>

        {{-- Input Nilai Terkini --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-sm font-bold text-slate-900">Input Nilai Siswa Terkini</h2>
                <a href="{{ route('nilai.laporan') }}" class="rounded-lg px-3 py-1.5 text-xs font-semibold text-blue-600 transition hover:bg-blue-50">Laporan Rapor</a>
            </div>

            @if($recentNilai->isNotEmpty())
                <div class="relative mb-3">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400"></i>
                    <input type="search" data-table-search="tbl-nilai" placeholder="Cari siswa atau mata pelajaran..."
                           class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs text-slate-700 placeholder-slate-400 transition focus:border-blue-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-blue-100">
                </div>

                <div class="overflow-x-auto rounded-xl border border-slate-100">
                    <table id="tbl-nilai" class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Siswa</th>
                                <th class="px-4 py-3 font-semibold">Mata Pelajaran</th>
                                <th class="px-4 py-3 text-center font-semibold">Jenis</th>
                                <th class="px-4 py-3 text-right font-semibold">Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentNilai as $nl)
                                <tr class="transition hover:bg-blue-50/40">
                                    <td class="px-4 py-3 font-medium text-slate-800">{{ $nl->siswa->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-slate-500">{{ $nl->mataPelajaran->nama_mapel ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-700">{{ $nl->jenis_nilai }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold {{ $nl->nilai >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($nl->nilai, 1) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 text-center text-xs text-slate-400">Belum ada nilai yang diinput.</div>
            @endif
        </section>
    </div>
</div>

<script>
    // Live search untuk tabel di halaman (hanya memfilter baris yang sudah dimuat)
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