<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Tampilkan dashboard dengan data dinamis.
     */
    public function dashboard()
    {
        // Ambil semua pengumuman yang aktif
        $pengumuman = Pengumuman::where('is_active', true)->get();

        // Data dinamis untuk kartu ringkasan
        $totalSiswa = Siswa::count();
        $kehadiranHariIni = Absensi::whereDate('tanggal', Carbon::today())
                                   ->where('status', 'Hadir')
                                   ->count();
        $izinSakitHariIni = Absensi::whereDate('tanggal', Carbon::today())
                                   ->whereIn('status', ['Izin', 'Sakit'])
                                   ->count();
        $tidakHadirHariIni = $totalSiswa - ($kehadiranHariIni + $izinSakitHariIni);
        
        // Data dinamis untuk grafik
        $labels = [];
        $hadirData = [];
        $izinSakitData = [];
        $tidakHadirData = [];

        // Mengambil data untuk 7 hari terakhir
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('D'); // e.g., "Mon", "Tue"
            
            $hadirData[] = Absensi::whereDate('tanggal', $date)->where('status', 'Hadir')->count();
            $izinSakitData[] = Absensi::whereDate('tanggal', $date)->whereIn('status', ['Izin', 'Sakit'])->count();
            $tidakHadirData[] = $totalSiswa - (Absensi::whereDate('tanggal', $date)->whereIn('status', ['Hadir', 'Izin', 'Sakit'])->count());
        }

        // --- BAGIAN KRUSIAL YANG HARUS ADA DI CONTROLLER ANDA ---
        // Mengambil semua siswa dan absensi mereka hari ini
        $siswaHariIni = Siswa::with(['absensis' => function ($query) {
                                   $query->whereDate('tanggal', Carbon::today());
                               }])
                               ->get();

        return view('dashboard', compact(
            'pengumuman',
            'totalSiswa',
            'kehadiranHariIni',
            'izinSakitHariIni',
            'tidakHadirHariIni',
            'labels',
            'hadirData',
            'izinSakitData',
            'tidakHadirData',
            'siswaHariIni' // Pastikan nama variabel ini sesuai
        ));
    }
}