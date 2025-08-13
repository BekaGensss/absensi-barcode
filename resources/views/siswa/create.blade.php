@extends('layouts.app')

@section('title', 'Tambah Siswa')

@section('content')
<style>
    /* Custom keyframes for a smoother, more deliberate entrance animation */
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in-up { animation: slide-in-up 0.7s ease-out forwards; }
</style>

<div class="p-4 sm:p-8">
    <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
            Tambah Siswa Baru
        </h1>
        
        <form action="{{ route('siswa.store') }}" method="POST" class="animate-slide-in-up" style="animation-delay: 0.4s;">
            @csrf
            
            <div class="space-y-6">
                <div class="relative">
                    <label for="nama_siswa" class="block text-sm font-medium text-gray-700 mb-1">Nama Siswa</label>
                    <div class="relative mt-1">
                        <input type="text" name="nama_siswa" id="nama_siswa" value="{{ old('nama_siswa') }}" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('nama_siswa') border-red-500 ring-red-200 @enderror" required>
                        <i class="fas fa-user-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors duration-300"></i>
                    </div>
                    @error('nama_siswa')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="relative">
                    <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">NISN</label>
                    <div class="relative mt-1">
                        <input type="text" name="nisn" id="nisn" value="{{ old('nisn') }}" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('nisn') border-red-500 ring-red-200 @enderror" required>
                        <i class="fas fa-id-card absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors duration-300"></i>
                    </div>
                    @error('nisn')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="relative">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                    <div class="relative mt-1">
                        <select name="kelas_id" id="kelas_id" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('kelas_id') border-red-500 ring-red-200 @enderror" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $item)
                                <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-graduation-cap absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition-colors duration-300"></i>
                    </div>
                    @error('kelas_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="w-full sm:w-auto bg-teal-500 text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2">
                    <i class="fas fa-plus mr-2"></i>
                    <span>Tambah Data</span>
                </button>
                <a href="{{ route('siswa.index') }}" class="w-full sm:w-auto text-center text-gray-600 px-6 py-3 rounded-full font-semibold text-sm hover:text-gray-800 hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">
                    <i class="fas fa-chevron-left mr-2"></i>Kembali ke Daftar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection