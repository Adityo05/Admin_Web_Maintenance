<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'email',
        'password_hash',
        'full_name',
        'profile_picture',
        'phone',
        'jabatan',
        'department',
        'is_active',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Verify password
     */
    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password_hash);
    }

    /**
     * Set password
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password_hash'] = Hash::make($value);
    }

    /**
     * Relasi ke UserAsset
     */
    public function userAssets()
    {
        return $this->hasMany(UserAsset::class, 'karyawan_id', 'id');
    }

    /**
     * Relasi ke Assets melalui UserAsset
     */
    public function assets()
    {
        return $this->belongsToMany(Asset::class, 'user_assets', 'karyawan_id', 'assets_id')
            ->withPivot('assigned_at', 'created_at');
    }

    /**
     * Relasi ke KaryawanAplikasi
     */
    public function karyawanAplikasi()
    {
        return $this->hasMany(\App\Models\KaryawanAplikasi::class, 'karyawan_id', 'id');
    }
}
