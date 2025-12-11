<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Buat 10 bimbingan untuk pembimbing 1
for ($i = 1; $i <= 10; $i++) {
    $mahasiswa = DB::table('mahasiswa')->inRandomOrder()->first();
    $bidangMinat = DB::table('bidang_minat')->inRandomOrder()->first();
    $topikId = DB::table('topik_skripsi')->insertGetId([
        'mahasiswa_id' => $mahasiswa->id,
        'bidang_minat_id' => $bidangMinat->id,
        'judul' => 'Testing Kuota Pembimbing 1 - ' . $i,
        'status' => 'diterima',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    DB::table('usulan_pembimbing')->insert([
        'topik_id' => $topikId,
        'dosen_id' => 1,
        'urutan' => 1,
        'status' => 'diterima',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo "✓ Berhasil membuat 10 bimbingan untuk pembimbing 1\n";

// Buat 15 bimbingan untuk pembimbing 2
for ($i = 1; $i <= 15; $i++) {
    $mahasiswa = DB::table('mahasiswa')->inRandomOrder()->first();
    $bidangMinat = DB::table('bidang_minat')->inRandomOrder()->first();
    $topikId = DB::table('topik_skripsi')->insertGetId([
        'mahasiswa_id' => $mahasiswa->id,
        'bidang_minat_id' => $bidangMinat->id,
        'judul' => 'Testing Kuota Pembimbing 2 - ' . $i,
        'status' => 'diterima',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    
    DB::table('usulan_pembimbing')->insert([
        'topik_id' => $topikId,
        'dosen_id' => 1,
        'urutan' => 2,
        'status' => 'diterima',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

echo "✓ Berhasil membuat 15 bimbingan untuk pembimbing 2\n";
echo "\nKuota dosen ID 1 sekarang FULL: 10/10 dan 15/15\n";
