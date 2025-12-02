<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $table = 'assets';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'nama_assets',
        'kode_assets',
        'jenis_assets',
        'foto',
        'status',
        'mt_priority',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke BagianMesin
     */
    public function bagianMesin(): HasMany
    {
        return $this->hasMany(BagianMesin::class, 'assets_id', 'id');
    }

    /**
     * Relasi ke KomponenAsset
     */
    public function komponenAssets(): HasMany
    {
        return $this->hasMany(KomponenAsset::class, 'assets_id', 'id');
    }

    /**
     * Relasi ke Karyawan melalui UserAsset
     */
    public function karyawan()
    {
        return $this->belongsToMany(Karyawan::class, 'user_assets', 'assets_id', 'karyawan_id')
            ->withPivot('assigned_at', 'created_at');
    }
}
