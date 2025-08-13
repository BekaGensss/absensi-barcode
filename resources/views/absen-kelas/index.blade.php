@extends('layouts.app')

@section('title', 'Absensi Manual')

@section('content')
<style>
    /* Keyframes khusus untuk animasi masuk yang lebih halus */
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in-up { animation: slide-in-up 0.7s ease-out forwards; }
    
    /* Animasi bounce subtle untuk ikon saat kosong */
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce-subtle { animation: bounce-subtle 1s infinite; }

    /* Gaya khusus untuk radio button yang diubah menjadi badge */
    .radio-badge input[type="radio"] {
        display: none;
    }
    .radio-badge input[type="radio"] + span {
        transition: all 0.2s ease-in-out;
        cursor: pointer;
        padding: 0.35rem 0.75rem;
        border-radius: 9999px;
        font-weight: 600;
        font-size: 0.875rem; /* text-sm */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
    }
    .radio-badge input[type="radio"]:not(:checked) + span {
        background-color: #f3f4f6; /* bg-gray-100 */
        color: #6b7280; /* text-gray-500 */
    }
    .radio-badge input[type="radio"]:checked + span {
        border-color: transparent;
    }
    .radio-badge input[type="radio"][value="Hadir"]:checked + span {
        background-color: #d1fae5; /* bg-green-100 */
        color: #065f46; /* text-green-700 */
    }
    .radio-badge input[type="radio"][value="Sakit"]:checked + span {
        background-color: #fef3c7; /* bg-yellow-100 */
        color: #b45309; /* text-yellow-700 */
    }
    .radio-badge input[type="radio"][value="Izin"]:checked + span {
        background-color: #dbeafe; /* bg-blue-100 */
        color: #1e40af; /* text-blue-700 */
    }
    .radio-badge input[type="radio"][value="Alfa"]:checked + span {
        background-color: #fee2e2; /* bg-red-100 */
        color: #991b1b; /* text-red-700 */
    }
</style>

