<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\TopikSkripsi;
use App\Models\UsulanPembimbing;
use App\Models\Bimbingan;
use App\Models\PendaftaranSidang;
use App\Models\PelaksanaanSidang;
use App\Models\JadwalSidang;
use App\Models\Ruangan;
use App\Models\BidangMinat;
use App\Models\Periode;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "=== SCRIPT PENGISIAN DATA LENGKAP UNTUK KUOTA PENUH ===\n\n";

try {
    DB::beginTransaction();
    
    // 1. Cari dosen Abdullah Basuki Rahmat
    $dosen = Dosen::where('nama', 'like', '%Abdullah Basuki Rahmat%')->first();
    
    if (!$dosen) {
        echo "❌ Dosen Abdullah Basuki Rahmat tidak ditemukan!\n";
        exit;
    }
    
    echo "✅ Dosen ditemukan: {$dosen->nama}\n";
    echo "   Kuota Pembimbing 1: {$dosen->kuota_pembimbing_1}\n";
    echo "   Kuota Pembimbing 2: {$dosen->kuota_pembimbing_2}\n\n";
    
    // 2. Cari bidang minat dan ruangan
    $bidangMinat = BidangMinat::where('prodi_id', $dosen->prodi_id)->where('is_active', true)->first();
    $ruangan = Ruangan::where('is_active', true)->inRandomOrder()->first();
    $periode = Periode::where('is_active', true)->first();
    
    if (!$bidangMinat || !$ruangan || !$periode) {
        echo "❌ Data pendukung tidak lengkap!\n";
        exit;
    }
    
    // 3. Hitung kuota
    $totalKuotaPembimbing1 = $dosen->kuota_pembimbing_1;
    $totalKuotaPembimbing2 = $dosen->kuota_pembimbing_2;
    
    echo "📊 Akan membuat:\n";
    echo "   - Pembimbing 1: {$totalKuotaPembimbing1} mahasiswa\n";
    echo "   - Pembimbing 2: {$totalKuotaPembimbing2} mahasiswa\n\n";
    
    $createdCount = 0;
    
    // ================ PEMBIMBING 1 ================
    echo "🔄 Membuat mahasiswa dengan dosen sebagai Pembimbing 1...\n";
    for ($i = 1; $i <= $totalKuotaPembimbing1; $i++) {
        $nim = '119140' . str_pad($i + 100, 3, '0', STR_PAD_LEFT);
        
        if (Mahasiswa::where('nim', $nim)->exists()) {
            echo "   ⚠️  {$nim} sudah ada, skip...\n";
            continue;
        }
        
        // Buat user dan mahasiswa
        $user = User::create([
            'name' => "Mahasiswa Bimbingan 1 - {$i}",
            'username' => "mhs_bimbing1_{$i}",
            'email' => "mhs.bimbing1.{$i}@example.com",
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);
        
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $nim,
            'nama' => "Mahasiswa Bimbingan 1 - {$i}",
            'prodi_id' => $dosen->prodi_id,
            'angkatan' => 2021 + ($i % 3),
            'email' => "mhs.bimbing1.{$i}@example.com",
            'no_hp' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
        ]);
        
        // Buat topik skripsi
        $topik = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'bidang_minat_id' => $bidangMinat->id,
            'judul' => "Implementasi Sistem Informasi untuk Mahasiswa {$mahasiswa->nama}",
            'status' => 'diterima',
        ]);
        
        // Buat usulan pembimbing
        UsulanPembimbing::create([
            'topik_id' => $topik->id,
            'dosen_id' => $dosen->id,
            'urutan' => 1,
            'status' => 'diterima',
            'tanggal_respon' => now(),
        ]);
        
        $dosenLain = Dosen::where('id', '!=', $dosen->id)
            ->where('prodi_id', $dosen->prodi_id)
            ->inRandomOrder()
            ->first();
        
        if ($dosenLain) {
            UsulanPembimbing::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosenLain->id,
                'urutan' => 2,
                'status' => 'diterima',
                'tanggal_respon' => now(),
            ]);
        }
        
        // Buat bimbingan
        $totalBimbingan = rand(3, 5);
        for ($j = 1; $j <= $totalBimbingan; $j++) {
            Bimbingan::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosen->id,
                'jenis' => ($j <= 2) ? 'proposal' : 'skripsi',
                'bimbingan_ke' => $j,
                'pokok_bimbingan' => "Pembahasan BAB {$j}",
                'pesan_mahasiswa' => "Mohon review BAB {$j}",
                'pesan_dosen' => "Sudah bagus, lanjutkan",
                'status' => 'disetujui',
                'tanggal_bimbingan' => now()->subDays(30 - ($j * 5)),
                'tanggal_respon' => now()->subDays(29 - ($j * 5)),
            ]);
        }
        
        // Buat jadwal dan pendaftaran seminar proposal
        $jadwalSempro = JadwalSidang::create([
            'ruangan_id' => $ruangan->id,
            'periode_id' => $periode->id,
            'tanggal' => now()->subDays(rand(20, 40))->format('Y-m-d'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
        ]);
        
        $pendaftaranSempro = PendaftaranSidang::create([
            'topik_id' => $topik->id,
            'jadwal_sidang_id' => $jadwalSempro->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
        ]);
        
        PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSempro->id,
            'tanggal_sidang' => now()->subDays(rand(20, 40)),
            'tempat' => 'Ruang Seminar A',
            'status' => 'selesai',
        ]);
        
        // 70% masih proses, 30% sudah sidang tapi tidak lulus
        if ($i <= $totalKuotaPembimbing1 * 0.7) {
            // Masih proses - buat jadwal tapi belum sidang atau masih menunggu
            if ($i % 2 == 0) {
                // Masih menunggu persetujuan
                $jadwalSidang = JadwalSidang::create([
                    'ruangan_id' => $ruangan->id,
                    'periode_id' => $periode->id,
                    'tanggal' => now()->addDays(rand(7, 21))->format('Y-m-d'),
                    'waktu_mulai' => '13:00',
                    'waktu_selesai' => '15:00',
                ]);
                
                PendaftaranSidang::create([
                    'topik_id' => $topik->id,
                    'jadwal_sidang_id' => $jadwalSidang->id,
                    'jenis' => 'sidang_skripsi',
                    'status_pembimbing_1' => 'menunggu',
                    'status_pembimbing_2' => 'menunggu',
                    'status_koordinator' => 'menunggu',
                ]);
            } else {
                // Sudah dijadwalkan
                $jadwalSidang = JadwalSidang::create([
                    'ruangan_id' => $ruangan->id,
                    'periode_id' => $periode->id,
                    'tanggal' => now()->addDays(rand(1, 14))->format('Y-m-d'),
                    'waktu_mulai' => '13:00',
                    'waktu_selesai' => '15:00',
                ]);
                
                $pendaftaranSidang = PendaftaranSidang::create([
                    'topik_id' => $topik->id,
                    'jadwal_sidang_id' => $jadwalSidang->id,
                    'jenis' => 'sidang_skripsi',
                    'status_pembimbing_1' => 'disetujui',
                    'status_pembimbing_2' => 'disetujui',
                    'status_koordinator' => 'disetujui',
                ]);
                
                PelaksanaanSidang::create([
                    'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                    'tanggal_sidang' => now()->addDays(rand(1, 14)),
                    'tempat' => 'Ruang Sidang A',
                    'status' => 'dijadwalkan',
                ]);
            }
        } else {
            // Sudah sidang tapi TIDAK LULUS
            $jadwalSidang = JadwalSidang::create([
                'ruangan_id' => $ruangan->id,
                'periode_id' => $periode->id,
                'tanggal' => now()->subDays(rand(1, 15))->format('Y-m-d'),
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '15:00',
            ]);
            
            $pendaftaranSidang = PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jadwal_sidang_id' => $jadwalSidang->id,
                'jenis' => 'sidang_skripsi',
                'status_pembimbing_1' => 'disetujui',
                'status_pembimbing_2' => 'disetujui',
                'status_koordinator' => 'disetujui',
            ]);
            
            $pelaksanaanSidang = PelaksanaanSidang::create([
                'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                'tanggal_sidang' => now()->subDays(rand(1, 15)),
                'tempat' => 'Ruang Sidang A',
                'status' => 'selesai',
            ]);
            
            // Buat nilai TIDAK LULUS (nilai < 55)
            \App\Models\Nilai::create([
                'pelaksanaan_sidang_id' => $pelaksanaanSidang->id,
                'dosen_id' => $dosen->id,
                'jenis_nilai' => 'ujian',
                'nilai' => rand(30, 50),
            ]);
        }
        
        $createdCount++;
        echo "   ✅ {$nim} - {$mahasiswa->nama}\n";
    }
    
    // ================ PEMBIMBING 2 ================
    echo "\n🔄 Membuat mahasiswa dengan dosen sebagai Pembimbing 2...\n";
    for ($i = 1; $i <= $totalKuotaPembimbing2; $i++) {
        $nim = '119140' . str_pad($i + 200, 3, '0', STR_PAD_LEFT);
        
        if (Mahasiswa::where('nim', $nim)->exists()) {
            echo "   ⚠️  {$nim} sudah ada, skip...\n";
            continue;
        }
        
        // Buat user dan mahasiswa
        $user = User::create([
            'name' => "Mahasiswa Bimbingan 2 - {$i}",
            'username' => "mhs_bimbing2_{$i}",
            'email' => "mhs.bimbing2.{$i}@example.com",
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);
        
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $nim,
            'nama' => "Mahasiswa Bimbingan 2 - {$i}",
            'prodi_id' => $dosen->prodi_id,
            'angkatan' => 2021 + ($i % 3),
            'email' => "mhs.bimbing2.{$i}@example.com",
            'no_hp' => '08123457' . str_pad($i, 4, '0', STR_PAD_LEFT),
        ]);
        
        // Buat topik skripsi
        $topik = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'bidang_minat_id' => $bidangMinat->id,
            'judul' => "Pengembangan Aplikasi untuk Mahasiswa {$mahasiswa->nama}",
            'status' => 'diterima',
        ]);
        
        // Buat usulan pembimbing (dosen lain sebagai pembimbing 1)
        $dosenLain = Dosen::where('id', '!=', $dosen->id)
            ->where('prodi_id', $dosen->prodi_id)
            ->inRandomOrder()
            ->first();
        
        if ($dosenLain) {
            UsulanPembimbing::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosenLain->id,
                'urutan' => 1,
                'status' => 'diterima',
                'tanggal_respon' => now(),
            ]);
        }
        
        // Dosen target sebagai pembimbing 2
        UsulanPembimbing::create([
            'topik_id' => $topik->id,
            'dosen_id' => $dosen->id,
            'urutan' => 2,
            'status' => 'diterima',
            'tanggal_respon' => now(),
        ]);
        
        // Buat bimbingan
        $totalBimbingan = rand(3, 5);
        for ($j = 1; $j <= $totalBimbingan; $j++) {
            Bimbingan::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosen->id,
                'jenis' => ($j <= 2) ? 'proposal' : 'skripsi',
                'bimbingan_ke' => $j,
                'pokok_bimbingan' => "Pembahasan BAB {$j}",
                'pesan_mahasiswa' => "Mohon review BAB {$j}",
                'pesan_dosen' => "Sudah bagus",
                'status' => 'disetujui',
                'tanggal_bimbingan' => now()->subDays(30 - ($j * 5)),
                'tanggal_respon' => now()->subDays(29 - ($j * 5)),
            ]);
        }
        
        // Buat jadwal dan pendaftaran seminar proposal
        $jadwalSempro = JadwalSidang::create([
            'ruangan_id' => $ruangan->id,
            'periode_id' => $periode->id,
            'tanggal' => now()->subDays(rand(20, 40))->format('Y-m-d'),
            'waktu_mulai' => '09:00',
            'waktu_selesai' => '11:00',
        ]);
        
        $pendaftaranSempro = PendaftaranSidang::create([
            'topik_id' => $topik->id,
            'jadwal_sidang_id' => $jadwalSempro->id,
            'jenis' => 'seminar_proposal',
            'status_pembimbing_1' => 'disetujui',
            'status_pembimbing_2' => 'disetujui',
            'status_koordinator' => 'disetujui',
        ]);
        
        PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSempro->id,
            'tanggal_sidang' => now()->subDays(rand(20, 40)),
            'tempat' => 'Ruang Seminar B',
            'status' => 'selesai',
        ]);
        
        // 70% masih proses, 30% sudah sidang tapi tidak lulus
        if ($i <= $totalKuotaPembimbing2 * 0.7) {
            if ($i % 2 == 0) {
                $jadwalSidang = JadwalSidang::create([
                    'ruangan_id' => $ruangan->id,
                    'periode_id' => $periode->id,
                    'tanggal' => now()->addDays(rand(7, 21))->format('Y-m-d'),
                    'waktu_mulai' => '13:00',
                    'waktu_selesai' => '15:00',
                ]);
                
                PendaftaranSidang::create([
                    'topik_id' => $topik->id,
                    'jadwal_sidang_id' => $jadwalSidang->id,
                    'jenis' => 'sidang_skripsi',
                    'status_pembimbing_1' => 'menunggu',
                    'status_pembimbing_2' => 'menunggu',
                    'status_koordinator' => 'menunggu',
                ]);
            } else {
                $jadwalSidang = JadwalSidang::create([
                    'ruangan_id' => $ruangan->id,
                    'periode_id' => $periode->id,
                    'tanggal' => now()->addDays(rand(1, 14))->format('Y-m-d'),
                    'waktu_mulai' => '13:00',
                    'waktu_selesai' => '15:00',
                ]);
                
                $pendaftaranSidang = PendaftaranSidang::create([
                    'topik_id' => $topik->id,
                    'jadwal_sidang_id' => $jadwalSidang->id,
                    'jenis' => 'sidang_skripsi',
                    'status_pembimbing_1' => 'disetujui',
                    'status_pembimbing_2' => 'disetujui',
                    'status_koordinator' => 'disetujui',
                ]);
                
                PelaksanaanSidang::create([
                    'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                    'tanggal_sidang' => now()->addDays(rand(1, 14)),
                    'tempat' => 'Ruang Sidang B',
                    'status' => 'dijadwalkan',
                ]);
            }
        } else {
            $jadwalSidang = JadwalSidang::create([
                'ruangan_id' => $ruangan->id,
                'periode_id' => $periode->id,
                'tanggal' => now()->subDays(rand(1, 15))->format('Y-m-d'),
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '15:00',
            ]);
            
            $pendaftaranSidang = PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jadwal_sidang_id' => $jadwalSidang->id,
                'jenis' => 'sidang_skripsi',
                'status_pembimbing_1' => 'disetujui',
                'status_pembimbing_2' => 'disetujui',
                'status_koordinator' => 'disetujui',
            ]);
            
            $pelaksanaanSidang = PelaksanaanSidang::create([
                'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                'tanggal_sidang' => now()->subDays(rand(1, 15)),
                'tempat' => 'Ruang Sidang B',
                'status' => 'selesai',
            ]);
            
            \App\Models\Nilai::create([
                'pelaksanaan_sidang_id' => $pelaksanaanSidang->id,
                'dosen_id' => $dosen->id,
                'jenis_nilai' => 'ujian',
                'nilai' => rand(30, 50),
            ]);
        }
        
        $createdCount++;
        echo "   ✅ {$nim} - {$mahasiswa->nama}\n";
    }
    
    DB::commit();
    
    echo "\n✅ SELESAI!\n";
    echo "📊 Total mahasiswa dibuat: {$createdCount}\n";
    echo "   - 70% masih dalam proses\n";
    echo "   - 30% sudah sidang tapi TIDAK LULUS (nilai < 55)\n\n";
    
    $dosen->refresh();
    echo "📈 Status Kuota Dosen {$dosen->nama}:\n";
    echo "   Pembimbing 1: {$dosen->jumlah_bimbingan_1}/{$dosen->kuota_pembimbing_1} (Sisa: {$dosen->sisa_kuota_1})\n";
    echo "   Pembimbing 2: {$dosen->jumlah_bimbingan_2}/{$dosen->kuota_pembimbing_2} (Sisa: {$dosen->sisa_kuota_2})\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
