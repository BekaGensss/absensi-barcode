@extends('layouts.app')

@section('title', 'Edit Pengumuman')

@section('content')
<style>
    /* Keyframes khusus untuk animasi masuk yang lebih halus */
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in-up { animation: slide-in-up 0.7s ease-out forwards; }
</style>

<div class="p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
            Edit Pengumuman: <span class="text-teal-500">{{ $pengumuman->judul }}</span>
        </h1>
        
        <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" class="animate-slide-in-up" style="animation-delay: 0.4s;">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="relative">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">Judul Pengumuman</label>
                    <div class="relative mt-1">
                        <input type="text" name="judul" id="judul" value="{{ old('judul', $pengumuman->judul) }}" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('judul') border-red-500 ring-red-200 @enderror" required>
                        <i class="fas fa-bullhorn absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                    @error('judul')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="relative">
                    <label for="isi" class="block text-sm font-medium text-gray-700 mb-1">Isi Pengumuman</label>
                    <div class="relative mt-1">
                        <textarea name="isi" id="isi" rows="5" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('isi') border-red-500 ring-red-200 @enderror" required>{{ old('isi', $pengumuman->isi) }}</textarea>
                        <i class="fas fa-file-alt absolute left-3 top-4 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                    @error('isi')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" {{ $pengumuman->is_active ? 'checked' : '' }} class="mr-3 h-5 w-5 text-teal-600 rounded focus:ring-teal-500 cursor-pointer transition-all duration-300 ease-in-out">
                    <label for="is_active" class="text-sm font-medium text-gray-700">Aktifkan Pengumuman</label>
                </div>
            </div>
            
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="w-full sm:w-auto bg-teal-500 text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2">
                    <i class="fas fa-save mr-2"></i>
                    <span>Perbarui Data</span>
                </button>
                <a href="{{ route('pengumuman.index') }}" class="w-full sm:w-auto text-center text-gray-600 px-6 py-3 rounded-full font-semibold text-sm hover:text-gray-800 hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">
                    <i class="fas fa-chevron-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection