<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aplikasi extends Model
{
    protected $table = 'aplikasi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'kode_aplikasi',
        'nama_aplikasi',
    ];

    /**
     * Relasi ke KaryawanAplikasi
     */
    public function karyawanAplikasi()
    {
        return $this->hasMany(KaryawanAplikasi::class, 'aplikasi_id', 'id');
    }
}
