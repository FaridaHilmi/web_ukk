@extends('layouts.app')

@section('title', 'Input Presensi - Super Simpel')
@section('page_title', 'Presensi Siswa')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Sederhana -->
    <div class="flex items-center justify-between pb-2">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Presensi Kelas</h2>
            <p class="text-xs text-slate-500 mt-0.5">Tandai kehadiran siswa dengan satu kali klik.</p>
        </div>
        <a href="{{ route('absensi.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600 bg-white border border-slate-200/80 rounded-xl hover:bg-slate-50 transition">
            <i class="fa-solid fa-arrow-left text-[10px]"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Filter Kelas & Tanggal Ringkas -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('absensi.create') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
            <div class="sm:col-span-6">
                <label for="kelas_id" class="block text-xs font-semibold text-slate-600 mb-1">Pilih Kelas</label>
                <select id="kelas_id" name="kelas_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-4">
                <label for="tanggal" class="block text-xs font-semibold text-slate-600 mb-1">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="{{ $tanggal }}" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
            </div>

            <div class="sm:col-span-2">
                <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold transition">
                    Pilih
                </button>
            </div>
        </form>
    </div>

    <!-- Lembar Presensi Sekali Klik -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Bar Aksi Atas -->
        <div class="p-4 sm:px-6 bg-blue-50/40 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <span class="text-xs font-bold text-slate-800">
                    Kelas: {{ $kelasList->find($selectedKelasId)?->nama_kelas ?? 'Kelas' }}
                </span>
                <span class="text-xs text-slate-500 ml-2">
                    ({{ $siswaList->count() }} Siswa) &bull; {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}
                </span>
            </div>
            @if($siswaList->count() > 0)
                <button type="button" onclick="setAllHadir()" 
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-50 rounded-xl text-xs font-bold shadow-2xs transition">
                    <i class="fa-solid fa-check-double text-xs"></i>
                    <span>Tandai Semua Hadir</span>
                </button>
            @endif
        </div>

        @if($siswaList->count() > 0)
            <form method="POST" action="{{ route('absensi.store') }}">
                @csrf
                <input type="hidden" name="batch" value="1">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                <input type="hidden" name="tanggal" value="{{ $tanggal }}">

                <!-- List Siswa -->
                <div class="divide-y divide-slate-100">
                    @foreach($siswaList as $idx => $s)
                        @php
                            $status = $existingAbsensi->get($s->id, 'hadir');
                        @endphp
                        <div class="p-4 sm:px-6 hover:bg-slate-50/60 transition flex flex-col lg:flex-row lg:items-center justify-between gap-3" id="row-{{ $s->id }}">
                            
                            <!-- Info Siswa -->
                            <div class="flex items-center gap-3 min-w-0 lg:w-1/3">
                                <span class="text-xs font-semibold text-slate-400 w-5 text-right">{{ $idx + 1 }}.</span>
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($s->nama, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $s->nama }}</p>
                                    <p class="text-[11px] text-slate-400 font-mono">NISN: {{ $s->nisn }}</p>
                                </div>
                            </div>

                            <!-- Tombol Toggle Status Sekali Klik -->
                            <div class="flex items-center gap-1.5 shrink-0">
                                <!-- Hidden input yang dikirim ke server -->
                                <input type="hidden" name="absensi[{{ $s->id }}][status]" id="status-input-{{ $s->id }}" value="{{ $status }}">

                                <!-- Tombol Hadir -->
                                <button type="button" onclick="selectStatus('{{ $s->id }}', 'hadir')" id="btn-{{ $s->id }}-hadir"
                                    class="status-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition border {{ $status === 'hadir' ? 'bg-emerald-600 text-white border-emerald-600 shadow-2xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                    Hadir
                                </button>

                                <!-- Tombol Izin -->
                                <button type="button" onclick="selectStatus('{{ $s->id }}', 'izin')" id="btn-{{ $s->id }}-izin"
                                    class="status-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition border {{ $status === 'izin' ? 'bg-blue-600 text-white border-blue-600 shadow-2xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                    Izin
                                </button>

                                <!-- Tombol Sakit -->
                                <button type="button" onclick="selectStatus('{{ $s->id }}', 'sakit')" id="btn-{{ $s->id }}-sakit"
                                    class="status-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition border {{ $status === 'sakit' ? 'bg-amber-500 text-white border-amber-500 shadow-2xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                    Sakit
                                </button>

                                <!-- Tombol Alpa -->
                                <button type="button" onclick="selectStatus('{{ $s->id }}', 'alpa')" id="btn-{{ $s->id }}-alpa"
                                    class="status-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition border {{ $status === 'alpa' ? 'bg-rose-600 text-white border-rose-600 shadow-2xs' : 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                                    Alpa
                                </button>
                            </div>

                            <!-- Input Keterangan Ringkas -->
                            <div class="lg:w-1/4">
                                <input type="text" name="absensi[{{ $s->id }}][keterangan]" placeholder="Catatan tambahan (opsional)..."
                                    class="w-full px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-1 focus:ring-blue-500">
                            </div>

                        </div>
                    @endforeach
                </div>

                <!-- Footer Simpan -->
                <div class="p-4 sm:px-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-xs text-slate-500">Pastikan status kehadiran telah sesuai.</p>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('absensi.index') }}" class="px-4 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 rounded-xl text-xs font-semibold transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-sm shadow-blue-500/20 transition">
                            <i class="fa-solid fa-check mr-1.5"></i> Simpan Presensi Kelas
                        </button>
                    </div>
                </div>
            </form>
        @else
            <!-- Empty State yang Ramah -->
            <div class="py-12 px-4 text-center">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                    <i class="fa-solid fa-users-slash"></i>
                </div>
                <h4 class="text-sm font-bold text-slate-800">Belum Ada Siswa</h4>
                <p class="text-xs text-slate-500 mt-1">Kelas ini belum memiliki daftar siswa terdaftar.</p>
                <div class="mt-4">
                    <a href="{{ route('siswa.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 text-white rounded-xl text-xs font-semibold">
                        <i class="fa-solid fa-plus"></i> Tambah Siswa Baru
                    </a>
                </div>
            </div>
        @endif
    </div>

</div>

<!-- Script Toggle Sekali Klik -->
<script>
    const statusStyles = {
        'hadir': 'bg-emerald-600 text-white border-emerald-600 shadow-2xs',
        'izin':  'bg-blue-600 text-white border-blue-600 shadow-2xs',
        'sakit': 'bg-amber-500 text-white border-amber-500 shadow-2xs',
        'alpa':  'bg-rose-600 text-white border-rose-600 shadow-2xs'
    };
    const inactiveStyle = 'bg-slate-50 text-slate-600 border-slate-200 hover:bg-slate-100';

    function selectStatus(siswaId, newStatus) {
        // Set nilai input hidden
        document.getElementById('status-input-' + siswaId).value = newStatus;

        // Reset semua tombol untuk siswa ini
        ['hadir', 'izin', 'sakit', 'alpa'].forEach(st => {
            const btn = document.getElementById('btn-' + siswaId + '-' + st);
            if (btn) {
                btn.className = 'status-btn px-3 py-1.5 rounded-lg text-xs font-semibold transition border ' + 
                    (st === newStatus ? statusStyles[st] : inactiveStyle);
            }
        });
    }

    function setAllHadir() {
        const inputs = document.querySelectorAll('input[id^="status-input-"]');
        inputs.forEach(input => {
            const siswaId = input.id.replace('status-input-', '');
            selectStatus(siswaId, 'hadir');
        });
    }
</script>
@endsection
