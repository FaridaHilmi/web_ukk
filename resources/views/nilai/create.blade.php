@extends('layouts.app')

@section('title', 'Input Nilai Siswa')
@section('page_title', 'Form Input Nilai Siswa')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Input Nilai Siswa</h2>
            <p class="text-xs text-slate-500">Pilih kelas, mata pelajaran, jenis penilaian, lalu masukkan nilai siswa</p>
        </div>
        <a href="{{ route('nilai.index') }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition-all">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Daftar Nilai
        </a>
    </div>

    <!-- Parameter Filter Selector -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm">
        <form method="GET" action="{{ route('nilai.create') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
            <div>
                <label for="kelas_id" class="block text-xs font-bold text-slate-700 mb-1.5">Rombel Kelas <span class="text-rose-500">*</span></label>
                <select id="kelas_id" name="kelas_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id }}" {{ $selectedKelasId == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="mata_pelajaran_id" class="block text-xs font-bold text-slate-700 mb-1.5">Mata Pelajaran <span class="text-rose-500">*</span></label>
                <select id="mata_pelajaran_id" name="mata_pelajaran_id" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach($mataPelajaranList as $m)
                        <option value="{{ $m->id }}" {{ $selectedMapelId == $m->id ? 'selected' : '' }}>
                            {{ $m->nama_mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="jenis_nilai" class="block text-xs font-bold text-slate-700 mb-1.5">Jenis Penilaian <span class="text-rose-500">*</span></label>
                <select id="jenis_nilai" name="jenis_nilai" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="UH" {{ $selectedJenis === 'UH' ? 'selected' : '' }}>Ulangan Harian (UH)</option>
                    <option value="UTS" {{ $selectedJenis === 'UTS' ? 'selected' : '' }}>Ujian Tengah Semester (UTS)</option>
                    <option value="UAS" {{ $selectedJenis === 'UAS' ? 'selected' : '' }}>Ujian Akhir Semester (UAS)</option>
                </select>
            </div>

            <div>
                <label for="semester" class="block text-xs font-bold text-slate-700 mb-1.5">Semester <span class="text-rose-500">*</span></label>
                <select id="semester" name="semester" onchange="this.form.submit()"
                    class="w-full px-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="1" {{ $selectedSemester === '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ $selectedSemester === '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-semibold h-[38px]">
                Muat Siswa
            </button>
        </form>
    </div>

    <!-- Student Scoring Sheet -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-slate-50/50">
            <div>
                <h3 class="font-bold text-sm text-slate-800">
                    Input Nilai {{ $selectedJenis }}: {{ $kelasList->find($selectedKelasId)?->nama_kelas }} - {{ $mataPelajaranList->find($selectedMapelId)?->nama_mapel }}
                </h3>
                <p class="text-xs text-slate-500">Semester: {{ $selectedSemester }} | Masukkan nilai skala 0 - 100</p>
            </div>
            <span class="text-xs font-semibold text-blue-600 bg-blue-50 px-3 py-1 rounded-full border border-blue-100">
                Total Siswa: {{ $siswaList->count() }}
            </span>
        </div>

        @if($siswaList->count() > 0)
            <form method="POST" action="{{ route('nilai.store') }}">
                @csrf
                <input type="hidden" name="batch" value="1">
                <input type="hidden" name="kelas_id" value="{{ $selectedKelasId }}">
                <input type="hidden" name="mata_pelajaran_id" value="{{ $selectedMapelId }}">
                <input type="hidden" name="jenis_nilai" value="{{ $selectedJenis }}">
                <input type="hidden" name="semester" value="{{ $selectedSemester }}">

                <div class="overflow-x-auto">
                    <table class="w-full text-xs text-left">
                        <thead class="bg-slate-50 text-slate-500 font-semibold uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="py-3 px-4 w-12 text-center">No</th>
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4">NISN</th>
                                <th class="py-3 px-4 text-center">Jenis Kelamin</th>
                                <th class="py-3 px-4 w-48 text-right">Nilai Angka (0 - 100)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($siswaList as $idx => $s)
                                @php
                                    $score = $existingNilai->get($s->id, '');
                                @endphp
                                <tr class="hover:bg-slate-50/80">
                                    <td class="py-3.5 px-4 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="py-3.5 px-4 font-bold text-slate-900">{{ $s->nama }}</td>
                                    <td class="py-3.5 px-4 font-mono text-slate-500">{{ $s->nisn }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $s->jenis_kelamin === 'L' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700' }}">
                                            {{ $s->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <input type="number" step="0.01" min="0" max="100" name="nilai[{{ $s->id }}]" value="{{ $score }}"
                                            placeholder="Contoh: 85.5"
                                            class="w-32 px-3 py-1.5 text-xs text-right font-bold bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('nilai.index') }}" class="px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-semibold">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold shadow-md shadow-blue-500/20">
                        <i class="fa-solid fa-save mr-1.5"></i> Simpan Seluruh Nilai
                    </button>
                </div>
            </form>
        @else
            <div class="py-12 text-center text-slate-400">
                <i class="fa-solid fa-user-xmark text-3xl mb-2 text-slate-300"></i>
                <p class="text-sm">Tidak ada siswa terdaftar pada kelas ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection
