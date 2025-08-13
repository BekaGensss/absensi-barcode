<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsensiExport;
use App\Exports\ArrayExport; // Tambahkan baris ini
use Barryvdh\DomPDF\Facade as PDF;

class RekapAbsensiController extends Controller
{
    /**
     * Menampilkan rekap kehadiran harian.
     */
    public function kehadiran(Request $request)
    {
        $kelas_id = $request->input('kelas_id');
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());
        $kelas = Kelas::with('siswa')->get();

        if ($kelas_id) {
            $absensi = Absensi::where('kelas_id', $kelas_id)
                             ->whereDate('tanggal', $tanggal)
                             ->with(['siswa', 'kelas'])
                             ->get();
        } else {
            $absensi = collect();
        }

        return view('rekap.kehadiran', compact('kelas', 'kelas_id', 'tanggal', 'absensi'));
    }

    /**
     * Menampilkan rekap absensi bulanan atau per periode.
     */
    public function rekapAbsensi(Request $request)
    {
        $kelas_id = $request->input('kelas_id');
        $bulan = $request->input('bulan', Carbon::today()->month);
        $tahun = $request->input('tahun', Carbon::today()->year);
        $kelas = Kelas::all();

        if ($kelas_id) {
            $absensi_rekap = Absensi::where('kelas_id', $kelas_id)
                                    ->whereMonth('tanggal', $bulan)
                                    ->whereYear('tanggal', $tahun)
                                    ->with('siswa')
                                    ->get()
                                    ->groupBy('siswa_id');
        } else {
            $absensi_rekap = collect();
        }

        return view('rekap.rekap-absensi', compact('kelas', 'kelas_id', 'bulan', 'tahun', 'absensi_rekap'));
    }

    /**
     * Mengekspor rekap absensi bulanan ke Excel.
     */
    public function export(Request $request)
    {
        $kelas_id = $request->input('kelas_id');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if (!$kelas_id) {
            return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu untuk mengekspor data.');
        }

        return Excel::download(new RekapAbsensiExport($kelas_id, $bulan, $tahun), 'rekap_absensi_' . $bulan . '_' . $tahun . '.xlsx');
    }

    /**
     * Metode baru: Mengekspor rekap kehadiran harian ke Excel.
     * Kode ini telah diperbaiki.
     */
    public function exportExcel(Request $request)
    {
        $kelas_id = $request->input('kelas_id');
        $tanggal = $request->input('tanggal');

        // Tambahkan validasi untuk kelas dan tanggal
        if (!$kelas_id) {
            return redirect()->back()->with('error', 'Pilih kelas terlebih dahulu untuk mengekspor data.');
        }

        if (!$tanggal) {
            return redirect()->back()->with('error', 'Tanggal tidak boleh kosong.');
        }

        // Mengambil data absensi harian yang sudah difilter
        $absensi = Absensi::where('kelas_id', $kelas_id)
                          ->whereDate('tanggal', $tanggal)
                          ->with(['siswa', 'kelas'])
                          ->get();

        // Jika tidak ada data absensi, kembalikan dengan pesan error
        if ($absensi->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data absensi untuk kelas dan tanggal yang dipilih.');
        }

        // Membuat array data untuk ekspor Excel. Menggunakan toArray() untuk konversi
        $data = $absensi->map(function ($item, $key) {
            return [
                'No' => $key + 1,
                'Nama Siswa' => $item->siswa->nama_siswa ?? '-',
                'NISN' => $item->siswa->nisn ?? '-',
                'Status' => $item->status,
                'Waktu Absen' => Carbon::parse($item->waktu_absen)->format('H:i'),
            ];
        })->toArray();
        
        // Pastikan $absensi tidak kosong sebelum mencoba mengambil kelas
        $nama_kelas = $absensi->first()->kelas->nama_kelas ?? 'Tidak diketahui';

        $headings = [
            ['Rekap Harian Kehadiran'],
            ['Kelas: ' . $nama_kelas],
            ['Tanggal: ' . Carbon::parse($tanggal)->format('d F Y')],
            [],
            ['No', 'Nama Siswa', 'NISN', 'Status', 'Waktu Absen']
        ];
        
        // Menggabungkan headings dan data menjadi satu array
        $combinedData = array_merge($headings, $data);

        // Mengunduh file Excel menggunakan ArrayExport
        return Excel::download(new ArrayExport($combinedData), 'rekap_harian_' . $tanggal . '.xlsx');
    }
}