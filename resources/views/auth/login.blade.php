<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke Sistem</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/tsparticles-slim@2.12.0/tsparticles.slim.min.js"></script>
    
    <style>
        /* ... (CSS Anda tidak perlu diubah) ... */
        :root {
            --primary-color: #0077c2;
            --secondary-color: #005f99;
            --light-bg-card: rgba(255, 255, 255, 0.7);
            --dark-bg-card: rgba(0, 0, 0, 0.5);
            --border-color-light: rgba(0, 0, 0, 0.1);
            --border-color-dark: rgba(255, 255, 255, 0.3);
            --shadow-color-light: rgba(0, 0, 0, 0.15);
            --shadow-color-dark: rgba(0, 0, 0, 0.4);
            --text-color-light: #fff;
            --text-color-dark: #2c2c2c;
            --input-bg-light: #f9fafb;
            --input-bg-dark: #4a4a4a;
            --link-color-light: #0077c2;
            --link-color-dark: #a1d1e9;
            --primary-glow: 0 0 10px rgba(0, 119, 194, 0.5);
            --shadow-glow: 0 5px 20px rgba(0, 119, 194, 0.3);
        }
        body.theme-pagi, body.theme-siang {
            --glass-bg: var(--light-bg-card);
            --border-color: var(--border-color-light);
            --text-color: #222;
            --input-bg-color: var(--input-bg-light);
            --shadow-color: var(--shadow-color-light);
            --link-color: var(--link-color-light);
        }
        body.theme-malam {
            --glass-bg: var(--dark-bg-card);
            --border-color: var(--border-color-dark);
            --text-color: #e0e0e0;
            --input-bg-color: var(--input-bg-dark);
            --shadow-color: var(--shadow-color-dark);
            --link-color: var(--link-color-dark);
        }
        body.theme-malam .login-form-card {
            background-color: var(--glass-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px var(--shadow-color);
        }
        body.theme-malam .form-label { color: var(--text-color); }
        body.theme-malam .login-title { color: var(--text-color); }
        body.theme-malam .form-input {
            background-color: var(--input-bg-color);
            color: var(--text-color-light);
            border-color: rgba(255,255,255,0.3);
        }
        body.theme-malam .form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0,119,194,0.5);
        }
        body.theme-malam .form-link { color: #fff; }
        body.theme-malam .text-gray-600, body.theme-malam .text-gray-400 {
            color: var(--text-color);
        }
        body.theme-malam .login-button:hover {
            box-shadow: var(--shadow-glow);
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        body {
            background-image: url('{{ asset('images/bg_sekolah.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text-color);
            overflow: auto;
            transition: color 0.5s ease, background-color 0.5s ease;
        }
        #login-particles {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            transition: background 0.5s ease;
        }
        #login-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }
        .login-form-card {
            background-color: var(--light-bg-card);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid var(--border-color-light);
            box-shadow: 0 10px 30px var(--shadow-color-light);
            max-width: 800px;
            width: 100%;
            display: flex;
            overflow: hidden;
            opacity: 0;
            transform: scale(0.9);
            animation: fadeInScale 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
        @keyframes fadeInScale {
            to { opacity: 1; transform: scale(1); }
        }
        .login-visual {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-color-light);
            padding: 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            transform: translateX(-100%);
            opacity: 0;
            animation: slideInLeft 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
        .login-form-content {
            padding: 40px;
            flex: 1;
            transform: translateX(100%);
            opacity: 0;
            animation: slideInRight 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }
        @keyframes slideInLeft {
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideInRight {
            to { transform: translateX(0); opacity: 1; }
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-title {
            font-size: 1.8em;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
        }
        .form-group {
            margin-top: 1.25rem;
            position: relative;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.9em;
            color: var(--text-color);
            margin-bottom: 0.5rem;
            display: block;
            transition: all 0.3s ease;
        }
        .form-input {
            width: 100%;
            padding: 0.9rem 1.1rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background-color: var(--input-bg-color);
            color: var(--text-color-dark);
            transition: all 0.3s ease;
            box-sizing: border-box;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0,119,194,0.3);
        }
        .form-link {
            color: var(--link-color);
            text-decoration: none;
            font-size: 0.85em;
            font-weight: 600;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }
        .form-link:hover {
            text-decoration: underline;
            color: var(--secondary-color);
            text-shadow: 0 0 5px rgba(0, 119, 194, 0.2);
        }
        .form-checkbox {
            accent-color: var(--primary-color);
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }
        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            cursor: pointer;
            transition: color 0.3s ease;
            z-index: 2;
        }
        .password-toggle-icon:hover {
            color: #4a4a4a;
        }
        .password-input {
            padding-right: 40px; 
        }
        .login-button {
            width: 100%;
            padding: 0.9rem 1.25rem;
            font-weight: 700;
            font-size: 1em;
            background-color: var(--primary-color);
            color: var(--text-color-light);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            margin-top: 25px;
            position: relative;
            overflow: hidden;
        }
        .login-button::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background-color: rgba(255, 255, 255, 0.15);
            transition: all 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            border-radius: 50%;
            transform: translate(-50%, -50%) scale(0);
        }
        .login-button:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
        .login-button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,119,194,0.3);
        }
        .login-button:active {
            transform: translateY(0);
            box-shadow: none;
        }
        .flex { display: flex; }
        .justify-center { justify-content: center; }
        .justify-between { justify-content: space-between; }
        .items-center { align-items: center; }
        .mt-4 { margin-top: 1rem; }
        .ms-2 { margin-left: 0.5rem; }
        .inline-flex { display: inline-flex; }
        .text-sm { font-size: 0.875rem; }
        @media (max-width: 768px) {
            .login-form-card {
                flex-direction: column;
                max-width: 400px;
            }
            .login-visual {
                padding: 20px;
            }
        }
    </style>
