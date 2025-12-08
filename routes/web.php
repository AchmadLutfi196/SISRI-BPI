<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\PeriodeController as AdminPeriodeController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;
use App\Http\Controllers\Admin\KoordinatorProdiController as AdminKoordinatorProdiController;
use App\Http\Controllers\Admin\RuanganController as AdminRuanganController;

// Mahasiswa Controllers
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\TopikController;
use App\Http\Controllers\Mahasiswa\BimbinganController as MahasiswaBimbinganController;
use App\Http\Controllers\Mahasiswa\SidangController;
use App\Http\Controllers\Mahasiswa\RevisiSidangController as MahasiswaRevisiSidangController;

// Dosen Controllers
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\ValidasiUsulanController;
use App\Http\Controllers\Dosen\BimbinganController as DosenBimbinganController;
use App\Http\Controllers\Dosen\NilaiSemproController;
use App\Http\Controllers\Dosen\NilaiSidangController;
use App\Http\Controllers\Dosen\PersetujuanSidangController;
use App\Http\Controllers\Dosen\JadwalUjianController;
use App\Http\Controllers\Dosen\BeritaAcaraController;
use App\Http\Controllers\Dosen\ValidasiRevisiController;

// Koordinator Controllers
use App\Http\Controllers\Koordinator\DashboardController as KoordinatorDashboardController;
use App\Http\Controllers\Koordinator\BidangMinatController;
use App\Http\Controllers\Koordinator\PenjadwalanController;
use App\Http\Controllers\Koordinator\PendaftaranController;
use App\Http\Controllers\Koordinator\DaftarNilaiController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect('/dashboard');
    }
    return redirect('/login');
});

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isMahasiswa()) {
        return redirect()->route('mahasiswa.dashboard');
    } elseif ($user->isDosen()) {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->isKoordinator()) {
        return redirect()->route('koordinator.dashboard');
    }
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deletePhoto'])->name('profile.photo.delete');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa Management
    Route::resource('mahasiswa', AdminMahasiswaController::class);
    Route::post('/mahasiswa/{mahasiswa}/toggle-status', [AdminMahasiswaController::class, 'toggleStatus'])->name('mahasiswa.toggle-status');
    Route::post('/mahasiswa/{mahasiswa}/reset-password', [AdminMahasiswaController::class, 'resetPassword'])->name('mahasiswa.reset-password');
    
    // Dosen Management
    Route::resource('dosen', AdminDosenController::class);
    Route::post('/dosen/{dosen}/toggle-status', [AdminDosenController::class, 'toggleStatus'])->name('dosen.toggle-status');
    Route::post('/dosen/{dosen}/reset-password', [AdminDosenController::class, 'resetPassword'])->name('dosen.reset-password');
    
    // Periode Management
    Route::resource('periode', AdminPeriodeController::class);
    Route::post('/periode/{periode}/activate', [AdminPeriodeController::class, 'activate'])->name('periode.activate');
    
    // Unit Management (Fakultas, Jurusan, Prodi)
    Route::resource('unit', AdminUnitController::class);
    
    // Koordinator Prodi Management
    Route::resource('koordinator', AdminKoordinatorProdiController::class)->except(['edit', 'update']);
    Route::patch('/koordinator/{koordinator}/toggle-status', [AdminKoordinatorProdiController::class, 'toggleStatus'])->name('koordinator.toggle-status');
    
    // Ruangan Management
    Route::resource('ruangan', AdminRuanganController::class)->except(['show']);
});

