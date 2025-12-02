<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceTemplate extends Model
{
    protected $table = 'mt_template';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'assets_id',
        'nama_template',
        'deskripsi',
        'interval_hari',
        'periode',
        'start_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'interval_hari' => 'integer',
        'start_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Asset
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'assets_id', 'id');
    }
}
