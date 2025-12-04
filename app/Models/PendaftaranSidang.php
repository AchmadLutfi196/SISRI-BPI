<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PendaftaranSidang extends Model
{
    use HasFactory;

    protected $table = 'pendaftaran_sidang';

    protected $fillable = [
        'topik_id',
        'jadwal_sidang_id',
        'jenis',
        'status_pembimbing_1',
        'status_pembimbing_2',
        'status_koordinator',
        'catatan_pembimbing_1',
        'catatan_pembimbing_2',
        'catatan_koordinator',
        'file_dokumen',
        'file_dokumen_original_name',
    ];

    /**
     * Get the topik.
     */
    public function topik(): BelongsTo
    {
        return $this->belongsTo(TopikSkripsi::class, 'topik_id');
    }
    
    /**
     * Get the topik skripsi (alias for topik).
     */
    public function topikSkripsi(): BelongsTo
    {
        return $this->belongsTo(TopikSkripsi::class, 'topik_id');
    }
    
    /**
     * Get the mahasiswa through topik.
     */
    public function mahasiswa()
    {
        return $this->hasOneThrough(
            Mahasiswa::class,
            TopikSkripsi::class,
            'id', // Foreign key on topik_skripsi table
            'id', // Foreign key on mahasiswa table
            'topik_id', // Local key on pendaftaran_sidang table
            'mahasiswa_id' // Local key on topik_skripsi table
        );
    }

    /**
     * Get the jadwal sidang.
     */
    public function jadwalSidang(): BelongsTo
    {
        return $this->belongsTo(JadwalSidang::class);
    }

    /**
     * Get pelaksanaan sidang.
     */
    public function pelaksanaanSidang(): HasOne
    {
        return $this->hasOne(PelaksanaanSidang::class);
    }

    /**
     * Check if fully approved.
     */
    public function isFullyApproved(): bool
    {
        return $this->status_pembimbing_1 === 'disetujui' 
            && $this->status_pembimbing_2 === 'disetujui' 
            && $this->status_koordinator === 'disetujui';
    }

    /**
     * Check if rejected by any party.
     */
    public function isRejected(): bool
    {
        return $this->status_pembimbing_1 === 'ditolak' 
            || $this->status_pembimbing_2 === 'ditolak' 
            || $this->status_koordinator === 'ditolak';
    }

    /**
     * Scope for approved.
     */
    public function scopeApproved($query)
    {
        return $query->where('status_pembimbing_1', 'disetujui')
            ->where('status_pembimbing_2', 'disetujui')
            ->where('status_koordinator', 'disetujui');
    }

    /**
     * Scope for rejected.
     */
    public function scopeRejected($query)
    {
        return $query->where(function ($q) {
            $q->where('status_pembimbing_1', 'ditolak')
              ->orWhere('status_pembimbing_2', 'ditolak')
              ->orWhere('status_koordinator', 'ditolak');
        });
    }

    /**
     * Scope for active (not rejected).
     */
    public function scopeActive($query)
    {
        return $query->where('status_pembimbing_1', '!=', 'ditolak')
            ->where('status_pembimbing_2', '!=', 'ditolak')
            ->where('status_koordinator', '!=', 'ditolak');
    }
}
