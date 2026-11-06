@extends('layouts.app')

@section('title', 'Mata Pelajaran')
@section('page_title', 'Daftar Mata Pelajaran')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Mata Pelajaran</h2>
            <p class="text-xs text-slate-500">Kelola dan lihat kurikulum mata pelajaran sekolah</p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('mata-pelajaran.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Mata Pelajaran
            </a>
        @endif
    </div>

    <!-- Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('mata-pelajaran.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama mata pelajaran..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl">
                Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('mata-pelajaran.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Kode Mapel</th>
                        <th class="py-3 px-4">Nama Mata Pelajaran</th>
                        <th class="py-3 px-4 text-center">Guru Pengampu</th>
                        <th class="py-3 px-4 text-center">Data Nilai</th>
                        @if(Auth::user()->isAdmin())
                            <th class="py-3 px-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($mataPelajaran as $item)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 font-mono font-bold text-blue-700">
                                <span class="px-2.5 py-1 bg-blue-50 border border-blue-200 rounded-lg">
                                    {{ $item->kode_mapel }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900 text-sm">
                                {{ $item->nama_mapel }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    {{ $item->guru_count }} Guru
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="text-xs font-semibold text-slate-600">
                                    {{ $item->nilai_count }} Nilai
                                </span>
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('mata-pelajaran.edit', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Mapel">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('mata-pelajaran.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Hapus Mapel">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isAdmin() ? 5 : 4 }}" class="py-8 text-center text-slate-400">Tidak ada mata pelajaran.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mataPelajaran->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $mataPelajaran->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
