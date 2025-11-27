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
        // Dosen 1
        $user1 = User::create([
            'name' => 'Dr. Agus Wijaya, M.Kom.',
            'username' => '197001011995121001',
            'email' => 'agus@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);
        $user1->assignRole('dosen');

        Dosen::create([
            'user_id' => $user1->id,
            'nip' => '197001011995121001',
            'nidn' => '0001017001',
            'nama' => 'Dr. Agus Wijaya, M.Kom.',
            'prodi_id' => $units['prodi']->id,
            'no_hp' => '081111111111',
            'email' => 'agus@sisri.test',
        ]);

        // Dosen 2
        $user2 = User::create([
            'name' => 'Dr. Dewi Lestari, M.T.',
            'username' => '198005152005012001',
            'email' => 'dewi@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);
        $user2->assignRole('dosen');

        Dosen::create([
            'user_id' => $user2->id,
            'nip' => '198005152005012001',
            'nidn' => '0015058001',
            'nama' => 'Dr. Dewi Lestari, M.T.',
            'prodi_id' => $units['prodi']->id,
            'no_hp' => '081222222222',
            'email' => 'dewi@sisri.test',
        ]);

        // Dosen 3
        $user3 = User::create([
            'name' => 'Ir. Hendra Kusuma, M.Cs.',
            'username' => '196808081994031002',
            'email' => 'hendra@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'dosen',
            'is_active' => true,
        ]);
        $user3->assignRole('dosen');

        Dosen::create([
            'user_id' => $user3->id,
            'nip' => '196808081994031002',
            'nidn' => '0008086801',
            'nama' => 'Ir. Hendra Kusuma, M.Cs.',
            'prodi_id' => $units['prodi2']->id,
            'no_hp' => '081333333333',
            'email' => 'hendra@sisri.test',
        ]);
    }

    private function createKoordinatorUser(array $units): void
    {
        $user = User::create([
            'name' => 'Dr. Rina Marlina, M.Kom.',
            'username' => '197503201999032001',
            'email' => 'rina@sisri.test',
            'password' => Hash::make('password'),
            'role' => 'koordinator',
            'is_active' => true,
        ]);
        $user->assignRole('koordinator');

        // Create Dosen record for Koordinator
        $dosen = Dosen::create([
            'user_id' => $user->id,
            'nip' => '197503201999032001',
            'nidn' => '0020037501',
            'nama' => 'Dr. Rina Marlina, M.Kom.',
            'prodi_id' => $units['prodi']->id,
            'no_hp' => '081444444444',
            'email' => 'rina@sisri.test',
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
        
        // Get dosen
        $dosenAgus = Dosen::where('nip', '197001011995121001')->first();
        $dosenDewi = Dosen::where('nip', '198005152005012001')->first();
        
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
            'dosen_id' => $dosenAgus->id,
            'urutan' => 1,
            'status' => 'diterima',
            'catatan' => 'Bersedia menjadi pembimbing 1',
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikBudi->id,
            'dosen_id' => $dosenDewi->id,
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
            'dosen_id' => $dosenDewi->id,
            'urutan' => 1,
            'status' => 'menunggu',
            'catatan' => null,
        ]);
        
        UsulanPembimbing::create([
            'topik_id' => $topikSiti->id,
            'dosen_id' => $dosenAgus->id,
            'urutan' => 2,
            'status' => 'menunggu',
            'catatan' => null,
        ]);
    }
    
//     private function createSampleSidang(array $units): void
//     {
//         // Get mahasiswa Budi (yang sudah punya topik approved)
//         $mahasiswaBudi = Mahasiswa::where('nim', '2021001')->first();
//         $topikBudi = TopikSkripsi::where('mahasiswa_id', $mahasiswaBudi->id)->first();
        
//         // Get dosen
//         $dosenAgus = Dosen::where('nip', '197001011995121001')->first();
//         $dosenDewi = Dosen::where('nip', '198005152005012001')->first();
//         $dosenHendra = Dosen::where('nip', '196808081994031002')->first();
        
//         // Get jadwal sidang
//         $jadwalSeminar = JadwalSidang::where('jenis', 'seminar_proposal')->first();
        
//         // Buat pendaftaran sidang untuk Budi - Seminar Proposal (sudah disetujui semua)
//         $pendaftaranBudi = PendaftaranSidang::create([
//             'topik_id' => $topikBudi->id,
//             'jadwal_sidang_id' => $jadwalSeminar->id,
//             'jenis' => 'seminar_proposal',
//             'status_pembimbing_1' => 'disetujui',
//             'status_pembimbing_2' => 'disetujui',
//             'status_koordinator' => 'disetujui',
//             'catatan_pembimbing_1' => 'Sudah siap untuk seminar',
//             'catatan_pembimbing_2' => 'Disetujui',
//             'catatan_koordinator' => 'Disetujui untuk seminar proposal',
//         ]);
        
//         // Buat pelaksanaan sidang - Status SELESAI (untuk testing input nilai)
//         $pelaksanaanBudi = PelaksanaanSidang::create([
//             'pendaftaran_sidang_id' => $pendaftaranBudi->id,
//             'tanggal_sidang' => now()->subDays(3), // 3 hari yang lalu
//             'tempat' => 'Ruang Sidang A - Gedung Teknik Lt. 3',
//             'status' => 'selesai',
//             'berita_acara' => 'Mahasiswa telah melaksanakan seminar proposal dengan baik.',
//         ]);
        
//         // Tambah penguji sidang (pembimbing 1 & 2 + 1 penguji)
//         PengujiSidang::create([
//             'pelaksanaan_sidang_id' => $pelaksanaanBudi->id,
//             'dosen_id' => $dosenAgus->id,
//             'role' => 'pembimbing_1',
//             'ttd_berita_acara' => true,
//             'tanggal_ttd' => now()->subDays(3),
//         ]);
        
//         PengujiSidang::create([
//             'pelaksanaan_sidang_id' => $pelaksanaanBudi->id,
//             'dosen_id' => $dosenDewi->id,
//             'role' => 'pembimbing_2',
//             'ttd_berita_acara' => true,
//             'tanggal_ttd' => now()->subDays(3),
//         ]);
        
//         PengujiSidang::create([
//             'pelaksanaan_sidang_id' => $pelaksanaanBudi->id,
//             'dosen_id' => $dosenHendra->id,
//             'role' => 'penguji_1',
//             'ttd_berita_acara' => true,
//             'tanggal_ttd' => now()->subDays(3),
//         ]);
//     }
// }
