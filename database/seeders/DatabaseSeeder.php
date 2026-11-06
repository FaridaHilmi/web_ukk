<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Data Kelas
        $kelasXRpl = Kelas::firstOrCreate(
            ['nama_kelas' => 'X-RPL 1'],
            ['tingkat' => '10']
        );
        $kelasXiRpl = Kelas::firstOrCreate(
            ['nama_kelas' => 'XI-RPL 1'],
            ['tingkat' => '11']
        );
        $kelasXiRpl2 = Kelas::firstOrCreate(
            ['nama_kelas' => 'XI-RPL 2'],
            ['tingkat' => '11']
        );
        $kelasXiiRpl = Kelas::firstOrCreate(
            ['nama_kelas' => 'XII-RPL 1'],
            ['tingkat' => '12']
        );

        // 2. Data Mata Pelajaran
        $mapelWeb = MataPelajaran::firstOrCreate(
            ['kode_mapel' => 'RPL-01'],
            ['nama_mapel' => 'Pemrograman Web & Mobile']
        );
        $mapelDb = MataPelajaran::firstOrCreate(
            ['kode_mapel' => 'RPL-02'],
            ['nama_mapel' => 'Basis Data & SQL']
        );
        $mapelMtk = MataPelajaran::firstOrCreate(
            ['kode_mapel' => 'UM-01'],
            ['nama_mapel' => 'Matematika Terapan']
        );
        $mapelBindo = MataPelajaran::firstOrCreate(
            ['kode_mapel' => 'UM-02'],
            ['nama_mapel' => 'Bahasa Indonesia']
        );
        $mapelBing = MataPelajaran::firstOrCreate(
            ['kode_mapel' => 'UM-03'],
            ['nama_mapel' => 'Bahasa Inggris']
        );

        // 3. User Admin / TU
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@sekolah.sch.id'],
            [
                'name' => 'Administrator TU',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // 4. Data Guru & User Guru (5 Guru)
        $userGuru1 = User::firstOrCreate(
            ['email' => 'guru@sekolah.sch.id'],
            [
                'name' => 'Budi Santoso, S.Kom.',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $guru1 = Guru::firstOrCreate(
            ['nip' => '198501152010011001'],
            [
                'user_id' => $userGuru1->id,
                'nama' => 'Budi Santoso, S.Kom.',
                'mata_pelajaran_id' => $mapelWeb->id,
                'telepon' => '081234567890',
            ]
        );

        $userGuru2 = User::firstOrCreate(
            ['email' => 'siti.guru@sekolah.sch.id'],
            [
                'name' => 'Siti Nurhaliza, M.Pd.',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $guru2 = Guru::firstOrCreate(
            ['nip' => '198904222015022003'],
            [
                'user_id' => $userGuru2->id,
                'nama' => 'Siti Nurhaliza, M.Pd.',
                'mata_pelajaran_id' => $mapelMtk->id,
                'telepon' => '081234567891',
            ]
        );

        $userGuru3 = User::firstOrCreate(
            ['email' => 'ahmad.guru@sekolah.sch.id'],
            [
                'name' => 'Ahmad Fauzi, S.T.',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $guru3 = Guru::firstOrCreate(
            ['nip' => '199107182018031002'],
            [
                'user_id' => $userGuru3->id,
                'nama' => 'Ahmad Fauzi, S.T.',
                'mata_pelajaran_id' => $mapelDb->id,
                'telepon' => '081234567892',
            ]
        );

        // Guru 4 (Tambahan)
        $userGuru4 = User::firstOrCreate(
            ['email' => 'hendra.guru@sekolah.sch.id'],
            [
                'name' => 'Hendra Wijaya, S.Pd.',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $guru4 = Guru::firstOrCreate(
            ['nip' => '199305142020011004'],
            [
                'user_id' => $userGuru4->id,
                'nama' => 'Hendra Wijaya, S.Pd.',
                'mata_pelajaran_id' => $mapelBindo->id,
                'telepon' => '081234567893',
            ]
        );

        // Guru 5 (Tambahan)
        $userGuru5 = User::firstOrCreate(
            ['email' => 'maya.guru@sekolah.sch.id'],
            [
                'name' => 'Maya Anggraini, S.Pd.',
                'password' => Hash::make('password'),
                'role' => 'guru',
            ]
        );
        $guru5 = Guru::firstOrCreate(
            ['nip' => '199508202022022005'],
            [
                'user_id' => $userGuru5->id,
                'nama' => 'Maya Anggraini, S.Pd.',
                'mata_pelajaran_id' => $mapelBing->id,
                'telepon' => '081234567894',
            ]
        );

        // 5. Data Siswa & User Siswa (6 Siswa)
        $userSiswa1 = User::firstOrCreate(
            ['email' => 'siswa@sekolah.sch.id'],
            [
                'name' => 'Muhammad Rizky Pratama',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa1 = Siswa::firstOrCreate(
            ['nisn' => '0061234567'],
            [
                'user_id' => $userSiswa1->id,
                'nama' => 'Muhammad Rizky Pratama',
                'kelas_id' => $kelasXiRpl->id,
                'jenis_kelamin' => 'L',
                'telepon' => '085712345671',
                'alamat' => 'Jl. Merdeka No. 45, Jakarta Barat',
            ]
        );

        $userSiswa2 = User::firstOrCreate(
            ['email' => 'anisa@sekolah.sch.id'],
            [
                'name' => 'Anisa Rahmawati',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa2 = Siswa::firstOrCreate(
            ['nisn' => '0061234568'],
            [
                'user_id' => $userSiswa2->id,
                'nama' => 'Anisa Rahmawati',
                'kelas_id' => $kelasXiRpl->id,
                'jenis_kelamin' => 'P',
                'telepon' => '085712345672',
                'alamat' => 'Jl. Anggrek No. 12, Jakarta Barat',
            ]
        );

        $userSiswa3 = User::firstOrCreate(
            ['email' => 'bagas@sekolah.sch.id'],
            [
                'name' => 'Bagas Saputra',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa3 = Siswa::firstOrCreate(
            ['nisn' => '0061234569'],
            [
                'user_id' => $userSiswa3->id,
                'nama' => 'Bagas Saputra',
                'kelas_id' => $kelasXiRpl->id,
                'jenis_kelamin' => 'L',
                'telepon' => '085712345673',
                'alamat' => 'Jl. Kenanga No. 8, Jakarta Selatan',
            ]
        );

        $userSiswa4 = User::firstOrCreate(
            ['email' => 'dewi@sekolah.sch.id'],
            [
                'name' => 'Dewi Lestari',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa4 = Siswa::firstOrCreate(
            ['nisn' => '0061234570'],
            [
                'user_id' => $userSiswa4->id,
                'nama' => 'Dewi Lestari',
                'kelas_id' => $kelasXiRpl2->id,
                'jenis_kelamin' => 'P',
                'telepon' => '085712345674',
                'alamat' => 'Jl. Cempaka No. 20, Jakarta Timur',
            ]
        );

        // Siswa 5 (Tambahan)
        $userSiswa5 = User::firstOrCreate(
            ['email' => 'fajar@sekolah.sch.id'],
            [
                'name' => 'Fajar Nugraha',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa5 = Siswa::firstOrCreate(
            ['nisn' => '0061234571'],
            [
                'user_id' => $userSiswa5->id,
                'nama' => 'Fajar Nugraha',
                'kelas_id' => $kelasXRpl->id,
                'jenis_kelamin' => 'L',
                'telepon' => '085712345675',
                'alamat' => 'Jl. Melati No. 15, Jakarta Pusat',
            ]
        );

        // Siswa 6 (Tambahan)
        $userSiswa6 = User::firstOrCreate(
            ['email' => 'salma@sekolah.sch.id'],
            [
                'name' => 'Salma Putri Maharani',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        $siswa6 = Siswa::firstOrCreate(
            ['nisn' => '0061234572'],
            [
                'user_id' => $userSiswa6->id,
                'nama' => 'Salma Putri Maharani',
                'kelas_id' => $kelasXiiRpl->id,
                'jenis_kelamin' => 'P',
                'telepon' => '085712345676',
                'alamat' => 'Jl. Flamboyan No. 27, Jakarta Selatan',
            ]
        );

        // 6. Data Absensi Sampel (5 Hari Terakhir)
        $daftarSiswa = [$siswa1, $siswa2, $siswa3, $siswa4, $siswa5, $siswa6];

        for ($i = 4; $i >= 0; $i--) {
            $tgl = Carbon::today()->subDays($i)->format('Y-m-d');
            foreach ($daftarSiswa as $idx => $s) {
                $status = 'hadir';
                $ket = null;
                if ($i === 2 && $idx === 2) {
                    $status = 'izin';
                    $ket = 'Acara keluarga';
                } elseif ($i === 1 && $idx === 1) {
                    $status = 'sakit';
                    $ket = 'Demam flu';
                } elseif ($i === 0 && $idx === 4) {
                    $status = 'hadir';
                    $ket = null;
                } elseif ($i === 3 && $idx === 5) {
                    $status = 'izin';
                    $ket = 'Urusan administrasi lomba';
                }

                Absensi::updateOrCreate(
                    ['siswa_id' => $s->id, 'tanggal' => $tgl],
                    [
                        'kelas_id' => $s->kelas_id,
                        'status' => $status,
                        'keterangan' => $ket,
                    ]
                );
            }
        }

        // 7. Data Nilai Sampel (UH, UTS, UAS)
        $nilaiSampel = [
            // Siswa 1 (Rizky)
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UH', 'nilai' => 88.5, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UTS', 'nilai' => 90.0, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UAS', 'nilai' => 92.0, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelDb->id, 'jenis_nilai' => 'UH', 'nilai' => 85.0, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelDb->id, 'jenis_nilai' => 'UTS', 'nilai' => 87.5, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelDb->id, 'jenis_nilai' => 'UAS', 'nilai' => 86.0, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UH', 'nilai' => 80.0, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UTS', 'nilai' => 82.5, 'semester' => '1'],
            ['siswa_id' => $siswa1->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UAS', 'nilai' => 84.0, 'semester' => '1'],

            // Siswa 2 (Anisa)
            ['siswa_id' => $siswa2->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UH', 'nilai' => 92.0, 'semester' => '1'],
            ['siswa_id' => $siswa2->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UTS', 'nilai' => 95.0, 'semester' => '1'],
            ['siswa_id' => $siswa2->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UAS', 'nilai' => 96.0, 'semester' => '1'],
            ['siswa_id' => $siswa2->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UH', 'nilai' => 90.0, 'semester' => '1'],
            ['siswa_id' => $siswa2->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UTS', 'nilai' => 93.0, 'semester' => '1'],
            ['siswa_id' => $siswa2->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UAS', 'nilai' => 94.0, 'semester' => '1'],

            // Siswa 3 (Bagas)
            ['siswa_id' => $siswa3->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UH', 'nilai' => 78.0, 'semester' => '1'],
            ['siswa_id' => $siswa3->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UTS', 'nilai' => 80.0, 'semester' => '1'],
            ['siswa_id' => $siswa3->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UAS', 'nilai' => 79.5, 'semester' => '1'],

            // Siswa 5 - Fajar Nugraha (Tambahan)
            ['siswa_id' => $siswa5->id, 'mata_pelajaran_id' => $mapelBindo->id, 'jenis_nilai' => 'UH', 'nilai' => 85.0, 'semester' => '1'],
            ['siswa_id' => $siswa5->id, 'mata_pelajaran_id' => $mapelBindo->id, 'jenis_nilai' => 'UTS', 'nilai' => 88.0, 'semester' => '1'],
            ['siswa_id' => $siswa5->id, 'mata_pelajaran_id' => $mapelBindo->id, 'jenis_nilai' => 'UAS', 'nilai' => 87.5, 'semester' => '1'],
            ['siswa_id' => $siswa5->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UH', 'nilai' => 82.0, 'semester' => '1'],
            ['siswa_id' => $siswa5->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UTS', 'nilai' => 84.0, 'semester' => '1'],
            ['siswa_id' => $siswa5->id, 'mata_pelajaran_id' => $mapelMtk->id, 'jenis_nilai' => 'UAS', 'nilai' => 86.0, 'semester' => '1'],

            // Siswa 6 - Salma Putri Maharani (Tambahan)
            ['siswa_id' => $siswa6->id, 'mata_pelajaran_id' => $mapelBing->id, 'jenis_nilai' => 'UH', 'nilai' => 94.0, 'semester' => '1'],
            ['siswa_id' => $siswa6->id, 'mata_pelajaran_id' => $mapelBing->id, 'jenis_nilai' => 'UTS', 'nilai' => 95.5, 'semester' => '1'],
            ['siswa_id' => $siswa6->id, 'mata_pelajaran_id' => $mapelBing->id, 'jenis_nilai' => 'UAS', 'nilai' => 97.0, 'semester' => '1'],
            ['siswa_id' => $siswa6->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UH', 'nilai' => 90.0, 'semester' => '1'],
            ['siswa_id' => $siswa6->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UTS', 'nilai' => 92.0, 'semester' => '1'],
            ['siswa_id' => $siswa6->id, 'mata_pelajaran_id' => $mapelWeb->id, 'jenis_nilai' => 'UAS', 'nilai' => 93.5, 'semester' => '1'],
        ];

        foreach ($nilaiSampel as $n) {
            Nilai::updateOrCreate(
                [
                    'siswa_id' => $n['siswa_id'],
                    'mata_pelajaran_id' => $n['mata_pelajaran_id'],
                    'jenis_nilai' => $n['jenis_nilai'],
                    'semester' => $n['semester'],
                ],
                [
                    'nilai' => $n['nilai'],
                ]
            );
        }
    }
}
