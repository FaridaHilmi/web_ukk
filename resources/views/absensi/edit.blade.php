@extends('layouts.app')

@section('title', 'Edit Presensi')
@section('page_title', 'Edit Catatan Presensi Siswa')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Presensi Siswa</h2>
            <p class="text-xs text-slate-500">Perbarui status kehadiran untuk: {{ $absensi->siswa->nama ?? '-' }}</p>
        </div>
        <a href="{{ route('absensi.index', ['kelas_id' => $absensi->kelas_id, 'tanggal' => $absensi->tanggal->format('Y-m-d')]) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('absensi.update', $absensi->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200/60 text-xs space-y-1">
                <p><span class="text-slate-400">Nama Siswa:</span> <strong class="text-slate-800">{{ $absensi->siswa->nama }}</strong></p>
                <p><span class="text-slate-400">NISN / Kelas:</span> <span class="font-mono">{{ $absensi->siswa->nisn }}</span> / {{ $absensi->kelas->nama_kelas }}</p>
                <p><span class="text-slate-400">Tanggal:</span> <strong class="text-blue-700">{{ $absensi->tanggal->isoFormat('dddd, D MMMM Y') }}</strong></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-2">Pilih Status Kehadiran <span class="text-rose-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer transition-colors {{ old('status', $absensi->status) === 'hadir' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200' }}">
                        <input type="radio" name="status" value="hadir" {{ old('status', $absensi->status) === 'hadir' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <span class="text-xs font-bold text-emerald-800">Hadir</span>
                    </label>
                    <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer transition-colors {{ old('status', $absensi->status) === 'izin' ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200' }}">
                        <input type="radio" name="status" value="izin" {{ old('status', $absensi->status) === 'izin' ? 'checked' : '' }} class="text-blue-600 focus:ring-blue-500">
                        <span class="text-xs font-bold text-blue-800">Izin</span>
                    </label>
                    <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer transition-colors {{ old('status', $absensi->status) === 'sakit' ? 'border-amber-500 bg-amber-50/50' : 'border-slate-200' }}">
                        <input type="radio" name="status" value="sakit" {{ old('status', $absensi->status) === 'sakit' ? 'checked' : '' }} class="text-amber-600 focus:ring-amber-500">
                        <span class="text-xs font-bold text-amber-800">Sakit</span>
                    </label>
                    <label class="p-3 border rounded-xl flex items-center gap-2 cursor-pointer transition-colors {{ old('status', $absensi->status) === 'alpa' ? 'border-rose-500 bg-rose-50/50' : 'border-slate-200' }}">
                        <input type="radio" name="status" value="alpa" {{ old('status', $absensi->status) === 'alpa' ? 'checked' : '' }} class="text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-bold text-rose-800">Alpa</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="keterangan" class="block text-xs font-bold text-slate-700 mb-1.5">Keterangan Tambahan</label>
                <input type="text" id="keterangan" name="keterangan" value="{{ old('keterangan', $absensi->keterangan) }}"
                    placeholder="Contoh: Sakit demam berdarah / izin acara keluarga"
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('absensi.index', ['kelas_id' => $absensi->kelas_id, 'tanggal' => $absensi->tanggal->format('Y-m-d')]) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Status Presensi
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
