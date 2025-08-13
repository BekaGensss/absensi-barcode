<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PengaturanController extends Controller
{
    /**
     * Menampilkan halaman pengaturan umum.
     */
    public function pengaturanUmum()
    {
        $settings = Setting::pluck('value', 'key');
        return view('pengaturan.umum', compact('settings'));
    }

    /**
     * Memperbarui pengaturan umum.
     */
    public function updatePengaturanUmum(Request $request)
    {
        $request->validate([
            'nama_sekolah' => 'required|string|max:255',
        ]);
        
        Setting::updateOrCreate(['key' => 'nama_sekolah'], ['value' => $request->nama_sekolah]);

        return redirect()->route('pengaturan-umum')->with('success', 'Pengaturan umum berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman pengumuman.
     */
    public function pengumuman()
    {
        return view('pengaturan.pengumuman');
    }

    /**
     * Menampilkan halaman profil.
     */
    public function profil()
    {
        $user = Auth::user();
        return view('pengaturan.profil', compact('user'));
    }

    /**
     * Memperbarui data profil user.
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        
        $user->save();

        return redirect()->route('profil')->with('success', 'Profil berhasil diperbarui.');
    }
}