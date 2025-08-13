@extends('layouts.app')

@section('title', 'Rekap Absensi Bulanan')

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

    /* Loading spinner kustom */
    .loading-spinner {
        width: 1.25rem; height: 1.25rem;
        border: 3px solid rgba(255, 255, 255, 0.7);
        border-bottom-color: #fff; border-radius: 50%;
        display: inline-block; box-sizing: border-box;
        animation: rotation 1s linear infinite;
    }
    @keyframes rotation { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>

<div class="p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
            Rekapitulasi Absensi Bulanan
        </h1>

        {{-- Notifikasi --}}
        @if(session('error'))
            <div class="bg-red-50 border border-red-300 text-red-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.4s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.4s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Form Filter --}}
        <form action="{{ route('rekap-absensi') }}" method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end animate-slide-in-up" style="animation-delay: 0.4s;" id="filter-form">
            <div class="w-full relative">
                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas</label>
                <div class="relative mt-1">
                    <select name="kelas_id" id="kelas_id" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-chalkboard-teacher absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                </div>
            </div>
            <div class="w-full relative">
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Pilih Bulan</label>
                <div class="relative mt-1">
                    <select name="bulan" id="bulan" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                        @php
                            $nama_bulan = [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
                        @endphp
                        @foreach($nama_bulan as $num => $name)
                            <option value="{{ $num }}" {{ $bulan == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                </div>
            </div>
            <div class="w-full relative">
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <div class="relative mt-1">
                    <input type="number" name="tahun" id="tahun" value="{{ $tahun }}" placeholder="Masukkan tahun" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                    <i class="fas fa-calendar absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                </div>
            </div>
            <div class="w-full flex items-end">
                <button type="submit" id="filter-button" class="w-full bg-teal-500 text-white px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 hover:bg-teal-600 hover:shadow-lg flex items-center justify-center space-x-2">
                    <span class="loading-spinner hidden"></span>
                    <span id="button-text"><i class="fas fa-filter mr-2"></i> Filter Data</span>
                </button>
            </div>
        </form>

        {{-- Area Hasil Rekap --}}
        @if($absensi_rekap->isEmpty() && $kelas_id)
            <div class="p-8 mb-4 text-center bg-yellow-50 rounded-xl border border-yellow-200 shadow-md animate-slide-in-up" style="animation-delay: 0.6s;">
                <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4 animate-bounce-subtle"></i>
                <p class="text-xl font-semibold text-yellow-800">Ups! Tidak ada data absensi.</p>
                <p class="text-gray-600 mt-2">Belum ada absensi yang tercatat untuk kelas dan periode yang Anda pilih. Silakan periksa kembali filter Anda.</p>
            </div>
        @elseif(!$kelas_id)
            <div class="p-8 mb-4 text-center bg-teal-50 rounded-xl border border-teal-200 shadow-md animate-slide-in-up" style="animation-delay: 0.6s;">
                <i class="fas fa-arrow-up text-4xl text-teal-500 mb-4 animate-bounce-subtle"></i>
                <p class="text-xl font-semibold text-teal-800">Mulai Rekap Absensi</p>
                <p class="text-gray-600 mt-2">Silakan pilih kelas dan bulan di atas untuk menampilkan data rekap kehadiran.</p>
            </div>
        @else
            <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 animate-slide-in-up" style="animation-delay: 0.6s;">
                <table class="min-w-full bg-white table-auto rounded-xl overflow-hidden border-collapse">
                    <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                        <tr class="text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-4 px-3 sm:px-6 text-left font-bold">No</th>
                            <th class="py-4 px-3 sm:px-6 text-left font-bold">Nama Siswa</th>
                            <th class="py-4 px-3 sm:px-6 text-center font-bold">Hadir</th>
                            <th class="py-4 px-3 sm:px-6 text-center font-bold">Sakit</th>
                            <th class="py-4 px-3 sm:px-6 text-center font-bold">Izin</th>
                            <th class="py-4 px-3 sm:px-6 text-center font-bold">Alfa</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 text-sm sm:text-base font-light">
                        @foreach($absensi_rekap as $siswa_id => $absen_data)
                            <tr class="border-b border-gray-100 hover:bg-teal-50 transition duration-200 transform hover:scale-[1.01] hover:shadow-md">
                                <td class="py-3 px-3 sm:px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                <td class="py-3 px-3 sm:px-6 text-left">{{ $absen_data->first()->siswa->nama_siswa ?? '-' }}</td>
                                <td class="py-3 px-3 sm:px-6 text-center font-semibold text-lg text-green-600">{{ $absen_data->where('status', 'Hadir')->count() }}</td>
                                <td class="py-3 px-3 sm:px-6 text-center font-semibold text-lg text-yellow-600">{{ $absen_data->where('status', 'Sakit')->count() }}</td>
                                <td class="py-3 px-3 sm:px-6 text-center font-semibold text-lg text-sky-600">{{ $absen_data->where('status', 'Izin')->count() }}</td>
                                <td class="py-3 px-3 sm:px-6 text-center font-semibold text-lg text-red-600">{{ $absen_data->where('status', 'Alfa')->count() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-end animate-slide-in-up" style="animation-delay: 0.8s;">
                <a href="{{ route('rekap-absensi.export', ['kelas_id' => $kelas_id, 'bulan' => $bulan, 'tahun' => $tahun]) }}" class="bg-emerald-500 text-white px-6 py-3 rounded-full font-semibold text-sm transition-all duration-300 hover:bg-emerald-600 hover:shadow-xl flex items-center justify-center space-x-2 transform hover:scale-105">
                    <i class="fas fa-file-excel mr-2"></i>
                    <span>Ekspor ke Excel</span>
                </a>
            </div>
        @endif
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filter-form');
        const filterButton = document.getElementById('filter-button');
        const buttonText = document.getElementById('button-text');
        const loadingSpinner = filterButton.querySelector('.loading-spinner');

        // Tangani submit formulir
        form.addEventListener('submit', function() {
            filterButton.disabled = true;
            filterButton.classList.remove('bg-teal-500', 'hover:bg-teal-600');
            filterButton.classList.add('bg-teal-300', 'cursor-not-allowed');

            loadingSpinner.classList.remove('hidden');
            buttonText.innerHTML = 'Memuat...';
        });

        // Atur ulang tombol saat halaman dimuat kembali dari cache browser
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                filterButton.disabled = false;
                filterButton.classList.remove('bg-teal-300', 'cursor-not-allowed');
                filterButton.classList.add('bg-teal-500', 'hover:bg-teal-600');
                loadingSpinner.classList.add('hidden');
                buttonText.innerHTML = '<i class="fas fa-filter mr-2"></i> Filter Data';
            }
        });
    });
</script>
@endsection