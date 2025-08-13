@extends('layouts.app')

@section('title', 'Tahun Ajaran')

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
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-4 md:mb-0 animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
                Daftar Tahun Ajaran
            </h1>
            <a href="{{ route('tahun-ajaran.create') }}" class="w-full md:w-auto bg-teal-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2 animate-slide-in-up" style="animation-delay: 0.4s;">
                <i class="fas fa-plus mr-2"></i>
                <span>Tambah Tahun Ajaran</span>
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

        <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 animate-slide-in-up" style="animation-delay: 0.8s;">
            <table class="min-w-full bg-white table-auto rounded-xl overflow-hidden border-collapse">
                <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                    <tr class="text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">No</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">Tahun Ajaran</th>
                        <th class="py-4 px-3 sm:px-6 text-left font-bold">Status</th>
                        <th class="py-4 px-3 sm:px-6 text-center font-bold">Kelola</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 text-sm sm:text-base font-light">
                    @forelse($tahun_ajaran as $item)
                        <tr class="border-b border-gray-100 hover:bg-teal-50 transition duration-200 transform hover:scale-[1.01] hover:shadow-md">
                            <td class="py-3 px-3 sm:px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                            <td class="py-3 px-3 sm:px-6 text-left">{{ $item->tahun_mulai }} / {{ $item->tahun_selesai }}</td>
                            <td class="py-3 px-6 text-left">
                                @if($item->is_active)
                                    <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-sm font-semibold">Aktif</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded-full text-sm font-semibold">Non-aktif</span>
                                @endif
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex md:flex-row kelola-mobile-stack items-center justify-center space-x-2">
                                    <a href="{{ route('tahun-ajaran.edit', $item->id) }}" class="h-8 flex items-center justify-center px-4 py-2 rounded-full bg-yellow-100 text-yellow-600 hover:bg-yellow-300 hover:text-yellow-800 transition duration-300 font-semibold transform hover:scale-105">
                                        Edit
                                    </a>
                                    <form action="{{ route('tahun-ajaran.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 flex items-center justify-center px-4 py-2 rounded-full bg-red-100 text-red-600 hover:bg-red-300 hover:text-red-800 transition duration-300 font-semibold transform hover:scale-105">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-center text-gray-500 animate-slide-in-up" style="animation-delay: 1.2s;">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-alt text-5xl text-gray-300 mb-4 animate-bounce-subtle"></i>
                                    <p class="text-lg font-semibold text-gray-600">Tidak ada data tahun ajaran.</p>
                                    <p class="text-gray-400 mt-1">Silakan tambahkan tahun ajaran baru untuk memulai.</p>
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