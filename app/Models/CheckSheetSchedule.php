<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckSheetSchedule extends Model
{
    protected $table = 'cek_sheet_schedule';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'template_id',
        'tgl_jadwal',
        'tgl_selesai',
        'foto_sblm',
        'foto_sesudah',
        'catatan',
        'completed_by',
        'created_at',
    ];

    protected $casts = [
        'tgl_jadwal' => 'date',
        'tgl_selesai' => 'date',
        'created_at' => 'datetime',
    ];

    /**
     * Relasi ke Karyawan (completed_by)
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'completed_by', 'id');
    }

    /**
     * Relasi ke CheckSheetTemplate
     */
    public function template(): BelongsTo
    {
        return $this->belongsTo(CheckSheetTemplate::class, 'template_id', 'id');
    }
}
