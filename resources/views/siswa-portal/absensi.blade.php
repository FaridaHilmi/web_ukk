@extends('layouts.app')

@section('title', 'Presensi Saya')
@section('page_title', 'Rekapitulasi Presensi Saya')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Riwayat & Rekapitulasi Presensi Pribadi</h2>
            <p class="text-xs text-slate-500">Nama: {{ $siswa->nama }} | NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        </div>
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
            <i class="fa-solid fa-print"></i> Cetak Rekap Presensi
        </button>
    </div>

    <!-- Summary Stat Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 bg-white rounded-xl border border-slate-200/80">
            <span class="text-[11px] text-slate-400 font-medium">Persentase Hadir</span>
            <p class="text-2xl font-extrabold text-blue-600 mt-0.5">{{ $persenHadir }}%</p>
            <span class="text-[10px] text-slate-400">Total: {{ $total }} Hari</span>
        </div>
        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
            <span class="text-[11px] text-emerald-600 font-semibold">Hadir</span>
            <p class="text-2xl font-extrabold text-emerald-800 mt-0.5">{{ $hadir }}</p>
            <span class="text-[10px] text-emerald-600">Tepat Waktu</span>
        </div>
        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
            <span class="text-[11px] text-blue-600 font-semibold">Izin</span>
            <p class="text-2xl font-extrabold text-blue-800 mt-0.5">{{ $izin }}</p>
            <span class="text-[10px] text-blue-600">Dengan Pemberitahuan</span>
        </div>
        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
            <span class="text-[11px] text-amber-600 font-semibold">Sakit</span>
            <p class="text-2xl font-extrabold text-amber-800 mt-0.5">{{ $sakit }}</p>
            <span class="text-[10px] text-amber-600">Surat Dokter / Ket</span>
        </div>
        <div class="p-4 bg-rose-50 rounded-xl border border-rose-100 col-span-2 sm:col-span-1">
            <span class="text-[11px] text-rose-600 font-semibold">Alpa</span>
            <p class="text-2xl font-extrabold text-rose-800 mt-0.5">{{ $alpa }}</p>
            <span class="text-[10px] text-rose-600">Tanpa Keterangan</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
        <form method="GET" action="{{ route('siswa-portal.absensi') }}" class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="w-full sm:w-56">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Filter Bulan</label>
                <input type="month" name="month" value="{{ request('month') }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-full sm:w-48">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Status Kehadiran</label>
                <select name="status" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="hadir" {{ request('status') === 'hadir' ? 'selected' : '' }}>Hadir</option>
                    <option value="izin" {{ request('status') === 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ request('status') === 'sakit' ? 'selected' : '' }}>Sakit</option>
                    <option value="alpa" {{ request('status') === 'alpa' ? 'selected' : '' }}>Alpa</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold h-[38px]">
                    Filter
                </button>
                @if(request()->hasAny(['month', 'status']))
                    <a href="{{ route('siswa-portal.absensi') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold h-[38px] flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Attendance Log Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-sm text-slate-800">Catatan Harian Kehadiran</h3>
            <span class="text-xs text-slate-400">Total data: {{ $absensiList->total() }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Hari & Tanggal</th>
                        <th class="py-3 px-4 text-center">Status Kehadiran</th>
                        <th class="py-3 px-4">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($absensiList as $idx => $item)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">
                                {{ ($absensiList->currentPage() - 1) * $absensiList->perPage() + $idx + 1 }}
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-900">
                                {{ $item->tanggal->isoFormat('dddd, D MMMM Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $item->status === 'hadir' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $item->status === 'izin' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $item->status === 'sakit' ? 'bg-amber-100 text-amber-800' : '' }}
                                    {{ $item->status === 'alpa' ? 'bg-rose-100 text-rose-800' : '' }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500 italic">
                                {{ $item->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">Belum ada riwayat kehadiran pada filter ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($absensiList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $absensiList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
