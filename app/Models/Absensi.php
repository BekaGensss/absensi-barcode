<?php

// File: app/Models/Absensi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tanggal',
        'waktu_absen',
        'status',
        'attachment', // Kolom baru untuk lampiran
    ];

    /**
     * Relasi dengan tabel Siswa
     * Satu Absensi dimiliki oleh satu Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    /**
     * Relasi dengan tabel Kelas
     * Satu Absensi dimiliki oleh satu Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}