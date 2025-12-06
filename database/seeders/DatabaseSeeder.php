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
use App\Models\Bimbingan;
use App\Models\BimbinganHistory;
use App\Models\Nilai;
use App\Models\RevisiSidang;
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
        
        // Create Sample Bimbingan (untuk testing fitur bimbingan)
        $this->createSampleBimbingan($units);
        
        // Create Sample Pendaftaran untuk testing Jadwal Otomatis
        $this->createSamplePendaftaranForAutoSchedule($units);
        
        // Create Complete Sidang with Nilai & Revisi (mahasiswa yang sudah selesai)
        $this->createCompleteSidangWithNilai($units);
        
        // Create Sidang untuk Koordinator Fika bisa input nilai
        $this->createSidangForKoordinatorNilai($units);
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
            ['nip' => '19740610200812', 'nama' => 'Abdullah Basuki Rahmat, S.Si., M.T.', 'email' => 'basuki@if.trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19860926201404', 'nama' => 'Ach Khozaimi, S.Kom., M.Kom.', 'email' => 'khozaimi@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19810109200604', 'nama' => 'Achmad Jauhari, S.T., M.Kom.', 'email' => 'jauhari@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19800503200312', 'nama' => 'Andharini Dwi Cahyani, S.Kom., M.Kom., Ph.D.', 'email' => 'andharini@if.trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19790222200501', 'nama' => 'Ari Kusumaningsih, S.T., M.T.', 'email' => 'ari@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19691118200112', 'nama' => 'Prof. Dr. Arif Muntasa, M.T.', 'email' => 'arif@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19841104200812', 'nama' => 'Devie Rosa Anamisa, S.Kom., M.Kom.', 'email' => 'devi@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19780309200312', 'nama' => 'Dr. Arik Kurniawati, S.Kom., M.T.', 'email' => 'arik@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19800325200312', 'nama' => 'Dr. Bain Khusnul Khotimah, S.T., M.Kom.', 'email' => 'bain@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19780225200501', 'nama' => 'Dr. Cucun Very Angkoso, S.T., M.T.', 'email' => 'cucun@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19840716200812', 'nama' => 'Dr. Eka Mala Sari Rochman, S.Kom., M.Kom.', 'email' => 'eka@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19780820200212', 'nama' => 'Dr. Indah Agustien Siradjuddin, S.Kom., M.Kom.', 'email' => 'indah@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19790510200604', 'nama' => 'Dr. Meidya Koeshardianto, S.Si., M.T.', 'email' => 'meidya@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19780317200312', 'nama' => 'Dr. Noor Ifada, S.T., MISD.', 'email' => 'ifada@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19830607200604', 'nama' => 'Dr. Rika Yunitarini, S.T., M.T.', 'email' => 'rika@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19800820200312', 'nama' => 'Dr. Rima Tri Wahyuningrum, S.T., M.T.', 'email' => 'rima@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19740221200801', 'nama' => 'Dwi Kuswanto, S.Pd., M.T.', 'email' => 'dwi@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19891011202012', 'nama' => 'Fifin Ayu Mufarroha, S.Kom., M.Kom.', 'email' => 'fifin@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19760627200801', 'nama' => 'Firdaus Solihin, S.Kom., M.Kom.', 'email' => 'firdaus@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19790828200501', 'nama' => 'Hermawan, S.T., M.Kom.', 'email' => 'hermawan@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19770722200312', 'nama' => 'Husni, S.Kom., M.T.', 'email' => 'husni@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19881018201504', 'nama' => 'Ika Oktavia Suzanti, S.Kom., M.Cs.', 'email' => 'suzan@trunojoyo.ac.id', 'gender' => 'P'],
            ['nip' => '19810820200604', 'nama' => 'Iwan Santosa, S.T., M.T.', 'email' => 'iwan@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19790217200312', 'nama' => 'Kurniawan Eka Permana, S.Kom., M.Sc.', 'email' => 'kurniawan@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19770713200212', 'nama' => 'Moch. Kautsar Sophan, S.Kom., M.M.T.', 'email' => 'kautsar@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19730520200212', 'nama' => 'Mula\'ab, S.Si., M.Kom.', 'email' => 'mulaab@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19790313200604', 'nama' => 'Sigit Susanto Putro, S.Kom., M.Kom.', 'email' => 'sigit@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19840413200812', 'nama' => 'Yoga Dwitya Pramudita, S.Kom., M.Cs.', 'email' => 'yoga@trunojoyo.ac.id', 'gender' => 'L'],
            ['nip' => '19800213200604', 'nama' => 'Yonathan Ferry Hendrawan, S.T., MIT.', 'email' => 'yonathan@trunojoyo.ac.id', 'gender' => 'L'],
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
        // Get active periode (Semester Ganjil 2024/2025)
        $periodeAktif = Periode::where('is_active', true)->first();
        
        // Jadwal Seminar Proposal - Active Now (November 2025)
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'periode_id' => $periodeAktif->id,
            'nama' => 'Seminar Proposal Periode November 2025',
            'jenis' => 'seminar_proposal',
            'tanggal_buka' => '2025-11-01',
            'tanggal_tutup' => '2025-11-30',
            'deskripsi' => 'Pendaftaran seminar proposal untuk mahasiswa yang sudah memenuhi syarat bimbingan',
            'is_active' => true,
        ]);
        
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'periode_id' => $periodeAktif->id,
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
            'periode_id' => $periodeAktif->id,
            'nama' => 'Sidang Skripsi Periode November 2025',
            'jenis' => 'sidang_skripsi',
            'tanggal_buka' => '2025-11-01',
            'tanggal_tutup' => '2025-11-30',
            'deskripsi' => 'Pendaftaran sidang skripsi untuk mahasiswa yang sudah lulus seminar proposal',
            'is_active' => true,
        ]);
        
        JadwalSidang::create([
            'prodi_id' => $units['prodi']->id,
            'periode_id' => $periodeAktif->id,
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
            'file_dokumen' => 'dokumen-sidang/sample_proposal_fajar.pdf',
            'file_dokumen_original_name' => 'Proposal_Budi_Santoso_ML.pdf',
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
        // Get dosen pembimbing dari data siakad
        $dosenList = Dosen::whereIn('nip', [
            '19740610200812', // Abdullah Basuki Rahmat
            '19860926201404', // Ach Khozaimi
            '19810109200604', // Achmad Jauhari
            '19800503200312', // Andharini Dwi Cahyani
            '19790222200501', // Ari Kusumaningsih
            '19780309200312', // Dr. Arik Kurniawati
            '19800325200312', // Dr. Bain Khusnul Khotimah
        ])->get()->keyBy('nip');
        
        // Get bidang minat
        $bidangMinatAI = BidangMinat::where('nama', 'Artificial Intelligence')->first();
        $bidangMinatWeb = BidangMinat::where('nama', 'Web Development')->first();
        $bidangMinatData = BidangMinat::where('nama', 'Data Science')->first();
        $bidangMinatSE = BidangMinat::where('nama', 'Software Engineering')->first();
        $bidangMinatIoT = BidangMinat::where('nama', 'Internet of Things')->first();
        $bidangMinatNetwork = BidangMinat::where('nama', 'Computer Networks')->first();
        $bidangMinatMobile = BidangMinat::where('nama', 'Mobile Development')->first();
        
        // Get jadwal sidang Desember
        $jadwalSeminarDes = JadwalSidang::where('jenis', 'seminar_proposal')
            ->where('nama', 'like', '%Desember%')
            ->first();
        
        // ========== UPDATE: Siti Rahayu (existing) ==========
        $mahasiswaSiti = Mahasiswa::where('nim', '2021002')->first();
        $topikSiti = TopikSkripsi::where('mahasiswa_id', $mahasiswaSiti->id)->first();
        
        $topikSiti->update([
            'status' => 'diterima',
            'catatan' => 'Topik disetujui untuk penelitian'
        ]);
        
        UsulanPembimbing::where('topik_id', $topikSiti->id)->update([
            'status' => 'diterima',
            'catatan' => 'Bersedia menjadi pembimbing'
        ]);
        
        PendaftaranSidang::create([
            'topik_id' => $topikSiti->id,
            'jadwal_sidang_id' => $jadwalSeminarDes->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'menunggu',
            'catatan_pembimbing_1' => 'Sudah siap untuk seminar proposal',
            'catatan_pembimbing_2' => 'Disetujui untuk seminar',
        ]);

        // ========== DATA 7 MAHASISWA BARU ==========
        $mahasiswaData = [
            [
                'nim' => '230411100059',
                'nama' => 'Achmad Lutfi Madhani',
                'email' => 'lutfi.madhani@sisri.test',
                'judul' => 'Pengembangan Sistem Informasi Skripsi Berbasis Web dengan Laravel',
                'bidang_minat' => $bidangMinatWeb,
                'pembimbing1_nip' => '19740610200812', // Abdullah Basuki Rahmat
                'pembimbing2_nip' => '19860926201404', // Ach Khozaimi
            ],
            [
                'nim' => '230411100049',
                'nama' => 'Maharani Putri Mindartik',
                'email' => 'maharani.putri@sisri.test',
                'judul' => 'Implementasi Deep Learning untuk Klasifikasi Citra Medis',
                'bidang_minat' => $bidangMinatAI,
                'pembimbing1_nip' => '19810109200604', // Achmad Jauhari
                'pembimbing2_nip' => '19800503200312', // Andharini Dwi Cahyani
            ],
            [
                'nim' => '230411100166',
                'nama' => 'Dicky Prasetyo',
                'email' => 'dicky.prasetyo@sisri.test',
                'judul' => 'Analisis Sentimen Media Sosial Menggunakan Natural Language Processing',
                'bidang_minat' => $bidangMinatData,
                'pembimbing1_nip' => '19800503200312', // Andharini Dwi Cahyani
                'pembimbing2_nip' => '19790222200501', // Ari Kusumaningsih
            ],
            [
                'nim' => '230411100003',
                'nama' => 'Harits Putra Junaidi',
                'email' => 'harits.putra@sisri.test',
                'judul' => 'Pengembangan Aplikasi Mobile E-Commerce dengan Flutter',
                'bidang_minat' => $bidangMinatMobile,
                'pembimbing1_nip' => '19780309200312', // Dr. Arik Kurniawati
                'pembimbing2_nip' => '19740610200812', // Abdullah Basuki Rahmat
            ],
            [
                'nim' => '230411100067',
                'nama' => 'Mohamad Askhab Firdaus',
                'email' => 'askhab.firdaus@sisri.test',
                'judul' => 'Implementasi Sistem Monitoring Jaringan Berbasis IoT',
                'bidang_minat' => $bidangMinatIoT,
                'pembimbing1_nip' => '19860926201404', // Ach Khozaimi
                'pembimbing2_nip' => '19800325200312', // Dr. Bain Khusnul Khotimah
            ],
            [
                'nim' => '230411100025',
                'nama' => 'Roni Firnanda',
                'email' => 'roni.firnanda@sisri.test',
                'judul' => 'Analisis Keamanan Jaringan Menggunakan Metode Penetration Testing',
                'bidang_minat' => $bidangMinatNetwork,
                'pembimbing1_nip' => '19800325200312', // Dr. Bain Khusnul Khotimah
                'pembimbing2_nip' => '19810109200604', // Achmad Jauhari
            ],
            [
                'nim' => '230411100104',
                'nama' => 'Moch Sigit Aringga',
                'email' => 'sigit.aringga@sisri.test',
                'judul' => 'Pengembangan Sistem Manajemen Proyek dengan Metodologi Agile',
                'bidang_minat' => $bidangMinatSE,
                'pembimbing1_nip' => '19790222200501', // Ari Kusumaningsih
                'pembimbing2_nip' => '19780309200312', // Dr. Arik Kurniawati
            ],
        ];

        foreach ($mahasiswaData as $data) {
            // Create User
            $user = User::create([
                'name' => $data['nama'],
                'username' => $data['nim'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
                'is_active' => true,
            ]);
            $user->assignRole('mahasiswa');

            // Create Mahasiswa
            $mahasiswa = Mahasiswa::create([
                'user_id' => $user->id,
                'nim' => $data['nim'],
                'nama' => $data['nama'],
                'prodi_id' => $units['prodi']->id,
                'angkatan' => '2023',
                'no_hp' => '08' . rand(1000000000, 9999999999),
            ]);

            // Create Topik Skripsi (status: diterima)
            $topik = TopikSkripsi::create([
                'mahasiswa_id' => $mahasiswa->id,
                'bidang_minat_id' => $data['bidang_minat']->id,
                'judul' => $data['judul'],
                'file_proposal' => null,
                'status' => 'diterima',
                'catatan' => 'Topik disetujui, silakan lanjutkan bimbingan',
            ]);

            // Create Usulan Pembimbing (status: diterima)
            UsulanPembimbing::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosenList[$data['pembimbing1_nip']]->id,
                'urutan' => 1,
                'status' => 'diterima',
                'catatan' => 'Bersedia menjadi pembimbing 1',
            ]);

            UsulanPembimbing::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosenList[$data['pembimbing2_nip']]->id,
                'urutan' => 2,
                'status' => 'diterima',
                'catatan' => 'Bersedia menjadi pembimbing 2',
            ]);

            // Create Pendaftaran Sidang (status_koordinator: menunggu - untuk jadwal otomatis)
            PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jadwal_sidang_id' => $jadwalSeminarDes->id,
                'jenis' => 'seminar_proposal',
                'status_pembimbing_1' => 'disetujui',
                'status_pembimbing_2' => 'disetujui',
                'status_koordinator' => 'menunggu', // MENUNGGU - siap dijadwalkan otomatis!
                'catatan_pembimbing_1' => 'Mahasiswa sudah siap untuk seminar proposal',
                'catatan_pembimbing_2' => 'Disetujui untuk melanjutkan ke seminar',
            ]);
        }
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
            [
                'nama' => 'Ruang Sidang C',
                'lokasi' => 'Gedung Teknik Lt. 2',
                'kapasitas' => 25,
                'is_active' => true,
            ],
        ];

        foreach ($ruangans as $ruangan) {
            Ruangan::create($ruangan);
        }
    }
    
    /**
     * Create sample bimbingan untuk mahasiswa Budi (yang topiknya sudah diterima)
     * Ini untuk testing fitur bimbingan mahasiswa & dosen
     */
    private function createSampleBimbingan(array $units): void
    {
        // Get mahasiswa Budi
        $mahasiswaBudi = Mahasiswa::where('nim', '2021001')->first();
        $topikBudi = TopikSkripsi::where('mahasiswa_id', $mahasiswaBudi->id)->first();
        
        // Get pembimbing dari usulan pembimbing
        $pembimbing1 = UsulanPembimbing::where('topik_id', $topikBudi->id)
            ->where('urutan', 1)
            ->first();
        $pembimbing2 = UsulanPembimbing::where('topik_id', $topikBudi->id)
            ->where('urutan', 2)
            ->first();
        
        // ========== BIMBINGAN PROPOSAL ==========
        // Bimbingan 1 - Sudah disetujui (bimbingan pertama)
        $bimbingan1 = Bimbingan::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $pembimbing1->dosen_id,
            'jenis' => 'proposal',
            'pokok_bimbingan' => 'Pembahasan BAB 1 - Latar Belakang dan Rumusan Masalah',
            'file_bimbingan' => null,
            'pesan_mahasiswa' => 'Pak, saya sudah menyusun BAB 1 sesuai template. Mohon koreksinya.',
            'pesan_dosen' => 'Latar belakang sudah cukup bagus, perbaiki rumusan masalah agar lebih spesifik.',
            'file_revisi' => null,
            'status' => 'disetujui',
            'tanggal_bimbingan' => now()->subDays(30),
            'tanggal_respon' => now()->subDays(28),
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan1->id,
            'status' => 'menunggu',
            'aksi' => 'submit',
            'catatan' => 'Mahasiswa mengajukan bimbingan',
            'oleh' => $mahasiswaBudi->user->name,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan1->id,
            'status' => 'disetujui',
            'aksi' => 'approve',
            'catatan' => 'Latar belakang sudah cukup bagus',
            'oleh' => $pembimbing1->dosen->nama,
        ]);
        
        // Bimbingan 2 - Sudah disetujui
        $bimbingan2 = Bimbingan::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $pembimbing1->dosen_id,
            'jenis' => 'proposal',
            'pokok_bimbingan' => 'Pembahasan BAB 2 - Tinjauan Pustaka dan Landasan Teori',
            'file_bimbingan' => null,
            'pesan_mahasiswa' => 'Pak, BAB 2 sudah selesai. Saya lampirkan jurnal referensi yang digunakan.',
            'pesan_dosen' => 'Tinjauan pustaka perlu diperkuat dengan referensi terbaru (5 tahun terakhir). Tambahkan minimal 5 jurnal internasional.',
            'file_revisi' => null,
            'status' => 'disetujui',
            'tanggal_bimbingan' => now()->subDays(25),
            'tanggal_respon' => now()->subDays(23),
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan2->id,
            'status' => 'menunggu',
            'aksi' => 'submit',
            'catatan' => 'Mahasiswa mengajukan bimbingan BAB 2',
            'oleh' => $mahasiswaBudi->user->name,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan2->id,
            'status' => 'direvisi',
            'aksi' => 'revise',
            'catatan' => 'Perlu tambahan referensi jurnal internasional',
            'oleh' => $pembimbing1->dosen->nama,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan2->id,
            'status' => 'disetujui',
            'aksi' => 'approve',
            'catatan' => 'Revisi sudah sesuai',
            'oleh' => $pembimbing1->dosen->nama,
        ]);
        
        // Bimbingan 3 - Dengan pembimbing 2, sudah disetujui
        $bimbingan3 = Bimbingan::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $pembimbing2->dosen_id,
            'jenis' => 'proposal',
            'pokok_bimbingan' => 'Konsultasi Metodologi Penelitian',
            'file_bimbingan' => null,
            'pesan_mahasiswa' => 'Bu, mohon arahan untuk metodologi penelitian yang sesuai dengan topik saya.',
            'pesan_dosen' => 'Gunakan metodologi CRISP-DM untuk proyek machine learning. Sertakan diagram alur penelitian.',
            'file_revisi' => null,
            'status' => 'disetujui',
            'tanggal_bimbingan' => now()->subDays(20),
            'tanggal_respon' => now()->subDays(18),
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan3->id,
            'status' => 'menunggu',
            'aksi' => 'submit',
            'catatan' => 'Konsultasi metodologi penelitian',
            'oleh' => $mahasiswaBudi->user->name,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan3->id,
            'status' => 'disetujui',
            'aksi' => 'approve',
            'catatan' => 'Metodologi CRISP-DM sudah sesuai',
            'oleh' => $pembimbing2->dosen->nama,
        ]);
        
        // Bimbingan 4 - BAB 3, sedang direvisi (belum selesai)
        $bimbingan4 = Bimbingan::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $pembimbing1->dosen_id,
            'jenis' => 'proposal',
            'pokok_bimbingan' => 'Pembahasan BAB 3 - Analisis dan Perancangan Sistem',
            'file_bimbingan' => null,
            'pesan_mahasiswa' => 'Pak, ini draft BAB 3 tentang analisis kebutuhan dan perancangan sistem.',
            'pesan_dosen' => 'Use case diagram perlu diperbaiki. Tambahkan activity diagram untuk proses utama.',
            'file_revisi' => null,
            'status' => 'direvisi',
            'tanggal_bimbingan' => now()->subDays(10),
            'tanggal_respon' => now()->subDays(8),
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan4->id,
            'status' => 'menunggu',
            'aksi' => 'submit',
            'catatan' => 'Mahasiswa mengajukan bimbingan BAB 3',
            'oleh' => $mahasiswaBudi->user->name,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan4->id,
            'status' => 'direvisi',
            'aksi' => 'revise',
            'catatan' => 'Use case diagram perlu diperbaiki',
            'oleh' => $pembimbing1->dosen->nama,
        ]);
        
        // Bimbingan 5 - Baru diajukan, status menunggu
        $bimbingan5 = Bimbingan::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $pembimbing2->dosen_id,
            'jenis' => 'proposal',
            'pokok_bimbingan' => 'Review Draft Proposal Lengkap',
            'file_bimbingan' => null,
            'pesan_mahasiswa' => 'Bu, mohon review draft proposal lengkap sebelum seminar proposal.',
            'pesan_dosen' => null,
            'file_revisi' => null,
            'status' => 'menunggu',
            'tanggal_bimbingan' => now()->subDays(2),
            'tanggal_respon' => null,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan5->id,
            'status' => 'menunggu',
            'aksi' => 'submit',
            'catatan' => 'Mahasiswa mengajukan review proposal lengkap',
            'oleh' => $mahasiswaBudi->user->name,
        ]);
        
        // ========== BIMBINGAN UNTUK SITI (mahasiswa dengan topik sudah diterima) ==========
        $mahasiswaSiti = Mahasiswa::where('nim', '2021002')->first();
        $topikSiti = TopikSkripsi::where('mahasiswa_id', $mahasiswaSiti->id)->first();
        
        // Update topik Siti jadi diterima (sebelumnya menunggu)
        $topikSiti->update(['status' => 'diterima']);
        UsulanPembimbing::where('topik_id', $topikSiti->id)->update(['status' => 'diterima']);
        
        $pembimbingSiti1 = UsulanPembimbing::where('topik_id', $topikSiti->id)->where('urutan', 1)->first();
        
        // Bimbingan untuk Siti - baru 1 kali
        $bimbinganSiti = Bimbingan::create([
            'topik_id' => $topikSiti->id,
            'dosen_id' => $pembimbingSiti1->dosen_id,
            'jenis' => 'proposal',
            'pokok_bimbingan' => 'Diskusi awal topik dan ruang lingkup penelitian',
            'file_bimbingan' => null,
            'pesan_mahasiswa' => 'Pak, saya ingin mendiskusikan ruang lingkup penelitian untuk sistem informasi akademik.',
            'pesan_dosen' => 'Fokuskan pada modul tertentu saja, misalnya modul KRS dan nilai. Jangan terlalu luas.',
            'file_revisi' => null,
            'status' => 'disetujui',
            'tanggal_bimbingan' => now()->subDays(15),
            'tanggal_respon' => now()->subDays(13),
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbinganSiti->id,
            'status' => 'menunggu',
            'aksi' => 'submit',
            'catatan' => 'Diskusi awal topik',
            'oleh' => $mahasiswaSiti->user->name,
        ]);
        
        BimbinganHistory::create([
            'bimbingan_id' => $bimbinganSiti->id,
            'status' => 'disetujui',
            'aksi' => 'approve',
            'catatan' => 'Ruang lingkup sudah jelas',
            'oleh' => $pembimbingSiti1->dosen->nama,
        ]);
    }
    
    /**
     * Create complete sidang with nilai dan revisi
     * Mahasiswa yang sudah lulus sempro dan sedang/selesai sidang skripsi
     */
    private function createCompleteSidangWithNilai(array $units): void
    {
        // Create mahasiswa baru yang sudah selesai seminar proposal (lengkap dengan nilai)
        $dosenList = Dosen::whereIn('nip', [
            '19740610200812', // Abdullah Basuki Rahmat
            '19860926201404', // Ach Khozaimi
            '19810109200604', // Achmad Jauhari
            '19800503200312', // Andharini Dwi Cahyani
            '19790222200501', // Ari Kusumaningsih
            '19691118200112', // Prof. Dr. Arif Muntasa
            '19780309200312', // Dr. Arik Kurniawati
            '19800325200312', // Dr. Bain Khusnul Khotimah
            '19780225200501', // Dr. Cucun Very Angkoso
        ])->get()->keyBy('nip');
        
        $bidangMinatAI = BidangMinat::where('nama', 'Artificial Intelligence')->first();
        $bidangMinatData = BidangMinat::where('nama', 'Data Science')->first();
        
        $jadwalSeminarNov = JadwalSidang::where('jenis', 'seminar_proposal')
            ->where('nama', 'like', '%November%')
            ->first();
        $jadwalSidangNov = JadwalSidang::where('jenis', 'sidang_skripsi')
            ->where('nama', 'like', '%November%')
            ->first();
        
        // ========== MAHASISWA 1: DEWI - Sudah lulus sempro, sedang sidang skripsi (dijadwalkan) ==========
        $userDewi = User::create([
            'name' => 'Dewi Kartika Sari',
            'username' => '2020002',
            'email' => 'dewi.kartika@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userDewi->assignRole('mahasiswa');
        
        $mahasiswaDewi = Mahasiswa::create([
            'user_id' => $userDewi->id,
            'nim' => '2020002',
            'nama' => 'Dewi Kartika Sari',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2020',
            'no_hp' => '081234567893',
        ]);
        
        $topikDewi = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaDewi->id,
            'bidang_minat_id' => $bidangMinatAI->id,
            'judul' => 'Sistem Deteksi Penyakit Tanaman Padi Menggunakan CNN',
            'status' => 'diterima',
            'catatan' => 'Topik sangat relevan dengan pertanian modern',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikDewi->id,
            'dosen_id' => $dosenList['19691118200112']->id, // Prof Arif
            'urutan' => 1,
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikDewi->id,
            'dosen_id' => $dosenList['19800503200312']->id, // Andharini
            'urutan' => 2,
            'status' => 'diterima',
        ]);
        
        // Sempro Dewi - sudah selesai
        $pendaftaranSemproDewi = PendaftaranSidang::create([
            'topik_id' => $topikDewi->id,
            'jadwal_sidang_id' => $jadwalSeminarNov->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'catatan_koordinator' => 'Dijadwalkan untuk sempro November',
            'file_dokumen' => 'dokumen-sidang/sample_proposal_dewi.pdf',
            'file_dokumen_original_name' => 'Proposal_Dewi_Kartika_CNN_Tanaman.pdf',
        ]);
        
        $pelaksanaanSemproDewi = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSemproDewi->id,
            'tanggal_sidang' => now()->subDays(20),
            'tempat' => 'Ruang Sidang A - Gedung Teknik Lt. 3',
            'status' => 'selesai',
            'berita_acara' => 'Seminar proposal berjalan lancar. Mahasiswa lulus dengan revisi minor.',
        ]);
        
        // Penguji sempro Dewi
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19691118200112']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(20),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(20),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(20),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'role' => 'penguji_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(20),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19780309200312']->id,
            'role' => 'penguji_3',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(20),
        ]);
        
        // Nilai sempro Dewi (hanya nilai ujian - 5 dosen: 2 pembimbing + 3 penguji)
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19691118200112']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 82.50,
            'catatan' => 'Presentasi bagus, perlu perbaikan di metodologi',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 80.00,
            'catatan' => 'Jawaban cukup memuaskan',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 78.50,
            'catatan' => 'Perlu pendalaman teori CNN',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 80.00,
            'catatan' => 'Metodologi penelitian sudah baik',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19780309200312']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 79.00,
            'catatan' => 'Perlu perbaikan pada BAB 3',
        ]);
        
        // Revisi sempro Dewi - sudah disetujui
        RevisiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproDewi->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'file_revisi' => null,
            'catatan' => 'Perbaiki penjelasan arsitektur CNN di BAB 2',
            'status' => 'disetujui',
            'tanggal_submit' => now()->subDays(15),
            'tanggal_validasi' => now()->subDays(13),
        ]);
        
        // Sidang skripsi Dewi - sudah dijadwalkan (belum dilaksanakan)
        $pendaftaranSidangDewi = PendaftaranSidang::create([
            'topik_id' => $topikDewi->id,
            'jadwal_sidang_id' => $jadwalSidangNov->id,
            'jenis' => 'sidang_skripsi',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'catatan_koordinator' => 'Siap untuk sidang skripsi',
            'file_dokumen' => 'dokumen-sidang/sample_proposal_dewi.pdf',
            'file_dokumen_original_name' => 'Skripsi_Dewi_Kartika_CNN_Tanaman.pdf',
        ]);
        
        $pelaksanaanSidangDewi = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSidangDewi->id,
            'tanggal_sidang' => now()->addDays(5), // 5 hari lagi
            'tempat' => 'Ruang Sidang B - Gedung Teknik Lt. 3',
            'status' => 'dijadwalkan',
            'berita_acara' => null,
        ]);
        
        // Penguji sidang Dewi
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangDewi->id,
            'dosen_id' => $dosenList['19691118200112']->id,
            'role' => 'pembimbing_1',
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangDewi->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'role' => 'pembimbing_2',
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangDewi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'role' => 'penguji_1',
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangDewi->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'role' => 'penguji_2',
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangDewi->id,
            'dosen_id' => $dosenList['19780309200312']->id,
            'role' => 'penguji_3',
        ]);
        
        // ========== MAHASISWA 2: ANDI - Sudah LULUS semua (sempro + sidang skripsi) ==========
        $userAndi = User::create([
            'name' => 'Andi Pratama Putra',
            'username' => '2019001',
            'email' => 'andi.pratama@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userAndi->assignRole('mahasiswa');
        
        $mahasiswaAndi = Mahasiswa::create([
            'user_id' => $userAndi->id,
            'nim' => '2019001',
            'nama' => 'Andi Pratama Putra',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2019',
            'no_hp' => '081234567894',
        ]);
        
        $topikAndi = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaAndi->id,
            'bidang_minat_id' => $bidangMinatData->id,
            'judul' => 'Analisis Sentimen Review Produk E-Commerce Menggunakan LSTM',
            'status' => 'diterima',
            'catatan' => 'Topik bagus dan relevan',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikAndi->id,
            'dosen_id' => $dosenList['19810109200604']->id, // Achmad Jauhari
            'urutan' => 1,
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikAndi->id,
            'dosen_id' => $dosenList['19790222200501']->id, // Ari Kusumaningsih
            'urutan' => 2,
            'status' => 'diterima',
        ]);
        
        // Sempro Andi - sudah selesai
        $pendaftaranSemproAndi = PendaftaranSidang::create([
            'topik_id' => $topikAndi->id,
            'jadwal_sidang_id' => $jadwalSeminarNov->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'file_dokumen' => 'dokumen-sidang/sample_proposal_andi.pdf',
            'file_dokumen_original_name' => 'Proposal_Andi_LSTM_Sentimen.pdf',
        ]);
        
        $pelaksanaanSemproAndi = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSemproAndi->id,
            'tanggal_sidang' => now()->subDays(45),
            'tempat' => 'Ruang Sidang C - Gedung Teknik Lt. 2',
            'status' => 'selesai',
            'berita_acara' => 'Lulus dengan revisi minor',
        ]);
        
        // Penguji sempro Andi
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(45),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19790222200501']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(45),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(45),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19800325200312']->id,
            'role' => 'penguji_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(45),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19780225200501']->id,
            'role' => 'penguji_3',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(45),
        ]);
        
        // Nilai sempro Andi (hanya nilai ujian - 5 dosen: 2 pembimbing + 3 penguji)
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 85.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19790222200501']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 84.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 82.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19800325200312']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 83.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19780225200501']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 81.00,
        ]);
        
        // Revisi sempro Andi - sudah disetujui
        RevisiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproAndi->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'catatan' => 'Perbaiki preprocessing data',
            'status' => 'disetujui',
            'tanggal_submit' => now()->subDays(40),
            'tanggal_validasi' => now()->subDays(38),
        ]);
        
        // Sidang skripsi Andi - SELESAI (sudah lulus!)
        $pendaftaranSidangAndi = PendaftaranSidang::create([
            'topik_id' => $topikAndi->id,
            'jadwal_sidang_id' => $jadwalSidangNov->id,
            'jenis' => 'sidang_skripsi',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'file_dokumen' => 'dokumen-sidang/sample_proposal_andi.pdf',
            'file_dokumen_original_name' => 'Skripsi_Andi_LSTM_Sentimen.pdf',
        ]);
        
        $pelaksanaanSidangAndi = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSidangAndi->id,
            'tanggal_sidang' => now()->subDays(10),
            'tempat' => 'Ruang Sidang A - Gedung Teknik Lt. 3',
            'status' => 'selesai',
            'berita_acara' => 'Mahasiswa dinyatakan LULUS dengan predikat Sangat Memuaskan.',
        ]);
        
        // Penguji sidang Andi
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(10),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19790222200501']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(10),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19691118200112']->id, // Prof Arif sebagai penguji
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(10),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'role' => 'penguji_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(10),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19800325200312']->id,
            'role' => 'penguji_3',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(10),
        ]);
        
        // Nilai sidang skripsi Andi (hanya nilai ujian - 5 dosen: 2 pembimbing + 3 penguji)
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 88.00,
            'catatan' => 'Penguasaan materi sangat baik',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19790222200501']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 86.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19691118200112']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 85.00,
            'catatan' => 'Implementasi LSTM sangat baik',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 84.00,
            'catatan' => 'Analisis data sudah baik',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19800325200312']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 83.00,
            'catatan' => 'Kesimpulan dan saran sudah tepat',
        ]);
        
        // Revisi sidang Andi - sudah disetujui
        RevisiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19691118200112']->id,
            'catatan' => 'Perbaiki format penulisan daftar pustaka sesuai IEEE',
            'status' => 'disetujui',
            'tanggal_submit' => now()->subDays(7),
            'tanggal_validasi' => now()->subDays(5),
        ]);
        
        RevisiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangAndi->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'catatan' => 'Tambahkan analisis confusion matrix di hasil pengujian',
            'status' => 'disetujui',
            'tanggal_submit' => now()->subDays(7),
            'tanggal_validasi' => now()->subDays(4),
        ]);
        
        // ========== MAHASISWA 3: RINA - Sempro selesai, revisi belum selesai ==========
        $userRina = User::create([
            'name' => 'Rina Wulandari',
            'username' => '2020003',
            'email' => 'rina.wulandari@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userRina->assignRole('mahasiswa');
        
        $mahasiswaRina = Mahasiswa::create([
            'user_id' => $userRina->id,
            'nim' => '2020003',
            'nama' => 'Rina Wulandari',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2020',
            'no_hp' => '081234567895',
        ]);
        
        $topikRina = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaRina->id,
            'bidang_minat_id' => $bidangMinatAI->id,
            'judul' => 'Sistem Rekomendasi Buku Perpustakaan dengan Collaborative Filtering',
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikRina->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'urutan' => 1,
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikRina->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'urutan' => 2,
            'status' => 'diterima',
        ]);
        
        // Sempro Rina - selesai tapi revisi belum tuntas
        $pendaftaranSemproRina = PendaftaranSidang::create([
            'topik_id' => $topikRina->id,
            'jadwal_sidang_id' => $jadwalSeminarNov->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'file_dokumen' => 'dokumen-sidang/sample_proposal_rina.pdf',
            'file_dokumen_original_name' => 'Proposal_Rina_Algoritma_Genetika.pdf',
        ]);
        
        $pelaksanaanSemproRina = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSemproRina->id,
            'tanggal_sidang' => now()->subDays(7),
            'tempat' => 'Ruang Sidang B - Gedung Teknik Lt. 3',
            'status' => 'selesai',
            'berita_acara' => 'Lulus dengan beberapa revisi yang harus diselesaikan',
        ]);
        
        // Penguji sempro Rina
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(7),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(7),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(7),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'role' => 'penguji_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(7),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19780225200501']->id,
            'role' => 'penguji_3',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(7),
        ]);
        
        // Nilai sempro Rina
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 80.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 78.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 79.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 77.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 76.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19810109200604']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 75.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19780225200501']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 74.00,
        ]);
        
        // Revisi sempro Rina - satu sudah disetujui, satu masih menunggu
        RevisiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'catatan' => 'Perbaiki diagram use case',
            'status' => 'disetujui',
            'tanggal_submit' => now()->subDays(4),
            'tanggal_validasi' => now()->subDays(2),
        ]);
        
        RevisiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproRina->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'catatan' => 'Jelaskan lebih detail algoritma collaborative filtering yang digunakan',
            'status' => 'menunggu', // Belum submit revisi
            'tanggal_submit' => now()->subDays(7),
            'tanggal_validasi' => null,
        ]);
        
        // Bimbingan untuk beberapa mahasiswa baru ini
        // Bimbingan Dewi
        Bimbingan::create([
            'topik_id' => $topikDewi->id,
            'dosen_id' => $dosenList['19691118200112']->id,
            'jenis' => 'skripsi',
            'pokok_bimbingan' => 'Review BAB 4 - Implementasi CNN untuk deteksi penyakit padi',
            'pesan_mahasiswa' => 'Pak, ini hasil implementasi CNN dengan accuracy 92%',
            'pesan_dosen' => 'Bagus! Coba tambahkan augmentasi data untuk meningkatkan akurasi',
            'status' => 'disetujui',
            'tanggal_bimbingan' => now()->subDays(12),
            'tanggal_respon' => now()->subDays(10),
        ]);
        
        Bimbingan::create([
            'topik_id' => $topikDewi->id,
            'dosen_id' => $dosenList['19800503200312']->id,
            'jenis' => 'skripsi',
            'pokok_bimbingan' => 'Konsultasi BAB 5 - Kesimpulan dan Saran',
            'pesan_mahasiswa' => 'Bu, mohon review kesimpulan penelitian saya',
            'status' => 'menunggu',
            'tanggal_bimbingan' => now()->subDays(1),
        ]);
    }
    
    /**
     * Create sidang dimana Dr. Fika (Koordinator) menjadi penguji
     * Sehingga Fika bisa login dan input nilai
     */
    private function createSidangForKoordinatorNilai(array $units): void
    {
        // Get Dr. Fika sebagai dosen
        $dosenFika = Dosen::where('nip', '19830305200604')->first();
        
        // Get dosen lain untuk pembimbing
        $dosenList = Dosen::whereIn('nip', [
            '19740610200812', // Abdullah Basuki Rahmat
            '19860926201404', // Ach Khozaimi
        ])->get()->keyBy('nip');
        
        $bidangMinatAI = BidangMinat::where('nama', 'Artificial Intelligence')->first();
        $bidangMinatWeb = BidangMinat::where('nama', 'Web Development')->first();
        
        $jadwalSeminarNov = JadwalSidang::where('jenis', 'seminar_proposal')
            ->where('nama', 'like', '%November%')
            ->first();
        $jadwalSidangNov = JadwalSidang::where('jenis', 'sidang_skripsi')
            ->where('nama', 'like', '%November%')
            ->first();
        
        // ========== MAHASISWA 1: FAJAR - Sempro selesai, Fika sebagai penguji (belum input nilai) ==========
        $userFajar = User::create([
            'name' => 'Fajar Nugroho',
            'username' => '2020004',
            'email' => 'fajar.nugroho@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userFajar->assignRole('mahasiswa');
        
        $mahasiswaFajar = Mahasiswa::create([
            'user_id' => $userFajar->id,
            'nim' => '2020004',
            'nama' => 'Fajar Nugroho',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2020',
            'no_hp' => '081234567896',
        ]);
        
        $topikFajar = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaFajar->id,
            'bidang_minat_id' => $bidangMinatAI->id,
            'judul' => 'Implementasi Chatbot Berbasis NLP untuk Layanan Akademik',
            'status' => 'diterima',
            'catatan' => 'Topik disetujui oleh koordinator',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikFajar->id,
            'dosen_id' => $dosenList['19740610200812']->id, // Abdullah
            'urutan' => 1,
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikFajar->id,
            'dosen_id' => $dosenList['19860926201404']->id, // Khozaimi
            'urutan' => 2,
            'status' => 'diterima',
        ]);
        
        // Sempro Fajar - selesai, FIKA sebagai penguji, BELUM ADA NILAI
        $pendaftaranSemproFajar = PendaftaranSidang::create([
            'topik_id' => $topikFajar->id,
            'jadwal_sidang_id' => $jadwalSeminarNov->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
            'catatan_koordinator' => 'Dijadwalkan untuk sempro',
        ]);
        
        $pelaksanaanSemproFajar = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSemproFajar->id,
            'tanggal_sidang' => now()->subDays(2), // 2 hari lalu
            'tempat' => 'Ruang Sidang A - Gedung Teknik Lt. 3',
            'status' => 'selesai',
            'berita_acara' => 'Seminar proposal telah dilaksanakan dengan baik.',
        ]);
        
        // Penguji sempro Fajar - FIKA sebagai penguji_1
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(2),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(2),
        ]);
        
        // FIKA sebagai penguji - BELUM input nilai!
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenFika->id,
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(2),
        ]);
        
        // Pembimbing sudah input nilai, tapi Fika (penguji) BELUM
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 82.00,
            'catatan' => 'Bimbingan baik dan konsisten',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 80.00,
            'catatan' => 'Presentasi cukup baik',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 81.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproFajar->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 79.00,
        ]);
        
        // FIKA BELUM INPUT NILAI - ini yang akan diinput oleh Fika
        
        // ========== MAHASISWA 2: MAYA - Sidang skripsi selesai, Fika penguji, belum input nilai ==========
        $userMaya = User::create([
            'name' => 'Maya Anggraini',
            'username' => '2019002',
            'email' => 'maya.anggraini@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'is_active' => true,
        ]);
        $userMaya->assignRole('mahasiswa');
        
        $mahasiswaMaya = Mahasiswa::create([
            'user_id' => $userMaya->id,
            'nim' => '2019002',
            'nama' => 'Maya Anggraini',
            'prodi_id' => $units['prodi']->id,
            'angkatan' => '2019',
            'no_hp' => '081234567897',
        ]);
        
        $topikMaya = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswaMaya->id,
            'bidang_minat_id' => $bidangMinatWeb->id,
            'judul' => 'Pengembangan E-Learning Adaptif dengan Metode Item Response Theory',
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id, // Khozaimi
            'urutan' => 1,
            'status' => 'diterima',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id, // Abdullah
            'urutan' => 2,
            'status' => 'diterima',
        ]);
        
        // Sempro Maya - sudah selesai
        $pendaftaranSemproMaya = PendaftaranSidang::create([
            'topik_id' => $topikMaya->id,
            'jadwal_sidang_id' => $jadwalSeminarNov->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
        ]);
        
        $pelaksanaanSemproMaya = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSemproMaya->id,
            'tanggal_sidang' => now()->subDays(30),
            'tempat' => 'Ruang Sidang B - Gedung Teknik Lt. 3',
            'status' => 'selesai',
            'berita_acara' => 'Lulus sempro',
        ]);
        
        // Penguji sempro Maya (sudah selesai semua)
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
        ]);
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
        ]);
        
        // Nilai sempro Maya (sudah lengkap)
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 85.00,
        ]);
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 83.00,
        ]);
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 84.00,
        ]);
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSemproMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 82.00,
        ]);
        
        // Sidang Skripsi Maya - selesai, FIKA sebagai penguji, BELUM input nilai
        $pendaftaranSidangMaya = PendaftaranSidang::create([
            'topik_id' => $topikMaya->id,
            'jadwal_sidang_id' => $jadwalSidangNov->id,
            'jenis' => 'sidang_skripsi',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
        ]);
        
        $pelaksanaanSidangMaya = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSidangMaya->id,
            'tanggal_sidang' => now()->subDays(1), // Kemarin
            'tempat' => 'Ruang Sidang C - Gedung Teknik Lt. 2',
            'status' => 'selesai',
            'berita_acara' => 'Sidang skripsi telah dilaksanakan. Menunggu input nilai.',
        ]);
        
        // Penguji sidang Maya - FIKA sebagai penguji
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'role' => 'pembimbing_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(1),
        ]);
        
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'role' => 'pembimbing_2',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(1),
        ]);
        
        // FIKA sebagai penguji sidang skripsi Maya
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenFika->id,
            'role' => 'penguji_1',
            'ttd_berita_acara' => true,
            'tanggal_ttd' => now()->subDays(1),
        ]);
        
        // Pembimbing sudah input nilai
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 88.00,
            'catatan' => 'Bimbingan sangat baik dari awal sampai akhir',
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenList['19860926201404']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 86.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'bimbingan',
            'nilai' => 87.00,
        ]);
        
        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaanSidangMaya->id,
            'dosen_id' => $dosenList['19740610200812']->id,
            'jenis_nilai' => 'ujian',
            'nilai' => 85.00,
        ]);
        
        // FIKA BELUM INPUT NILAI untuk sidang Maya - ini yang akan diinput
    }
}
