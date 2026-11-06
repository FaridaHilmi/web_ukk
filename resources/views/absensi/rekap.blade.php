@extends('layouts.app')

@section('title', 'Rekap Presensi')
@section('page_title', 'Rekapitulasi Presensi Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header & Print Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Rekapitulasi Presensi Siswa</h2>
            <p class="text-xs text-slate-500">Laporan akumulasi kehadiran siswa per periode tanggal dan kelas</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                <i class="fa-solid fa-print"></i> Cetak Rekap
            </button>
            <a href="{{ route('absensi.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
        <form method="GET" action="{{ route('absensi.rekap') }}" class="flex flex-col md:flex-row gap-3 items-end">
            <div class="flex-1 w-full">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rombel Kelas</label>
                <select name="kelas_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-full md:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold h-[38px]">
                Filter Rekap
            </button>
        </form>
    </div>

    <!-- Print Header (Visible only when printing) -->
    <div class="hidden print:block text-center pb-4 border-b border-slate-800 mb-4">
        <h1 class="text-xl font-bold uppercase">Laporan Rekapitulasi Presensi Siswa</h1>
        <p class="text-xs">Kelas: {{ $kelasList->find($selectedKelasId)?->nama_kelas }} | Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <!-- Table Rekap -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4 text-center text-emerald-700">Hadir</th>
                        <th class="py-3 px-4 text-center text-blue-700">Izin</th>
                        <th class="py-3 px-4 text-center text-amber-700">Sakit</th>
                        <th class="py-3 px-4 text-center text-rose-700">Alpa</th>
                        <th class="py-3 px-4 text-center font-bold">Total Hari</th>
                        <th class="py-3 px-4 text-center">% Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($rekapData as $idx => $r)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $r['siswa']->nama }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">{{ $r['siswa']->nisn }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-700 bg-emerald-50/30">{{ $r['hadir'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-blue-700 bg-blue-50/30">{{ $r['izin'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-amber-700 bg-amber-50/30">{{ $r['sakit'] }}</td>
                            <td class="py-3.5 px-4 text-center font-bold text-rose-700 bg-rose-50/30">{{ $r['alpa'] }}</td>
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-800">{{ $r['total'] }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $r['persen'] >= 85 ? 'bg-emerald-100 text-emerald-800' : ($r['persen'] >= 75 ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $r['persen'] }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">Tidak ada data rekap presensi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
