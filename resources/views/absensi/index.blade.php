@extends('layouts.app')

@section('title', 'Presensi Siswa')
@section('page_title', 'Data Presensi Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Presensi Siswa</h2>
            <p class="text-xs text-slate-500">Pencatatan dan pemantauan kehadiran harian siswa per rombongan belajar</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('absensi.rekap') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                <i class="fa-solid fa-chart-bar"></i> Rekap Presensi
            </a>
            <a href="{{ route('absensi.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all">
                <i class="fa-solid fa-clipboard-check"></i> Input Presensi Kelas
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('absensi.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="w-full md:w-56">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-full md:w-56">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pilih Rombel Kelas</label>
                <select name="kelas_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl h-[38px]">
                    Tampilkan
                </button>
                @if(request()->hasAny(['tanggal', 'kelas_id']))
                    <a href="{{ route('absensi.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl h-[38px] flex items-center">
                        Hari Ini
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Summary Box for Selected Filter -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 bg-white rounded-xl border border-slate-200/80">
            <span class="text-[11px] text-slate-400 font-medium">Total Tercatat</span>
            <p class="text-2xl font-extrabold text-slate-800 mt-0.5">{{ $summary['total'] }}</p>
        </div>
        <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
            <span class="text-[11px] text-emerald-600 font-semibold">Hadir</span>
            <p class="text-2xl font-extrabold text-emerald-800 mt-0.5">{{ $summary['hadir'] }}</p>
        </div>
        <div class="p-4 bg-blue-50 rounded-xl border border-blue-100">
            <span class="text-[11px] text-blue-600 font-semibold">Izin</span>
            <p class="text-2xl font-extrabold text-blue-800 mt-0.5">{{ $summary['izin'] }}</p>
        </div>
        <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
            <span class="text-[11px] text-amber-600 font-semibold">Sakit</span>
            <p class="text-2xl font-extrabold text-amber-800 mt-0.5">{{ $summary['sakit'] }}</p>
        </div>
        <div class="p-4 bg-rose-50 rounded-xl border border-rose-100 col-span-2 sm:col-span-1">
            <span class="text-[11px] text-rose-600 font-semibold">Alpa</span>
            <p class="text-2xl font-extrabold text-rose-800 mt-0.5">{{ $summary['alpa'] }}</p>
        </div>
    </div>

    <!-- Absensi Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Tanggal Presensi</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($absensiList as $item)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $item->siswa->nama ?? '-' }}
                                <span class="block text-[10px] text-slate-400 font-mono font-normal">NISN: {{ $item->siswa->nisn ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-600">
                                {{ $item->kelas->nama_kelas ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $item->tanggal->isoFormat('D MMMM Y') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
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
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('absensi.edit', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Presensi">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('absensi.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan presensi ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Hapus Presensi">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Tidak ada catatan presensi pada tanggal dan kelas yang dipilih.
                                <div class="mt-2">
                                    <a href="{{ route('absensi.create', ['tanggal' => $tanggal, 'kelas_id' => $kelasId]) }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                        + Input Presensi Sekarang
                                    </a>
                                </div>
                            </td>
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
