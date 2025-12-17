<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'user_id',
        'nip',
        'nidn',
        'nama',
        'prodi_id',
        'email',
        'no_hp',
        'foto',
        'kuota_pembimbing_1',
        'kuota_pembimbing_2',
    ];

    /**
     * Get the user that owns the dosen.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prodi of the dosen.
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'prodi_id');
    }

    /**
     * Get koordinator prodi records.
     */
    public function koordinatorProdi(): HasMany
    {
        return $this->hasMany(KoordinatorProdi::class);
    }

    /**
     * Get active koordinator prodi.
     */
    public function activeKoordinatorProdi(): HasOne
    {
        return $this->hasOne(KoordinatorProdi::class)->where('is_active', true);
    }

    /**
     * Get usulan pembimbing.
     */
    public function usulanPembimbing(): HasMany
    {
        return $this->hasMany(UsulanPembimbing::class);
    }

    /**
     * Get bimbingan.
     */
    public function bimbingan(): HasMany
    {
        return $this->hasMany(Bimbingan::class);
    }

    /**
     * Get penguji sidang.
     */
    public function pengujiSidang(): HasMany
    {
        return $this->hasMany(PengujiSidang::class);
    }

    /**
     * Get revisi sidang.
     */
    public function revisiSidang(): HasMany
    {
        return $this->hasMany(RevisiSidang::class);
    }

    /**
     * Get nilai.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Get foto URL or null.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if ($this->foto) {
            return Storage::url($this->foto);
        }
        return null;
    }

    /**
     * Get initials from nama.
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->nama);
        $initials = '';
        
        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper(substr($word, 0, 1));
                if (strlen($initials) >= 2) break;
            }
        }
        
        return $initials ?: 'D';
    }

    /**
     * Get jumlah mahasiswa bimbingan aktif sebagai pembimbing 1.
     */
    public function getJumlahBimbingan1Attribute(): int
    {
        $count = 0;
        $usulanPembimbing = $this->usulanPembimbing()
            ->where('urutan', 1)
            ->where('status', 'diterima')
            ->whereHas('topik', function ($query) {
                $query->whereNotIn('status', ['selesai', 'ditolak']);
            })
            ->with(['topik.mahasiswa.pendaftaranSidang.pelaksanaanSidang'])
            ->get();
        
        foreach ($usulanPembimbing as $usulan) {
            $sudahLulus = false;
            $mahasiswa = $usulan->topik->mahasiswa ?? null;
            
            if ($mahasiswa) {
                // Cek apakah mahasiswa sudah punya pelaksanaan sidang skripsi yang lulus
                foreach ($mahasiswa->pendaftaranSidang as $pendaftaran) {
                    // HANYA CEK SIDANG SKRIPSI, BUKAN SEMINAR PROPOSAL
                    if ($pendaftaran->jenis === 'sidang_skripsi' &&
                        $pendaftaran->pelaksanaanSidang && 
                        $pendaftaran->pelaksanaanSidang->status === 'selesai' &&
                        $pendaftaran->pelaksanaanSidang->isLulus()) {
                        $sudahLulus = true;
                        break;
                    }
                }
            }
            
            // Hitung hanya jika belum lulus
            if (!$sudahLulus) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get jumlah mahasiswa bimbingan aktif sebagai pembimbing 2.
     */
    public function getJumlahBimbingan2Attribute(): int
    {
        $count = 0;
        $usulanPembimbing = $this->usulanPembimbing()
            ->where('urutan', 2)
            ->where('status', 'diterima')
            ->whereHas('topik', function ($query) {
                $query->whereNotIn('status', ['selesai', 'ditolak']);
            })
            ->with(['topik.mahasiswa.pendaftaranSidang.pelaksanaanSidang'])
            ->get();
        
        foreach ($usulanPembimbing as $usulan) {
            $sudahLulus = false;
            $mahasiswa = $usulan->topik->mahasiswa ?? null;
            
            if ($mahasiswa) {
                // Cek apakah mahasiswa sudah punya pelaksanaan sidang skripsi yang lulus
                foreach ($mahasiswa->pendaftaranSidang as $pendaftaran) {
                    // HANYA CEK SIDANG SKRIPSI, BUKAN SEMINAR PROPOSAL
                    if ($pendaftaran->jenis === 'sidang_skripsi' &&
                        $pendaftaran->pelaksanaanSidang && 
                        $pendaftaran->pelaksanaanSidang->status === 'selesai' &&
                        $pendaftaran->pelaksanaanSidang->isLulus()) {
                        $sudahLulus = true;
                        break;
                    }
                }
            }
            
            // Hitung hanya jika belum lulus
            if (!$sudahLulus) {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get sisa kuota pembimbing 1.
     */
    public function getSisaKuota1Attribute(): int
    {
        return max(0, $this->kuota_pembimbing_1 - $this->jumlah_bimbingan_1);
    }

    /**
     * Get sisa kuota pembimbing 2.
     */
    public function getSisaKuota2Attribute(): int
    {
        return max(0, $this->kuota_pembimbing_2 - $this->jumlah_bimbingan_2);
    }

    /**
     * Cek apakah kuota pembimbing 1 masih tersedia.
     */
    public function hasKuota1Available(): bool
    {
        return $this->sisa_kuota_1 > 0;
    }

    /**
     * Cek apakah kuota pembimbing 2 masih tersedia.
     */
    public function hasKuota2Available(): bool
    {
        return $this->sisa_kuota_2 > 0;
    }

    /**
     * Cek apakah kuota tersedia berdasarkan urutan.
     */
    public function hasKuotaAvailable(int $urutan): bool
    {
        return $urutan == 1 ? $this->hasKuota1Available() : $this->hasKuota2Available();
    }
}
