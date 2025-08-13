<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'wali_kelas_id',
        'tahun_ajaran_id',
    ];

    /**
     * Relasi dengan tabel TahunAjaran
     * Satu Kelas dimiliki oleh satu TahunAjaran
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Relasi dengan tabel Users (sebagai Wali Kelas)
     * Satu Kelas dimiliki oleh satu User
     */
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    /**
     * Relasi dengan tabel Siswa
     * Satu Kelas memiliki banyak Siswa
     */
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    /**
     * Relasi dengan tabel Absensi
     * Satu Kelas memiliki banyak Absensi
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'kelas_id');
    }
}