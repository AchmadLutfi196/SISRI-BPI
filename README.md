# SISRI-BPI (Sistem Informasi Skripsi - Bimbingan dan Penilaian Integratif)

## 📋 Deskripsi

**SISRI-BPI** adalah aplikasi web berbasis Laravel untuk mengelola Sistem Informasi Pengajuan Tugas Akhir/Skripsi. Aplikasi ini dirancang untuk membantu proses pengajuan topik skripsi, bimbingan, penjadwalan sidang, dan penilaian secara sistematis dan terintegrasi.

### ✨ Fitur Utama:
- 📝 Pengajuan dan persetujuan topik skripsi
- 👨‍🏫 Manajemen dosen pembimbing
- 📅 Penjadwalan seminar proposal dan sidang skripsi
- 📊 Sistem penilaian terintegrasi
- 👥 Multi-role: Admin, Koordinator Prodi, Dosen, dan Mahasiswa

## 🚀 Teknologi yang Digunakan

| Teknologi | Persentase |
|-----------|------------|
| Blade Template | 74.4% |
| PHP | 25.5% |
| Lainnya | 0.1% |

### Stack Teknologi:
- **Framework**: Laravel 12 (PHP)
- **Authentication**: Laravel Breeze
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS
- **Build Tool**: Vite
- **Database**: SQLite
- **Package Manager**: Composer & NPM

## 📁 Struktur Direktori

```
SISRI-BPI/
├── app/                 # Logika aplikasi (Controllers, Models, dll)
│   ├── Http/Controllers # Controller untuk setiap role
│   └── Models/          # Model Eloquent (Mahasiswa, Dosen, TopikSkripsi, dll)
├── bootstrap/           # File bootstrap Laravel
├── config/              # Konfigurasi aplikasi
├── database/            # Migrasi dan seeder database
├── public/              # File publik (assets, index.php)
├── resources/           # Views, CSS, dan JavaScript
│   └── views/           # Blade templates per role
├── routes/              # Definisi routing aplikasi
├── storage/             # File storage dan cache
├── tests/               # Unit dan feature tests
├── composer.json        # Dependensi PHP
├── package.json         # Dependensi Node.js
├── tailwind.config.js   # Konfigurasi Tailwind CSS
└── vite.config.js       # Konfigurasi Vite
```

## ⚙️ Persyaratan Sistem

- PHP >= 8.2
- Composer
- Node.js & NPM
- SQLite
- Web Server (Apache/Nginx) atau `php artisan serve`

## 🛠️ Instalasi

1. **Clone repository**
   ```bash
   git clone https://github.com/Hritss28/SISRI-BPI.git
   cd SISRI-BPI
   ```

2. **Install dependensi PHP**
   ```bash
   composer install
   ```

3.  **Install dependensi Node. js**
   ```bash
   npm install
   ```

4. **Konfigurasi environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Konfigurasi database**
   - Edit file `.env` dan sesuaikan konfigurasi database:
   ```env
   DB_CONNECTION=sqlite
   ```

6. **Jalankan migrasi database**
   ```bash
   php artisan migrate
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Jalankan aplikasi**
   ```bash
   php artisan serve
   ```

9.  Akses aplikasi melalui browser di `http://localhost:8000`

## 👤 Akun Default

Setelah menjalankan seeder, gunakan akun berikut untuk login:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@sisri.test | password |
| Koordinator | rina@sisri.test | password |
| Dosen | agus@sisri.test | password |
| Mahasiswa | budi@sisri.test | password |

catatan : jika ingin login menggunakan email dan password sendiri, bisa di tambah di halaman admin. (harus login dengan role : Admin agar bisa menambahkan user/mahasiswa/dosen/koordinator)

## 📖 Dokumentasi

- 📄 **Dokumentasi Teknis**: Lihat `tech doc sisri.md`
- 📄 **Logika Sistem**: Lihat `logic sisri.md`


## 🧪 Testing

Jalankan test dengan perintah:
```bash
php artisan serve
```

## 📝 Lisensi

Proyek ini dikembangkan untuk keperluan Tugas Pemodelan Proses Bisnis.

## 👨‍💻 Kontributor

- [@Hritss28](https://github.com/Hritss28)

---

<p align="center">
  Dibuat dengan ❤️ menggunakan Laravel & Tailwind CSS
</p>