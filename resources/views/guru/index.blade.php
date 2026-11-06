@extends('layouts.app')

@section('title', 'Data Guru')
@section('page_title', 'Daftar Guru Pengajar')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Guru Pengajar</h2>
            <p class="text-xs text-slate-500">Kelola informasi guru, NIP, mata pelajaran yang diampu, dan akun login</p>
        </div>
        <a href="{{ route('guru.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all">
            <i class="fa-solid fa-user-plus"></i> Tambah Guru Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('guru.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau no telepon..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-full md:w-56">
                <select name="mata_pelajaran_id" class="w-full py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Mata Pelajaran</option>
                    @foreach($mataPelajaranList as $m)
                        <option value="{{ $m->id }}" {{ request('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'mata_pelajaran_id']))
                    <a href="{{ route('guru.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Guru Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Nama Guru</th>
                        <th class="py-3 px-4">NIP</th>
                        <th class="py-3 px-4">Mata Pelajaran Diampu</th>
                        <th class="py-3 px-4">Kontak / Email</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($guru as $item)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $item->nama }}</p>
                                        <p class="text-[10px] text-slate-400">Akun: {{ $item->user->email ?? 'Tidak terhubung' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600 font-semibold">{{ $item->nip }}</td>
                            <td class="py-3.5 px-4">
                                @if($item->mataPelajaran)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        <i class="fa-solid fa-book-bookmark text-[10px]"></i>
                                        {{ $item->mataPelajaran->nama_mapel }}
                                    </span>
                                @else
                                    <span class="text-slate-400 italic">Belum ditentukan</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="space-y-0.5">
                                    <p class="text-slate-600"><i class="fa-solid fa-phone text-[10px] text-slate-400 mr-1"></i> {{ $item->telepon ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('guru.show', $item->id) }}" class="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg" title="Detail Profil">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('guru.edit', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Guru">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form method="POST" action="{{ route('guru.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini beserta akun login-nya?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Hapus Guru">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada data guru.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($guru->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $guru->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
