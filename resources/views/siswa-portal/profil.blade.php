@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page_title', 'Profil & Biodata Siswa')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-700 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/15 flex flex-col sm:flex-row items-center gap-5">
        <div class="w-20 h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl font-bold border border-white/30">
            {{ strtoupper(substr($siswa->nama, 0, 2)) }}
        </div>
        <div class="text-center sm:text-left">
            <span class="inline-block px-3 py-0.5 rounded-full text-[10px] font-bold uppercase bg-white/20 text-blue-50 mb-1">
                Siswa Aktif
            </span>
            <h2 class="text-2xl font-extrabold">{{ $siswa->nama }}</h2>
            <p class="text-xs text-blue-100 font-mono mt-0.5">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        </div>
    </div>

    <!-- Data Detail Cards -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm space-y-6">
        <div>
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                <i class="fa-solid fa-id-card text-blue-600"></i> Informasi Pribadi Siswa
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Nama Lengkap</span>
                    <strong class="text-slate-800 text-sm">{{ $siswa->nama }}</strong>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Nomor Induk Siswa Nasional (NISN)</span>
                    <strong class="text-slate-800 font-mono text-sm">{{ $siswa->nisn }}</strong>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Rombongan Belajar (Kelas)</span>
                    <strong class="text-slate-800 text-sm">{{ $siswa->kelas->nama_kelas ?? '-' }}</strong>
                    <span class="text-[10px] text-slate-400 block">Tingkat {{ $siswa->kelas->tingkat ?? '-' }}</span>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Jenis Kelamin</span>
                    <strong class="text-slate-800 text-sm">{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</strong>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Nomor Telepon / Kontak Wali</span>
                    <strong class="text-slate-800 text-sm">{{ $siswa->telepon ?? '-' }}</strong>
                </div>

                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Alamat Tempat Tinggal</span>
                    <strong class="text-slate-800 text-sm">{{ $siswa->alamat ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                <i class="fa-solid fa-shield text-blue-600"></i> Informasi Akun Login
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Email Pengguna</span>
                    <strong class="text-slate-800 font-mono text-sm">{{ $siswa->user->email ?? '-' }}</strong>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-400 block mb-0.5">Hak Akses (Role)</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800">
                        {{ $siswa->user->role ?? 'Siswa' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex flex-wrap gap-3 justify-end">
            <a href="{{ route('siswa-portal.absensi') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-all">
                <i class="fa-solid fa-calendar-check mr-1.5"></i> Cek Rekap Presensi
            </a>
            <a href="{{ route('siswa-portal.nilai') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20 transition-all">
                <i class="fa-solid fa-award mr-1.5"></i> Cek Rapor / Transkrip
            </a>
        </div>
    </div>

</div>
@endsection