// ==================== MAHASISWA ROUTES ====================
Route::middleware(['auth', 'role:mahasiswa'])->prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [MahasiswaDashboardController::class, 'index'])->name('dashboard');
    
    // Topik Skripsi
    Route::get('/topik', [TopikController::class, 'index'])->name('topik.index');
    Route::get('/topik/create', [TopikController::class, 'create'])->name('topik.create');
    Route::post('/topik', [TopikController::class, 'store'])->name('topik.store');
    Route::get('/topik/{topik}/edit', [TopikController::class, 'edit'])->name('topik.edit');
    Route::put('/topik/{topik}', [TopikController::class, 'update'])->name('topik.update');
    
    // Bimbingan
    Route::get('/bimbingan', [MahasiswaBimbinganController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/create', [MahasiswaBimbinganController::class, 'create'])->name('bimbingan.create');
    Route::post('/bimbingan', [MahasiswaBimbinganController::class, 'store'])->name('bimbingan.store');
    Route::get('/bimbingan/{bimbingan}', [MahasiswaBimbinganController::class, 'show'])->name('bimbingan.show');
    Route::post('/bimbingan/{bimbingan}/upload-revisi', [MahasiswaBimbinganController::class, 'uploadRevisi'])->name('bimbingan.upload-revisi');
    
    // Sidang
    Route::get('/sidang', [SidangController::class, 'index'])->name('sidang.index');
    Route::get('/sidang/create', [SidangController::class, 'create'])->name('sidang.create');
    Route::post('/sidang', [SidangController::class, 'store'])->name('sidang.store');
    Route::get('/sidang/{pendaftaran}', [SidangController::class, 'show'])->name('sidang.show');
    Route::get('/sidang/{pendaftaran}/download-dokumen', [SidangController::class, 'downloadDokumen'])->name('sidang.download-dokumen');
    Route::get('/sidang/{pendaftaran}/download-berita-acara', [SidangController::class, 'downloadBeritaAcara'])->name('sidang.download-berita-acara');
    
    // Revisi Pasca Sidang
    Route::get('/revisi', [MahasiswaRevisiSidangController::class, 'index'])->name('revisi.index');
    Route::get('/revisi/{pelaksanaan}', [MahasiswaRevisiSidangController::class, 'show'])->name('revisi.show');
    Route::post('/revisi/{pelaksanaan}/submit/{dosenId}', [MahasiswaRevisiSidangController::class, 'submitRevisi'])->name('revisi.submit');
    Route::get('/revisi-file/{revisi}/download', [MahasiswaRevisiSidangController::class, 'downloadRevisi'])->name('revisi.download');
});

