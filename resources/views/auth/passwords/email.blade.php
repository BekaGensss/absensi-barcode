<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Kata Sandi</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Gaya dasar dan animasi */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Warna latar belakang yang lembut */
            background-image: url('{{ asset('images/bg_sekolah.png') }}'); /* Gambar latar belakang */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        /* Overlay gelap semi-transparan untuk readability */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Warna overlay */
            z-index: -1;
        }

        /* Animasi masuk form */
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .animate-fadeInScale {
            animation: fadeInScale 0.6s ease-out forwards;
        }

        /* Styling untuk efek fokus input */
        .form-input {
            transition: all 0.2s ease-in-out;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6; /* Border biru yang cerah */
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
        }

        /* Gaya tombol yang lebih menarik */
        .btn-primary {
            background-image: linear-gradient(to right, #4f46e5, #3b82f6); /* Gradient warna */
            box-shadow: 0 4px 14px 0 rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform: translateY(0);
        }
        .btn-primary:hover {
            box-shadow: 0 6px 20px 0 rgba(0, 0, 0, 0.25);
            transform: translateY(-3px); /* Efek angkat tombol */
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Animasi status message */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slideInUp {
            animation: slideInUp 0.5s ease-out forwards;
        }
    </style>
</head>
<body>

    <div class="w-full max-w-md px-4">
        <!-- Card utama untuk form -->
        <div class="bg-white p-8 md:p-12 rounded-2xl shadow-2xl border border-gray-100 animate-fadeInScale">
            <div class="text-center mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">{{ __('Reset Kata Sandi') }}</h1>
                <p class="text-gray-500 mt-2 text-sm">{{ __('Masukkan alamat email Anda untuk menerima tautan reset kata sandi.') }}</p>
            </div>

            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 animate-slideInUp" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-8">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">{{ __('Alamat Email') }}</label>
                    <input id="email" type="email" class="form-input w-full px-5 py-3 border rounded-xl transition-colors duration-200 {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="text-red-500 text-xs mt-2 block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!-- Tombol Kirim Tautan -->
                <div class="flex items-center justify-center">
                    <button type="submit" class="w-full px-6 py-3 text-lg btn-primary text-white font-bold rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        {{ __('Kirim Tautan Reset Kata Sandi') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
