<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMAS') - Sistem Informasi Sekolah</title>

    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb', // Soft Primary Blue
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 antialiased min-h-screen flex">

    <!-- Mobile Backdrop -->
    <div id="sidebar-backdrop" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/30 backdrop-blur-xs z-30 hidden md:hidden transition-opacity"></div>

    <!-- SIDEBAR MINIMALIS & USER-FRIENDLY -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200/70 flex flex-col transition-transform duration-200 transform -translate-x-full md:translate-x-0">
        
        <!-- Header Logo -->
        <div class="h-16 flex items-center px-6 border-b border-slate-100 bg-white">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-sm shadow-blue-500/20">
                    <i class="fa-solid fa-graduation-cap text-sm"></i>
                </div>
                <div>
                    <h1 class="font-bold text-sm text-slate-900 tracking-tight">SIMAS<span class="text-blue-600">.</span></h1>
                    <p class="text-[10px] text-slate-400 font-medium">Sistem Presensi & Nilai</p>
                </div>
            </a>
        </div>

        <!-- Profil Singkat Pengguna -->
        <div class="p-3 m-3 bg-blue-50/50 border border-blue-100/60 rounded-xl flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-xs">
                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
            </div>
            <div class="overflow-hidden flex-1">
                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <span class="text-[10px] font-semibold text-blue-700 capitalize">
                    {{ Auth::user()->role === 'admin' ? 'Tata Usaha (TU)' : (Auth::user()->role === 'guru' ? 'Guru Pengajar' : 'Siswa') }}
                </span>
            </div>
        </div>

        <!-- Menu Navigasi Bersih & Terarah -->
        <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto text-xs font-semibold">
            
            <!-- Dashboard (Semua Role) -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-sm shadow-blue-500/20' : 'text-slate-600 hover:bg-slate-100/80' }}">
                <i class="fa-solid fa-gauge-high w-4 text-center {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                <span>Dashboard</span>
            </a>

            @if(Auth::user()->isAdmin())
                <!-- MENU ADMIN / TU -->
                <div class="pt-4 pb-1 px-3 text-[10px] font-bold tracking-wider uppercase text-slate-400">Master Data</div>
                
                <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('siswa.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-users w-4 text-center {{ request()->routeIs('siswa.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Data Siswa</span>
                </a>

                <a href="{{ route('guru.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('guru.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-chalkboard-user w-4 text-center {{ request()->routeIs('guru.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Data Guru</span>
                </a>

                <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('kelas.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-school w-4 text-center {{ request()->routeIs('kelas.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Data Kelas</span>
                </a>

                <a href="{{ route('mata-pelajaran.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('mata-pelajaran.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-book-bookmark w-4 text-center {{ request()->routeIs('mata-pelajaran.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Mata Pelajaran</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[10px] font-bold tracking-wider uppercase text-slate-400">Akademik & Akun</div>

                <a href="{{ route('absensi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('absensi.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-clipboard-user w-4 text-center {{ request()->routeIs('absensi.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Presensi Siswa</span>
                </a>

                <a href="{{ route('nilai.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('nilai.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-chart-line w-4 text-center {{ request()->routeIs('nilai.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Laporan Nilai</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-user-gear w-4 text-center {{ request()->routeIs('admin.users.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Kelola Pengguna</span>
                </a>

            @elseif(Auth::user()->isGuru())
                <!-- MENU GURU -->
                <div class="pt-4 pb-1 px-3 text-[10px] font-bold tracking-wider uppercase text-slate-400">Penilaian</div>

                <a href="{{ route('nilai.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('nilai.index') || request()->routeIs('nilai.create') || request()->routeIs('nilai.edit') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-pen-to-square w-4 text-center {{ request()->routeIs('nilai.*') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Input & Nilai Siswa</span>
                </a>

                <a href="{{ route('nilai.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('nilai.laporan') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-chart-pie w-4 text-center {{ request()->routeIs('nilai.laporan') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Laporan Rapor</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[10px] font-bold tracking-wider uppercase text-slate-400">Kehadiran Siswa</div>

                <a href="{{ route('absensi.create') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('absensi.create') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-clipboard-check w-4 text-center {{ request()->routeIs('absensi.create') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Input Presensi Kelas</span>
                </a>

                <a href="{{ route('absensi.rekap') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('absensi.rekap') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-calendar-check w-4 text-center {{ request()->routeIs('absensi.rekap') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Rekap Presensi</span>
                </a>

                <div class="pt-4 pb-1 px-3 text-[10px] font-bold tracking-wider uppercase text-slate-400">Referensi</div>

                <a href="{{ route('siswa.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('siswa.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-users w-4 text-center text-slate-400"></i>
                    <span>Daftar Siswa</span>
                </a>

                <a href="{{ route('kelas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('kelas.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-school w-4 text-center text-slate-400"></i>
                    <span>Daftar Kelas</span>
                </a>

            @elseif(Auth::user()->isSiswa())
                <!-- MENU SISWA (SANGAT RINGKAS) -->
                <div class="pt-4 pb-1 px-3 text-[10px] font-bold tracking-wider uppercase text-slate-400">Menu Pribadi</div>

                <a href="{{ route('siswa-portal.profil') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('siswa-portal.profil') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-id-card w-4 text-center {{ request()->routeIs('siswa-portal.profil') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Biodata Diri</span>
                </a>

                <a href="{{ route('siswa-portal.absensi') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('siswa-portal.absensi') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-calendar-check w-4 text-center {{ request()->routeIs('siswa-portal.absensi') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Riwayat Presensi</span>
                </a>

                <a href="{{ route('siswa-portal.nilai') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all {{ request()->routeIs('siswa-portal.nilai') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-slate-600 hover:bg-slate-100/80' }}">
                    <i class="fa-solid fa-award w-4 text-center {{ request()->routeIs('siswa-portal.nilai') ? 'text-blue-600' : 'text-slate-400' }}"></i>
                    <span>Rapor / Transkrip</span>
                </a>
            @endif

        </nav>

        <!-- Tombol Logout -->
        <div class="p-3 border-t border-slate-100 bg-white">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 transition font-medium text-xs">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                    <span>Keluar Akun</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- KONTEN UTAMA -->
    <div class="flex-1 flex flex-col md:pl-64 min-w-0">
        
        <!-- Topbar Sederhana -->
        <header class="h-16 bg-white/95 backdrop-blur-xs border-b border-slate-200/70 sticky top-0 z-20 px-4 md:px-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleSidebar()" class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 focus:outline-none">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                <h2 class="font-bold text-sm text-slate-800">@yield('page_title', 'Halaman')</h2>
            </div>

            <div class="flex items-center gap-3">
                <div class="hidden sm:flex items-center gap-2 text-xs text-slate-500 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200/60">
                    <i class="fa-regular fa-calendar text-blue-600"></i>
                    <span>{{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</span>
                </div>

                <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-bold text-slate-800 leading-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-blue-600 font-semibold uppercase">{{ Auth::user()->role }}</p>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center text-xs">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Area Isi Halaman -->
        <main class="flex-1 p-4 md:p-6 lg:p-8 max-w-7xl w-full mx-auto">
            
            <!-- Toast Feedback Ramah & Sederhana -->
            @if(session('success'))
                <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 flex items-center gap-3 text-xs shadow-2xs">
                    <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-check text-xs"></i>
                    </div>
                    <span class="flex-1 font-semibold">{{ session('success') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-5 p-3.5 rounded-xl bg-rose-50 border border-rose-200/80 text-rose-800 flex items-center gap-3 text-xs shadow-2xs">
                    <div class="w-6 h-6 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-xs"></i>
                    </div>
                    <span class="flex-1 font-semibold">{{ session('error') }}</span>
                    <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                        <i class="fa-solid fa-xmark text-sm"></i>
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-900 text-xs shadow-2xs">
                    <div class="flex items-center gap-2 font-bold mb-1.5 text-amber-800">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Mohon periksa isian formulir:</span>
                    </div>
                    <ul class="list-disc list-inside text-[11px] space-y-0.5 text-amber-800 pl-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Slot Konten Halaman -->
            @yield('content')
        </main>

        <!-- Footer Bersih -->
        <footer class="mt-auto py-4 px-6 border-t border-slate-200/60 bg-white text-[11px] text-center text-slate-400">
            &copy; {{ date('Y') }} SIMAS Sekolah &bull; Sistem Presensi & Nilai Siswa
        </footer>
    </div>

    <!-- Script Drawer Mobile -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
