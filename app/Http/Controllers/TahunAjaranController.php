<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
    /**
     * Menampilkan daftar tahun ajaran.
     */
    public function index()
    {
        // Mengambil semua data tahun ajaran dari database
        $tahun_ajaran = TahunAjaran::all();
        // Mengirim data ke view
        return view('tahun-ajaran.index', compact('tahun_ajaran'));
    }

    /**
     * Menampilkan form untuk membuat tahun ajaran baru.
     */
    public function create()
    {
        return view('tahun-ajaran.create');
    }

    /**
     * Menyimpan data tahun ajaran baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data dari form
        $request->validate([
            'tahun_mulai' => 'required|integer',
            'tahun_selesai' => 'required|integer|gt:tahun_mulai',
        ]);
        
        // Jika checkbox 'is_active' dicentang
        if ($request->has('is_active')) {
            // Non-aktifkan semua tahun ajaran lain
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
        }

        // Membuat data baru
        TahunAjaran::create([
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail tahun ajaran (tidak digunakan di sini).
     */
    public function show(TahunAjaran $tahunAjaran)
    {
        //
    }

    /**
     * Menampilkan form untuk mengedit tahun ajaran.
     */
    public function edit(TahunAjaran $tahunAjaran)
    {
        return view('tahun-ajaran.edit', compact('tahunAjaran'));
    }

    /**
     * Memperbarui data tahun ajaran di database.
     */
    public function update(Request $request, TahunAjaran $tahunAjaran)
    {
        // Validasi data
        $request->validate([
            'tahun_mulai' => 'required|integer',
            'tahun_selesai' => 'required|integer|gt:tahun_mulai',
        ]);

        // Jika checkbox 'is_active' dicentang
        if ($request->has('is_active')) {
            // Non-aktifkan semua tahun ajaran lain
            TahunAjaran::where('is_active', true)->update(['is_active' => false]);
        }
        
        // Memperbarui data
        $tahunAjaran->update([
            'tahun_mulai' => $request->tahun_mulai,
            'tahun_selesai' => $request->tahun_selesai,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    /**
     * Menghapus data tahun ajaran.
     */
    public function destroy(TahunAjaran $tahunAjaran)
    {
        $tahunAjaran->delete();
        return redirect()->route('tahun-ajaran.index')->with('success', 'Tahun ajaran berhasil dihapus.');
    }
}