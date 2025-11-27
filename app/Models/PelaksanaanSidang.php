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
}
