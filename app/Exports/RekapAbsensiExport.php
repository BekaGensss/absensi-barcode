<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class RekapAbsensiExport implements FromView, ShouldAutoSize, WithTitle
{
    protected $kelas_id;
    protected $bulan;
    protected $tahun;

    public function __construct(int $kelas_id, int $bulan, int $tahun)
    {
        $this->kelas_id = $kelas_id;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function view(): View
    {
        // Mendapatkan objek kelas berdasarkan kelas_id
        $kelas = Kelas::findOrFail($this->kelas_id);
        
        // Menentukan jumlah hari dalam bulan yang dipilih
        $tanggal_awal_bulan = Carbon::createFromDate($this->tahun, $this->bulan, 1);
        $jumlah_hari = $tanggal_awal_bulan->daysInMonth;
        
        // Mengambil data absensi yang sudah dikelompokkan berdasarkan siswa
        $absensi_rekap = Absensi::where('kelas_id', $this->kelas_id)
                                ->whereMonth('tanggal', $this->bulan)
                                ->whereYear('tanggal', $this->tahun)
                                ->with('siswa')
                                ->get()
                                ->groupBy('siswa_id');

        return view('exports.rekap-absensi', [
            'absensi_rekap' => $absensi_rekap,
            'kelas' => $kelas,
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
            'jumlah_hari' => $jumlah_hari,
            'tanggal_awal_bulan' => $tanggal_awal_bulan
        ]);
    }
    
    /**
     * Tambahan: Menetapkan judul untuk sheet Excel.
     * Ini akan membuat tab Excel memiliki nama yang relevan.
     */
    public function title(): string
    {
        $bulan_nama = Carbon::create()->month($this->bulan)->translatedFormat('F');
        return "Rekap Absensi Bulan {$bulan_nama} {$this->tahun}";
    }
}