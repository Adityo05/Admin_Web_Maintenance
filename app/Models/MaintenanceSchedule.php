<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceSchedule extends Model
{
    protected $table = 'mt_schedule';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'assets_id',
        'template_id',
        'tgl_jadwal',
        'tgl_selesai',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tgl_jadwal' => 'date',
        'tgl_selesai' => 'date',
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

    /**
     * Relasi ke MaintenanceTemplate
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(MaintenanceTemplate::class, 'template_id', 'id');
    }
}
