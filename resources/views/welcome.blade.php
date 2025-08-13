<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Absensi Sekolah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-slim@2.12.0/tsparticles.slim.min.js"></script>
    
    <style>
        :root {
            --primary-color: #007BFF;
            --secondary-color: #0056b3;
            --glass-bg: rgba(255, 255, 255, 0.6);
            --border-color: rgba(0, 0, 0, 0.1);
            --shadow-color: rgba(0, 0, 0, 0.08);
            --text-color: #333;
            --text-color-light: #fff;
            --glow-color: rgba(0, 123, 255, 0.2);
            --particle-color: #333;
        }

        body.theme-malam {
            --glass-bg: rgba(0, 0, 0, 0.4);
            --border-color: rgba(255, 255, 255, 0.1);
            --shadow-color: rgba(0, 0, 0, 0.3);
            --text-color: #E2E8F0;
            --text-color-light: #fff;
            --glow-color: rgba(100, 150, 255, 0.3);
            --particle-color: #fff;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            position: relative;
            background-image: url('{{ asset('images/bg_sekolah.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: var(--text-color);
            overflow: hidden;
            transition: color 0.8s ease;
        }

        /* --- Perubahan untuk Latar Belakang & Overlay --- */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.5); /* Overlay putih terang untuk tema siang */
            transition: background 0.8s ease;
            z-index: -2;
        }
        body.theme-malam::before {
            background: rgba(0, 0, 0, 0.6); /* Overlay gelap untuk tema malam */
        }
        
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            visibility: visible;
            transition: opacity 0.6s ease, visibility 0.6s ease;
        }
        #preloader.loaded {
            opacity: 0;
            visibility: hidden;
        }
        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--border-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        
        @keyframes pulseShadow {
            0% { box-shadow: 0 8px 30px var(--shadow-color); }
            50% { box-shadow: 0 12px 40px var(--glow-color); }
            100% { box-shadow: 0 8px 30px var(--shadow-color); }
        }

        #tsparticles {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .container {
            text-align: center;
            background-color: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px 60px;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 30px var(--shadow-color);
            max-width: 800px;
            opacity: 0;
            animation: fadeInUp 1s cubic-bezier(0.2, 0.8, 0.2, 1) forwards, pulseShadow 4s ease-in-out infinite;
            animation-delay: 0.5s;
        }
        
        .container h1 {
            color: var(--text-color);
            font-weight: 800;
            margin-bottom: 10px;
            font-size: 3.2em;
            letter-spacing: -1px;
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: 1s;
        }

        .container p {
            font-size: 1.1em;
            margin-top: 0;
            margin-bottom: 30px;
            font-weight: 400;
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: 1.2s;
        }

        #dynamic-quote {
            font-style: italic;
            font-size: 1.1em;
            margin-top: 25px;
            line-height: 1.6;
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: 1.4s;
        }
        
        .auth-links {
            position: absolute;
            top: 30px;
            right: 30px;
            display: flex;
            gap: 15px;
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: 1.6s;
        }
        .auth-links a {
            color: var(--text-color-light);
            background-color: var(--primary-color);
            padding: 12px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 15px var(--shadow-color);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            position: relative;
            overflow: hidden;
        }
        .auth-links a:hover {
            background-color: var(--secondary-color);
            transform: translateY(-5px);
            box-shadow: 0 8px 25px var(--glow-color);
        }
        .auth-links a i {
            margin-right: 8px;
            transition: transform 0.3s ease;
        }
        .auth-links a:hover i {
            transform: scale(1.1) rotate(5deg);
        }

        .real-time-info {
            position: absolute;
            top: 30px;
            left: 30px;
            background-color: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 15px 20px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            color: var(--text-color);
            font-size: 0.9em;
            text-align: left;
            opacity: 0;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards, pulseShadow 4s ease-in-out infinite;
            animation-delay: 1.8s;
        }
        .real-time-info span {
            display: block;
            font-variant-numeric: tabular-nums;
            margin-bottom: 5px;
        }
        .real-time-info i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        .real-time-info span:last-child {
            margin-bottom: 0;
        }
        #real-time-weather {
            display: flex;
            align-items: center;
        }
        #real-time-weather img {
            width: 25px;
            height: 25px;
            margin-right: 8px;
        }

        .social-links {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 20px;
            animation: fadeInUp 0.8s cubic-bezier(0.2, 0.8, 0.2, 1) forwards;
            animation-delay: 2s;
            opacity: 0;
        }
        .social-links a {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 45px;
            height: 45px;
            background-color: var(--glass-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 50%;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 15px var(--shadow-color);
            color: var(--text-color);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .social-links a:hover {
            transform: translateY(-5px) scale(1.1);
            color: var(--text-color-light);
            box-shadow: 0 8px 25px var(--glow-color);
        }
        .social-links a.facebook:hover { background-color: #1877F2; }
        .social-links a.linkedin:hover { background-color: #0A66C2; }
        .social-links a.instagram:hover { background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); }
        .social-links a.youtube:hover { background-color: #FF0000; }
        
        body.theme-malam .social-links a {
            background-color: rgba(0, 0, 0, 0.4);
            color: var(--text-color);
        }
    </style>
</head>
<body>
    <div id="preloader"><div class="spinner"></div></div>

    <div id="tsparticles"></div>

    <div class="auth-links">
        @if (Route::has('login'))
            <a href="{{ route('login') }}"><i class="fa-solid fa-right-to-bracket"></i>Login</a>
            <a href="{{ route('register') }}"><i class="fa-solid fa-user-plus"></i>Register</a>
        @endif
    </div>

    <div class="real-time-info">
        <span id="real-time-clock"></span>
        <span id="real-time-location"></span>
        <span id="real-time-weather"></span>
    </div>

    <div class="container">
        <h1>Selamat Datang</h1>
        <p>Di Sistem Absensi Sekolah Digital</p>
        <p id="dynamic-quote"></p>
    </div>

    <div class="social-links">
        <a href="#" target="_blank" class="facebook" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
        <a href="#" target="_blank" class="linkedin" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
        <a href="#" target="_blank" class="instagram" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" target="_blank" class="youtube" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
    </div>
    
    <script>
        const quotes = [
            { quote: "Pendidikan adalah paspor masa depan.", author: "Malcolm X" },
            { quote: "Belajar bukanlah persiapan untuk hidup, belajar adalah hidup itu sendiri.", author: "John Dewey" },
            { quote: "Semua orang adalah guru, setiap tempat adalah sekolah.", author: "Ki Hajar Dewantara" },
            { quote: "Tujuan utama pendidikan bukanlah ilmu, melainkan tindakan.", author: "John Ruskin" },
            { quote: "Pendidikan adalah senjata paling mematikan di dunia, karena dengan pendidikan Anda dapat mengubah dunia.", author: "Nelson Mandela" },
            { quote: "Ilmu tanpa amal adalah omong kosong, amal tanpa ilmu adalah kesesatan.", author: "Imam Al-Ghazali" },
            { quote: "Janganlah pernah berhenti belajar, karena hidup tak pernah berhenti mengajarkan.", author: "Anonim" },
            { quote: "Tujuan dari pendidikan adalah untuk menggantikan pikiran kosong dengan pikiran terbuka.", author: "Malcolm Forbes" },
            { quote: "Pendidikan adalah jembatan emas menuju masa depan.", author: "Soekarno" },
            { quote: "Jika kamu tidak mengejar apa yang kamu inginkan, kamu tidak akan pernah mendapatkannya.", author: "Nora Roberts" },
            { quote: "Belajar adalah proses tak berujung, karena dunia tak pernah berhenti berputar.", author: "Albert Einstein" },
            { quote: "Kegagalan adalah guru yang paling berharga.", author: "Robert Kiyosaki" },
            { quote: "Pendidikan bukan sekadar mengisi wadah, tetapi menyalakan api.", author: "William Butler Yeats" },
            { quote: "Hidup adalah 10% apa yang terjadi pada kita dan 90% bagaimana kita meresponnya.", author: "Charles R. Swindoll" },
            { quote: "Masa depan adalah milik mereka yang percaya pada keindahan mimpi mereka.", author: "Eleanor Roosevelt" }
        ];

        const weatherApiKey = 'a8d9c0dc0485944b1b3a3faf28aea7b9';

        function updateClockAndTheme() {
            const now = new Date();
            const hour = now.getHours();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const formattedDate = now.toLocaleDateString('id-ID', dateOptions);
            const formattedTime = now.toLocaleTimeString('id-ID', timeOptions).replace(/\./g, ':');

            let themeClass = 'theme-siang';
            let clockIcon = 'fa-solid fa-sun';
            let particleColor = '#333';

            if (hour >= 6 && hour < 18) {
                themeClass = 'theme-siang';
                clockIcon = 'fa-solid fa-sun';
                particleColor = '#333';
            } else {
                themeClass = 'theme-malam';
                clockIcon = 'fa-solid fa-moon';
                particleColor = '#fff';
            }

            document.body.className = themeClass;
            document.getElementById('real-time-clock').innerHTML = `<i class="${clockIcon}"></i>${formattedDate} | ${formattedTime}`;

            const particlesContainer = document.getElementById('tsparticles');
            if (particlesContainer.tsParticles) {
                particlesContainer.tsParticles.options.particles.color.value = particleColor;
                particlesContainer.tsParticles.refresh();
            }
        }

        async function getWeather(lat, lon) {
            if (!weatherApiKey) {
                document.getElementById('real-time-weather').innerHTML = '<i class="fa-solid fa-cloud-sun"></i>Cuaca: API Key tidak ada.';
                return;
            }
            try {
                const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&units=metric&lang=id&appid=${weatherApiKey}`);
                const data = await response.json();

                if (data.cod !== 200) {
                    console.error('Error fetching weather data:', data.message);
                    document.getElementById('real-time-weather').innerHTML = '<i class="fa-solid fa-cloud-sun-rain"></i>Cuaca: Gagal mengambil data';
                    return;
                }
                
                if (data.weather && data.weather.length > 0) {
                    const temp = Math.round(data.main.temp);
                    const desc = data.weather[0].description;
                    const iconCode = data.weather[0].icon;
                    const weatherIconUrl = `https://openweathermap.org/img/wn/${iconCode}.png`;
                    
                    document.getElementById('real-time-weather').innerHTML = `<img src="${weatherIconUrl}" alt="${desc}" onerror="this.src='https://openweathermap.org/img/wn/01d.png';">Cuaca: ${temp}°C, ${desc}`;
                }
            } catch (error) {
                console.error('Network or parsing error:', error);
                document.getElementById('real-time-weather').innerHTML = '<i class="fa-solid fa-cloud-sun-rain"></i>Cuaca: Gagal mengambil data';
            }
        }

        function getLocation() {
            document.getElementById('real-time-location').innerHTML = '<i class="fa-solid fa-location-dot"></i>Mencari lokasi...';
            document.getElementById('real-time-weather').innerHTML = '<i class="fa-solid fa-cloud-sun"></i>Cuaca: Memuat...';
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;
                        document.getElementById('real-time-location').innerHTML = `<i class="fa-solid fa-location-dot"></i>Lokasi: ${lat.toFixed(4)}, ${lon.toFixed(4)}`;
                        getWeather(lat, lon);
                    },
                    (error) => {
                        console.error('Geolocation Error:', error);
                        document.getElementById('real-time-location').innerHTML = '<i class="fa-solid fa-location-dot"></i>Lokasi tidak dapat diakses.';
                        document.getElementById('real-time-weather').innerHTML = '<i class="fa-solid fa-cloud-sun-rain"></i>Cuaca: Gagal mendapatkan lokasi.';
                    }
                );
            } else {
                document.getElementById('real-time-location').innerHTML = '<i class="fa-solid fa-location-dot"></i>Geolocation tidak didukung.';
                document.getElementById('real-time-weather').innerHTML = '<i class="fa-solid fa-cloud-sun-rain"></i>Cuaca: Gagal mendapatkan lokasi.';
            }
        }

        function setRandomQuote() {
            const randomIndex = Math.floor(Math.random() * quotes.length);
            const randomQuote = quotes[randomIndex];
            document.getElementById('dynamic-quote').textContent = `"${randomQuote.quote}" - ${randomQuote.author}`;
        }

        window.addEventListener('load', () => {
            const preloader = document.getElementById('preloader');
            preloader.classList.add('loaded');
        });

        document.addEventListener('DOMContentLoaded', () => {
            updateClockAndTheme();
            setInterval(updateClockAndTheme, 1000);
            getLocation();
            setRandomQuote();

            tsParticles.load('tsparticles', {
                particles: {
                    number: { value: 50 },
                    color: { value: '#333' },
                    shape: { type: 'circle' },
                    opacity: { value: 0.6 },
                    size: { value: 3 },
                    links: { enable: true, distance: 150, color: '#999', opacity: 0.4, width: 1 },
                    move: { enable: true, speed: 1.5, direction: 'none', random: true, straight: false, out_mode: 'out', bounce: false }
                }
            });
        });
    </script>
</body>
</html>