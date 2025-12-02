<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BagianMesin extends Model
{
    protected $table = 'bg_mesin';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'assets_id',
        'nama_bagian',
    ];

    /**
     * Relasi ke Asset
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }

    /**
     * Relasi ke KomponenAsset
     */
    public function komponenAssets(): HasMany
    {
        return $this->hasMany(KomponenAsset::class, 'bg_mesin_id', 'id');
    }
}
