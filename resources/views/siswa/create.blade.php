@extends('layouts.app')

@section('title', 'Tambah Siswa Baru')
@section('page_title', 'Tambah Data Siswa')

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
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Siswa Baru</h1>
                <p class="text-xs text-slate-500">Lengkapi formulir pendaftaran siswa dan akun portal akademik.</p>
            </div>
        </div>
        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-100/60">
            <i class="fa-solid fa-user-plus text-[10px]"></i> Formulir Siswa
        </span>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs overflow-hidden">
        <form method="POST" action="{{ route('siswa.store') }}" class="p-6 sm:p-8 space-y-6">
            @csrf

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
                            <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required
                                placeholder="Contoh: 0061234567"
                                class="w-full pl-9 pr-4 py-2.5 text-xs font-mono bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1">Nomor Induk Siswa Nasional (harus unik).</p>
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
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                placeholder="Contoh: Muhammad Rizky Pratama"
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
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                            <option value="P" {{ old('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                        </select>
                    </div>

                    <!-- Rombel Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Rombel Kelas <span class="text-rose-500">*</span>
                        </label>
                        <select id="kelas_id" name="kelas_id" required
                            class="w-full px-3.5 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-700">
                            <option value="">-- Pilih Rombel Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
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
                    <!-- Nomor Telepon / Wali -->
                    <div>
                        <label for="telepon" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Nomor Telepon / WhatsApp Wali
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-xs">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}"
                                placeholder="Contoh: 085712345678"
                                class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Alamat Lengkap -->
                    <div>
                        <label for="alamat" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Alamat Tempat Tinggal
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none text-xs">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <input type="text" id="alamat" name="alamat" value="{{ old('alamat') }}"
                                placeholder="Contoh: Jl. Merdeka No. 45, Jakarta Barat"
                                class="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: PENGATURAN AKUN LOGIN (ROLE: SISWA) -->
            <div>
                <div class="flex items-center gap-2 pb-3 mb-4 border-b border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs font-bold">3</span>
                    <h3 class="text-sm font-bold text-slate-800">Akun Login Siswa (Portal Mandiri)</h3>
                </div>

                <div class="space-y-4">
                    <!-- Opsi Pemilihan Akun -->
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="flex-1 min-w-[180px] p-3 rounded-xl border border-blue-200 bg-blue-50/40 hover:bg-blue-50 cursor-pointer transition-all flex items-center gap-2.5">
                            <input type="radio" name="user_option" value="new" checked onchange="toggleUserOption('new')" 
                                class="text-blue-600 focus:ring-blue-500">
                            <span class="text-xs font-bold text-slate-800">Buat Akun Siswa Baru</span>
                        </label>

                        @if($availableUsers->count() > 0)
                            <label class="flex-1 min-w-[180px] p-3 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 cursor-pointer transition-all flex items-center gap-2.5">
                                <input type="radio" name="user_option" value="existing" onchange="toggleUserOption('existing')" 
                                    class="text-blue-600 focus:ring-blue-500">
                                <span class="text-xs font-semibold text-slate-700">Tautkan Akun Tersedia ({{ $availableUsers->count() }})</span>
                            </label>
                        @endif
                    </div>

                    <!-- Input Akun Baru -->
                    <div id="new-user-fields" class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Email Akun Siswa <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                placeholder="siswa@sekolah.sch.id"
                                class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>

                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                                Kata Sandi Awal <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password"
                                placeholder="Minimal 6 karakter"
                                class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        </div>
                    </div>

                    <!-- Input Tautkan Akun Lama -->
                    <div id="existing-user-field" class="hidden p-4 rounded-xl bg-slate-50/70 border border-slate-200/80">
                        <label for="user_id" class="block text-xs font-bold text-slate-700 mb-1.5">
                            Pilih Akun Pengguna Terdaftar
                        </label>
                        <select id="user_id" name="user_id" 
                            class="w-full px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                            @foreach($availableUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                            @endforeach
                        </select>
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
                        <i class="fa-solid fa-check"></i>
                        <span>Simpan Data Siswa</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>

<script>
    function toggleUserOption(option) {
        const newFields = document.getElementById('new-user-fields');
        const existingField = document.getElementById('existing-user-field');
        if (option === 'new') {
            newFields.classList.remove('hidden');
            existingField.classList.add('hidden');
        } else {
            newFields.classList.add('hidden');
            existingField.classList.remove('hidden');
        }
    }
</script>
@endsection
