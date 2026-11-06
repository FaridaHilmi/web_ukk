<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMAS AKADEMIK</title>
    
    <!-- Google Font: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Logo & Brand Header -->
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-600 text-white shadow-md shadow-blue-500/20 mb-3">
                <i class="fa-solid fa-graduation-cap text-xl"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">SIMAS AKADEMIK</h1>
            <p class="text-xs text-slate-500 mt-1">Sistem Informasi Absensi & Nilai Siswa</p>
        </div>

        <!-- Main Card -->
        <div class="mt-6 bg-white py-8 px-6 shadow-sm border border-slate-200/90 rounded-2xl sm:px-8">
            
            <!-- Alert Notification -->
            @if(session('success'))
                <div class="mb-5 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-check text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-5 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                    <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <!-- Segmented Role Selector -->
            <div class="mb-6">
                <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-2">Pilih Peran Akun</label>
                <div class="grid grid-cols-3 gap-1 bg-slate-100 p-1 rounded-xl">
                    <button type="button" id="tab-admin" onclick="selectRole('admin', 'admin@sekolah.sch.id', 'password')"
                        class="role-btn py-2 text-xs font-semibold rounded-lg transition-all bg-white text-blue-700 shadow-sm border border-slate-200/60">
                        Admin TU
                    </button>
                    <button type="button" id="tab-guru" onclick="selectRole('guru', 'guru@sekolah.sch.id', 'password')"
                        class="role-btn py-2 text-xs font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                        Guru
                    </button>
                    <button type="button" id="tab-siswa" onclick="selectRole('siswa', 'siswa@sekolah.sch.id', 'password')"
                        class="role-btn py-2 text-xs font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                        Siswa
                    </button>
                </div>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-regular fa-envelope text-xs"></i>
                        </span>
                        <input type="email" id="email" name="email" value="{{ old('email', 'admin@sekolah.sch.id') }}" required autofocus
                            placeholder="nama@sekolah.sch.id"
                            class="w-full pl-9 pr-3 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-xs font-semibold text-slate-700">Kata Sandi</label>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input type="password" id="password" name="password" value="password" required
                            placeholder="••••••••"
                            class="w-full pl-9 pr-9 py-2.5 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                            <i id="eye-icon" class="fa-regular fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" checked class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                        <span>Ingat saya</span>
                    </label>
                    <span class="text-[11px] text-slate-400">Akun resmi sekolah</span>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold text-xs rounded-xl shadow-sm shadow-blue-500/30 transition-all">
                        Masuk ke Akun
                    </button>
                </div>
            </form>

            <!-- Quick Account Hint -->
            <div class="mt-6 pt-5 border-t border-slate-100 text-center">
                <p class="text-[11px] text-slate-400">
                    Kata sandi bawaan untuk semua akun demo: <span class="font-mono font-semibold text-slate-700 bg-slate-100 px-1.5 py-0.5 rounded">password</span>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-slate-400 mt-6">
            &copy; {{ date('Y') }} SIMAS Sekolah &bull; Sistem Presensi & Nilai
        </p>
    </div>

    <!-- Script for Role Tab & Password Visibility -->
    <script>
        function selectRole(role, email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;

            const buttons = document.querySelectorAll('.role-btn');
            buttons.forEach(btn => {
                btn.className = 'role-btn py-2 text-xs font-medium rounded-lg text-slate-600 hover:text-slate-900 transition-all';
            });

            const activeBtn = document.getElementById('tab-' + role);
            if (activeBtn) {
                activeBtn.className = 'role-btn py-2 text-xs font-semibold rounded-lg transition-all bg-white text-blue-700 shadow-sm border border-slate-200/60';
            }
        }

        function togglePasswordVisibility() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
