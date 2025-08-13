<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    /**
     * Menampilkan daftar kelas dan wali kelas yang mengampunya, dengan filter.
     */
    public function index(Request $request)
    {
        $query = Kelas::with('waliKelas');

        // Tambahkan logika filter jika ada input pencarian
        if ($request->has('search')) {
            $query->where('nama_kelas', 'like', '%' . $request->search . '%');
        }

        $kelas = $query->get();
        return view('wali-kelas.index', compact('kelas'));
    }

    /**
     * Menampilkan form untuk menunjuk/mengubah wali kelas.
     * Kita akan menggunakan metode ini untuk fungsi 'Tambah' dan 'Edit'.
     */
    public function create()
    {
        // Redirect ke daftar kelas untuk memilih kelas mana yang akan di-assign wali kelas
        return redirect()->route('wali-kelas.index');
    }

    /**
     * Menampilkan form untuk mengedit penunjukan wali kelas.
     */
    public function edit(Kelas $waliKelas)
    {
        $users = User::all();
        return view('wali-kelas.edit', compact('waliKelas', 'users'));
    }

    /**
     * Memperbarui penunjukan wali kelas.
     */
    public function update(Request $request, Kelas $waliKelas)
    {
        $request->validate([
            'wali_kelas_id' => 'required|exists:users,id',
        ]);
        
        $waliKelas->update(['wali_kelas_id' => $request->wali_kelas_id]);

        return redirect()->route('wali-kelas.index')->with('success', 'Wali kelas berhasil diperbarui.');
    }

    /**
     * Menghapus penunjukan wali kelas.
     */
    public function destroy(Kelas $waliKelas)
    {
        $waliKelas->update(['wali_kelas_id' => null]);
        return redirect()->route('wali-kelas.index')->with('success', 'Wali kelas berhasil dihapus dari kelas ini.');
    }
}