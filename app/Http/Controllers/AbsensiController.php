<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Digunakan untuk mengelola file
use Carbon\Carbon;

class AbsensiController extends Controller
{
    /**
     * Menampilkan halaman pemindai QR Code untuk admin/guru.
     * @return \Illuminate\View\View
     */
    public function scanner()
    {
        return view('absensi.scanner');
    }

    /**
     * Menyimpan absensi dari hasil pemindaian oleh admin/guru.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scan(Request $request)
    {
        // Validasi NISN dari QR Code
        $request->validate([
            'nisn' => 'required|string',
        ]);

        $siswa = Siswa::where('nisn', $request->nisn)->first();

        // Jika siswa tidak ditemukan
        if (!$siswa) {
            return response()->json([
                'success' => false, 
                'message' => 'Siswa tidak ditemukan.'
            ], 404);
        }

        // Cek apakah siswa sudah absen dengan status 'Hadir' hari ini
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
                             ->whereDate('tanggal', Carbon::today())
                             ->where('status', 'Hadir')
                             ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false, 
                'message' => 'Siswa sudah absen hari ini.'
            ], 409);
        }

        // Simpan data absensi baru
        Absensi::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $siswa->kelas_id,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_absen' => Carbon::now(),
            'status' => 'Hadir',
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Absensi berhasil dicatat!', 
            'siswa' => $siswa
        ], 200);
    }
    
    /**
     * Menampilkan halaman absensi mandiri untuk siswa.
     *
     * @param  string  $nisn
     * @return \Illuminate\View\View
     */
    public function mandiri($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
        
        // Memeriksa apakah siswa sudah absen hari ini, dengan status 'Hadir'
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
                             ->whereDate('tanggal', Carbon::today())
                             ->where('status', 'Hadir')
                             ->exists();
        
        return view('absensi.mandiri', compact('siswa', 'sudahAbsen'));
    }

    /**
     * Mengelola proses absensi mandiri oleh siswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $nisn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function absenMandiri(Request $request, $nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
                             ->whereDate('tanggal', Carbon::today())
                             ->whereIn('status', ['Hadir', 'Izin', 'Sakit'])
                             ->exists();

        if ($sudahAbsen) {
            return redirect()->route('absensi.siswa.dashboard', $siswa->nisn)
                             ->with('error', 'Anda sudah melakukan absensi hari ini.');
        }

        Absensi::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $siswa->kelas_id,
            'tanggal' => Carbon::now()->toDateString(),
            'waktu_absen' => Carbon::now(),
            'status' => 'Hadir',
        ]);

        return redirect()->route('absensi.siswa.dashboard', $siswa->nisn)
                         ->with('success', 'Absensi berhasil dicatat!');
    }
    
    /**
     * Menambahkan metode untuk mengajukan absensi (Izin/Sakit) oleh siswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $nisn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function ajukanAbsen(Request $request, $nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();

        // Validasi input dari form pengajuan absensi
        $request->validate([
            'status' => 'required|in:Izin,Sakit',
            'attachment' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048', // Batasan file: 2MB, PDF/gambar
        ]);

        // Cek apakah siswa sudah memiliki absensi untuk hari ini
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
                             ->whereDate('tanggal', Carbon::today())
                             ->exists();

        if ($sudahAbsen) {
            return redirect()->back()->with('error', 'Anda sudah mengajukan absensi hari ini.');
        }

        // Unggah file lampiran
        $path = $request->file('attachment')->store('attachments', 'public');

        // Simpan data absensi ke database
        Absensi::create([
            'siswa_id' => $siswa->id,
            'kelas_id' => $siswa->kelas_id,
            'tanggal' => Carbon::now()->toDateString(),
            'status' => $request->status, // Status dari form (Izin/Sakit)
            'attachment' => $path, // Simpan path file yang diunggah
        ]);

        return redirect()->route('absensi.siswa.dashboard', $siswa->nisn)
                         ->with('success', 'Pengajuan absensi berhasil dikirim!');
    }

    /**
     * Menampilkan dashboard khusus untuk siswa.
     *
     * @param  string  $nisn
     * @return \Illuminate\View\View
     */
    public function siswaDashboard($nisn)
    {
        $siswa = Siswa::where('nisn', $nisn)->firstOrFail();
        $pengumuman = Pengumuman::where('is_active', true)->get();

        return view('siswa.dashboard', compact('siswa', 'pengumuman'));
    }
}
