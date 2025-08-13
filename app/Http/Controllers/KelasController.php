<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Menampilkan daftar kelas.
     */
    public function index()
    {
        // Mengambil semua data kelas dengan relasi wali kelas dan tahun ajaran
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])->get();
        // Mengirim data ke view
        return view('kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form untuk membuat kelas baru.
     */
    public function create()
    {
        // Mengambil data wali kelas (user) yang bisa ditunjuk
        $wali_kelas = User::all();
        // Mengambil data tahun ajaran yang aktif
        $tahun_ajaran_aktif = TahunAjaran::where('is_active', true)->first();
        
        return view('kelas.create', compact('wali_kelas', 'tahun_ajaran_aktif'));
    }

    /**
     * Menyimpan data kelas baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data dari form
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        // Membuat data baru
        Kelas::create([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail kelas (tidak digunakan di sini).
     */
    public function show(Kelas $kela)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit kelas.
     */
    public function edit(Kelas $kela)
    {
        // Mengambil data wali kelas (user) yang bisa ditunjuk
        $wali_kelas = User::all();
        // Mengambil data tahun ajaran yang aktif
        $tahun_ajaran_aktif = TahunAjaran::where('is_active', true)->first();
        
        return view('kelas.edit', compact('kela', 'wali_kelas', 'tahun_ajaran_aktif'));
    }

    /**
     * Memperbarui data kelas di database.
     */
    public function update(Request $request, Kelas $kela)
    {
        // Validasi data
        $request->validate([
            'nama_kelas' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:users,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);
        
        // Memperbarui data
        $kela->update([
            'nama_kelas' => $request->nama_kelas,
            'wali_kelas_id' => $request->wali_kelas_id,
            'tahun_ajaran_id' => $request->tahun_ajaran_id,
        ]);

        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    /**
     * Menghapus data kelas.
     */
    public function destroy(Kelas $kela)
    {
        $kela->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}