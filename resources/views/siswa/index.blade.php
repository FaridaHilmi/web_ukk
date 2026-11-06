@extends('layouts.app')

@section('title', 'Data Siswa')
@section('page_title', 'Daftar Siswa')

@section('content')
<div class="space-y-6">

    <!-- Top Action & Title Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-blue-50 text-blue-700 text-[11px] font-bold border border-blue-100/60">
                    <i class="fa-solid fa-users text-[10px]"></i> Master Data
                </span>
                <span class="text-slate-400 text-xs">&bull;</span>
                <span class="text-slate-500 text-xs font-medium">{{ $siswa->total() }} Siswa Terdaftar</span>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Daftar Siswa</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                {{ Auth::user()->isAdmin() ? 'Kelola biodata siswa, pembagian rombongan belajar, dan sinkronisasi akun login.' : 'Direktori data induk siswa dan riwayat akademik sekolah.' }}
            </p>
        </div>

        @if(Auth::user()->isAdmin())
            <div class="flex items-center gap-2.5">
                <a href="{{ route('siswa.create') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-blue-500/25 hover:shadow-md hover:shadow-blue-500/20 transition-all duration-200 group">
                    <i class="fa-solid fa-plus text-xs group-hover:rotate-90 transition-transform duration-200"></i>
                    <span>Tambah Siswa Baru</span>
                </a>
            </div>
        @endif
    </div>

    <!-- Quick Stats Cards (Clean Modern White & Soft Blue) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/70 shadow-2xs hover:border-blue-200 hover:shadow-xs transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Total Siswa</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($siswa->total()) }}</h3>
                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-blue-600 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Aktif Terdata
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shrink-0 border border-blue-100/50">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/70 shadow-2xs hover:border-blue-200 hover:shadow-xs transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Rombel Kelas</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($kelasList->count()) }}</h3>
                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-indigo-600 mt-0.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span> Kelas Terdaftar
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-lg shrink-0 border border-indigo-100/50">
                <i class="fa-solid fa-school"></i>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/70 shadow-2xs hover:border-blue-200 hover:shadow-xs transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Filter Aktif</p>
                <h3 class="text-lg font-bold text-slate-800 mt-1 truncate max-w-[130px]">
                    {{ request('kelas_id') ? ($kelasList->firstWhere('id', request('kelas_id'))->nama_kelas ?? 'Kelas Tertentu') : 'Semua Kelas' }}
                </h3>
                <span class="text-[11px] text-slate-400 font-medium">
                    {{ request('jenis_kelamin') ? (request('jenis_kelamin') === 'L' ? 'Laki-laki' : 'Perempuan') : 'Semua Gender' }}
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-lg shrink-0 border border-slate-200/60">
                <i class="fa-solid fa-filter"></i>
            </div>
        </div>

        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/70 shadow-2xs hover:border-blue-200 hover:shadow-xs transition-all duration-200 flex items-center justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">Data Halaman</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ $siswa->count() }}</h3>
                <span class="text-[11px] text-slate-400 font-medium">
                    Dari total {{ $siswa->total() }} entri
                </span>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg shrink-0 border border-emerald-100/50">
                <i class="fa-solid fa-id-card"></i>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar (Minimalist, Elevated, Responsive) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/70 shadow-2xs">
        <form method="GET" action="{{ route('siswa.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            
            <!-- Kolom Pencarian -->
            <div class="md:col-span-5 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama siswa, NISN, telepon, atau alamat..."
                    class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-400">
            </div>

            <!-- Filter Kelas -->
            <div class="md:col-span-3">
                <select name="kelas_id" 
                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-700">
                    <option value="">Semua Rombel Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Gender -->
            <div class="md:col-span-2">
                <select name="jenis_kelamin" 
                    class="w-full px-3.5 py-2.5 text-xs bg-slate-50/70 hover:bg-slate-50 focus:bg-white border border-slate-200/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all text-slate-700">
                    <option value="">Semua Gender</option>
                    <option value="L" {{ request('jenis_kelamin') === 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                    <option value="P" {{ request('jenis_kelamin') === 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                </select>
            </div>

            <!-- Tombol Submit & Reset -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" 
                    class="flex-1 py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-all shadow-xs flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-filter text-[10px]"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['search', 'kelas_id', 'jenis_kelamin']))
                    <a href="{{ route('siswa.index') }}" 
                        title="Reset Filter"
                        class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Student Data Table (Clean White, Generous Whitespace, Subtle Interactions) -->
    <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="py-3.5 px-5">Siswa</th>
                        <th class="py-3.5 px-4">NISN</th>
                        <th class="py-3.5 px-4">Rombel Kelas</th>
                        <th class="py-3.5 px-4 text-center">Gender</th>
                        <th class="py-3.5 px-4">Kontak & Alamat</th>
                        <th class="py-3.5 px-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/90 text-slate-700">
                    @forelse($siswa as $item)
                        <tr class="hover:bg-blue-50/30 transition-colors duration-150 group">
                            
                            <!-- Profil & Nama Siswa -->
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl {{ $item->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700 border-blue-100/70' : 'bg-pink-50 text-pink-700 border-pink-100/70' }} border flex items-center justify-center text-xs font-extrabold shrink-0 shadow-2xs">
                                        {{ strtoupper(substr($item->nama, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('siswa.show', $item->id) }}" class="font-bold text-slate-900 group-hover:text-blue-700 transition-colors block truncate max-w-xs">
                                            {{ $item->nama }}
                                        </a>
                                        <p class="text-[11px] text-slate-400 truncate mt-0.5">
                                            <i class="fa-regular fa-envelope text-[10px] mr-1"></i>{{ $item->user->email ?? 'Belum ada akun' }}
                                        </p>
                                    </div>
                                </div>
                            </td>

                            <!-- NISN -->
                            <td class="py-4 px-4 font-mono font-semibold text-slate-700">
                                <span class="bg-slate-50 border border-slate-200/70 px-2 py-1 rounded-md text-[11px]">
                                    {{ $item->nisn }}
                                </span>
                            </td>

                            <!-- Rombel Kelas -->
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-50/70 text-blue-700 border border-blue-100/60">
                                    <i class="fa-solid fa-graduation-cap text-[10px]"></i>
                                    {{ $item->kelas->nama_kelas ?? 'Belum diatur' }}
                                </span>
                            </td>

                            <!-- Gender / Jenis Kelamin -->
                            <td class="py-4 px-4 text-center">
                                @if($item->jenis_kelamin === 'L')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 text-blue-700">
                                        <i class="fa-solid fa-mars text-[10px]"></i> L
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-pink-50 text-pink-700">
                                        <i class="fa-solid fa-venus text-[10px]"></i> P
                                    </span>
                                @endif
                            </td>

                            <!-- Kontak & Alamat -->
                            <td class="py-4 px-4 max-w-xs">
                                <p class="text-slate-800 font-medium truncate">
                                    <i class="fa-solid fa-phone text-[10px] text-slate-400 mr-1.5"></i>{{ $item->telepon ?? '-' }}
                                </p>
                                <p class="text-[11px] text-slate-400 truncate mt-0.5" title="{{ $item->alamat }}">
                                    <i class="fa-solid fa-location-dot text-[10px] text-slate-400 mr-1.5"></i>{{ $item->alamat ?? 'Alamat belum diisi' }}
                                </p>
                            </td>

                            <!-- Tombol Aksi -->
                            <td class="py-4 px-5 text-center">
                                <div class="inline-flex items-center gap-1 bg-slate-50/80 p-1 rounded-xl border border-slate-200/60">
                                    <a href="{{ route('siswa.show', $item->id) }}" 
                                       class="w-7 h-7 rounded-lg text-slate-600 hover:text-blue-700 hover:bg-white flex items-center justify-center transition-all" 
                                       title="Lihat Detail Profil">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('siswa.edit', $item->id) }}" 
                                           class="w-7 h-7 rounded-lg text-blue-600 hover:text-blue-800 hover:bg-white flex items-center justify-center transition-all" 
                                           title="Edit Data Siswa">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </a>

                                        <form method="POST" action="{{ route('siswa.destroy', $item->id) }}" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data siswa {{ $item->nama }} beserta akun loginnya?');" 
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="w-7 h-7 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-white flex items-center justify-center transition-all" 
                                                title="Hapus Siswa">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-14 px-4 text-center">
                                <div class="w-14 h-14 mx-auto mb-3 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-2xl">
                                    <i class="fa-solid fa-user-xmark"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800">Tidak Ada Data Siswa</h4>
                                <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                                    {{ request()->hasAny(['search', 'kelas_id', 'jenis_kelamin']) ? 'Tidak ditemukan data siswa yang cocok dengan filter pencarian.' : 'Belum ada data siswa yang didaftarkan ke sistem.' }}
                                </p>
                                @if(request()->hasAny(['search', 'kelas_id', 'jenis_kelamin']))
                                    <div class="mt-4">
                                        <a href="{{ route('siswa.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition">
                                            <i class="fa-solid fa-rotate-left"></i>
                                            <span>Reset Filter Pencarian</span>
                                        </a>
                                    </div>
                                @elseif(Auth::user()->isAdmin())
                                    <div class="mt-4">
                                        <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm transition">
                                            <i class="fa-solid fa-plus"></i>
                                            <span>Tambah Siswa Pertama</span>
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Section -->
        @if($siswa->hasPages())
            <div class="p-4 sm:px-6 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Menampilkan <span class="font-bold text-slate-800">{{ $siswa->firstItem() }}</span> sampai <span class="font-bold text-slate-800">{{ $siswa->lastItem() }}</span> dari <span class="font-bold text-slate-800">{{ $siswa->total() }}</span> siswa
                </div>
                <div>
                    {{ $siswa->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
