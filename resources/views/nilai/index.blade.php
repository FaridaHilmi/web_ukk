@extends('layouts.app')

@section('title', 'Data Nilai Siswa')
@section('page_title', 'Kelola Nilai Siswa')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Data Nilai Siswa</h2>
            <p class="text-xs text-slate-500">
                {{ Auth::user()->isGuru() ? 'Kelola penilaian harian (UH), tengah semester (UTS), dan akhir semester (UAS)' : 'Laporan data nilai akademik siswa' }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('nilai.laporan') }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                <i class="fa-solid fa-chart-pie"></i> Laporan Rapor Kelas
            </a>
            @if(Auth::user()->isGuru())
                <a href="{{ route('nilai.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all">
                    <i class="fa-solid fa-pen-to-square"></i> Input Nilai Siswa
                </a>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('nilai.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rombel Kelas</label>
                <select name="kelas_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($mataPelajaranList as $m)
                        <option value="{{ $m->id }}" {{ (request('mata_pelajaran_id') == $m->id || (!request()->filled('mata_pelajaran_id') && Auth::user()->isGuru() && $guruProfile && $guruProfile->mata_pelajaran_id == $m->id)) ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Jenis Penilaian</label>
                <select name="jenis_nilai" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Jenis</option>
                    <option value="UH" {{ request('jenis_nilai') === 'UH' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                    <option value="UTS" {{ request('jenis_nilai') === 'UTS' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                    <option value="UAS" {{ request('jenis_nilai') === 'UAS' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Semester</label>
                <select name="semester" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Semester</option>
                    <option value="1" {{ request('semester') === '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ request('semester') === '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl h-[38px]">
                    Filter
                </button>
                @if(request()->hasAny(['kelas_id', 'mata_pelajaran_id', 'jenis_nilai', 'semester']))
                    <a href="{{ route('nilai.index', ['all_mapel' => 1]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl h-[38px] flex items-center">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Nilai Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Mata Pelajaran</th>
                        <th class="py-3 px-4 text-center">Jenis Penilaian</th>
                        <th class="py-3 px-4 text-center">Semester</th>
                        <th class="py-3 px-4 text-right">Nilai Angka</th>
                        <th class="py-3 px-4 text-center">Kriteria</th>
                        @if(Auth::user()->isGuru())
                            <th class="py-3 px-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($nilaiList as $item)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $item->siswa->nama ?? '-' }}
                                <span class="block text-[10px] text-slate-400 font-mono font-normal">NISN: {{ $item->siswa->nisn ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-600">
                                {{ $item->siswa->kelas->nama_kelas ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800">{{ $item->mataPelajaran->nama_mapel ?? '-' }}</span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase
                                    {{ $item->jenis_nilai === 'UH' ? 'bg-indigo-50 text-indigo-700 border border-indigo-100' : '' }}
                                    {{ $item->jenis_nilai === 'UTS' ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                    {{ $item->jenis_nilai === 'UAS' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}">
                                    {{ $item->jenis_nilai }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center text-slate-500">
                                Sem. {{ $item->semester }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-sm {{ $item->nilai >= 75 ? 'text-blue-600' : 'text-rose-600' }}">
                                {{ number_format($item->nilai, 2) }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $item->nilai >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $item->nilai >= 75 ? 'Tuntas' : 'Remedial' }}
                                </span>
                            </td>
                            @if(Auth::user()->isGuru())
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('nilai.edit', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Nilai">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('nilai.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data nilai ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Hapus Nilai">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isGuru() ? 8 : 7 }}" class="py-8 text-center text-slate-400">
                                Belum ada catatan nilai yang sesuai dengan filter.
                                @if(Auth::user()->isGuru())
                                    <div class="mt-2">
                                        <a href="{{ route('nilai.create') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                                            + Input Nilai Sekarang
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($nilaiList->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $nilaiList->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
