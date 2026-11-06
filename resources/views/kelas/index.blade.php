@extends('layouts.app')

@section('title', 'Data Kelas')
@section('page_title', 'Daftar Kelas & Rombel')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Kelas</h2>
            <p class="text-xs text-slate-500">Kelola dan lihat rombongan belajar tingkat kelas</p>
        </div>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('kelas.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
            </a>
        @endif
    </div>

    <!-- Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('kelas.index') }}" class="flex gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kelas atau tingkat..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl">
                Cari
            </button>
            @if(request()->filled('search'))
                <a href="{{ route('kelas.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Kelas Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Nama Kelas</th>
                        <th class="py-3 px-4 text-center">Tingkat</th>
                        <th class="py-3 px-4 text-center">Jumlah Siswa</th>
                        @if(Auth::user()->isAdmin())
                            <th class="py-3 px-4 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($kelas as $item)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3.5 px-4 font-bold text-slate-900 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold">
                                    <i class="fa-solid fa-chalkboard"></i>
                                </span>
                                {{ $item->nama_kelas }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                    Kelas {{ $item->tingkat }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-blue-600">
                                {{ $item->siswa_count }} Siswa
                            </td>
                            @if(Auth::user()->isAdmin())
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('kelas.edit', $item->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Kelas">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form method="POST" action="{{ route('kelas.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini? Semua siswa di kelas ini juga akan terhapus.');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Hapus Kelas">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->isAdmin() ? 4 : 3 }}" class="py-8 text-center text-slate-400">Tidak ada data kelas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kelas->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $kelas->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
