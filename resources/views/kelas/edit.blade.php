@extends('layouts.app')

@section('title', 'Edit Kelas')

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
            Edit Kelas: <span class="text-teal-500">{{ $kela->nama_kelas }}</span>
        </h1>
        
        <form action="{{ route('kelas.update', $kela->id) }}" method="POST" class="animate-slide-in-up" style="animation-delay: 0.4s;">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div class="relative">
                    <label for="nama_kelas" class="block text-sm font-medium text-gray-700 mb-1">Nama Kelas</label>
                    <div class="relative mt-1">
                        <input type="text" name="nama_kelas" id="nama_kelas" value="{{ old('nama_kelas', $kela->nama_kelas) }}" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-4 pr-10 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('nama_kelas') border-red-500 ring-red-200 @enderror" required>
                        <i class="fas fa-school absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                    @error('nama_kelas')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="relative">
                    <label for="wali_kelas_id" class="block text-sm font-medium text-gray-700 mb-1">Wali Kelas</label>
                    <div class="relative mt-1">
                        <select name="wali_kelas_id" id="wali_kelas_id" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-4 pr-10 py-2 text-gray-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 @error('wali_kelas_id') border-red-500 ring-red-200 @enderror">
                            <option value="">-- Pilih Wali Kelas --</option>
                            @foreach($wali_kelas as $user)
                                <option value="{{ $user->id }}" {{ old('wali_kelas_id', $kela->wali_kelas_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chalkboard-teacher absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                    @error('wali_kelas_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="tahun_ajaran_id" class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran Aktif</label>
                    <div class="relative mt-1">
                        @if($tahun_ajaran_aktif)
                            <input type="hidden" name="tahun_ajaran_id" value="{{ $tahun_ajaran_aktif->id }}">
                            <p class="block w-full px-4 py-2 rounded-lg bg-gray-50 border border-gray-300 text-gray-900 font-medium">
                                {{ $tahun_ajaran_aktif->tahun_mulai }} / {{ $tahun_ajaran_aktif->tahun_selesai }}
                            </p>
                        @else
                            <p class="block w-full px-4 py-2 rounded-lg bg-red-50 border border-red-300 text-red-700 font-medium">
                                Belum ada Tahun Ajaran aktif. Silakan tambahkan di menu Tahun Ajaran.
                            </p>
                        @endif
                    </div>
                    @error('tahun_ajaran_id')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                <button type="submit" class="w-full sm:w-auto bg-teal-500 text-white px-6 py-3 rounded-full font-semibold text-sm hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center justify-center space-x-2" {{ $tahun_ajaran_aktif ? '' : 'disabled' }}>
                    <i class="fas fa-save mr-2"></i>
                    <span>Perbarui Data</span>
                </button>
                <a href="{{ route('kelas.index') }}" class="w-full sm:w-auto text-center text-gray-600 px-6 py-3 rounded-full font-semibold text-sm hover:text-gray-800 hover:bg-gray-100 transition-colors duration-300 transform hover:scale-105">
                    <i class="fas fa-chevron-left mr-2"></i>Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection