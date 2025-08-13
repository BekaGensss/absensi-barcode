<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Import semua Controller yang dibutuhkan
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\WaliKelasController;
use App\Http\Controllers\AbsenKelasController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\RekapAbsensiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PengumumanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda.
| Rute-rute ini dimuat oleh RouteServiceProvider dan akan
| diberikan grup middleware "web".
|
*/

// Halaman utama yang tidak memerlukan otentikasi (publik)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rute untuk otentikasi (login, register, dll.)
Auth::routes();

// Rute Absensi Mandiri untuk Siswa (Publik)
// Rute ini tidak dilindungi middleware 'auth' karena siswa belum login
Route::get('/absensi-mandiri/{nisn}', [AbsensiController::class, 'mandiri'])->name('absensi.mandiri');
Route::post('/absensi-mandiri/{nisn}/absen', [AbsensiController::class, 'absenMandiri'])->name('absensi.absenMandiri');
Route::post('/absensi-mandiri/{nisn}/ajukan-absen', [AbsensiController::class, 'ajukanAbsen'])->name('absensi.ajukanAbsen');
Route::get('/siswa-dashboard/{nisn}', [AbsensiController::class, 'siswaDashboard'])->name('absensi.siswa.dashboard');

// --- Grup rute yang hanya bisa diakses setelah login ---
Route::middleware(['auth'])->group(function () {

    // Rute Home utama, akan langsung mengarahkan ke dashboard
    Route::get('/home', function () {
        return redirect()->route('dashboard');
    })->name('home');

    // Rute untuk profil pengguna (dapat diakses oleh semua pengguna yang login)
    Route::get('/profil', [PengaturanController::class, 'profil'])->name('profil');
    Route::put('/profil/update', [PengaturanController::class, 'updateProfil'])->name('profil.update');

    
    // --- Grup rute yang bisa diakses oleh Admin & Guru ---
    Route::middleware(['checkrole:admin,guru'])->group(function () {

        // Menu UTAMA & REKAP
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::get('/kehadiran', [RekapAbsensiController::class, 'kehadiran'])->name('kehadiran');
        Route::get('/rekap-absensi', [RekapAbsensiController::class, 'rekapAbsensi'])->name('rekap-absensi');
        Route::get('/rekap-absensi/export', [RekapAbsensiController::class, 'export'])->name('rekap-absensi.export');
        
        // --- PENAMBAHAN RUTE YANG DIBUTUHKAN ---
        Route::get('/absensi/export/excel', [RekapAbsensiController::class, 'exportExcel'])->name('absensi.export.excel');
        
        // Menu ABSEN QR
        Route::get('/absensi-qr', [AbsensiController::class, 'scanner'])->name('absensi-qr.scanner');
        Route::post('/absensi-qr/scan', [AbsensiController::class, 'scan'])->name('absensi-qr.scan');
    });
    
    // --- Grup rute yang hanya bisa diakses oleh Admin ---
    Route::middleware(['checkrole:admin'])->group(function () {
        
        // Rute untuk mengunduh QR Code siswa harus diletakkan di atas rute resource siswa
        Route::get('/siswa/{siswa}/download-qrcode', [SiswaController::class, 'downloadQrCode'])->name('siswa.download-qrcode');
        // Rute resource untuk siswa
        Route::resource('siswa', SiswaController::class);

        // Rute khusus untuk menghapus lampiran harus diletakkan di atas rute resource absen-kelas
        // Ini adalah urutan yang sudah benar untuk route yang lebih spesifik
        Route::delete('/absen-kelas/{absensi}/attachment', [AbsenKelasController::class, 'deleteAttachment'])->name('absen-kelas.delete-attachment');
        
        // Rute resource untuk absen-kelas
        // Route ini secara otomatis menciptakan route DELETE /absen-kelas/{id} yang memanggil metode 'destroy' di controller
        Route::resource('absen-kelas', AbsenKelasController::class);

        // Rute resource lainnya
        Route::resource('tahun-ajaran', TahunAjaranController::class);
        Route::resource('kelas', KelasController::class);
        Route::resource('wali-kelas', WaliKelasController::class)->parameters(['wali-kelas' => 'waliKelas']);
        
        // Menu PENGATURAN & Pengumuman (hanya untuk Admin)
        Route::resource('users', UserController::class);
        Route::get('/pengaturan-umum', [PengaturanController::class, 'pengaturanUmum'])->name('pengaturan-umum');
        Route::put('/pengaturan-umum/update', [PengaturanController::class, 'updatePengaturanUmum'])->name('pengaturan-umum.update');
        Route::post('/pengumuman/send-all', [PengumumanController::class, 'sendAll'])->name('pengumuman.send-all');
        Route::post('/pengumuman/{pengumuman}/send-manual', [PengumumanController::class, 'sendManual'])->name('pengumuman.send-manual');
        Route::post('/pengumuman/{pengumuman}/send-multiple', [PengumumanController::class, 'sendToMultiple'])->name('pengumuman.send-multiple');
        Route::resource('pengumuman', PengumumanController::class);
    });
});
