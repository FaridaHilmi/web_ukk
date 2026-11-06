@extends('layouts.app')

@section('title', 'Laporan Nilai Siswa')
@section('page_title', 'Laporan & Rapor Nilai Kelas')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Laporan Hasil Belajar & Nilai Rapor</h2>
            <p class="text-xs text-slate-500">Rekapitulasi akumulasi nilai Ulangan Harian (UH), UTS, UAS, dan Nilai Akhir</p>
        </div>
        <div class="flex gap-2">
            <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </button>
            <a href="{{ route('nilai.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm print:hidden">
        <form method="GET" action="{{ route('nilai.laporan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Rombel Kelas</label>
                <select name="kelas_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Mata Pelajaran</label>
                <select name="mata_pelajaran_id" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($mataPelajaranList as $m)
                        <option value="{{ $m->id }}" {{ $selectedMapelId == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Semester</label>
                <select name="semester" class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ $selectedSemester === '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ $selectedSemester === '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>

            <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold h-[38px]">
                Tampilkan Laporan
            </button>
        </form>
    </div>

    <!-- Print Header -->
    <div class="hidden print:block text-center pb-4 border-b border-slate-800 mb-4">
        <h1 class="text-xl font-bold uppercase">Laporan Hasil Evaluasi Belajar Siswa</h1>
        <p class="text-xs">
            Kelas: {{ $activeKelas->nama_kelas ?? '-' }} | Mata Pelajaran: {{ $activeMapel->nama_mapel ?? '-' }} | Semester {{ $selectedSemester }}
        </p>
    </div>

    <!-- Table Laporan -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div>
                <h3 class="font-bold text-sm text-slate-800">
                    {{ $activeMapel->nama_mapel ?? '-' }} - Kelas {{ $activeKelas->nama_kelas ?? '-' }}
                </h3>
                <p class="text-xs text-slate-500">Bobot Penilaian: UH (30%) + UTS (30%) + UAS (40%)</p>
            </div>
            <span class="text-xs font-bold text-slate-600">KKM: 75.0</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4 w-12 text-center">No</th>
                        <th class="py-3 px-4">Nama Siswa</th>
                        <th class="py-3 px-4">NISN</th>
                        <th class="py-3 px-4 text-right">Rata-rata UH (30%)</th>
                        <th class="py-3 px-4 text-right">Nilai UTS (30%)</th>
                        <th class="py-3 px-4 text-right">Nilai UAS (40%)</th>
                        <th class="py-3 px-4 text-right font-bold text-slate-900">Nilai Akhir</th>
                        <th class="py-3 px-4 text-center">Predikat</th>
                        <th class="py-3 px-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($rekapNilai as $idx => $rn)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $rn['siswa']->nama }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-500">{{ $rn['siswa']->nisn }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-slate-700">{{ $rn['uh'] }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-slate-700">{{ $rn['uts'] }}</td>
                            <td class="py-3.5 px-4 text-right font-semibold text-slate-700">{{ $rn['uas'] }}</td>
                            <td class="py-3.5 px-4 text-right font-extrabold text-sm {{ $rn['akhir'] !== '-' && $rn['akhir'] >= 75 ? 'text-blue-600' : ($rn['akhir'] === '-' ? 'text-slate-400' : 'text-rose-600') }}">
                                {{ $rn['akhir'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold">
                                {{ $rn['predikat'] }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($rn['akhir'] !== '-')
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $rn['akhir'] >= 75 ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $rn['akhir'] >= 75 ? 'Tuntas' : 'Remedial' }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum Lengkap</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-8 text-center text-slate-400">Tidak ada data siswa untuk kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
