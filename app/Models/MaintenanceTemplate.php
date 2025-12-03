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
        'bg_mesin_id',
        'periode',
        'interval_periode',
        'start_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'interval_periode' => 'integer',
        'start_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke BagianMesin
     */
    public function bagianMesin(): BelongsTo
    {
        return $this->belongsTo(BagianMesin::class, 'bg_mesin_id', 'id');
    }
}
