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
}
