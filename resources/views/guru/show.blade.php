@extends('layouts.app')

@section('title', 'Detail Guru')
@section('page_title', 'Profil Guru Pengajar')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Detail Profil Guru</h2>
            <p class="text-xs text-slate-500">Informasi lengkap data guru pengajar</p>
        </div>
        <a href="{{ route('guru.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 p-6 md:p-8 shadow-sm">
        <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
            <div class="w-16 h-16 rounded-2xl bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-2xl">
                {{ strtoupper(substr($guru->nama, 0, 2)) }}
            </div>
            <div>
                <h3 class="text-lg font-bold text-slate-900">{{ $guru->nama }}</h3>
                <p class="text-xs text-slate-400 font-mono mt-0.5">NIP: {{ $guru->nip }}</p>
                <span class="inline-block mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
                    Guru Pengajar
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-6 border-b border-slate-100 text-xs">
            <div>
                <span class="text-slate-400 block mb-1">Mata Pelajaran Diampu</span>
                <p class="font-bold text-slate-800 text-sm">
                    {{ $guru->mataPelajaran->nama_mapel ?? 'Belum ditentukan' }}
                    @if($guru->mataPelajaran)
                        <span class="text-xs font-normal text-blue-600 font-mono">({{ $guru->mataPelajaran->kode_mapel }})</span>
                    @endif
                </p>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">Nomor Telepon / WhatsApp</span>
                <p class="font-bold text-slate-800 text-sm">{{ $guru->telepon ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">Email Akun Login</span>
                <p class="font-bold text-slate-800 font-mono">{{ $guru->user->email ?? '-' }}</p>
            </div>
            <div>
                <span class="text-slate-400 block mb-1">Terdaftar Sejak</span>
                <p class="font-bold text-slate-800">{{ $guru->created_at->format('d F Y') }}</p>
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-3">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('guru.edit', $guru->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                    <i class="fa-solid fa-pen-to-square mr-1.5"></i> Edit Profil Guru
                </a>
            @endif
        </div>
    </div>

</div>
@endsection
