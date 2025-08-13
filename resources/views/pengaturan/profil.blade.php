@extends('layouts.app')

@section('title', 'Profil Pengguna')

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
        <div class="text-center mb-8">
            <div class="mx-auto w-24 h-24 bg-gradient-to-br from-teal-500 to-teal-600 rounded-full flex items-center justify-center text-white text-4xl font-bold shadow-lg animate-slide-in-up" style="animation-delay: 0.2s;">
                <i class="fas fa-user-circle text-4xl"></i>
            </div>
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mt-4 tracking-tight animate-slide-in-up" style="animation-delay: 0.4s; font-family: 'Inter', sans-serif;">Pengaturan Profil</h1>
            <p class="text-gray-500 mt-2 text-lg animate-slide-in-up" style="animation-delay: 0.6s;">Perbarui informasi pribadi dan kata sandi Anda</p>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl relative mb-6 shadow-md animate-slide-in-up" role="alert" style="animation-delay: 0.8s;">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-check-circle text-2xl"></i>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('profil.update') }}" method="POST" class="animate-slide-in-up" style="animation-delay: 1s;">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="relative">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative mt-1">
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-300 @error('name') border-red-500 ring-red-200 @enderror" placeholder="Masukkan nama lengkap Anda" required>
                        <i class="fas fa-user-circle absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition duration-300"></i>
                    </div>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                    <div class="relative mt-1">
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-300 @error('email') border-red-500 ring-red-200 @enderror" placeholder="contoh@email.com" required>
                        <i class="fas fa-envelope absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition duration-300"></i>
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border-t border-gray-200 mt-8 pt-8 space-y-6">
                <p class="text-gray-500 text-sm text-center font-medium">Kosongkan kolom di bawah jika Anda tidak ingin mengubah kata sandi.</p>
                
                <div class="relative">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi Baru</label>
                    <div class="relative mt-1">
                        <input type="password" name="password" id="password" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-300 @error('password') border-red-500 ring-red-200 @enderror" placeholder="Isi untuk mengubah kata sandi">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition duration-300"></i>
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="relative">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                    <div class="relative mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-colors duration-300" placeholder="Ulangi kata sandi baru">
                        <i class="fas fa-lock absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 transition duration-300"></i>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" class="w-full bg-teal-500 text-white px-6 py-3 rounded-full font-semibold shadow-md hover:bg-teal-600 transform hover:scale-105 transition-all duration-300 ease-in-out flex items-center justify-center space-x-2">
                    <i class="fas fa-save mr-2"></i>
                    <span>Perbarui Profil</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection