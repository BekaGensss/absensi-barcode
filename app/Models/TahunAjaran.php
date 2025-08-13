<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit, jika nama model tidak sesuai dengan konvensi Laravel
    protected $table = 'tahun_ajaran';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'tahun_mulai',
        'tahun_selesai',
        'is_active',
    ];

    /**
     * Relasi dengan tabel Kelas
     * Satu TahunAjaran memiliki banyak Kelas
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'tahun_ajaran_id');
    }
}