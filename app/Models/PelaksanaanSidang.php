<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PelaksanaanSidang extends Model
{
    use HasFactory;

    protected $table = 'pelaksanaan_sidang';

    protected $fillable = [
        'pendaftaran_sidang_id',
        'tanggal_sidang',
        'tempat',
        'status',
        'berita_acara',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sidang' => 'datetime',
        ];
    }

    /**
     * Get the pendaftaran sidang.
     */
    public function pendaftaranSidang(): BelongsTo
    {
        return $this->belongsTo(PendaftaranSidang::class);
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
     * Alias for nilai (plural form for convenience).
     */
    public function nilais(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * Get jadwal sidang (alias for backward compatibility).
     */
    public function jadwalSidang()
    {
        return (object)[
            'tanggal' => $this->tanggal_sidang,
            'waktu_mulai' => $this->tanggal_sidang ? $this->tanggal_sidang->format('H:i') : null,
            'waktu_selesai' => $this->tanggal_sidang ? $this->tanggal_sidang->copy()->addHours(2)->format('H:i') : null,
        ];
    }

    /**
     * Scope for scheduled.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'dijadwalkan');
    }

    /**
     * Scope for completed.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'selesai');
    }

    /**
     * Get nilai rata-rata (hanya untuk jenis_nilai = ujian).
     */
    public function getNilaiRataRataAttribute(): ?float
    {
        $nilaiUjian = $this->nilai()->where('jenis_nilai', 'ujian')->get();
        if ($nilaiUjian->isEmpty()) {
            return null;
        }
        return $nilaiUjian->avg('nilai');
    }

    /**
     * Get nilai huruf dari nilai rata-rata.
     */
    public function getNilaiHurufAttribute(): ?string
    {
        $nilai = $this->nilai_rata_rata;
        if ($nilai === null) {
            return null;
        }
        
        return match(true) {
            $nilai >= 85 => 'A',
            $nilai >= 80 => 'A-',
            $nilai >= 75 => 'B+',
            $nilai >= 70 => 'B',
            $nilai >= 65 => 'B-',
            $nilai >= 60 => 'C+',
            $nilai >= 55 => 'C',
            $nilai >= 50 => 'D',
            default => 'E',
        };
    }

    /**
     * Cek apakah sidang lulus (nilai >= C, tidak D atau E).
     */
    public function isLulus(): bool
    {
        $nilai = $this->nilai_rata_rata;
        if ($nilai === null) {
            return false;
        }
        
        // Lulus jika nilai >= 55 (minimal C+)
        return $nilai >= 60;
    }

    /**
     * Cek apakah sidang tidak lulus (nilai D atau E).
     */
    public function isTidakLulus(): bool
    {
        $nilai = $this->nilai_rata_rata;
        if ($nilai === null) {
            return false;
        }
        
        // Tidak lulus jika nilai < 55 (D atau E)
        return $nilai < 55;
    }
}
