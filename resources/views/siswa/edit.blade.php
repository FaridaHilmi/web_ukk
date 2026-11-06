@extends('layouts.app')

@section('title', 'Edit Data Siswa')
@section('page_title', 'Edit Data Siswa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between pb-1">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.index') }}" 
               class="w-9 h-9 rounded-xl bg-white border border-slate-200/80 hover:bg-slate-50 text-slate-600 flex items-center justify-center text-xs shadow-2xs transition-all">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Data Siswa</h1>
                <p class="text-xs text-slate-500">Perbarui identitas, rombel kelas, atau informasi akun: <strong class="text-slate-800">{{ $siswa->nama }}</strong></p>
            </div>
        </div>
        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100/60">
            <i class="fa-solid fa-pen-to-square text-[10px]"></i> NISN: {{ $siswa->nisn }}
        </span>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs overflow-hidden">
        <form method="POST" action="{{ route('siswa.update', $siswa->id) }}" class="p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <!-- SECTION 1: IDENTITAS UTAMA SISWA -->
            <div>
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">1</span>
                    <h3 class="text-sm font-bold text-slate-800">Identitas Utama Siswa</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-xs font-bold text-slate-700 mb-1.5">
                            NISN <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-xs">
                                <i class="fa-solid fa-id-badge"></i>
                            </span>
                            <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" required
                                class="w-full pl-9 pr-4 py-2.5 text-xs font-mono bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nama Lengkap Siswa <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-xs">
                                <i class="fa-solid fa-user"></i>
                            </span>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $siswa->nama) }}" required
                                class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-700">
                            <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                    </div>

                    <!-- Rombel Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Rombel Kelas <span class="text-rose-500">*</span>
                        </label>
                        <select id="kelas_id" name="kelas_id" required
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-700">
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id', $siswa->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: KONTAK & TEMPAT TINGGAL -->
            <div>
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">2</span>
                    <h3 class="text-sm font-bold text-slate-800">Kontak & Tempat Tinggal</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Nomor Telepon -->
                    <div>
                        <label for="telepon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nomor Telepon / WhatsApp
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-xs">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input type="text" id="telepon" name="telepon" value="{{ old('telepon', $siswa->telepon) }}"
                                class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Alamat Tempat Tinggal
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-xs">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat', $siswa->alamat) }}"
                                class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: AKUN LOGIN SISWA -->
            <div>
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">3</span>
                    <h3 class="text-sm font-bold text-slate-800">Akun Pengguna Login (Portal Siswa)</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Email Akun Siswa <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $siswa->user->email ?? '') }}" required
                            class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Ganti Kata Sandi (Opsional)
                        </label>
                        <input type="password" id="password" name="password"
                            placeholder="Biarkan kosong jika tidak diubah"
                            class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    </div>
                </div>
            </div>

            <!-- Form Action Footer -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                <p class="text-[11px] text-slate-400">Kolom bertanda <span class="text-rose-500 font-bold">*</span> wajib diisi.</p>
                <div class="flex items-center gap-2.5">
                    <a href="{{ route('siswa.index') }}" 
                        class="px-4 py-2.5 bg-white border border-slate-200/80 hover:bg-slate-50 text-slate-700 rounded-xl text-xs font-semibold transition-all">
                        Batal
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-blue-500/25 transition-all">
                        <i class="fa-solid fa-save"></i>
                        <span>Perbarui Data Siswa</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection
