<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #e2e8f0; /* Latar belakang abu-abu terang */
            color: #1a202c; /* Teks default gelap */
        }
        
        /* Kartu dengan gaya glassmorphism */
        .glass-card {
            background-color: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease-in-out;
            transform: scale(1);
        }
        
        .glass-card:hover {
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: scale(1.01);
        }

        /* Animasi fade in slide up */
        .animated-fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInSlideUp 0.8s ease-out forwards;
        }

        @keyframes fadeInSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }

        /* Scrollbar kustom */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background-color: #94a3b8;
        }

        /* Hover effect untuk pengumuman */
        .announcement-card {
            transition: transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 123, 255, 0.2);
        }

    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-2xl mx-auto">
        <div class="glass-card p-8 md:p-12 text-center animated-fade-in">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2 animated-fade-in">
                Dashboard
            </h1>
            <p class="text-lg text-gray-600 mb-6 animated-fade-in delay-100">
                Selamat Datang, <span class="font-bold text-indigo-600">{{ $siswa->nama_siswa }}</span>
            </p>

            <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-200 mb-8 animated-fade-in delay-200">
                <div class="flex items-center justify-center space-x-3">
                    <i class="fa-solid fa-graduation-cap text-2xl text-indigo-500"></i>
                    <p class="text-xl font-semibold text-indigo-800">
                        Anda terdaftar di kelas: <span class="text-indigo-600 font-bold">{{ $siswa->kelas->nama_kelas }}</span>
                    </p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg animated-fade-in delay-300" role="alert">
                    <div class="flex items-center">
                        <div class="py-1">
                            <i class="fa-solid fa-circle-check text-green-500 mr-3"></i>
                        </div>
                        <div>
                            <p class="font-bold">Berhasil!</p>
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg animated-fade-in delay-300" role="alert">
                    <div class="flex items-center">
                        <div class="py-1">
                            <i class="fa-solid fa-circle-xmark text-red-500 mr-3"></i>
                        </div>
                        <div>
                            <p class="font-bold">Gagal!</p>
                            <p class="text-sm">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="text-left animated-fade-in delay-400">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-bullhorn text-indigo-500 mr-3"></i> Pengumuman Terbaru
                </h2>
                <div class="space-y-4 overflow-y-auto max-h-72 custom-scrollbar">
                    @forelse($pengumuman as $item)
                        <div class="announcement-card flex items-start p-5 rounded-xl border border-gray-200 bg-white shadow-sm">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-info text-xl text-indigo-400 mt-1"></i>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="font-semibold text-base text-gray-800">{{ $item->judul }}</p>
                                <p class="text-sm text-gray-500 mt-1">{{ $item->isi }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center p-5 rounded-xl border border-gray-200 bg-gray-50">
                            <i class="fa-solid fa-info-circle text-xl text-gray-400 mr-3"></i>
                            <p class="font-medium text-sm text-gray-500">Tidak ada pengumuman aktif saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>