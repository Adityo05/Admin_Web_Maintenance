<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaryawanAplikasi extends Model
{
    protected $table = 'karyawan_aplikasi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'karyawan_id',
        'aplikasi_id',
        'role',
    ];

    /**
     * Relasi ke Karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id', 'id');
    }

    /**
     * Relasi ke Aplikasi
     */
    public function aplikasi()
    {
        return $this->belongsTo(\App\Models\Aplikasi::class, 'aplikasi_id', 'id');
    }
}
