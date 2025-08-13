@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-center items-center min-h-screen">
        {{-- Mengubah lebar card menjadi lebih lebar (max-w-2xl) --}}
        <div class="w-full max-w-2xl">
            <!-- Card utama untuk form -->
            {{-- Mengurangi padding vertikal (p-8 md:p-12) menjadi p-6 md:p-8 --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-2xl border border-gray-100 backdrop-filter backdrop-blur-sm animate-card-in">
                
                <!-- Bagian visual dengan ikon di atas form -->
                <div class="text-center mb-6 md:mb-8">
                    <i class="fas fa-lock text-5xl md:text-6xl text-indigo-600 mb-2 md:mb-4 animate-title-in" style="animation-delay: 0.2s;"></i>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight animate-title-in">{{ __('Reset Kata Sandi') }}</h1>
                    <p class="text-gray-500 mt-2 text-sm animate-title-in" style="animation-delay: 0.4s;">{{ __('Silakan masukkan kata sandi baru Anda.') }}</p>
                </div>

                <!-- Bagian form -->
                <div>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Address -->
                        <div class="mb-4 animate-input-in" style="--order: 1;">
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Alamat Email') }}</label>
                            <input id="email" type="email" class="w-full px-5 py-3 border rounded-xl transition-all duration-300 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:border-indigo-400" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="text-red-500 text-xs mt-2 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-4 animate-input-in" style="--order: 2;">
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Kata Sandi Baru') }}</label>
                            <div class="relative">
                                <input id="password" type="password" class="w-full px-5 py-3 border rounded-xl transition-all duration-300 {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:border-indigo-400" name="password" required autocomplete="new-password">
                                <i id="password-toggle" class="fa-solid fa-eye password-toggle-icon"></i>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-xs mt-2 block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-6 animate-input-in" style="--order: 3;">
                            <label for="password-confirm" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Konfirmasi Kata Sandi') }}</label>
                            <div class="relative">
                                <input id="password-confirm" type="password" class="w-full px-5 py-3 border rounded-xl transition-all duration-300 border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 hover:border-indigo-400" name="password_confirmation" required autocomplete="new-password">
                                <i id="password-confirm-toggle" class="fa-solid fa-eye password-toggle-icon"></i>
                            </div>
                        </div>

                        <!-- Tombol Reset Password -->
                        <div class="flex items-center justify-center mt-6 animate-input-in" style="--order: 4;">
                            <button type="submit" class="w-full px-6 py-3 text-lg font-bold rounded-full text-white bg-indigo-600 shadow-lg transition-all duration-300 hover:bg-indigo-700 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                {{ __('Reset Kata Sandi') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Tambahkan script dan style di sini --}}
<style>
    /* Mengubah gaya body agar lebih profesional dan menambahkan background */
    body {
        font-family: 'Inter', sans-serif;
        background-image: url('{{ asset('images/bg_sekolah.png') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        position: relative;
        z-index: 1;
    }
    body::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4); /* Overlay gelap */
        z-index: -1;
    }
    
    .min-h-screen {
        min-height: 100vh;
        background-color: transparent; /* Pastikan tidak ada warna latar belakang ganda */
    }

    /* Efek fokus input yang lebih modern */
    input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    }
    
    .password-toggle-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        cursor: pointer;
        transition: color 0.3s ease;
        z-index: 2;
    }
    .password-toggle-icon:hover {
        color: #4a4a4a;
    }
    
    /* Gaya tombol yang lebih menarik dengan transisi */
    .btn-primary {
        background-image: linear-gradient(to right, #4f46e5, #3b82f6);
        box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        transform: translateY(0);
    }
    .btn-primary:hover {
        transform: translateY(-3px); /* Efek angkat tombol */
        box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.2);
    }
    .btn-primary:active {
        transform: translateY(0);
        box-shadow: none;
    }

    /* Keyframes untuk animasi */
    @keyframes card-in {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.98);
            filter: blur(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
            filter: blur(0);
        }
    }
    
    @keyframes title-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes input-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Animasi dengan delay */
    .animate-card-in { animation: card-in 0.8s ease-out forwards; }
    .animate-title-in { animation: title-in 0.6s ease-out 0.4s forwards; opacity: 0; }
    .animate-input-in { animation: input-in 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards; opacity: 0; }
    
    /* Menerapkan delay pada setiap input agar muncul secara berurutan */
    .animate-input-in:nth-child(1) { animation-delay: 0.6s; }
    .animate-input-in:nth-child(2) { animation-delay: 0.8s; }
    .animate-input-in:nth-child(3) { animation-delay: 1.0s; }
    .animate-input-in:nth-child(4) { animation-delay: 1.2s; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Logika untuk menampilkan/menyembunyikan password
        function setupPasswordToggle(inputElementId, toggleElementId) {
            const passwordInput = document.getElementById(inputElementId);
            const passwordToggle = document.getElementById(toggleElementId);

            if (passwordInput && passwordToggle) {
                passwordToggle.addEventListener('click', () => {
                    const isPassword = passwordInput.getAttribute('type') === 'password';
                    passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                    
                    if (isPassword) {
                        passwordToggle.classList.remove('fa-eye');
                        passwordToggle.classList.add('fa-eye-slash');
                    } else {
                        passwordToggle.classList.remove('fa-eye-slash');
                        passwordToggle.classList.add('fa-eye');
                    }
                });
            }
        }
        
        // Panggil fungsi untuk kedua input password
        setupPasswordToggle('password', 'password-toggle');
        setupPasswordToggle('password-confirm', 'password-confirm-toggle');
    });
</script>
@endsection