</head>
<body class="theme-pagi">
    <div id="login-particles"></div>
    <div id="login-wrapper">
        <div class="login-form-card">
            <div class="login-visual">
                <i class="fa-solid fa-graduation-cap fa-5x"></i>
                <h2 style="font-size: 2em; font-weight: 700; margin-top: 20px;">Selamat Datang!</h2>
                <p style="font-weight: 300; margin-top: 10px;">Masuk untuk melanjutkan ke sistem.</p>
            </div>
            <div class="login-form-content">
                <h1 class="login-title">Masuk ke Sistem</h1>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Masukkan alamat email Anda" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <div style="position: relative;">
                            <input id="password" class="form-input password-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi Anda" />
                            <i id="password-toggle" class="fa-solid fa-eye password-toggle-icon"></i>
                        </div>
                    </div>
                    <div class="flex items-center justify-between mt-4">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                            <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat Saya</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="form-link" href="{{ route('password.request') }}">Lupa kata sandi?</a>
                        @endif
                    </div>
                    <button type="submit" class="login-button">Masuk</button>
                </form>
                <div class="flex justify-center mt-4 text-sm text-gray-600 dark:text-gray-400">
                    Belum punya akun? <a href="{{ route('register') }}" class="form-link ms-2">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // PENTING: Pindahkan semua kode Anda di dalam event listener 'DOMContentLoaded'
        // untuk memastikan semua skrip eksternal (seperti tsParticles) sudah dimuat.
        document.addEventListener('DOMContentLoaded', () => {
            function updateTheme() {
                const now = new Date();
                const hour = now.getHours();
                let themeClass;
                let particleColor;
                let particleDensity;
                
                if (hour >= 6 && hour < 12) {
                    themeClass = 'theme-pagi';
                    particleColor = '#aaaaaa';
                    particleDensity = 60;
                } else if (hour >= 12 && hour < 18) {
                    themeClass = 'theme-siang';
                    particleColor = '#000000';
                    particleDensity = 80;
                } else {
                    themeClass = 'theme-malam';
                    particleColor = '#ffffff';
                    particleDensity = 50;
                }
                document.body.className = themeClass;
                
                const particlesContainer = document.getElementById('login-particles');
                if (particlesContainer && typeof tsParticles !== 'undefined') {
                    tsParticles.load('login-particles', {
                        particles: {
                            number: { value: particleDensity, density: { enable: true, value_area: 800 } },
                            color: { value: particleColor },
                            shape: { type: 'circle' },
                            opacity: { value: 0.5, random: true },
                            size: { value: 3, random: true },
                            links: { enable: true, distance: 150, color: particleColor, opacity: 0.4, width: 1 },
                            move: { enable: true, speed: 1.5, direction: 'none', random: true, straight: false, out_mode: 'out' }
                        },
                        interactivity: {
                            events: {
                                onhover: { enable: true, mode: 'grab' },
                                onclick: { enable: true, mode: 'push' }
                            },
                            modes: {
                                grab: { distance: 140, links: { opacity: 1 } },
                                push: { quantity: 4 }
                            }
                        }
                    });
                }
            }

            updateTheme();
            setInterval(updateTheme, 60000);
            
            // Logika untuk menampilkan/menyembunyikan kata sandi
            const passwordInput = document.getElementById('password');
            const passwordToggle = document.getElementById('password-toggle');
            
            if (passwordInput && passwordToggle) {
                passwordToggle.addEventListener('click', () => {
                    // Gunakan classList.toggle untuk keamanan dan kemudahan
                    passwordInput.type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordToggle.classList.toggle('fa-eye');
                    passwordToggle.classList.toggle('fa-eye-slash');
                });
            } else {
                console.error('Element with id "password" or "password-toggle" not found.');
            }
        });
    </script>
</body>
</html>