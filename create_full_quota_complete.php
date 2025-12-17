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
    echo "   Kuota Pembimbing 2: {$dosen->kuota_pembimbing_2}\n";
    echo "   Prodi ID: {$dosen->prodi_id}\n\n";
    
    // 2. Cari bidang minat di prodi yang sama
    $bidangMinat = BidangMinat::where('prodi_id', $dosen->prodi_id)
        ->where('is_active', true)
        ->first();
    
    if (!$bidangMinat) {
        echo "❌ Bidang minat tidak ditemukan!\n";
        exit;
    }
    
    echo "✅ Bidang Minat: {$bidangMinat->nama}\n\n";
    
    // 3. Hitung total kuota yang perlu diisi
    $totalKuotaPembimbing1 = $dosen->kuota_pembimbing_1;
    $totalKuotaPembimbing2 = $dosen->kuota_pembimbing_2;
    $totalKuota = max($totalKuotaPembimbing1, $totalKuotaPembimbing2);
    
    echo "📊 Total mahasiswa yang akan dibuat: {$totalKuota}\n";
    echo "   - Sebagai Pembimbing 1: {$totalKuotaPembimbing1} mahasiswa\n";
    echo "   - Sebagai Pembimbing 2: {$totalKuotaPembimbing2} mahasiswa\n\n";
    
    $createdCount = 0;
    $startAngkatan = 2021;
    
    // 4. Buat mahasiswa untuk mengisi kuota pembimbing 1
    echo "🔄 Membuat mahasiswa dengan dosen sebagai Pembimbing 1...\n";
    for ($i = 1; $i <= $totalKuotaPembimbing1; $i++) {
        $nim = '119140' . str_pad($i + 100, 3, '0', STR_PAD_LEFT);
        
        // Cek apakah mahasiswa sudah ada
        $existingMahasiswa = Mahasiswa::where('nim', $nim)->first();
        if ($existingMahasiswa) {
            echo "   ⚠️  Mahasiswa {$nim} sudah ada, skip...\n";
            continue;
        }
        
        // Buat user
        $user = User::create([
            'name' => "Mahasiswa Bimbingan 1 - {$i}",
            'username' => "mhs_bimbing1_{$i}",
            'email' => "mhs.bimbing1.{$i}@example.com",
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);
        
        // Buat mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $nim,
            'nama' => "Mahasiswa Bimbingan 1 - {$i}",
            'prodi_id' => $dosen->prodi_id,
            'angkatan' => $startAngkatan + ($i % 3),
            'email' => "mhs.bimbing1.{$i}@example.com",
            'no_hp' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
        ]);
        
        // Buat topik skripsi
        $topik = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'bidang_minat_id' => $bidangMinat->id,
            'judul' => "Implementasi Sistem {$mahasiswa->nama} untuk Meningkatkan Efisiensi Proses Bisnis",
            'status' => 'diterima',
        ]);
        
        // Buat usulan pembimbing 1 (dosen target)
        $usulanPembimbing1 = UsulanPembimbing::create([
            'topik_id' => $topik->id,
            'dosen_id' => $dosen->id,
            'urutan' => 1,
            'status' => 'diterima',
            'tanggal_respon' => now(),
        ]);
        
        // Buat usulan pembimbing 2 (dosen lain random)
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
        
        // Buat beberapa data bimbingan
        $totalBimbingan = rand(3, 6);
        for ($j = 1; $j <= $totalBimbingan; $j++) {
            $jenisBimbingan = ($j <= 2) ? 'proposal' : 'skripsi';
            Bimbingan::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosen->id,
                'jenis' => $jenisBimbingan,
                'bimbingan_ke' => $j,
                'pokok_bimbingan' => "Pembahasan dan revisi BAB {$j}",
                'pesan_mahasiswa' => "Mohon review untuk BAB {$j}",
                'pesan_dosen' => "Sudah bagus, lanjutkan ke BAB berikutnya",
                'status' => 'disetujui',
                'tanggal_bimbingan' => now()->subDays(30 - ($j * 5)),
                'tanggal_respon' => now()->subDays(29 - ($j * 5)),
            ]);
        }
        
        // Buat pendaftaran seminar proposal
        $pendaftaranSempro = PendaftaranSidang::create([
            'topik_id' => $topik->id,
            'jenis' => 'seminar_proposal',
            'status' => 'disetujui',
        ]);
        
        // Buat pelaksanaan seminar proposal
        $pelaksanaanSempro = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSempro->id,
            'tanggal_sidang' => now()->subDays(rand(20, 40)),
            'tempat' => 'Ruang Seminar ' . chr(65 + ($i % 5)),
            'status' => 'selesai',
        ]);
        
        // Buat pendaftaran sidang skripsi
        // 70% masih dalam proses, 30% sudah selesai tapi TIDAK LULUS (nilai < 55)
        if ($i <= $totalKuotaPembimbing1 * 0.7) {
            // Mahasiswa masih dalam proses (belum sidang atau dijadwalkan)
            $pendaftaranSidang = PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jenis' => 'sidang_skripsi',
                'status' => ($i % 2 == 0) ? 'menunggu' : 'disetujui',
            ]);
            
            // Jika disetujui, buat jadwal (dijadwalkan tapi belum selesai)
            if ($i % 2 != 0) {
                PelaksanaanSidang::create([
                    'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                    'tanggal_sidang' => now()->addDays(rand(1, 14)),
                    'tempat' => 'Ruang Sidang ' . chr(65 + ($i % 5)),
                    'status' => 'dijadwalkan',
                ]);
            }
        } else {
            // Mahasiswa sudah sidang tapi TIDAK LULUS (masih mengurangi kuota)
            $pendaftaranSidang = PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jenis' => 'sidang_skripsi',
                'status' => 'disetujui',
            ]);
            
            $pelaksanaanSidang = PelaksanaanSidang::create([
                'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                'tanggal_sidang' => now()->subDays(rand(1, 15)),
                'tempat' => 'Ruang Sidang ' . chr(65 + ($i % 5)),
                'status' => 'selesai',
            ]);
            
            // Buat nilai TIDAK LULUS (nilai < 55)
            $nilaiRataRata = rand(30, 50);
            \App\Models\Nilai::create([
                'pelaksanaan_sidang_id' => $pelaksanaanSidang->id,
                'dosen_id' => $dosen->id,
                'jenis_nilai' => 'ujian',
                'nilai' => $nilaiRataRata,
            ]);
        }
        
        $createdCount++;
        echo "   ✅ {$mahasiswa->nim} - {$mahasiswa->nama} (Pembimbing 1)\n";
    }
    
    echo "\n🔄 Membuat mahasiswa dengan dosen sebagai Pembimbing 2...\n";
    for ($i = 1; $i <= $totalKuotaPembimbing2; $i++) {
        $nim = '119140' . str_pad($i + 200, 3, '0', STR_PAD_LEFT);
        
        // Cek apakah mahasiswa sudah ada
        $existingMahasiswa = Mahasiswa::where('nim', $nim)->first();
        if ($existingMahasiswa) {
            echo "   ⚠️  Mahasiswa {$nim} sudah ada, skip...\n";
            continue;
        }
        
        // Buat user
        $user = User::create([
            'name' => "Mahasiswa Bimbingan 2 - {$i}",
            'username' => "mhs_bimbing2_{$i}",
            'email' => "mhs.bimbing2.{$i}@example.com",
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
            'email_verified_at' => now(),
        ]);
        
        // Buat mahasiswa
        $mahasiswa = Mahasiswa::create([
            'user_id' => $user->id,
            'nim' => $nim,
            'nama' => "Mahasiswa Bimbingan 2 - {$i}",
            'prodi_id' => $dosen->prodi_id,
            'angkatan' => $startAngkatan + ($i % 3),
            'email' => "mhs.bimbing2.{$i}@example.com",
            'no_hp' => '08123457' . str_pad($i, 4, '0', STR_PAD_LEFT),
        ]);
        
        // Buat topik skripsi
        $topik = TopikSkripsi::create([
            'mahasiswa_id' => $mahasiswa->id,
            'bidang_minat_id' => $bidangMinat->id,
            'judul' => "Pengembangan Aplikasi {$mahasiswa->nama} Berbasis Web untuk Optimalisasi Layanan",
            'status' => 'diterima',
        ]);
        
        // Buat usulan pembimbing 1 (dosen lain random)
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
        
        // Buat usulan pembimbing 2 (dosen target)
        $usulanPembimbing2 = UsulanPembimbing::create([
            'topik_id' => $topik->id,
            'dosen_id' => $dosen->id,
            'urutan' => 2,
            'status' => 'diterima',
            'tanggal_respon' => now(),
        ]);
        
        // Buat beberapa data bimbingan
        $totalBimbingan = rand(3, 6);
        for ($j = 1; $j <= $totalBimbingan; $j++) {
            $jenisBimbingan = ($j <= 2) ? 'proposal' : 'skripsi';
            Bimbingan::create([
                'topik_id' => $topik->id,
                'dosen_id' => $dosen->id,
                'jenis' => $jenisBimbingan,
                'bimbingan_ke' => $j,
                'pokok_bimbingan' => "Revisi dan perbaikan dokumen skripsi bagian {$j}",
                'pesan_mahasiswa' => "Mohon review untuk bagian {$j}",
                'pesan_dosen' => "Perbaikan sudah sesuai, lanjutkan",
                'status' => 'disetujui',
                'tanggal_bimbingan' => now()->subDays(30 - ($j * 5)),
                'tanggal_respon' => now()->subDays(29 - ($j * 5)),
            ]);
        }
        
        // Buat pendaftaran seminar proposal
        $pendaftaranSempro = PendaftaranSidang::create([
            'topik_id' => $topik->id,
            'jenis' => 'seminar_proposal',
            'status' => 'disetujui',
        ]);
        
        // Buat pelaksanaan seminar proposal
        $pelaksanaanSempro = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaranSempro->id,
            'tanggal_sidang' => now()->subDays(rand(20, 40)),
            'tempat' => 'Ruang Seminar ' . chr(65 + ($i % 5)),
            'status' => 'selesai',
        ]);
        
        // Buat pendaftaran sidang skripsi
        // 70% masih dalam proses, 30% sudah selesai tapi TIDAK LULUS (nilai < 55)
        if ($i <= $totalKuotaPembimbing2 * 0.7) {
            // Mahasiswa masih dalam proses (belum sidang atau dijadwalkan)
            $pendaftaranSidang = PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jenis' => 'sidang_skripsi',
                'status' => ($i % 2 == 0) ? 'menunggu' : 'disetujui',
            ]);
            
            // Jika disetujui, buat jadwal (dijadwalkan tapi belum selesai)
            if ($i % 2 != 0) {
                PelaksanaanSidang::create([
                    'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                    'tanggal_sidang' => now()->addDays(rand(1, 14)),
                    'tempat' => 'Ruang Sidang ' . chr(65 + ($i % 5)),
                    'status' => 'dijadwalkan',
                ]);
            }
        } else {
            // Mahasiswa sudah sidang tapi TIDAK LULUS (masih mengurangi kuota)
            $pendaftaranSidang = PendaftaranSidang::create([
                'topik_id' => $topik->id,
                'jenis' => 'sidang_skripsi',
                'status' => 'disetujui',
            ]);
            
            $pelaksanaanSidang = PelaksanaanSidang::create([
                'pendaftaran_sidang_id' => $pendaftaranSidang->id,
                'tanggal_sidang' => now()->subDays(rand(1, 15)),
                'tempat' => 'Ruang Sidang ' . chr(65 + ($i % 5)),
                'status' => 'selesai',
            ]);
            
            // Buat nilai TIDAK LULUS (nilai < 55)
            $nilaiRataRata = rand(30, 50);
            \App\Models\Nilai::create([
                'pelaksanaan_sidang_id' => $pelaksanaanSidang->id,
                'dosen_id' => $dosen->id,
                'jenis_nilai' => 'ujian',
                'nilai' => $nilaiRataRata,
            ]);
        }
        
        $createdCount++;
        echo "   ✅ {$mahasiswa->nim} - {$mahasiswa->nama} (Pembimbing 2)\n";
    }
    
    DB::commit();
    
    echo "\n✅ SELESAI!\n";
    echo "📊 Total mahasiswa yang dibuat: {$createdCount}\n";
    echo "   - Pembimbing 1: {$totalKuotaPembimbing1} mahasiswa\n";
    echo "   - Pembimbing 2: {$totalKuotaPembimbing2} mahasiswa\n";
    echo "   - 70% mahasiswa masih dalam proses (mengurangi kuota)\n";
    echo "   - 30% mahasiswa sudah sidang tapi TIDAK LULUS (nilai < 55, tetap mengurangi kuota)\n\n";
    
    // Refresh dan tampilkan kuota terbaru
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
