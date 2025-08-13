@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
<style>
    /* Keyframes khusus untuk animasi masuk yang lebih halus */
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in-up { animation: slide-in-up 0.7s ease-out forwards; }
    
    /* Animasi bounce subtle tetap dipertahankan karena bagus */
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    .animate-bounce-subtle { animation: bounce-subtle 1s infinite; }

    /* Gaya kustom untuk block button kelola */
    @media (max-width: 768px) {
        .kelola-mobile-block {
            flex-direction: column;
            gap: 0.5rem;
        }
    }
</style>

<div class="p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-4 md:mb-0 animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
                Daftar Siswa
            </h1>
            <a href="{{ route('siswa.create') }}" class="bg-teal-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2 animate-slide-in-up" style="animation-delay: 0.4s;">
                <i class="fas fa-plus mr-2"></i>
                <span>Tambah Siswa</span>
            </a>
        </div>
        
        {{-- Notifikasi --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.6s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        {{-- Form Pencarian --}}
        <form action="{{ route('siswa.index') }}" method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 items-end animate-slide-in-up" style="animation-delay: 0.8s;">
            <div class="w-full relative">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Nama Siswa</label>
                <div class="relative mt-1">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama..." class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                </div>
            </div>
            <div class="w-full flex items-end space-x-2 lg:col-span-2">
                <button type="submit" class="w-full md:w-auto bg-teal-500 text-white px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 hover:bg-teal-600 hover:shadow-lg flex items-center justify-center space-x-2">
                    <i class="fas fa-search mr-2"></i>
                    <span>Cari</span>
                </button>
                @if(request('search'))
                    <a href="{{ route('siswa.index') }}" class="w-full md:w-auto bg-gray-300 text-gray-800 px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 hover:bg-gray-400 hover:shadow-lg flex items-center justify-center space-x-2">
                        <i class="fas fa-times mr-2"></i>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 animate-slide-in-up" style="animation-delay: 1s;">
            <table class="min-w-full bg-white table-auto rounded-xl overflow-hidden border-collapse">
                <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                    <tr class="text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">No</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">Nama Siswa</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">NISN</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">Kelas</th>
                        <th class="py-4 px-3 sm:px-6 text-center font-bold">QR Code</th>
                        <th class="py-4 px-3 sm:px-6 text-center font-bold">Kelola</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm sm:text-base font-light">
                    @forelse($siswa as $item)
                        <tr class="border-b border-gray-100 hover:bg-teal-50 transition duration-200 transform hover:scale-[1.01] hover:shadow-md">
                            <td class="py-3 px-3 sm:px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="py-3 px-3 sm:px-6 text-left">{{ $item->nama_siswa }}</td>
                            <td class="py-3 px-3 sm:px-6 text-left">{{ $item->nisn }}</td>
                            <td class="py-3 px-3 sm:px-6 text-left">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                            <td class="py-3 px-3 sm:px-6 text-center">
                                @if($item->qr_code_path)
                                    <div class="inline-block relative group">
                                        <img src="{{ asset('storage/' . $item->qr_code_path) }}" alt="QR Code" class="h-16 w-16 object-contain mx-auto rounded-md border border-gray-200 transition-transform duration-300 group-hover:scale-110">
                                        <a href="{{ route('siswa.download-qrcode', $item->id) }}" class="absolute inset-0 bg-black bg-opacity-50 text-white flex items-center justify-center text-xs font-semibold rounded-md opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                            <i class="fas fa-download mr-1"></i> Unduh
                                        </a>
                                    </div>
                                @else
                                    <span class="text-gray-400 font-medium">Tidak ada</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex md:flex-row kelola-mobile-block items-center justify-center space-x-2">
                                    <a href="{{ route('siswa.edit', $item->id) }}" class="flex-grow md:flex-none text-center bg-yellow-100 text-yellow-600 font-semibold text-sm px-4 py-2 rounded-full hover:bg-yellow-300 hover:text-yellow-800 transition-colors duration-300 transform hover:scale-105">
                                        Edit
                                    </a>
                                    <form action="{{ route('siswa.destroy', $item->id) }}" method="POST" class="flex-grow md:flex-none" onsubmit="return confirm('Apakah Anda yakin ingin menghapus siswa ini? QR Code juga akan ikut terhapus.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-center bg-red-100 text-red-600 font-semibold text-sm px-4 py-2 rounded-full hover:bg-red-300 hover:text-red-800 transition-colors duration-300 transform hover:scale-105">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-gray-500 animate-slide-in-up" style="animation-delay: 1.2s;">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-box-open text-5xl text-gray-300 mb-4 animate-bounce-subtle"></i>
                                    <p class="text-lg font-semibold text-gray-600">Tidak ada data siswa yang ditemukan.</p>
                                    <p class="text-gray-400 mt-1">Silakan tambahkan siswa baru atau coba cari dengan kata kunci lain.</p>
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