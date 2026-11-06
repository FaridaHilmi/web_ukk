@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('page_title', 'Manajemen Pengguna Sistem')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Pengguna</h2>
            <p class="text-xs text-slate-500">Kelola akun akses login untuk Administrator, Guru, dan Siswa</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-semibold shadow-sm shadow-blue-500/20 transition-all">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna Baru
        </a>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-3">
            <div class="flex-1 relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                    class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="w-full md:w-48">
                <select name="role" class="w-full py-2 px-3 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Peran (Role)</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin / TU</option>
                    <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') === 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold rounded-xl">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                    <tr>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Email</th>
                        <th class="py-3 px-4 text-center">Peran (Role)</th>
                        <th class="py-3 px-4">Terdaftar</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/80">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-900">{{ $user->name }}</p>
                                        <p class="text-[10px] text-slate-400">ID: #{{ $user->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-slate-600">{{ $user->email }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                                    {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : '' }}
                                    {{ $user->role === 'guru' ? 'bg-emerald-100 text-emerald-800' : '' }}
                                    {{ $user->role === 'siswa' ? 'bg-amber-100 text-amber-800' : '' }}">
                                    {{ $user->role === 'admin' ? 'Admin / TU' : ($user->role === 'guru' ? 'Guru' : 'Siswa') }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="py-3 px-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg" title="Edit Pengguna">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    @if(Auth::id() !== $user->id)
                                        <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg" title="Hapus Pengguna">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400">Tidak ada pengguna ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
