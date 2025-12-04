<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== AKUN UNTUK TESTING FITUR UPLOAD DOKUMEN ===\n\n";

echo "MAHASISWA (dengan dokumen sudah diupload):\n";
echo "-----------------------------------------\n";
$mahasiswaWithDocs = ['2020002', '2019001', '2020003', '2021001'];
foreach ($mahasiswaWithDocs as $nim) {
    $user = App\Models\User::where('username', $nim)->first();
    if ($user) {
        $mhs = App\Models\Mahasiswa::where('nim', $nim)->first();
        $topik = $mhs ? App\Models\TopikSkripsi::where('mahasiswa_id', $mhs->id)->first() : null;
        $pendaftaran = $topik ? App\Models\PendaftaranSidang::where('topik_id', $topik->id)->whereNotNull('file_dokumen')->first() : null;
        
        echo "Username: {$user->username}\n";
        echo "Nama: {$user->name}\n";
        echo "Password: password\n";
        if ($pendaftaran) {
            echo "Dokumen: " . $pendaftaran->file_dokumen_original_name . "\n";
        }
        echo "\n";
    }
}

echo "\nDOSEN (pembimbing/penguji yang bisa download):\n";
echo "----------------------------------------------\n";
$dosenList = App\Models\User::where('role', 'dosen')->take(5)->get();
foreach ($dosenList as $user) {
    echo "Username: {$user->username} - {$user->name}\n";
}
echo "Password: password\n\n";

echo "KOORDINATOR:\n";
echo "------------\n";
$koordinator = App\Models\User::where('role', 'koordinator')->first();
if ($koordinator) {
    echo "Username: {$koordinator->username} - {$koordinator->name}\n";
    echo "Password: password\n";
}

echo "\n=== CATATAN ===\n";
echo "- Login sebagai mahasiswa (2020002/Dewi atau 2019001/Andi) untuk melihat halaman detail sidang dengan dokumen\n";
echo "- Andi (2019001) sudah memiliki SEMUA dosen yang TTD, jadi bisa download Berita Acara\n";
echo "- Dewi (2020002) memiliki sidang yang sudah dijadwalkan dengan dokumen\n";
echo "- Login sebagai dosen untuk melihat persetujuan sidang dan berita acara\n";
echo "- Login sebagai koordinator untuk melihat pendaftaran sidang\n";
