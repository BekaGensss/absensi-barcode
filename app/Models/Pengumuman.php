<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    // Nama tabel secara eksplisit agar tidak terjadi error pluralisasi
    protected $table = 'pengumumen';

    protected $fillable = [
        'judul',
        'isi',
        'is_active',
    ];
}