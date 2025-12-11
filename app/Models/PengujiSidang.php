<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengujiSidang extends Model
{
    use HasFactory;

    protected $table = 'penguji_sidang';

    protected $fillable = [
        'pelaksanaan_sidang_id',
        'dosen_id',
        'role',
        'ttd_berita_acara',
        'tanggal_ttd',
        'catatan_revisi',
    ];

    protected function casts(): array
    {
        return [
            'ttd_berita_acara' => 'boolean',
            'tanggal_ttd' => 'datetime',
        ];
    }

    /**
     * Get the pelaksanaan sidang.
     */
    public function pelaksanaanSidang(): BelongsTo
    {
        return $this->belongsTo(PelaksanaanSidang::class);
    }

    /**
     * Get the dosen.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Get the nilai for this penguji (via pelaksanaan_sidang_id and dosen_id).
     * Using getNilai method instead of nilai() to avoid Laravel relation conflict.
     */
    public function getNilai()
    {
        return Nilai::where('pelaksanaan_sidang_id', $this->pelaksanaan_sidang_id)
            ->where('dosen_id', $this->dosen_id)
            ->where('jenis_nilai', 'ujian')
            ->first();
    }
    
    /**
     * Check if this penguji has already given nilai.
     */
    public function hasNilai(): bool
    {
        return Nilai::where('pelaksanaan_sidang_id', $this->pelaksanaan_sidang_id)
            ->where('dosen_id', $this->dosen_id)
            ->where('jenis_nilai', 'ujian')
            ->exists();
    }
    
    /**
     * Get nilai attribute.
     */
    public function getNilaiDataAttribute()
    {
        return Nilai::where('pelaksanaan_sidang_id', $this->pelaksanaan_sidang_id)
            ->where('dosen_id', $this->dosen_id)
            ->where('jenis_nilai', 'ujian')
            ->first();
    }

    /**
     * Get the revisi sidang for this penguji.
     */
    public function revisiSidang(): HasOne
    {
        return $this->hasOne(RevisiSidang::class, 'dosen_id', 'dosen_id')
            ->where('pelaksanaan_sidang_id', $this->pelaksanaan_sidang_id);
    }
}
