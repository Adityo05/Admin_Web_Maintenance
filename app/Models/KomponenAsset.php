<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomponenAsset extends Model
{
    protected $table = 'komponen_assets';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'assets_id',
        'bg_mesin_id',
        'nama_bagian',
        'produk_id',
        'spesifikasi',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke Asset
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }

    /**
     * Relasi ke BagianMesin
     */
    public function bagianMesin(): BelongsTo
    {
        return $this->belongsTo(BagianMesin::class, 'bg_mesin_id', 'id');
    }
}
