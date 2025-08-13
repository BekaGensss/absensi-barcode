@extends('layouts.app')

@section('title', 'Daftar Wali Kelas')

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

    /* Gaya kustom untuk tombol Kelola di mobile */
    @media (max-width: 768px) {
        .kelola-mobile-stack {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        .kelola-mobile-stack > * {
            width: 100%;
        }
    }
</style>

<div class="p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center sm:text-left animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
            Daftar Wali Kelas
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
        
        {{-- Panduan Informasi --}}
        <div class="p-4 mb-6 rounded-xl shadow-sm text-sm text-teal-700 bg-teal-50 flex items-start space-x-3 animate-slide-in-up" style="animation-delay: 0.6s;">
            <i class="fas fa-info-circle text-lg mt-1 text-teal-500"></i>
            <p>
                Untuk menunjuk atau mengganti wali kelas, cari kelas yang diinginkan di tabel di bawah lalu klik tombol "Edit" atau "Tunjuk".
            </p>
        </div>
        
        {{-- Form Pencarian --}}
        <form action="{{ route('wali-kelas.index') }}" method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4 items-end animate-slide-in-up" style="animation-delay: 0.8s;">
            <div class="w-full relative">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Kelas</label>
                <div class="relative mt-1">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama kelas..." class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                </div>
            </div>
            <div class="w-full flex items-end space-x-2">
                <button type="submit" class="w-full md:w-auto bg-teal-500 text-white px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 hover:bg-teal-600 hover:shadow-lg flex items-center justify-center space-x-2">
                    <i class="fas fa-search mr-2"></i>
                    <span>Cari</span>
                </button>
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 animate-slide-in-up" style="animation-delay: 1s;">
            <table class="min-w-full bg-white table-auto rounded-xl overflow-hidden border-collapse">
                <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                    <tr class="text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">No</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">Nama Kelas</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">Nama Wali Kelas</th>
                        <th class="py-4 px-3 sm:px-6 text-center font-bold">Kelola</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm sm:text-base font-light">
                    @forelse($kelas as $item)
                        <tr class="border-b border-gray-100 hover:bg-teal-50 transition duration-200 transform hover:scale-[1.01] hover:shadow-md">
                            <td class="py-3 px-3 sm:px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="py-3 px-3 sm:px-6 text-left">{{ $item->nama_kelas }}</td>
                            <td class="py-3 px-3 sm:px-6 text-left">{{ $item->waliKelas->name ?? 'Belum Ditunjuk' }}</td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex md:flex-row kelola-mobile-stack items-center justify-center space-x-2">
                                    <a href="{{ route('wali-kelas.edit', $item->id) }}" class="h-8 flex items-center justify-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-300 hover:text-yellow-800 transition duration-300 font-semibold transform hover:scale-105">
                                        @if($item->waliKelas)
                                            Edit
                                        @else
                                            Tunjuk
                                        @endif
                                    </a>
                                    @if($item->waliKelas)
                                        <form action="{{ route('wali-kelas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penunjukan wali kelas ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="h-8 flex items-center justify-center px-4 py-2 rounded-full bg-red-100 text-red-600 hover:bg-red-300 hover:text-red-800 transition duration-300 font-semibold transform hover:scale-105">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-500 animate-slide-in-up" style="animation-delay: 1.2s;">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-school-flag text-5xl text-gray-300 mb-4 animate-bounce-subtle"></i>
                                    <p class="text-lg font-semibold text-gray-600">Tidak ada data kelas yang ditemukan.</p>
                                    <p class="text-gray-400 mt-1">Silakan tambahkan data kelas terlebih dahulu jika belum ada.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection