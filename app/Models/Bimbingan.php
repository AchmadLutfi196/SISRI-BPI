<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bimbingan extends Model
{
    use HasFactory;

    protected $table = 'bimbingan';

    protected $fillable = [
        'topik_id',
        'dosen_id',
        'jenis',
        'bimbingan_ke',
        'pokok_bimbingan',
        'file_bimbingan',
        'pesan_mahasiswa',
        'pesan_dosen',
        'file_revisi',
        'status',
        'tanggal_bimbingan',
        'tanggal_respon',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_bimbingan' => 'datetime',
            'tanggal_respon' => 'datetime',
        ];
    }

    /**
     * Get the topik.
     */
    public function topik(): BelongsTo
    {
        return $this->belongsTo(TopikSkripsi::class, 'topik_id');
    }

    /**
     * Get the dosen.
     */
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Get the histories.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(BimbinganHistory::class)->orderBy('created_at', 'asc');
    }

    /**
     * Scope for proposal bimbingan.
     */
    public function scopeProposal($query)
    {
        return $query->where('jenis', 'proposal');
    }

    /**
     * Scope for skripsi bimbingan.
     */
    public function scopeSkripsi($query)
    {
        return $query->where('jenis', 'skripsi');
    }

    /**
     * Scope for pending.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'menunggu');
    }
}
