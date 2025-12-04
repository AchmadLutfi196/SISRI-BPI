<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Barryvdh\DomPDF\Facade\Pdf;

// Create sample PDFs for testing
$samples = [
    'sample_proposal_dewi.pdf' => 'Proposal Skripsi: Sistem Deteksi Penyakit Tanaman Padi Menggunakan CNN',
    'sample_proposal_andi.pdf' => 'Proposal Skripsi: Analisis Sentimen Review Produk E-Commerce Menggunakan LSTM',
    'sample_proposal_rina.pdf' => 'Proposal Skripsi: Optimasi Rute Pengiriman Menggunakan Algoritma Genetika',
    'sample_proposal_fajar.pdf' => 'Proposal Skripsi: Prediksi Kelulusan Mahasiswa Menggunakan Machine Learning',
    'sample_proposal_nina.pdf' => 'Proposal Skripsi: Implementasi Blockchain untuk Sistem Voting Digital',
];

$storagePath = storage_path('app/public/dokumen-sidang');

foreach ($samples as $filename => $title) {
    $html = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; margin: 50px; }
            h1 { color: #333; text-align: center; }
            .content { margin-top: 30px; line-height: 1.6; }
            .header { text-align: center; margin-bottom: 30px; }
            .info { margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>DOKUMEN PROPOSAL SKRIPSI</h2>
            <h3>Program Studi Teknik Informatika</h3>
        </div>
        <div class='content'>
            <h1>{$title}</h1>
            <div class='info'>
                <p><strong>Dokumen ini adalah sample untuk testing fitur upload.</strong></p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
            </div>
        </div>
    </body>
    </html>";

    $pdf = Pdf::loadHTML($html);
    $pdf->save($storagePath . '/' . $filename);
    echo "Created: {$filename}\n";
}

echo "\nAll sample PDFs created successfully!\n";
