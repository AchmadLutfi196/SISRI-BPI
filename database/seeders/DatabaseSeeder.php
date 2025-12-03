<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Unit;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\KoordinatorProdi;
use App\Models\BidangMinat;
use App\Models\Periode;
use App\Models\JadwalSidang;
use App\Models\TopikSkripsi;
use App\Models\UsulanPembimbing;
use App\Models\PendaftaranSidang;
use App\Models\PelaksanaanSidang;
use App\Models\PengujiSidang;
use App\Models\Ruangan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Roles
        $this->createRoles();
        
        // Create Units (Fakultas, Jurusan, Prodi)
        $units = $this->createUnits();
        
        // Create Users and Related Data
        $this->createAdminUser();
        $this->createMahasiswaUsers($units);
        $this->createDosenUsers($units);
        $this->createKoordinatorUser($units);
        
        // Create Bidang Minat
        $this->createBidangMinat($units);
        
        // Create Active Periode
        $this->createPeriode();
        
        // Create Jadwal Sidang
        $this->createJadwalSidang($units);
        
        // Create Sample Topik Skripsi (for testing)
        $this->createSampleTopik($units);
        
        // Create Sample Sidang yang sudah dijadwalkan (for testing nilai)
        $this->createSampleSidang($units);
        
        // Create Ruangan
        $this->createRuangan();
        
        // Create Sample Pendaftaran untuk testing Jadwal Otomatis
        $this->createSamplePendaftaranForAutoSchedule($units);
    }

    private function createRoles(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'mahasiswa']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'koordinator']);
    }

    private function createUnits(): array
    {
        // Fakultas
        $fakultas = Unit::create([
            'nama' => 'Fakultas Teknik',
            'kode' => 'FT',
            'type' => 'fakultas',
            'parent_id' => null,
        ]);

        // Jurusan
        $jurusan = Unit::create([
            'nama' => 'Jurusan Teknik Informatika',
            'kode' => 'JTI',
            'type' => 'jurusan',
            'parent_id' => $fakultas->id,
        ]);

        // Prodi
        $prodi = Unit::create([
            'nama' => 'Program Studi Teknik Informatika',
            'kode' => 'TI',
            'type' => 'prodi',
            'parent_id' => $jurusan->id,
        ]);

        $prodi2 = Unit::create([
            'nama' => 'Program Studi Sistem Informasi',
            'kode' => 'SI',
            'type' => 'prodi',
            'parent_id' => $jurusan->id,
        ]);

        return [
            'fakultas' => $fakultas,
            'jurusan' => $jurusan,
            'prodi' => $prodi,
            'prodi2' => $prodi2,
        ];
    }

    private function createAdminUser(): void
    {
        $user = User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
        $user->assignRole('admin');
    }

    private function createMahasiswaUsers(array $units): void
    {
        // Mahasiswa 1
        $user1 = User::create([
            'name' => 'Budi Santoso',
            'username' => '2021001',
            'email' => 'budi@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $user1->assignRole('mahasiswa');

        Mahasiswa::create([
            'user_id' => $user1->id,
            'nim' => '2021001',
            'nama' => 'Budi Santoso',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2021',
            'no_hp' => '081234567890',
        ]);

        // Mahasiswa 2
        $user2 = User::create([
            'name' => 'Siti Rahayu',
            'username' => '2021002',
            'email' => 'siti@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $user2->assignRole('mahasiswa');

        Mahasiswa::create([
            'user_id' => $user2->id,
            'nim' => '2021002',
            'nama' => 'Siti Rahayu',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2021',
            'no_hp' => '081234567891',
        ]);

        // Mahasiswa 3
        $user3 = User::create([
            'name' => 'Ahmad Fauzi',
            'username' => '2020001',
            'email' => 'ahmad@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $user3->assignRole('mahasiswa');

        Mahasiswa::create([
            'user_id' => $user3->id,
            'nim' => '2020001',
            'nama' => 'Ahmad Fauzi',
            'prodi_id' => $units['prodi2']->id,
            'angkatan' => '2020',
            'no_hp' => '081234567892',
        ]);
    }

    private function createDosenUsers(array $units): void
    {
        // Data dosen dari database siakad_db_nest
        // Note: Dr. Fika Hastarita Rachman sudah menjadi Koordinator, jadi tidak dimasukkan di sini
        $dosenData = [
            ['nip' => '19740610200812', 'nama' => 'Abdullah Basuki Rahmat, S.Si., M.T.', 'email' => 'abdullah.basuki@if.trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19860926201404', 'nama' => 'Ach Khozaimi, S.Kom., M.Kom.', 'email' => 'khozaimi@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19810109200604', 'nama' => 'Achmad Jauhari, S.T., M.Kom.', 'email' => 'jauhari@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19800503200312', 'nama' => 'Andharini Dwi Cahyani, S.Kom., M.Kom., Ph.D.', 'email' => 'andharini@if.trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19790222200501', 'nama' => 'Ari Kusumaningsih, S.T., M.T.', 'email' => 'ari.kusumaningsih@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19691118200112', 'nama' => 'Prof. Dr. Arif Muntasa, M.T.', 'email' => 'arifmuntasa@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19841104200812', 'nama' => 'Devie Rosa Anamisa, S.Kom., M.Kom.', 'email' => 'devros_gress@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19780309200312', 'nama' => 'Dr. Arik Kurniawati, S.Kom., M.T.', 'email' => 'arik.kurniawati@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19800325200312', 'nama' => 'Dr. Bain Khusnul Khotimah, S.T., M.Kom.', 'email' => 'bain@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19780225200501', 'nama' => 'Dr. Cucun Very Angkoso, S.T., M.T.', 'email' => 'cucunvery@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19840716200812', 'nama' => 'Dr. Eka Mala Sari Rochman, S.Kom., M.Kom.', 'email' => 'em_sari@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19780820200212', 'nama' => 'Dr. Indah Agustien Siradjuddin, S.Kom., M.Kom.', 'email' => 'indah.siradjuddin@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19790510200604', 'nama' => 'Dr. Meidya Koeshardianto, S.Si., M.T.', 'email' => 'meidya@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19780317200312', 'nama' => 'Dr. Noor Ifada, S.T., MISD.', 'email' => 'noor.ifada@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19830607200604', 'nama' => 'Dr. Rika Yunitarini, S.T., M.T.', 'email' => 'rika.yunitarini@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19800820200312', 'nama' => 'Dr. Rima Tri Wahyuningrum, S.T., M.T.', 'email' => 'rimatriwahyuningrum@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19740221200801', 'nama' => 'Dwi Kuswanto, S.Pd., M.T.', 'email' => 'dwi.kuswanto@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19891011202012', 'nama' => 'Fifin Ayu Mufarroha, S.Kom., M.Kom.', 'email' => 'fifin.mufarroha@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19760627200801', 'nama' => 'Firdaus Solihin, S.Kom., M.Kom.', 'email' => 'firdaus@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19790828200501', 'nama' => 'Hermawan, S.T., M.Kom.', 'email' => 'hermawan@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19770722200312', 'nama' => 'Husni, S.Kom., M.T.', 'email' => 'husni@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19881018201504', 'nama' => 'Ika Oktavia Suzanti, S.Kom., M.Cs.', 'email' => 'iosuzanti@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19810820200604', 'nama' => 'Iwan Santosa, S.T., M.T.', 'email' => 'iwan.santosa@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19790217200312', 'nama' => 'Kurniawan Eka Permana, S.Kom., M.Sc.', 'email' => 'kurniawan@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19770713200212', 'nama' => 'Moch. Kautsar Sophan, S.Kom., M.M.T.', 'email' => 'kautsar@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19730520200212', 'nama' => 'Mula\'ab, S.Si., M.Kom.', 'email' => 'mulaab@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19790313200604', 'nama' => 'Sigit Susanto Putro, S.Kom., M.Kom.', 'email' => 'sigit.putro@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19840413200812', 'nama' => 'Yoga Dwitya Pramudita, S.Kom., M.Cs.', 'email' => 'yoga@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19800213200604', 'nama' => 'Yonathan Ferry Hendrawan, S.T., MIT.', 'email' => 'yonathan.hendrawan@trunojoyo.ac.id', 'gender' => 'L'],
        ];

        foreach ($dosenData as $index => $data) {
            // Alternating prodi assignment (TI and SI)
            $prodiId = ($index % 2 == 0) ? $units['prodi']->id : $units['prodi2']->id;
            
            $user = User::create([
                'name' => $data['nama'],
                'username' => $data['nip'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'is_active' => true,
            ]);
            $user->assignRole('dosen');

            Dosen::create([
                'user_id' => $user->id,
                'nip' => $data['nip'],
                'nidn' => null, // NIDN not available in source data
                'nama' => $data['nama'],
                'prodi_id' => $prodiId,
                'no_hp' => null,
                'email' => $data['email'],
            ]);
        }
    }

    private function createKoordinatorUser(array $units): void
    {
        // Koordinator menggunakan salah satu dosen dari data siakad
        // Dr. Fika Hastarita Rachman, S.T., M.Eng. sebagai Koordinator Prodi TI
        $user = User::create([
            'name' => 'Dr. Fika Hastarita Rachman, S.T., M.Eng.',
            'username' => '19830305200604',
            'email' => 'fika@trunojoyo.ac.id',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'is_active' => true,
        ]);
        $user->assignRole('koordinator');

        // Create Dosen record for Koordinator
        $dosen = Dosen::create([
            'user_id' => $user->id,
            'nip' => '19830305200604',
            'nidn' => null,
            'nama' => 'Dr. Fika Hastarita Rachman, S.T., M.Eng.',
            'prodi_id' => $units['prodi']->id,
            'no_hp' => null,
            'email' => 'fika@trunojoyo.ac.id',
        ]);

        KoordinatorProdi::create([
            'dosen_id' => $dosen->id,
            'prodi_id' => $units['prodi']->id,
            'tahun_mulai' => 2023,
            'tahun_selesai' => null,
            'is_active' => true,
        ]);
    }

    private function createBidangMinat(array $units): void
    {
        $bidangMinat = [
            ['nama' => 'Artificial Intelligence', 'deskripsi' => 'Penelitian tentang kecerdasan buatan, machine learning, dan deep learning'],
            ['nama' => 'Data Science', 'deskripsi' => 'Analisis data, big data, dan statistik komputasi'],
            ['nama' => 'Software Engineering', 'deskripsi' => 'Rekayasa perangkat lunak, pengembangan aplikasi, dan metodologi pengembangan'],
            ['nama' => 'Computer Networks', 'deskripsi' => 'Jaringan komputer, keamanan jaringan, dan cloud computing'],
            ['nama' => 'Internet of Things', 'deskripsi' => 'Sistem embedded, sensor, dan konektivitas perangkat'],
            ['nama' => 'Web Development', 'deskripsi' => 'Pengembangan aplikasi web, frontend, dan backend'],
            ['nama' => 'Mobile Development', 'deskripsi' => 'Pengembangan aplikasi mobile Android dan iOS'],
            ['nama' => 'Database Systems', 'deskripsi' => 'Sistem basis data, data warehousing, dan optimasi query'],
        ];

        foreach ($bidangMinat as $bm) {
            BidangMinat::create([
                'nama' => $bm['nama'],
                'deskripsi' => $bm['deskripsi'],
                'prodi_id' => $units['prodi']->id,
            ]);
        }
    }

    private function createPeriode(): void
    {
        Periode::create([
            'nama' => 'Semester Ganjil 2024/2025',
            'tahun_akademik' => '2024/2025',
            'jenis' => 'ganjil',
            'tanggal_mulai' => '2024-09-01',
            'tanggal_selesai' => '2025-02-28',
            'is_active' => true,
        ]);

        Periode::create([
            'nama' => 'Semester Genap 2024/2025',
            'tahun_akademik' => '2024/2025',
            'jenis' => 'genap',
            'tanggal_mulai' => '2025-03-01',
            'tanggal_selesai' => '2025-08-31',
            'is_active' => false,
        ]);
    }
    
    private function createJadwalSidang(array $units): void
    {
        // Jadwal Seminar Proposal - Active Now (November 2025)
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'nama' => 'Seminar Proposal Periode November 2025',
            'jenis' => 'seminar_proposal',
            'tanggal_buka' => '2025-11-01',
            'tanggal_tutup' => '2025-11-30',
            'deskripsi' => 'Pendaftaran seminar proposal untuk mahasiswa yang sudah memenuhi syarat bimbingan',
            'is_active' => true,
        ]);
        
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'nama' => 'Seminar Proposal Periode Desember 2025',
            'jenis' => 'seminar_proposal',
            'tanggal_buka' => '2025-12-01',
            'tanggal_tutup' => '2025-12-31',
            'deskripsi' => 'Pendaftaran seminar proposal untuk periode Desember',
            'is_active' => true,
        ]);
        
        // Jadwal Sidang Skripsi - Active Now (November 2025)
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'nama' => 'Sidang Skripsi Periode November 2025',
            'jenis' => 'sidang_skripsi',
            'tanggal_buka' => '2025-11-01',
            'tanggal_tutup' => '2025-11-30',
            'deskripsi' => 'Pendaftaran sidang skripsi untuk mahasiswa yang sudah lulus seminar proposal',
            'is_active' => true,
        ]);
        
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'nama' => 'Sidang Skripsi Periode Desember 2025',
            'jenis' => 'sidang_skripsi',
            'tanggal_buka' => '2025-12-01',
            'tanggal_tutup' => '2025-12-31',
            'deskripsi' => 'Pendaftaran sidang skripsi untuk periode Desember',
            'is_active' => true,
        ]);
    }
    
    private function createSampleTopik(array $units): void
    {
        // Get mahasiswa Budi (approved topik for testing)
        $mahasiswaBudi = Mahasiswa::where('nim', '2021001')->first();
        $mahasiswaSiti = Mahasiswa::where('nim', '2021002')->first();
        
        // Get dosen (menggunakan NIP dari data siakad)
        $dosenPembimbing1 = Dosen::where('nip', '19740610200812')->first(); // Abdullah Basuki Rahmat
        $dosenPembimbing2 = Dosen::where('nip', '19860926201404')->first(); // Ach Khozaimi
        
        // Get bidang minat
        $bidangMinatAI = BidangMinat::where('nama', 'Artificial Intelligence')->first();
        $bidangMinatWeb = BidangMinat::where('nama', 'Web Development')->first();
        
        // Topik untuk Budi - Status DITERIMA (bisa akses Bimbingan & Sidang)
        $topikBudi = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaBudi->id,
            'bidang_minat_id' => $bidangMinatAI->id,
            'judul' => 'Implementasi Machine Learning untuk Prediksi Kelulusan Mahasiswa',
            'file_proposal' => null,
            'status' => 'diterima',
            'catatan' => 'Topik disetujui. Silakan lanjutkan dengan bimbingan.',
        ]);
        
        // Usulan pembimbing untuk Budi - Status DITERIMA
        UsulanPembimbing::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $dosenPembimbing1->id,
            'urutan' => 1,
            'status' => 'diterima',
            'catatan' => 'Bersedia menjadi pembimbing 1',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $dosenPembimbing2->id,
            'urutan' => 2,
            'status' => 'diterima',
            'catatan' => 'Bersedia menjadi pembimbing 2',
        ]);
        
        // Topik untuk Siti - Status MENUNGGU (untuk testing workflow approval)
        $topikSiti = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaSiti->id,
            'bidang_minat_id' => $bidangMinatWeb->id,
            'judul' => 'Pengembangan Sistem Informasi Akademik Berbasis Web dengan Laravel',
            'file_proposal' => null,
            'status' => 'menunggu',
            'catatan' => null,
        ]);
        
        // Usulan pembimbing untuk Siti - Status MENUNGGU
        UsulanPembimbing::create([
            'topik_id' => $topikSiti->id,
            'dosen_id' => $dosenPembimbing2->id,
            'urutan' => 1,
            'status' => 'menunggu',
            'catatan' => null,
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikSiti->id,
            'dosen_id' => $dosenPembimbing1->id,
            'urutan' => 2,
            'status' => 'menunggu',
            'catatan' => null,
        ]);
    }
    
    private function createSampleSidang(array $units): void
    {
        // Get mahasiswa Budi (yang sudah punya topik approved)
        $mahasiswaBudi = Mahasiswa::where('nim', '2021001')->first();
        $topikBudi = TopikSkripsi::where('mahasiswa_id', $mahasiswaBudi->id)->first();
        
        // Get dosen (menggunakan NIP dari data siakad)
        $dosenPembimbing1 = Dosen::where('nip', '19740610200812')->first(); // Abdullah Basuki Rahmat
        $dosenPembimbing2 = Dosen::where('nip', '19860926201404')->first(); // Ach Khozaimi
        $dosenPenguji = Dosen::where('nip', '19810109200604')->first(); // Achmad Jauhari
        
        // Get jadwal sidang
        $jadwalSeminar = JadwalSidang::where('jenis', 'seminar_proposal')->first();
        
        // Buat pendaftaran sidang untuk Budi - Seminar Proposal (sudah disetujui semua)
        $pendaftaranBudi = PendaftaranSidang::create([
            'topik_id' => $topikBudi->id,
            'jadwal_sidang_id' => $jadwalSeminar->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'catatan_pembimbing_1' => 'Sudah siap untuk seminar',
            'catatan_pembimbing_2' => 'Disetujui',
            'catatan_koordinator' => 'Disetujui untuk seminar proposal',
        ]);
        
        // Buat pelaksanaan sidang - Status SELESAI (untuk testing input nilai)
        $pelaksanaanBudi = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranBudi->id,
            'tanggal_sidang' => now()->subDays(3), // 3 hari yang lalu
            'tempat' => 'Ruang Sidang A - Gedung Teknik Lt. 3',
            'status' => 'selesai',
            'berita_acara' => 'Mahasiswa telah melaksanakan seminar proposal dengan baik.',
        ]);
        
        // Tambah penguji sidang (pembimbing 1 & 2 + 1 penguji)
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanBudi->id,
            'dosen_id' => $dosenPembimbing1->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(3),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanBudi->id,
            'dosen_id' => $dosenPembimbing2->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(3),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanBudi->id,
            'dosen_id' => $dosenPenguji->id,
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(3),
        ]);
    }
    
    /**
     * Create sample pendaftaran untuk testing fitur Jadwal Otomatis
     * Scenario: Mahasiswa sudah mendaftar sidang, pembimbing sudah setuju, menunggu koordinator
     */
    private function createSamplePendaftaranForAutoSchedule(array $units): void
    {
        // Get dosen
        $dosenAgus = Dosen::where('nip', '197001011995121001')->first();
        $dosenDewi = Dosen::where('nip', '198005152005012001')->first();
        
        // Get bidang minat
        $bidangMinatAI = BidangMinat::where('nama', 'Artificial Intelligence')->first();
        $bidangMinatWeb = BidangMinat::where('nama', 'Web Development')->first();
        $bidangMinatData = BidangMinat::where('nama', 'Data Science')->first();
        
        // Get jadwal sidang Desember
        $jadwalSeminarDes = JadwalSidang::where('jenis', 'seminar_proposal')
            ->where('nama', 'like', '%Desember%')
            ->first();
        
        // ========== MAHASISWA 1: Siti Rahayu (Update existing) ==========
        // Update Siti's topik menjadi diterima dan buat pendaftaran
        $mahasiswaSiti = Mahasiswa::where('nim', '2021002')->first();
        $topikSiti = TopikSkripsi::where('mahasiswa_id', $mahasiswaSiti->id)->first();
        
        // Update status topik
        $topikSiti->update([
            'status' => 'diterima',
            'catatan' => 'Topik disetujui untuk penelitian'
        ]);
        
        // Update usulan pembimbing jadi diterima
        UsulanPembimbing::where('topik_id', $topikSiti->id)->update([
            'status' => 'diterima',
            'catatan' => 'Bersedia menjadi pembimbing'
        ]);
        
        // Buat pendaftaran sidang - MENUNGGU KOORDINATOR (untuk test jadwal otomatis)
        PendaftaranSidang::create([
            'topik_id' => $topikSiti->id,
            'jadwal_sidang_id' => $jadwalSeminarDes->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'menunggu', // MENUNGGU - bisa di-jadwal otomatis!
            'catatan_pembimbing_1' => 'Sudah siap untuk seminar proposal',
            'catatan_pembimbing_2' => 'Disetujui untuk seminar',
            'catatan_koordinator' => null,
        ]);
        
        // ========== MAHASISWA 2: Dina Permata (New) ==========
        $userDina = User::create([
            'name' => 'Dina Permata',
            'username' => '2021003',
            'email' => 'dina@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userDina->assignRole('mahasiswa');

        $mahasiswaDina = Mahasiswa::create([
            'user_id' => $userDina->id,
            'nim' => '2021003',
            'nama' => 'Dina Permata',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2021',
            'no_hp' => '081234567893',
        ]);
        
        // Topik untuk Dina
        $topikDina = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaDina->id,
            'bidang_minat_id' => $bidangMinatData->id,
            'judul' => 'Analisis Big Data untuk Prediksi Penjualan E-Commerce',
            'file_proposal' => null,
            'status' => 'diterima',
            'catatan' => 'Topik diterima, silakan lanjutkan bimbingan',
        ]);
        
        // Usulan pembimbing untuk Dina
        UsulanPembimbing::create([
            'topik_id' => $topikDina->id,
            'dosen_id' => $dosenAgus->id,
            'urutan' => 1,
            'status' => 'diterima',
            'catatan' => 'Bersedia',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikDina->id,
            'dosen_id' => $dosenDewi->id,
            'urutan' => 2,
            'status' => 'diterima',
            'catatan' => 'Bersedia',
        ]);
        
        // Pendaftaran sidang Dina - MENUNGGU KOORDINATOR
        PendaftaranSidang::create([
            'topik_id' => $topikDina->id,
            'jadwal_sidang_id' => $jadwalSeminarDes->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'menunggu', // MENUNGGU - bisa di-jadwal otomatis!
            'catatan_pembimbing_1' => 'Mahasiswa sudah siap untuk seminar',
            'catatan_pembimbing_2' => 'Setuju untuk seminar proposal',
            'catatan_koordinator' => null,
        ]);
        
        // ========== MAHASISWA 3: Rizky Pratama (New) ==========
        $userRizky = User::create([
            'name' => 'Rizky Pratama',
            'username' => '2021004',
            'email' => 'rizky@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userRizky->assignRole('mahasiswa');

        $mahasiswaRizky = Mahasiswa::create([
            'user_id' => $userRizky->id,
            'nim' => '2021004',
            'nama' => 'Rizky Pratama',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2021',
            'no_hp' => '081234567894',
        ]);
        
        // Topik untuk Rizky
        $topikRizky = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaRizky->id,
            'bidang_minat_id' => $bidangMinatWeb->id,
            'judul' => 'Pengembangan Sistem Manajemen Inventaris dengan Framework Laravel',
            'file_proposal' => null,
            'status' => 'diterima',
            'catatan' => 'Topik disetujui',
        ]);
        
        // Usulan pembimbing untuk Rizky
        UsulanPembimbing::create([
            'topik_id' => $topikRizky->id,
            'dosen_id' => $dosenDewi->id,
            'urutan' => 1,
            'status' => 'diterima',
            'catatan' => 'OK',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikRizky->id,
            'dosen_id' => $dosenAgus->id,
            'urutan' => 2,
            'status' => 'diterima',
            'catatan' => 'Setuju',
        ]);
        
        // Pendaftaran sidang Rizky - MENUNGGU KOORDINATOR
        PendaftaranSidang::create([
            'topik_id' => $topikRizky->id,
            'jadwal_sidang_id' => $jadwalSeminarDes->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'menunggu', // MENUNGGU - bisa di-jadwal otomatis!
            'catatan_pembimbing_1' => 'Bisa lanjut ke seminar',
            'catatan_pembimbing_2' => 'Disetujui',
            'catatan_koordinator' => null,
        ]);
    }

    private function createRuangan(): void
    {
        $ruangans = [
            [
                'nama' => 'Ruang Sidang A',
                'lokasi' => 'Gedung Teknik Lt. 3',
                'kapasitas' => 20,
                'is_active' => true,
            ],
            [
                'nama' => 'Ruang Sidang B',
                'lokasi' => 'Gedung Teknik Lt. 3',
                'kapasitas' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
}
