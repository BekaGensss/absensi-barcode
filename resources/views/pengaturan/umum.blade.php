@extends('layouts.app')

@section('title', 'Pengaturan Umum')

@section('content')
<style>
    /* Keyframes khusus untuk animasi masuk yang lebih halus */
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in-up { animation: slide-in-up 0.7s ease-out forwards; }
</style>

<div class="flex items-center justify-center min-h-screen bg-gray-50 p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 max-w-2xl w-full border-t-8 border-teal-500 animate-slide-in-up">
        <div class="flex items-center justify-center space-x-4 mb-8 animate-slide-in-up" style="animation-delay: 0.2s;">
            <div class="p-3 bg-teal-500 rounded-full text-white shadow-lg">
                <i class="fas fa-cogs text-3xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 tracking-tight" style="font-family: 'Inter', sans-serif;">Pengaturan Umum</h1>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.4s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif
        
        <form action="{{ route('pengaturan-umum.update') }}" method="POST" class="animate-slide-in-up" style="animation-delay: 0.6s;">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="relative">
                    <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah</label>
                    <div class="relative mt-1">
                        <input type="text" name="nama_sekolah" id="nama_sekolah" value="{{ old('nama_sekolah', $settings['nama_sekolah'] ?? '') }}" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('nama_sekolah') border-red-500 ring-red-200 @enderror" required>
                        <i class="fas fa-building absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                    @error('nama_sekolah')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-8 flex justify-end">
                <button type="submit" class="w-full sm:w-auto bg-teal-500 text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2">
                    <i class="fas fa-save mr-2"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection