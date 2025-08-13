@extends('layouts.app')

@section('title', 'Tambah Wali Kelas')

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
        <div class="flex flex-col items-center text-center">
            <i class="fas fa-chalkboard-teacher text-5xl text-teal-500 mb-4 animate-slide-in-up" style="animation-delay: 0.2s;"></i>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-4 animate-slide-in-up" style="animation-delay: 0.4s; font-family: 'Inter', sans-serif;">
                Menunjuk Wali Kelas
            </h1>
            <p class="text-gray-600 mb-8 max-w-xl animate-slide-in-up" style="animation-delay: 0.6s;">
                Untuk menunjuk atau mengubah wali kelas, silakan cari dan pilih kelas yang diinginkan dari daftar yang tersedia.
            </p>
            <a href="{{ route('wali-kelas.index') }}" class="bg-teal-500 text-white px-8 py-3 rounded-full font-semibold hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md flex items-center space-x-2 animate-slide-in-up" style="animation-delay: 0.8s;">
                <i class="fas fa-chevron-left mr-2"></i>
                <span>Kembali ke Daftar Kelas</span>
            </a>
        </div>
    </div>
</div>
@endsection