<div class="p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center sm:text-left animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
            Absensi Manual Per Kelas
        </h1>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.4s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.4s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        {{-- Form Pencarian dan Filter --}}
        <form action="{{ route('absen-kelas.index') }}" method="GET" class="animate-slide-in-up" style="animation-delay: 0.6s;">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end mb-8">
                <div class="relative">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                    <div class="relative mt-1">
                        <select name="kelas_id" id="kelas_id" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $selected_kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chalkboard-teacher absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                </div>
                <div class="relative">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Siswa</label>
                    <div class="relative mt-1">
                        <input type="text" name="search" id="search" placeholder="Masukkan nama siswa..." value="{{ $search }}" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                </div>
                <div class="w-full">
                    <button type="submit" class="w-full bg-teal-500 text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2">
                        <i class="fas fa-search mr-2"></i>
                        <span>Tampilkan Siswa</span>
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabel Absensi Siswa --}}
        @if($selected_kelas_id && $siswa_list->isNotEmpty())
            <form action="{{ route('absen-kelas.store') }}" method="POST" enctype="multipart/form-data" class="animate-slide-in-up" style="animation-delay: 0.8s;">
                @csrf
                <input type="hidden" name="kelas_id" value="{{ $selected_kelas_id }}">
                <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 mt-8">
                    <table class="min-w-full bg-white table-auto rounded-xl overflow-hidden border-collapse">
                        <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                            <tr class="text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-4 px-3 sm:px-6 text-left font-bold">Nama Siswa</th>
                                <th class="py-4 px-3 sm:px-6 text-center font-bold">Kehadiran</th>
                                <th class="py-4 px-3 sm:px-6 text-center font-bold">Unggah Lampiran</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 text-sm sm:text-base font-light">
                            @foreach($siswa_list as $siswa)
                                <tr class="border-b border-gray-100 hover:bg-teal-50 transition duration-200 transform hover:scale-[1.01] hover:shadow-md">
                                    <td class="py-3 px-3 sm:px-6 text-left whitespace-nowrap">{{ $siswa->nama_siswa }}</td>
                                    <td class="py-3 px-6 text-center space-x-2 radio-badge">
                                        <label>
                                            <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="Hadir" {{ ($absensi_hari_ini[$siswa->id]->status ?? '') == 'Hadir' ? 'checked' : '' }}>
                                            <span>Hadir</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="Sakit" {{ ($absensi_hari_ini[$siswa->id]->status ?? '') == 'Sakit' ? 'checked' : '' }}>
                                            <span>Sakit</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="Izin" {{ ($absensi_hari_ini[$siswa->id]->status ?? '') == 'Izin' ? 'checked' : '' }}>
                                            <span>Izin</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="absensi[{{ $siswa->id }}][status]" value="Alfa" {{ ($absensi_hari_ini[$siswa->id]->status ?? '') == 'Alfa' ? 'checked' : '' }}>
                                            <span>Alfa</span>
                                        </label>
                                        <input type="hidden" name="absensi[{{ $siswa->id }}][siswa_id]" value="{{ $siswa->id }}">
                                    </td>
                                    <td class="py-3 px-6 text-center" id="attachment-cell-{{ $siswa->id }}">
                                        <div class="flex flex-col items-center">
                                            @if ($absensi_hari_ini[$siswa->id]->attachment ?? false)
                                                <div class="flex space-x-2 text-sm">
                                                    <a href="{{ asset('storage/' . $absensi_hari_ini[$siswa->id]->attachment) }}" target="_blank" class="text-teal-600 hover:text-teal-800 transition-colors duration-300 font-medium flex items-center">
                                                        <i class="fas fa-file-alt mr-1"></i> Lihat
                                                    </a>
                                                    <button type="button" onclick="deleteAttachment({{ $absensi_hari_ini[$siswa->id]->id }}, {{ $siswa->id }})" class="text-red-600 hover:text-red-800 transition-colors duration-300 font-medium flex items-center">
                                                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                                                    </button>
                                                </div>
                                            @else
                                                <input type="file" name="absensi[{{ $siswa->id }}][attachment]" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer transition-colors duration-300">
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-8 flex justify-end">
                    <button type="submit" class="bg-teal-500 text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center space-x-2">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan Absensi</span>
                    </button>
                </div>
            </form>
        @elseif($selected_kelas_id)
            <div class="p-4 rounded-xl shadow-sm text-sm text-yellow-700 bg-yellow-50 flex items-start space-x-3 mt-8 animate-slide-in-up" style="animation-delay: 0.8s;">
                <i class="fas fa-exclamation-circle text-lg mt-1 text-yellow-500"></i>
                <p>
                    Tidak ada data siswa di kelas ini.
                </p>
            </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[type=radio]').forEach(radio => {
            radio.addEventListener('change', (event) => {
                const siswaId = event.target.name.match(/\[(\d+)\]/)[1];
                const status = event.target.value;
                const attachmentCell = document.getElementById(`attachment-cell-${siswaId}`);

                if (status === 'Hadir') {
                    if (attachmentCell.querySelector('input[type="file"]')) {
                        attachmentCell.innerHTML = '';
                    }
                } else {
                    if (!attachmentCell.querySelector('input[type="file"]') && !attachmentCell.querySelector('a')) {
                         attachmentCell.innerHTML = `<input type="file" name="absensi[${siswaId}][attachment]" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer transition-colors duration-300">`;
                    }
                }
            });
        });
    });

    function deleteAttachment(absensiId, siswaId) {
        if (confirm('Apakah Anda yakin ingin menghapus lampiran ini?')) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch(`{{ url('absen-kelas') }}/${absensiId}/attachment`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal menghapus lampiran.');
                }
                return response.json();
            })
            .then(data => {
                const cell = document.getElementById(`attachment-cell-${data.siswaId}`);
                if (cell) {
                    cell.innerHTML = `
                        <input type="file" name="absensi[${data.siswaId}][attachment]" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer transition-colors duration-300">
                    `;
                }
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus lampiran.');
            });
        }
    }
</script>
@endsection