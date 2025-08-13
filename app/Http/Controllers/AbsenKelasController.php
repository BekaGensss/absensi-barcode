<?php
// File: app/Http/Controllers/AbsenKelasController.php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AbsenKelasController extends Controller
{
    public function index(Request $request)
    {
        $kelas = Kelas::all();
        $selected_kelas_id = $request->input('kelas_id');
        $siswa_list = collect();
        $search = $request->input('search');

        if ($selected_kelas_id) {
            $query = Siswa::where('kelas_id', $selected_kelas_id);
            
            // Tambahkan logika filter pencarian
            if ($search) {
                $query->where('nama_siswa', 'like', '%' . $search . '%');
            }
            
            $siswa_list = $query->get();
        }

        $absensi_hari_ini = Absensi::where('kelas_id', $selected_kelas_id)
                                    ->whereDate('tanggal', Carbon::today())
                                    ->get()
                                    ->keyBy('siswa_id');

        return view('absen-kelas.index', compact('kelas', 'selected_kelas_id', 'siswa_list', 'absensi_hari_ini', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'absensi' => 'required|array',
            'absensi.*.siswa_id' => 'required|exists:siswa,id',
            'absensi.*.status' => 'required|in:Hadir,Sakit,Izin,Alfa',
            'absensi.*.attachment' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
        ]);
        
        $tanggal = Carbon::today()->toDateString();
        $waktu = Carbon::now();

        try {
            foreach ($request->absensi as $siswaId => $data) {
                $attachmentPath = null;
                
                if ($request->hasFile("absensi.$siswaId.attachment")) {
                    $file = $request->file("absensi.$siswaId.attachment");
                    $attachmentPath = $file->store('attachments', 'public');
                }

                Absensi::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'tanggal' => $tanggal
                    ],
                    [
                        'kelas_id' => $request->kelas_id,
                        'status' => $data['status'],
                        'waktu_absen' => $waktu,
                        'attachment' => $attachmentPath,
                    ]
                );
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan absensi: ' . $e->getMessage());
        }

        return redirect()->route('absen-kelas.index')->with('success', 'Absensi berhasil disimpan.');
    }

    public function deleteAttachment(Absensi $absensi)
    {
        if ($absensi->attachment) {
            Storage::disk('public')->delete($absensi->attachment);
            $absensi->attachment = null;
            $absensi->save();
            return response()->json([
                'success' => true,
                'message' => 'Lampiran berhasil dihapus.',
                'absensiId' => $absensi->id,
                'siswaId' => $absensi->siswa_id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus lampiran.'
        ], 404);
    }

    public function show($id) { abort(404); }
    public function create() { abort(404); }
    public function edit($id) { abort(404); }
    public function update(Request $request, $id) { abort(404); }
    public function destroy($id) { abort(404); }
}