// ==================== DOSEN ROUTES ====================
// Koordinator juga bisa akses menu dosen karena koordinator adalah dosen juga
Route::middleware(['auth', 'role:dosen|koordinator'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    
    // Validasi Usulan Pembimbing
    Route::get('/validasi-usulan', [ValidasiUsulanController::class, 'index'])->name('validasi-usulan.index');
    Route::get('/validasi-usulan/{usulan}', [ValidasiUsulanController::class, 'show'])->name('validasi-usulan.show');
    Route::post('/validasi-usulan/{usulan}/approve', [ValidasiUsulanController::class, 'approve'])->name('validasi-usulan.approve');
    Route::post('/validasi-usulan/{usulan}/reject', [ValidasiUsulanController::class, 'reject'])->name('validasi-usulan.reject');
    Route::get('/validasi-usulan/{usulan}/download-proposal', [ValidasiUsulanController::class, 'downloadProposal'])->name('validasi-usulan.download-proposal');
    
    // Bimbingan
    Route::get('/bimbingan', [DosenBimbinganController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/{bimbingan}', [DosenBimbinganController::class, 'show'])->name('bimbingan.show');
    Route::post('/bimbingan/{bimbingan}/respond', [DosenBimbinganController::class, 'respond'])->name('bimbingan.respond');
    Route::get('/bimbingan/mahasiswa/{mahasiswa}', [DosenBimbinganController::class, 'mahasiswaDetail'])->name('bimbingan.mahasiswa');
    
    // Penilaian Seminar Proposal
    Route::get('/nilai-sempro', [NilaiSemproController::class, 'index'])->name('nilai-sempro.index');
    Route::get('/nilai-sempro/{pelaksanaan}/create', [NilaiSemproController::class, 'create'])->name('nilai-sempro.create');
    Route::post('/nilai-sempro/{pelaksanaan}', [NilaiSemproController::class, 'store'])->name('nilai-sempro.store');
    Route::put('/nilai-sempro/{nilai}', [NilaiSemproController::class, 'update'])->name('nilai-sempro.update');
    
    // Penilaian Sidang Skripsi
    Route::get('/nilai-sidang', [NilaiSidangController::class, 'index'])->name('nilai-sidang.index');
    Route::get('/nilai-sidang/{pelaksanaan}/create', [NilaiSidangController::class, 'create'])->name('nilai-sidang.create');
    Route::post('/nilai-sidang/{pelaksanaan}', [NilaiSidangController::class, 'store'])->name('nilai-sidang.store');
    Route::put('/nilai-sidang/{nilai}', [NilaiSidangController::class, 'update'])->name('nilai-sidang.update');
    
    // Persetujuan Sidang
    Route::get('/persetujuan-sidang', [PersetujuanSidangController::class, 'index'])->name('persetujuan-sidang.index');
    Route::get('/persetujuan-sidang/{pendaftaran}', [PersetujuanSidangController::class, 'show'])->name('persetujuan-sidang.show');
    Route::post('/persetujuan-sidang/{pendaftaran}/approve', [PersetujuanSidangController::class, 'approve'])->name('persetujuan-sidang.approve');
    Route::post('/persetujuan-sidang/{pendaftaran}/reject', [PersetujuanSidangController::class, 'reject'])->name('persetujuan-sidang.reject');
    Route::get('/persetujuan-sidang/{pendaftaran}/download-dokumen', [PersetujuanSidangController::class, 'downloadDokumen'])->name('persetujuan-sidang.download-dokumen');
    
    // Jadwal Ujian
    Route::get('/jadwal-ujian/sempro', [JadwalUjianController::class, 'sempro'])->name('jadwal-ujian.sempro');
    Route::get('/jadwal-ujian/sidang', [JadwalUjianController::class, 'sidang'])->name('jadwal-ujian.sidang');
    Route::get('/jadwal-ujian/{pelaksanaan}', [JadwalUjianController::class, 'show'])->name('jadwal-ujian.show');
    
    // Berita Acara
    Route::get('/berita-acara', [BeritaAcaraController::class, 'index'])->name('berita-acara.index');
    Route::get('/berita-acara/{pelaksanaan}', [BeritaAcaraController::class, 'show'])->name('berita-acara.show');
    Route::post('/berita-acara/{pelaksanaan}/tanda-tangan', [BeritaAcaraController::class, 'tandaTangan'])->name('berita-acara.tanda-tangan');
    Route::get('/berita-acara/{pelaksanaan}/download-pdf', [BeritaAcaraController::class, 'downloadPdf'])->name('berita-acara.download-pdf');
    Route::get('/berita-acara/{pelaksanaan}/download-dokumen', [BeritaAcaraController::class, 'downloadDokumen'])->name('berita-acara.download-dokumen');
    
    // Validasi Revisi Pasca Sidang
    Route::get('/validasi-revisi', [ValidasiRevisiController::class, 'index'])->name('validasi-revisi.index');
    Route::get('/validasi-revisi/{revisi}', [ValidasiRevisiController::class, 'show'])->name('validasi-revisi.show');
    Route::post('/validasi-revisi/{revisi}/approve', [ValidasiRevisiController::class, 'approve'])->name('validasi-revisi.approve');
    Route::post('/validasi-revisi/{revisi}/reject', [ValidasiRevisiController::class, 'reject'])->name('validasi-revisi.reject');
    Route::get('/validasi-revisi/{revisi}/download', [ValidasiRevisiController::class, 'downloadRevisi'])->name('validasi-revisi.download');
});

// ==================== KOORDINATOR ROUTES ====================
Route::middleware(['auth', 'role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KoordinatorDashboardController::class, 'index'])->name('dashboard');
    
    // Bidang Minat
    Route::resource('bidang-minat', BidangMinatController::class);
    
    // Pendaftaran Sempro/Sidang (untuk diproses koordinator)
    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::get('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'show'])->name('pendaftaran.show');
    Route::post('/pendaftaran/{pendaftaran}/approve', [PendaftaranController::class, 'approve'])->name('pendaftaran.approve');
    Route::post('/pendaftaran/{pendaftaran}/auto-approve', [PendaftaranController::class, 'autoApprove'])->name('pendaftaran.auto-approve');
    Route::post('/pendaftaran/{pendaftaran}/reject', [PendaftaranController::class, 'reject'])->name('pendaftaran.reject');
    Route::get('/pendaftaran/{pendaftaran}/edit-pelaksanaan', [PendaftaranController::class, 'editPelaksanaan'])->name('pendaftaran.edit-pelaksanaan');
    Route::put('/pendaftaran/{pendaftaran}/update-pelaksanaan', [PendaftaranController::class, 'updatePelaksanaan'])->name('pendaftaran.update-pelaksanaan');
    Route::post('/pendaftaran/pelaksanaan/{pelaksanaan}/complete', [PendaftaranController::class, 'completePelaksanaan'])->name('pendaftaran.complete-pelaksanaan');
    Route::get('/pendaftaran/{pendaftaran}/download-dokumen', [PendaftaranController::class, 'downloadDokumen'])->name('pendaftaran.download-dokumen');
    
    // Penjadwalan Sidang (CRUD jadwal periode)
    Route::get('/penjadwalan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index');
    Route::get('/penjadwalan/create', [PenjadwalanController::class, 'create'])->name('penjadwalan.create');
    Route::post('/penjadwalan', [PenjadwalanController::class, 'store'])->name('penjadwalan.store');
    Route::get('/penjadwalan/{jadwal}', [PenjadwalanController::class, 'show'])->name('penjadwalan.show');
    Route::get('/penjadwalan/{jadwal}/edit', [PenjadwalanController::class, 'edit'])->name('penjadwalan.edit');
    Route::put('/penjadwalan/{jadwal}', [PenjadwalanController::class, 'update'])->name('penjadwalan.update');
    Route::delete('/penjadwalan/{jadwal}', [PenjadwalanController::class, 'destroy'])->name('penjadwalan.destroy');
    
    // Penjadwalan - Persetujuan Pendaftaran
    Route::post('/penjadwalan/pendaftaran/{pendaftaran}/approve', [PenjadwalanController::class, 'approvePendaftaran'])->name('penjadwalan.approve-pendaftaran');
    Route::post('/penjadwalan/pendaftaran/{pendaftaran}/reject', [PenjadwalanController::class, 'rejectPendaftaran'])->name('penjadwalan.reject-pendaftaran');
    
    // Penjadwalan - Pelaksanaan Sidang (Manual)
    Route::get('/penjadwalan/pendaftaran/{pendaftaran}/pelaksanaan', [PenjadwalanController::class, 'createPelaksanaan'])->name('penjadwalan.create-pelaksanaan');
    Route::post('/penjadwalan/pendaftaran/{pendaftaran}/pelaksanaan', [PenjadwalanController::class, 'storePelaksanaan'])->name('penjadwalan.store-pelaksanaan');
    Route::post('/penjadwalan/pelaksanaan/{pelaksanaan}/complete', [PenjadwalanController::class, 'completePelaksanaan'])->name('penjadwalan.complete-pelaksanaan');
    
    // Daftar Nilai
    Route::get('/daftar-nilai', [DaftarNilaiController::class, 'index'])->name('daftar-nilai.index');
    Route::get('/daftar-nilai/{pelaksanaan}', [DaftarNilaiController::class, 'show'])->name('daftar-nilai.show');
});

require __DIR__.'/auth.php';
