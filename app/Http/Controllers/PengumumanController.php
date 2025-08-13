<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\AnnouncementNotification;
use Illuminate\Support\Facades\Notification;

class PengumumanController extends Controller
{
    /**
     * Menampilkan daftar pengumuman.
     */
    public function index()
    {
        $pengumuman = Pengumuman::all();
        return view('pengumuman.index', compact('pengumuman'));
    }

    /**
     * Menampilkan form untuk membuat pengumuman baru.
     */
    public function create()
    {
        return view('pengumuman.create');
    }

    /**
     * Menyimpan pengumuman baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);
        
        $pengumuman = Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengumuman (tidak digunakan).
     */
    public function show(Pengumuman $pengumuman)
    {
        // Metode ini tidak digunakan karena pengumuman langsung ditampilkan di halaman index
    }

    /**
     * Menampilkan form untuk mengedit pengumuman.
     */
    public function edit(Pengumuman $pengumuman)
    {
        return view('pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Memperbarui pengumuman.
     */
    public function update(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    /**
     * Menghapus pengumuman.
     */
    public function destroy(Pengumuman $pengumuman)
    {
        $pengumuman->delete();
        return redirect()->route('pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }
    
    /**
     * Mengirim semua pengumuman aktif ke semua user dengan peran 'admin' dan 'guru'.
     */
    public function sendAll()
    {
        $active_announcements = Pengumuman::where('is_active', true)->get();
        $recipients = User::whereIn('role', ['admin', 'guru'])->get();

        if ($active_announcements->isNotEmpty() && $recipients->isNotEmpty()) {
            foreach ($active_announcements as $announcement) {
                Notification::send($recipients, new AnnouncementNotification($announcement));
            }
            return redirect()->back()->with('success', 'Semua pengumuman aktif berhasil dikirim.');
        }

        return redirect()->back()->with('error', 'Tidak ada pengumuman aktif atau penerima.');
    }

    /**
     * Mengirim pengumuman ke alamat email yang spesifik.
     */
    public function sendManual(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'email_tujuan' => 'required|email',
        ]);

        Notification::route('mail', $request->email_tujuan)->notify(new AnnouncementNotification($pengumuman));

        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim ke ' . $request->email_tujuan);
    }
    
    /**
     * Mengirim pengumuman ke beberapa alamat email.
     */
    public function sendToMultiple(Request $request, Pengumuman $pengumuman)
    {
        $request->validate([
            'emails' => 'required|string',
        ]);
        
        $emails = explode(',', $request->emails);
        $cleanedEmails = array_map('trim', $emails);
        
        foreach ($cleanedEmails as $email) {
            Notification::route('mail', $email)->notify(new AnnouncementNotification($pengumuman));
        }

        return redirect()->back()->with('success', 'Pengumuman berhasil dikirim ke beberapa email.');
    }
}
