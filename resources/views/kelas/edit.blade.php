@extends('layouts.app')

@section('title', 'Edit Kelas')
@section('page_title', 'Edit Data Kelas')

@section('content')
<div class="max-w-xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Data Kelas</h2>
            <p class="text-xs text-slate-500">Perbarui nama atau tingkat kelas: {{ $kelas->nama_kelas }}</p>
        </div>
        <a href="{{ route('kelas.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('kelas.update', $kelas->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="nama_kelas" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                <input type="text" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="tingkat" class="block text-xs font-bold text-slate-700 mb-1.5">Tingkat Kelas <span class="text-rose-500">*</span></label>
                <select id="tingkat" name="tingkat" required
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="10" {{ old('tingkat', $kelas->tingkat) === '10' ? 'selected' : '' }}>Tingkat 10 (Kelas X)</option>
                    <option value="11" {{ old('tingkat', $kelas->tingkat) === '11' ? 'selected' : '' }}>Tingkat 11 (Kelas XI)</option>
                    <option value="12" {{ old('tingkat', $kelas->tingkat) === '12' ? 'selected' : '' }}>Tingkat 12 (Kelas XII)</option>
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('kelas.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Kelas
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
