<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\URL;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar siswa.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Memulai query builder untuk model Siswa
        $query = Siswa::with('kelas');

        // Menambahkan logika pencarian berdasarkan nama_siswa jika ada request 'search'
        if ($request->has('search')) {
            $query->where('nama_siswa', 'like', '%' . $request->search . '%');
        }

        // Mengambil data siswa dengan atau tanpa filter pencarian
        $siswa = $query->get();

        return view('siswa.index', compact('siswa'));
    }

    /**
     * Menampilkan form untuk membuat siswa baru.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', compact('kelas'));
    }

    /**
     * Menyimpan data siswa baru ke database dan membuat QR Code.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswa,nisn',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = Siswa::create($request->all());

        // Membuat dan menyimpan QR Code
        $this->generateQrCode($siswa);

        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail siswa (tidak digunakan di sini).
     * @param  \App\Models\Siswa  $siswa
     * @return void
     */
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit siswa.
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\View\View
     */
    public function edit(Siswa $siswa)
    {
        $kelas = Kelas::all();
        return view('siswa.edit', compact('siswa', 'kelas'));
    }

    /**
     * Memperbarui data siswa di database.
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nisn' => 'required|string|unique:siswa,nisn,' . $siswa->id,
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        // Jika NISN berubah, hapus QR Code lama dan buat yang baru
        if ($siswa->nisn !== $request->nisn) {
            if ($siswa->qr_code_path) {
                Storage::disk('public')->delete($siswa->qr_code_path);
            }
            $siswa->update($request->all());
            $this->generateQrCode($siswa);
        } else {
            $siswa->update($request->all());
        }

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    /**
     * Menghapus data siswa dan file QR Code.
     * @param  \App\Models\Siswa  $siswa
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Siswa $siswa)
    {
        // Hapus file QR Code dari storage
        if ($siswa->qr_code_path) {
            Storage::disk('public')->delete($siswa->qr_code_path);
        }
        
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    /**
     * Mengunduh QR Code siswa.
     * @param  \App\Models\Siswa  $siswa
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function downloadQrCode(Siswa $siswa)
    {
        if ($siswa->qr_code_path) {
            $filePath = storage_path('app/public/' . $siswa->qr_code_path);
            if (file_exists($filePath)) {
                return response()->download($filePath, 'qrcode-' . $siswa->nisn . '.svg');
            }
        }

        return redirect()->back()->with('error', 'QR Code tidak ditemukan.');
    }

    /**
     * Fungsi untuk membuat dan menyimpan QR Code.
     * @param  \App\Models\Siswa  $siswa
     * @return void
     */
    protected function generateQrCode(Siswa $siswa)
    {
        // Pastikan direktori 'qrcodes' ada di dalam storage/app/public
        if (!Storage::disk('public')->exists('qrcodes')) {
            Storage::disk('public')->makeDirectory('qrcodes');
        }
        
        // Membentuk URL unik untuk absensi mandiri menggunakan IP lokal
        // Catatan: Pastikan IP ini dapat diakses oleh perangkat yang akan memindai QR.
        $absensiUrl = "http://192.168.0.195:8000" . route('absensi.mandiri', ['nisn' => $siswa->nisn], false);
        
        // Nama file QR Code
        $fileName = 'qrcodes/' . $siswa->nisn . '.svg';
        
        // Menghasilkan dan menyimpan QR Code
        QrCode::size(200)
             ->style('round')
             ->margin(1)
             ->format('svg')
             ->generate($absensiUrl, storage_path('app/public/' . $fileName));
             
        // Menyimpan path file ke database
        $siswa->qr_code_path = $fileName;
        $siswa->save();
    }
}
