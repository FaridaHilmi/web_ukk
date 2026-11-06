@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('page_title', 'Tambah Pengguna Baru')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tambah Akun Pengguna</h2>
            <p class="text-xs text-slate-500">Buat kredensial login baru untuk staf TU, Guru, atau Siswa</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: Budi Santoso, S.Kom."
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email <span class="text-rose-500">*</span></label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                    placeholder="budi@sekolah.sch.id"
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="role" class="block text-xs font-bold text-slate-700 mb-1.5">Peran / Hak Akses (Role) <span class="text-rose-500">*</span></label>
                <select id="role" name="role" required
                    class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Pilih Peran Pengguna --</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin / Tata Usaha (Full CRUD Master, Presensi & User)</option>
                    <option value="guru" {{ old('role') === 'guru' ? 'selected' : '' }}>Guru (CRUD Nilai & Presensi, Read Data Sekolah)</option>
                    <option value="siswa" {{ old('role') === 'siswa' ? 'selected' : '' }}>Siswa (Read Only Portal Pribadi)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">Password <span class="text-rose-500">*</span></label>
                    <input type="password" id="password" name="password" required
                        placeholder="Minimal 6 karakter"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Ulangi Password <span class="text-rose-500">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                        placeholder="Ketik ulang password"
                        class="w-full px-4 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Pengguna
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
