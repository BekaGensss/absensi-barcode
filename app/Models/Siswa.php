<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Siswa extends Model
{
    use HasFactory, Notifiable;
    
    protected $table = 'siswa';
    
    protected $fillable = [
        'nama_siswa',
        'nisn',
        'email',
        'qr_code_path',
        'kelas_id',
    ];

    /**
     * Relasi dengan tabel Kelas
     * Satu Siswa dimiliki oleh satu Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    /**
     * Relasi dengan tabel Absensi
     * Satu Siswa memiliki banyak Absensi
     *
     * PERBAIKAN: Nama method diubah menjadi 'absensis'
     * agar sesuai dengan yang dipanggil di controller (Siswa::with(['absensis']))
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }
}
