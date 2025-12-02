<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckSheetTemplate extends Model
{
    protected $table = 'cek_sheet_template';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'komponen_assets_id',
        'periode',
        'interval_periode',
        'jenis_pekerjaan',
        'std_prwtn',
        'alat_bahan',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'interval_periode' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke KomponenAsset
     */
    public function komponenAsset(): BelongsTo
    {
        return $this->belongsTo(KomponenAsset::class, 'komponen_assets_id', 'id');
    }

}
