<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Mandiri</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb; /* bg-gray-50 */
            color: #1f2937; /* Teks default gelap */
        }
        
        @keyframes slide-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-in-up {
            animation: slide-in-up 0.7s ease-out forwards;
        }

        .glow-button {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        
        .glow-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(20, 184, 166, 0.4); /* Menggunakan warna teal */
        }
    </style>
</head>
<body class="antialiased flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md mx-auto">
        <div class="bg-white rounded-3xl shadow-xl p-8 md:p-12 text-center border-t-8 border-teal-500 animate-slide-in-up">
            <i class="fas fa-qrcode text-5xl text-teal-500 mb-4 animate-slide-in-up" style="animation-delay: 0.2s;"></i>
            <h1 class="text-3xl font-extrabold text-gray-800 mb-2 animate-slide-in-up" style="animation-delay: 0.4s;">
                Absensi Mandiri
            </h1>
            <p class="text-lg text-gray-600 mb-6 animate-slide-in-up" style="animation-delay: 0.6s;">
                Selamat datang, <span class="font-bold text-teal-500">{{ $siswa->nama_siswa }}</span>
                <br>Kelas: <span class="text-teal-500 font-bold">{{ $siswa->kelas->nama_kelas }}</span>
            </p>

            @if ($sudahAbsen)
                <div class="bg-green-50 border border-green-300 text-green-800 px-6 py-4 rounded-xl shadow-md mb-6 animate-slide-in-up" style="animation-delay: 0.8s;">
                    <div class="flex items-center justify-center space-x-3">
                        <i class="fas fa-check-circle text-2xl"></i>
                        <span class="font-medium">Anda sudah absen hari ini.</span>
                    </div>
                </div>
                <a href="{{ route('absensi.siswa.dashboard', $siswa->nisn) }}" class="inline-block w-full bg-teal-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md glow-button animated-fade-in delay-300">
                    <i class="fas fa-chart-line mr-2"></i> Lihat Dashboard
                </a>
            @else
                <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-6 py-4 rounded-xl shadow-md mb-6 animate-slide-in-up" style="animation-delay: 0.8s;">
                    <div class="flex items-center justify-center space-x-3">
                        <i class="fas fa-exclamation-circle text-2xl"></i>
                        <span class="font-medium">Klik tombol di bawah untuk mencatat kehadiran Anda.</span>
                    </div>
                </div>
                <form action="{{ route('absensi.absenMandiri', $siswa->nisn) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-teal-500 text-white px-6 py-3 rounded-full font-semibold hover:bg-teal-600 transition-all duration-300 transform hover:scale-105 shadow-md glow-button animate-slide-in-up" style="animation-delay: 1s;">
                        <i class="fas fa-clipboard-check mr-2"></i> Absen Hari Ini
                    </button>
                </form>
            @endif
        </div>
    </div>
</body>
</html>