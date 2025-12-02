<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAsset extends Model
{
    protected $table = 'user_assets';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'karyawan_id',
        'assets_id',
        'assigned_at',
        'created_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    /**
     * Relasi ke Karyawan
     */
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    /**
     * Relasi ke Asset
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }
}
