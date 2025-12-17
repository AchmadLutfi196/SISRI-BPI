<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Storage;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'prodi_id',
        'angkatan',
        'email',
        'no_hp',
        'foto',
    ];

    /**
     * Get the user that owns the mahasiswa.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the prodi of the mahasiswa.
     */
    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'prodi_id');
    }

    /**
     * Get the topik skripsi.
     */
    public function topikSkripsi(): HasMany
    {
        return $this->hasMany(TopikSkripsi::class);
    }

    /**
     * Get pendaftaran sidang through topik skripsi.
     */
    public function pendaftaranSidang(): HasManyThrough
    {
        return $this->hasManyThrough(
            PendaftaranSidang::class,
            TopikSkripsi::class,
            'mahasiswa_id', // Foreign key on TopikSkripsi table
            'topik_id',     // Foreign key on PendaftaranSidang table
            'id',           // Local key on Mahasiswa table
            'id'            // Local key on TopikSkripsi table
        );
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
        
        return $initials ?: 'M';
    }
}
