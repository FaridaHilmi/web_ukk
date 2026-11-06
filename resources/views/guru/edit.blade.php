@extends('layouts.app')

@section('title', 'Edit Guru')
@section('page_title', 'Edit Data Guru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Edit Data Guru</h2>
            <p class="text-xs text-slate-500">Perbarui informasi pengajar dan kredensial login</p>
        </div>
        <a href="{{ route('guru.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('guru.update', $guru->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nip" class="block text-xs font-bold text-slate-700 mb-1.5">NIP (Nomor Induk Pegawai) <span class="text-rose-500">*</span></label>
                    <input type="text" id="nip" name="nip" value="{{ old('nip', $guru->nip) }}" required
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="nama" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap & Gelar <span class="text-rose-500">*</span></label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $guru->nama) }}" required
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="mata_pelajaran_id" class="block text-xs font-bold text-slate-700 mb-1.5">Mata Pelajaran yang Diampu</label>
                    <select id="mata_pelajaran_id" name="mata_pelajaran_id"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mataPelajaranList as $m)
                            <option value="{{ $m->id }}" {{ old('mata_pelajaran_id', $guru->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>
                                {{ $m->kode_mapel }} - {{ $m->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="telepon" class="block text-xs font-bold text-slate-700 mb-1.5">Nomor Telepon / WhatsApp</label>
                    <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $guru->telepon) }}"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <!-- Akun Login Section -->
            <div class="pt-4 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-800 mb-2">Akun Login Pengguna (Email & Password)</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200/60">
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Email Guru <span class="text-rose-500">*</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email', $guru->user->email ?? '') }}" required
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Ganti Password (Opsional)</label>
                        <input type="password" id="password" name="password"
                            placeholder="Biarkan kosong jika tetap"
                            class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('guru.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Perbarui Data Guru
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
