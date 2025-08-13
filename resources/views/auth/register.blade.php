<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-slim@2.12.0/tsparticles.slim.min.js"></script>

    <style>
        /* ======================================================= */
        /* Global & Variable Configuration                         */
        /* ======================================================= */
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
        
        /* Theme Configuration based on time */
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

        body.theme-malam .register-form-card {
            background-color: var(--glass-bg);
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px var(--shadow-color);
        }
        body.theme-malam .form-label { color: var(--text-color); }
        body.theme-malam .register-title { color: var(--text-color); }
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
        body.theme-malam .register-button:hover {
            box-shadow: var(--shadow-glow);
        }

        /* ======================================================= */
        /* Base Styles & Layout                                    */
        /* ======================================================= */
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
        #register-particles {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: -1;
            transition: background 0.5s ease;
        }
        #register-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        /* ======================================================= */
        /* Register Card Styles (Kotak Panjang Lebih Kecil)        */
        /* ======================================================= */
        .register-form-card {
            background-color: var(--light-bg-card);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid var(--border-color-light);
            box-shadow: 0 10px 30px var(--shadow-color-light);
            max-width: 600px; /* Ukuran kartu lebih kecil */
            width: 100%;
            display: flex;
            overflow: hidden;
        }
        
        .register-visual {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: var(--text-color-light);
            padding: 30px; /* Padding lebih kecil */
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

        .register-form-content {
            padding: 30px; /* Padding lebih kecil */
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
            margin-bottom: 15px; /* Margin lebih kecil */
        }
        .register-title {
            font-size: 1.5em; /* Ukuran font judul lebih kecil */
            font-weight: 700;
            margin-bottom: 15px;
            letter-spacing: -0.5px;
        }
        
        /* Form Elements */
        .form-group {
            margin-top: 1rem; /* Margin lebih kecil */
            position: relative;
        }
        .form-label {
            font-weight: 600;
            font-size: 0.85em; /* Ukuran font label lebih kecil */
            color: var(--text-color);
            margin-bottom: 0.5rem;
            display: block;
            transition: all 0.3s ease;
        }
        .form-input {
            width: 100%;
            padding: 0.8rem 1rem; /* Padding lebih kecil */
            border-radius: 8px; /* Border radius lebih kecil */
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
            font-size: 0.8em; /* Ukuran font link lebih kecil */
            font-weight: 600;
            transition: color 0.3s ease, text-shadow 0.3s ease;
        }
        .form-link:hover {
            text-decoration: underline;
            color: var(--secondary-color);
            text-shadow: 0 0 5px rgba(0, 119, 194, 0.2);
        }
        
        /* Buttons */
        .register-button {
            width: 100%;
            padding: 0.8rem 1.1rem; /* Padding tombol lebih kecil */
            font-weight: 700;
            font-size: 0.95em; /* Ukuran font tombol lebih kecil */
            background-color: var(--primary-color);
            color: var(--text-color-light);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            margin-top: 20px; /* Margin tombol lebih kecil */
            position: relative;
            overflow: hidden;
        }
        .register-button::before {
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
        .register-button:hover::before {
            transform: translate(-50%, -50%) scale(1);
        }
        .register-button:hover {
            background-color: var(--secondary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,119,194,0.35);
        }
        .register-button:active {
            transform: translateY(0);
            box-shadow: none;
        }

        /* Utility classes for modern layout */
        .flex { display: flex; }
        .justify-center { justify-content: center; }
        .mt-4 { margin-top: 1rem; }
        .ms-2 { margin-left: 0.5rem; }
        .text-sm { font-size: 0.875rem; }

        @media (max-width: 768px) {
            .register-form-card {
                flex-direction: column-reverse;
                max-width: 400px;
            }
            .register-visual {
                padding: 20px;
            }
        }
    </style>
</head>
<body class="theme-pagi">
    <div id="register-particles"></div>
    <div id="register-wrapper">
        <div class="register-form-card">
            <div class="register-visual">
                <i class="fa-solid fa-user-plus fa-4x"></i>
                <h2 style="font-size: 1.8em; font-weight: 700; margin-top: 15px;">Bergabunglah dengan Kami</h2>
                <p style="font-weight: 300; margin-top: 5px; font-size: 0.9em;">Daftar untuk menikmati semua fitur terbaik.</p>
            </div>
            <div class="register-form-content">
                <h1 class="register-title">Daftar Akun Baru</h1>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input id="name" class="form-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap Anda" />
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input id="email" class="form-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan alamat email Anda" />
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input id="password" class="form-input" type="password" name="password" required autocomplete="new-password" placeholder="Buat kata sandi baru" />
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi Kata Sandi</label>
                        <input id="password_confirmation" class="form-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
                    </div>
                    <button type="submit" class="register-button">Daftar</button>
                </form>
                <div class="flex justify-center mt-4 text-sm text-gray-600 dark:text-gray-400">
                    Sudah punya akun? <a href="{{ route('login') }}" class="form-link ms-2">Masuk</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
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
            const particlesContainer = document.getElementById('register-particles');
            if (particlesContainer && particlesContainer.tsParticles) {
                particlesContainer.tsParticles.options.particles.color.value = particleColor;
                particlesContainer.tsParticles.options.particles.number.value = particleDensity;
                particlesContainer.tsParticles.refresh();
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            updateTheme();
            setInterval(updateTheme, 60000);
            
            tsParticles.load('register-particles', {
                particles: {
                    number: { value: 60, density: { enable: true, value_area: 800 } },
                    color: { value: '#000000' },
                    shape: { type: 'circle' },
                    opacity: { value: 0.5, random: true },
                    size: { value: 3, random: true },
                    links: { enable: true, distance: 150, color: '#000000', opacity: 0.4, width: 1 },
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
        });
    </script>
</body>
</html>
