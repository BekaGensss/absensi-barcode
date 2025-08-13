@extends('layouts.app')

@section('title', 'Dashboard Absensi Siswa')

@section('head')
{{-- Memuat font Inter dari Google Fonts untuk tampilan yang modern --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
@endsection

@section('content')
{{-- Bagian Styles CSS untuk Tampilan & Animasi --}}
<style>
    /* Menggunakan font Inter yang modern dan mudah dibaca */
    body {
        font-family: 'Inter', sans-serif;
    }
    
    /* Palet warna, bayangan, dan border yang bersih dan profesional */
    :root {
        --text-color-light: #f7fafc;
        --text-color-dark: #1f2937; /* Warna teks utama gelap (hitam) */
        --text-color-secondary: #6b7280;
        --shadow-light: 0 8px 16px rgba(0,0,0,0.1);
        --shadow-hover-light: 0 16px 32px rgba(0,0,0,0.2);
        --shadow-dark: 0 8px 16px rgba(0,0,0,0.3);
        --shadow-hover-dark: 0 16px 32px rgba(0,0,0,0.5);
        --bg-glass-light: rgba(255, 255, 255, 0.8);
        --bg-glass-dark: rgba(45, 55, 72, 0.6);
        --border-glass-light: rgba(226, 232, 240, 0.5);
        --border-glass-dark: rgba(74, 85, 104, 0.5);
    }
    
    .dark {
        --text-color-dark: #f7fafc; /* Warna teks utama gelap (putih untuk dark mode) */
        --text-color-light: #1a202c;
    }
    
    /* Gaya Card Dashboard yang Diperbarui dengan Gradient */
    .dashboard-card {
        padding: 2.5rem;
        border-radius: 1.5rem;
        box-shadow: var(--shadow-light);
        transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        position: relative;
        overflow: hidden;
        color: var(--text-color-light); /* Warna teks default untuk card */
        border: 1px solid transparent; /* Border transparan default */
    }
    .dark .dashboard-card {
        box-shadow: var(--shadow-dark);
        color: var(--text-color-light);
    }
    
    /* Efek hover yang lebih dramatis dan halus */
    .dashboard-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: var(--shadow-hover-light);
    }
    .dark .dashboard-card:hover {
        box-shadow: var(--shadow-hover-dark);
    }
    
    /* Warna Gradient untuk Setiap Card */
    .card-total-siswa { background-image: linear-gradient(135deg, #4299e1 0%, #3182ce 100%); }
    .card-kehadiran { background-image: linear-gradient(135deg, #48bb78 0%, #38a169 100%); }
    .card-izin-sakit { background-image: linear-gradient(135deg, #ecc94b 0%, #d69e2e 100%); }
    .card-tidak-hadir { background-image: linear-gradient(135deg, #f56565 0%, #e53e3e 100%); }
    
    .card-grafik, .card-notifikasi, .card-absensi-list {
        background-color: var(--bg-glass-light);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid var(--border-glass-light);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        color: var(--text-color-dark);
    }
    .dark .card-grafik, .dark .card-notifikasi, .dark .card-absensi-list {
        background-color: var(--bg-glass-dark);
        border: 1px solid var(--border-glass-dark);
        box-shadow: 0 10px 25px var(--shadow-dark);
        color: var(--text-color-light);
    }
    
    .card-label {
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }
    .card-value {
        font-size: 4.5rem;
        font-weight: 800;
        line-height: 1;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    /* Perbaikan untuk teks di card non-gradient */
    .card-grafik .card-label, .card-notifikasi .card-label, .card-absensi-list .card-label { 
        color: var(--text-color-dark); /* Warna hitam/gelap */
        text-shadow: none; 
    }
    .card-grafik .card-value, .card-notifikasi .card-value, .card-absensi-list .card-value { 
        color: var(--text-color-dark); /* Warna hitam/gelap */
        text-shadow: none;
    }
    .dark .card-grafik .card-label, .dark .card-notifikasi .card-label, .dark .card-absensi-list .card-label { 
        color: var(--text-color-secondary); 
    }
    .dark .card-grafik .card-value, .dark .card-notifikasi .card-value, .dark .card-absensi-list .card-value { 
        color: var(--text-color-light); 
    }
    
    .icon-placeholder {
        position: absolute;
        top: 2rem;
        right: 2.5rem;
        font-size: 6rem;
        opacity: 0.2;
        filter: drop-shadow(0 0 10px rgba(0,0,0,0.2));
        transition: transform 0.4s ease;
    }
    .dashboard-card:hover .icon-placeholder {
        transform: rotate(10deg) scale(1.1);
    }

    /* Ikon baru */
    .icon-users::before { content: '\f0c0'; }
    .icon-clipboard-check::before { content: '\f465'; }
    .icon-hospital-user::before { content: '\f80d'; }
    .icon-user-slash::before { content: '\f506'; }
    .icon-bell::before { content: '\f0f3'; }
    .icon-user-check::before { content: '\f4fc'; }
    
    /* Animasi saat Card muncul */
    .animated-card {
        animation: fadeInSlideUp 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
    }
    @keyframes fadeInSlideUp {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Animasi untuk Header */
    .animate-fadeInUp {
        animation: fadeInUp 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Perbaikan Khusus untuk Header */
    .dashboard-header-h1 {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--text-color-dark);
        transition: color 0.3s ease;
        text-shadow: none;
    }
    .dark .dashboard-header-h1 {
        color: var(--text-color-light);
    }
    .dashboard-header-p {
        color: var(--text-color-secondary);
        transition: color 0.3s ease;
    }
    .dark .dashboard-header-p {
        color: var(--text-color-secondary);
    }

    /* Scrollbar yang lebih rapi untuk Notifikasi */
    .custom-scrollbar::-webkit-scrollbar { width: 8px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.3); border-radius: 4px; }
    .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.1); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.3); }

    /* Gaya untuk notifikasi yang diperbarui */
    .notification-item {
        background-color: rgba(255,255,255,0.6);
        border: 1px solid rgba(0,0,0,0.1);
        backdrop-filter: blur(5px);
        transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-radius: 0.75rem;
    }
    .dark .notification-item {
        background-color: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
    }
    .notification-item:hover {
        background-color: rgba(59, 130, 246, 0.1);
        transform: scale(1.02);
    }
    .notification-title { color: var(--text-color-dark); }
    .notification-content { color: var(--text-color-secondary); }
    .dark .notification-title { color: var(--text-color-dark); }
    .dark .notification-content { color: var(--text-color-secondary); }

    /* Gaya untuk daftar absensi */
    .absensi-list-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        transition: transform 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .dark .absensi-list-item {
        background-color: rgba(0, 0, 0, 0.1);
    }
    .absensi-list-item:hover {
        transform: translateX(5px) scale(1.01);
    }
    .avatar-absensi {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid;
    }
    
    /* Perbaikan badge status */
    .badge-status {
        padding: 0.4rem 1rem;
        border-radius: 9999px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .badge-hadir {
        background-color: #22c55e;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .badge-izin {
        background-color: #f97316;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .badge-sakit {
        background-color: #eab308;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }
    .badge-alfa {
        background-color: #ef4444;
        color: white;
        text-shadow: 0 1px 2px rgba(0,0,0,0.15);
    }

    /* Efek hover pada badge */
    .badge-status:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    /* Kelas untuk warna border avatar */
    .border-hadir { border-color: #22c55e; }
    .border-izin { border-color: #f97316; }
    .border-sakit { border-color: #eab308; }
    .border-alfa { border-color: #ef4444; }

    /* Warna teks khusus untuk daftar absensi */
    .nama-siswa-hadir {
        color: #1f2937; /* Hitam */
        font-weight: 600;
        transition: color 0.3s ease;
    }
    .kelas-siswa-hadir {
        color: #6b7280; /* Abu-abu gelap */
        font-size: 0.875rem;
        transition: color 0.3s ease;
    }

    /* Warna teks khusus untuk dark mode */
    .dark .nama-siswa-hadir {
        color: #f3f4f6; /* Putih */
    }
    .dark .kelas-siswa-hadir {
        color: #9ca3af; /* Abu-abu muda */
    }
</style>

{{-- Konten Utama Dashboard --}}
<div class="p-8 space-y-8">
    
    {{-- Header Dashboard --}}
    <header class="flex flex-col md:flex-row justify-between items-start md:items-center animate-fadeInUp">
        <div>
            <h1 class="dashboard-header-h1 transition-colors duration-300">Dashboard</h1>
            <p class="mt-3 text-lg dashboard-header-p">Selamat datang, Admin! Pantau ringkasan data absensi dengan mudah.</p>
        </div>
    </header>

    {{-- Kartu Ringkasan (Summary Cards) dengan Animasi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        
        {{-- Card 1: Total Siswa --}}
        <div class="dashboard-card card-total-siswa animated-card" style="animation-delay: 0.1s;">
            <p class="card-label">Total Siswa</p>
            <p class="card-value">{{ $totalSiswa }}</p>
            <i class="fas fa-user-group icon-placeholder"></i>
        </div>

        {{-- Card 2: Kehadiran Hari Ini --}}
        <div class="dashboard-card card-kehadiran animated-card" style="animation-delay: 0.2s;">
            <p class="card-label">Kehadiran Hari Ini</p>
            <p class="card-value">{{ $kehadiranHariIni }}</p>
            <i class="fas fa-clipboard-check icon-placeholder"></i>
        </div>

        {{-- Card 3: Izin & Sakit Hari Ini --}}
        <div class="dashboard-card card-izin-sakit animated-card" style="animation-delay: 0.3s;">
            <p class="card-label">Izin & Sakit</p>
            <p class="card-value">{{ $izinSakitHariIni }}</p>
            <i class="fas fa-hospital-user icon-placeholder"></i>
        </div>

        {{-- Card 4: Tidak Hadir Hari Ini --}}
        <div class="dashboard-card card-tidak-hadir animated-card" style="animation-delay: 0.4s;">
            <p class="card-label">Tidak Hadir</p>
            <p class="card-value">{{ $tidakHadirHariIni }}</p>
            <i class="fas fa-user-slash icon-placeholder"></i>
        </div>
    </div>
    
    {{-- Bagian Grafik, Notifikasi, dan Daftar Absensi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- Card Grafik --}}
        <div class="dashboard-card card-grafik animated-card" style="animation-delay: 0.5s;">
            <h2 class="text-2xl font-bold notification-title mb-6">Grafik Kehadiran Mingguan</h2>
            <div>
                {{-- Elemen canvas untuk Chart.js --}}
                <canvas id="weeklyAttendanceChart"></canvas>
            </div>
        </div>

        {{-- Card Notifikasi --}}
        <div class="dashboard-card card-notifikasi animated-card" style="animation-delay: 0.6s;">
            <h2 class="text-2xl font-bold notification-title mb-6">Peringatan & Notifikasi</h2>
            <div class="space-y-4 overflow-y-auto max-h-80 custom-scrollbar">
                
                {{-- Bagian ini akan menampilkan Pengumuman dari Database --}}
                @forelse($pengumuman as $item)
                    <div class="flex items-start bg-blue-50 dark:bg-blue-900 p-4 rounded-lg border border-blue-200 dark:border-blue-700 notification-item">
                        <i class="fas fa-bell text-xl text-blue-500 mt-1 mr-4"></i>
                        <div>
                            <p class="font-bold text-sm notification-title">{{ $item->judul }}</p>
                            <p class="text-xs notification-content mt-1">{{ $item->isi }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-start bg-gray-50 dark:bg-gray-900 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="font-bold text-sm text-gray-800 dark:text-gray-200">Tidak ada pengumuman aktif.</p>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>
    </div>

    {{-- Bagian Daftar Absensi Hari Ini --}}
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
        <div class="dashboard-card card-absensi-list animated-card" style="animation-delay: 0.7s;">
            <h2 class="text-2xl font-bold notification-title mb-6">Absensi Siswa Hari Ini</h2>
            <div class="space-y-4 overflow-y-auto max-h-96 custom-scrollbar">
                {{-- Daftar absensi siswa --}}
                @forelse($siswaHariIni as $siswa)
                    @php
                        // Cek status absensi siswa untuk hari ini
                        $absensi = $siswa->absensis->first();
                        $status = $absensi ? $absensi->status : 'Alfa'; // Jika tidak ada data, statusnya Alfa
                        
                        // Menentukan warna badge berdasarkan status
                        $badgeClass = '';
                        $avatarBorderClass = '';
                        switch ($status) {
                            case 'Hadir':
                                $badgeClass = 'badge-hadir';
                                $avatarBorderClass = 'border-hadir';
                                break;
                            case 'Izin':
                                $badgeClass = 'badge-izin';
                                $avatarBorderClass = 'border-izin';
                                break;
                            case 'Sakit':
                                $badgeClass = 'badge-sakit';
                                $avatarBorderClass = 'border-sakit';
                                break;
                            case 'Alfa':
                            default:
                                $badgeClass = 'badge-alfa';
                                $avatarBorderClass = 'border-alfa';
                                break;
                        }
                    @endphp

                    <div class="absensi-list-item flex items-center p-4 rounded-lg shadow-sm">
                        {{-- Perbaikan: Mengakses nama siswa dengan 'nama_siswa' --}}
                        <img src="{{ $siswa->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($siswa->nama_siswa) . '&background=random&size=40' }}" alt="{{ $siswa->nama_siswa }}" class="avatar-absensi mr-4 {{ $avatarBorderClass }}">
                        
                        <div class="flex-grow">
                            {{-- Perbaikan: Mengakses nama siswa dengan 'nama_siswa' --}}
                            <p class="nama-siswa-hadir">{{ $siswa->nama_siswa }}</p>
                            <p class="kelas-siswa-hadir">
                                {{-- Mengakses nama kelas melalui relasi. Sesuaikan 'kelas' dengan nama relasi di model Siswa. --}}
                                Kelas {{ $siswa->kelas->nama_kelas ?? 'N/A' }} 
                            </p>
                        </div>
                        
                        <span class="ml-auto badge-status {{ $badgeClass }}">
                            {{ $status }}
                        </span>
                    </div>
                @empty
                    <div class="p-4 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-info-circle mr-2"></i> Belum ada data absensi yang tercatat untuk hari ini.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- Skrip untuk Grafik Chart.js --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.animated-card');
        animatedElements.forEach((el, index) => {
            el.style.animationDelay = `${0.1 * (index + 1)}s`;
            el.style.animationPlayState = 'running';
        });

        // Ambil data dinamis dari PHP ke JavaScript
        const labels = @json($labels);
        const hadirData = @json($hadirData);
        const izinSakitData = @json($izinSakitData);
        const tidakHadirData = @json($tidakHadirData);

        // Skrip untuk inisialisasi Chart.js
        const ctx = document.getElementById('weeklyAttendanceChart');
        if (ctx) {
            const weeklyAttendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels, // Menggunakan data dinamis
                    datasets: [
                        {
                            label: 'Hadir',
                            data: hadirData, // Menggunakan data dinamis
                            backgroundColor: '#4299e1', // Biru
                            borderRadius: 6,
                        },
                        {
                            label: 'Izin & Sakit',
                            data: izinSakitData, // Menggunakan data dinamis
                            backgroundColor: '#ecc94b', // Kuning
                            borderRadius: 6,
                        },
                        {
                            label: 'Tidak Hadir',
                            data: tidakHadirData, // Menggunakan data dinamis
                            backgroundColor: '#f56565', // Merah
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            grid: { display: false }
                        },
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: { 
                            position: 'bottom',
                            labels: {
                                font: {
                                    family: 'Inter'
                                }
                            }
                        },
                        tooltip: { 
                            mode: 'index',
                            intersect: false,
                            bodyFont: {
                                family: 'Inter'
                            },
                            titleFont: {
                                family: 'Inter',
                                weight: 'bold'
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection