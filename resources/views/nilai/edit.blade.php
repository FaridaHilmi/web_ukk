@extends('layouts.app')

@section('title', 'Edit Nilai Siswa')
@section('page_title', 'Edit Nilai Siswa')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Nilai Siswa</h2>
            <p class="text-xs text-slate-500">Perbarui nilai akademik siswa</p>
        </div>
        <a href="{{ route('nilai.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('nilai.update', $nilai->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 text-xs space-y-1">
                <p><span class="text-slate-400">Nama Siswa:</span> <strong class="text-slate-800">{{ $nilai->siswa->nama ?? '-' }}</strong></p>
                <p><span class="text-slate-400">NISN / Kelas:</span> <span class="font-mono">{{ $nilai->siswa->nisn ?? '-' }}</span> / {{ $nilai->siswa->kelas->nama_kelas ?? '-' }}</p>
                <p><span class="text-slate-400">Mata Pelajaran:</span> <strong class="text-blue-700">{{ $nilai->mataPelajaran->nama_mapel ?? '-' }}</strong></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="jenis_nilai" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Penilaian <span class="text-rose-500">*</span></label>
                    <select id="jenis_nilai" name="jenis_nilai" required
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="UH" {{ old('jenis_nilai', $nilai->jenis_nilai) === 'UH' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                        <option value="UTS" {{ old('jenis_nilai', $nilai->jenis_nilai) === 'UTS' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                        <option value="UAS" {{ old('jenis_nilai', $nilai->jenis_nilai) === 'UAS' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                    </select>
                </div>

                <div>
                    <label for="semester" class="block text-xs font-bold text-slate-700 mb-1.5">Semester <span class="text-rose-500">*</span></label>
                    <select id="semester" name="semester" required
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1" {{ old('semester', $nilai->semester) === '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                        <option value="2" {{ old('semester', $nilai->semester) === '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="nilai" class="block text-xs font-bold text-slate-700 mb-1.5">Nilai Angka (Skala 0 - 100) <span class="text-rose-500">*</span></label>
                <input type="number" step="0.01" min="0" max="100" id="nilai" name="nilai" value="{{ old('nilai', $nilai->nilai) }}" required
                    class="w-full px-4 py-2.5 text-xs font-bold text-slate-900 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('nilai.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Nilai
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
