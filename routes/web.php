<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\PeriodeController as AdminPeriodeController;
use App\Http\Controllers\Admin\UnitController as AdminUnitController;

// Mahasiswa Controllers
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\TopikController;
use App\Http\Controllers\Mahasiswa\BimbinganController as MahasiswaBimbinganController;
use App\Http\Controllers\Mahasiswa\SidangController;

// Dosen Controllers
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\ValidasiUsulanController;
use App\Http\Controllers\Dosen\BimbinganController as DosenBimbinganController;
use App\Http\Controllers\Dosen\NilaiController;
use App\Http\Controllers\Dosen\PersetujuanSidangController;

// Koordinator Controllers
use App\Http\Controllers\Koordinator\DashboardController as KoordinatorDashboardController;
use App\Http\Controllers\Koordinator\BidangMinatController;
use App\Http\Controllers\Koordinator\PenjadwalanController;
use App\Http\Controllers\Koordinator\DaftarNilaiController;

Route::get('/', function () {
    return view('welcome');
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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==================== ADMIN ROUTES ====================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Mahasiswa Management
    Route::resource('mahasiswa', AdminMahasiswaController::class);
    Route::post('/mahasiswa/{mahasiswa}/toggle-status', [AdminMahasiswaController::class, 'toggleStatus'])->name('mahasiswa.toggle-status');
    
    // Dosen Management
    Route::resource('dosen', AdminDosenController::class);
    Route::post('/dosen/{dosen}/toggle-status', [AdminDosenController::class, 'toggleStatus'])->name('dosen.toggle-status');
    
    // Periode Management
    Route::resource('periode', AdminPeriodeController::class);
    Route::post('/periode/{periode}/activate', [AdminPeriodeController::class, 'activate'])->name('periode.activate');
    
    // Unit Management (Fakultas, Jurusan, Prodi)
    Route::resource('unit', AdminUnitController::class);
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
});

// ==================== DOSEN ROUTES ====================
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DosenDashboardController::class, 'index'])->name('dashboard');
    
    // Validasi Usulan Pembimbing
    Route::get('/validasi-usulan', [ValidasiUsulanController::class, 'index'])->name('validasi-usulan.index');
    Route::get('/validasi-usulan/{usulan}', [ValidasiUsulanController::class, 'show'])->name('validasi-usulan.show');
    Route::post('/validasi-usulan/{usulan}/approve', [ValidasiUsulanController::class, 'approve'])->name('validasi-usulan.approve');
    Route::post('/validasi-usulan/{usulan}/reject', [ValidasiUsulanController::class, 'reject'])->name('validasi-usulan.reject');
    
    // Bimbingan
    Route::get('/bimbingan', [DosenBimbinganController::class, 'index'])->name('bimbingan.index');
    Route::get('/bimbingan/{bimbingan}', [DosenBimbinganController::class, 'show'])->name('bimbingan.show');
    Route::post('/bimbingan/{bimbingan}/respond', [DosenBimbinganController::class, 'respond'])->name('bimbingan.respond');
    Route::get('/bimbingan/mahasiswa/{mahasiswa}', [DosenBimbinganController::class, 'mahasiswaDetail'])->name('bimbingan.mahasiswa');
    
    // Penilaian Sidang
    Route::get('/nilai', [NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/{pelaksanaan}/create', [NilaiController::class, 'create'])->name('nilai.create');
    Route::post('/nilai/{pelaksanaan}', [NilaiController::class, 'store'])->name('nilai.store');
    Route::put('/nilai/{nilai}', [NilaiController::class, 'update'])->name('nilai.update');
    
    // Persetujuan Sidang
    Route::get('/persetujuan-sidang', [PersetujuanSidangController::class, 'index'])->name('persetujuan-sidang.index');
    Route::get('/persetujuan-sidang/{pendaftaran}', [PersetujuanSidangController::class, 'show'])->name('persetujuan-sidang.show');
    Route::post('/persetujuan-sidang/{pendaftaran}/approve', [PersetujuanSidangController::class, 'approve'])->name('persetujuan-sidang.approve');
    Route::post('/persetujuan-sidang/{pendaftaran}/reject', [PersetujuanSidangController::class, 'reject'])->name('persetujuan-sidang.reject');
});

// ==================== KOORDINATOR ROUTES ====================
Route::middleware(['auth', 'role:koordinator'])->prefix('koordinator')->name('koordinator.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [KoordinatorDashboardController::class, 'index'])->name('dashboard');
    
    // Bidang Minat
    Route::resource('bidang-minat', BidangMinatController::class);
    
    // Penjadwalan Sidang
    Route::get('/penjadwalan', [PenjadwalanController::class, 'index'])->name('penjadwalan.index');
    Route::get('/penjadwalan/jadwal', [PenjadwalanController::class, 'jadwal'])->name('penjadwalan.jadwal');
    Route::post('/penjadwalan/jadwal', [PenjadwalanController::class, 'storeJadwal'])->name('penjadwalan.store-jadwal');
    Route::get('/penjadwalan/pendaftaran', [PenjadwalanController::class, 'pendaftaran'])->name('penjadwalan.pendaftaran');
    Route::post('/penjadwalan/pendaftaran/{pendaftaran}/approve', [PenjadwalanController::class, 'approvePendaftaran'])->name('penjadwalan.approve-pendaftaran');
    Route::post('/penjadwalan/pendaftaran/{pendaftaran}/reject', [PenjadwalanController::class, 'rejectPendaftaran'])->name('penjadwalan.reject-pendaftaran');
    Route::get('/penjadwalan/pelaksanaan', [PenjadwalanController::class, 'pelaksanaan'])->name('penjadwalan.pelaksanaan');
    Route::post('/penjadwalan/pelaksanaan', [PenjadwalanController::class, 'storePelaksanaan'])->name('penjadwalan.store-pelaksanaan');
    Route::post('/penjadwalan/pelaksanaan/{pelaksanaan}/penguji', [PenjadwalanController::class, 'assignPenguji'])->name('penjadwalan.assign-penguji');
    
    // Daftar Nilai
    Route::get('/daftar-nilai', [DaftarNilaiController::class, 'index'])->name('daftar-nilai.index');
    Route::get('/daftar-nilai/{pelaksanaan}', [DaftarNilaiController::class, 'show'])->name('daftar-nilai.show');
    Route::get('/daftar-nilai/export', [DaftarNilaiController::class, 'export'])->name('daftar-nilai.export');
});

require __DIR__.'/auth.php';
