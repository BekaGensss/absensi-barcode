{{-- File: resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Pindahkan semua link CSS dan script yang diperlukan di luar blok @auth --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Gaya dasar */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
            color: #2c3e50;
        }
        
        /* Sidebar dan Konten Utama - Transisi lebih halus */
        .sidebar {
            width: 280px; /* Lebar yang lebih proporsional */
            transform: translateX(0);
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-color: #1f2937;
            color: #d1d5db;
            z-index: 50;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
        }
        .sidebar::-webkit-scrollbar { width: 8px; }
        .sidebar::-webkit-scrollbar-thumb { background-color: #4b5563; border-radius: 4px; }
        .sidebar::-webkit-scrollbar-track { background-color: #374151; }
        .sidebar.closed { transform: translateX(-100%); }
        .main-content {
            margin-left: 280px;
            transition: margin-left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            background-color: #ffffff;
        }
        .main-content.full-width { margin-left: 0; }
        
        /* Gaya untuk tautan navigasi - Profesional dan interaktif */
        .nav-link {
            padding: 16px 20px;
            border-radius: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            cursor: pointer;
            position: relative;
        }
        .nav-link:not(.active):hover {
            background-color: #374151;
            color: #e5e7eb;
            transform: translateX(5px);
        }
        .nav-link i {
            transition: color 0.3s ease;
        }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(99,102,241,0.2) 0%, rgba(99,102,241,0) 100%);
            color: #6366f1;
            font-weight: 600;
        }
        .nav-link.active i { color: #6366f1; }
        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 4px;
            height: 80%;
            background-color: #6366f1;
            border-radius: 0 4px 4px 0;
            transition: transform 0.3s ease-in-out;
            transform: translateY(-50%) scaleY(0);
        }
        .nav-link.active::before, .nav-link:hover::before {
            transform: translateY(-50%) scaleY(1);
        }

        .submenu-link {
            padding: 12px 20px 12px 52px;
            display: block;
            border-radius: 8px;
            transition: all 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            color: #a0aec0;
            position: relative;
            font-weight: 400;
        }
        .submenu-link:not(.active):hover {
            background-color: #374151;
            color: #e5e7eb;
        }
        .submenu-link.active {
            background-color: #4b5563;
            color: #6366f1;
            font-weight: 600;
        }
        .submenu-link::before {
            content: '';
            position: absolute;
            left: 34px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background-color: #4b5563;
            border-radius: 50%;
        }
        .submenu-link.active::before { background-color: #6366f1; }
        
        /* Animasi ikon panah */
        .rotated { transform: rotate(180deg); transition: transform 0.3s ease-in-out; }
        
        /* Animasi ikon hamburger */
        .hamburger-icon {
            display: flex; flex-direction: column; justify-content: space-between;
            width: 24px; height: 20px; cursor: pointer;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hamburger-icon span {
            display: block; height: 2px; background: #4a5568; border-radius: 2px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hamburger-icon.open span:nth-child(1) { transform: rotate(-45deg) translate(-5px, 6px); }
        .hamburger-icon.open span:nth-child(2) { opacity: 0; }
        .hamburger-icon.open span:nth-child(3) { transform: rotate(45deg) translate(-5px, -6px); }

        /* Overlay untuk mobile */
        #overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5); z-index: 49;
            display: none; opacity: 0;
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #overlay.visible { display: block; opacity: 1; }
        
        /* Dropdown menu user */
        .dropdown-menu {
            opacity: 0; transform: translateY(-10px);
            transition: opacity 0.2s ease-in-out, transform 0.2s ease-in-out;
            pointer-events: none;
        }
        .dropdown-menu.show { opacity: 1; transform: translateY(0); pointer-events: auto; }

        /* Animasi submenu yang profesional */
        .submenu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: all 0.4s ease-in-out; /* Transisi lebih halus */
        }
        .submenu.active {
            max-height: 500px;
            opacity: 1;
        }

        /* Responsif */
        @media (max-width: 768px) {
            .sidebar { position: fixed; height: 100vh; transform: translateX(-100%); }
            .sidebar.opened { transform: translateX(0); }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body class="font-sans antialiased">
    
    {{-- Pisahkan layout menjadi dua bagian: untuk pengguna yang sudah login dan belum --}}
    @auth
    <div class="min-h-screen flex">
        
        <aside id="sidebar" class="sidebar fixed z-50 h-screen overflow-hidden">
            <div class="p-6 border-b border-gray-700 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-100">
                    @if(isset($settings['nama_sekolah']))
                        {{ $settings['nama_sekolah'] }}
                    @else
                        SISWA ABSEN
                    @endif
                </a>
            </div>
            <nav class="flex-1 mt-4 p-4 space-y-2">
                <ul class="space-y-2">
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'guru')
                        <li>
                            <button class="nav-link w-full text-left flex items-center justify-between {{ is_parent_active(['dashboard']) }}" onclick="toggleSubMenu('utama-submenu', this)">
                                <span class="flex items-center flex-1">
                                    <i class="fas fa-home mr-4 text-lg"></i>
                                    <span>UTAMA</span>
                                </span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                            </button>
                            <ul id="utama-submenu" class="submenu mt-2 space-y-1 ml-6">
                                <li><a href="{{ route('dashboard') }}" class="submenu-link {{ is_active('dashboard') }}">Dashboard</a></li>
                            </ul>
                        </li>
                    @endif
                    
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'guru')
                        <li>
                            <a href="{{ route('absensi-qr.scanner') }}" class="nav-link w-full text-left flex items-center {{ is_active('absensi-qr.scanner') }}">
                                <i class="fas fa-qrcode mr-4 text-lg"></i>
                                <span>ABSEN QR</span>
                            </a>
                        </li>
                    @endif
                    
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'guru')
                        <li>
                            <button class="nav-link w-full text-left flex items-center justify-between {{ is_parent_active(['kehadiran', 'rekap-absensi']) }}" onclick="toggleSubMenu('rekap-submenu', this)">
                                <span class="flex items-center flex-1">
                                    <i class="fas fa-chart-bar mr-4 text-lg"></i>
                                    <span>REKAP</span>
                                </span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                            </button>
                            <ul id="rekap-submenu" class="submenu mt-2 space-y-1 ml-6">
                                <li><a href="{{ route('kehadiran') }}" class="submenu-link {{ is_active('kehadiran') }}">Kehadiran</a></li>
                                <li><a href="{{ route('rekap-absensi') }}" class="submenu-link {{ is_active('rekap-absensi') }}">Rekap Absensi</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <li>
                            <button class="nav-link w-full text-left flex items-center justify-between {{ is_parent_active(['siswa', 'wali-kelas', 'kelas', 'tahun-ajaran', 'absen-kelas']) }}" onclick="toggleSubMenu('master-submenu', this)">
                                <span class="flex items-center flex-1">
                                    <i class="fas fa-database mr-4 text-lg"></i>
                                    <span>MASTER</span>
                                </span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                            </button>
                            <ul id="master-submenu" class="submenu mt-2 space-y-1 ml-6">
                                <li><a href="{{ route('siswa.index') }}" class="submenu-link {{ is_active('siswa.index') }}">Siswa</a></li>
                                <li><a href="{{ route('wali-kelas.index') }}" class="submenu-link {{ is_active('wali-kelas.index') }}">Wali Kelas</a></li>
                                <li><a href="{{ route('kelas.index') }}" class="submenu-link {{ is_active('kelas.index') }}">Kelas</a></li>
                                <li><a href="{{ route('tahun-ajaran.index') }}" class="submenu-link {{ is_active('tahun-ajaran.index') }}">Tahun Ajaran</a></li>
                                <li><a href="{{ route('absen-kelas.index') }}" class="submenu-link {{ is_active('absen-kelas.index') }}">Absen Kelas</a></li>
                            </ul>
                        </li>
                    @endif

                    @if(Auth::user()->role === 'admin')
                        <li>
                            <button class="nav-link w-full text-left flex items-center justify-between {{ is_parent_active(['users', 'pengaturan-umum', 'pengumuman', 'profil']) }}" onclick="toggleSubMenu('pengaturan-submenu', this)">
                                <span class="flex items-center flex-1">
                                    <i class="fas fa-cogs mr-4 text-lg"></i>
                                    <span>PENGATURAN</span>
                                </span>
                                <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                            </button>
                            <ul id="pengaturan-submenu" class="submenu mt-2 space-y-1 ml-6">
                                <li><a href="{{ route('users.index') }}" class="submenu-link {{ is_active('users.index') }}">Kelola User</a></li>
                                <li><a href="{{ route('pengaturan-umum') }}" class="submenu-link {{ is_active('pengaturan-umum') }}">Pengaturan Umum</a></li>
                                <li><a href="{{ route('pengumuman.index') }}" class="submenu-link {{ is_active('pengumuman.index') }}">Pengumuman</a></li>
                                <li><a href="{{ route('profil') }}" class="submenu-link {{ is_active('profil') }}">Profil</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
            
            <div class="mt-auto p-4 border-t border-gray-700">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link w-full text-left">
                        <i class="fas fa-sign-out-alt mr-4 text-lg"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <div id="main-content" class="main-content flex-1 flex flex-col h-screen">
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 sticky top-0 z-40 shadow-sm">
                <button id="toggle-sidebar" class="focus:outline-none p-2">
                    <div id="hamburger-icon" class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center space-x-2 text-gray-800 font-medium focus:outline-none">
                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                            <i class="fas fa-user-circle text-xl"></i>
                        </div>
                        <span>{{ Auth::user()?->name }}</span>
                        <i class="fas fa-chevron-down text-xs transition-transform duration-300"></i>
                    </button>
                    
                    <div id="user-dropdown-menu" class="dropdown-menu absolute right-0 mt-3 w-48 bg-white rounded-md shadow-lg py-1 border border-gray-200 z-50">
                        <a href="{{ route('profil') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profil
                        </a>
                        <a href="{{ route('pengaturan-umum') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cogs mr-2"></i> Pengaturan
                        </a>
                        <div class="border-t border-gray-200 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <main class="flex-1 p-6 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
    @else
    {{-- Layout sederhana untuk pengguna yang belum login (halaman login, register, reset password) --}}
    <main class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="w-full max-w-md px-4">
             @yield('content')
        </div>
    </main>
    @endauth
    
    <div id="overlay"></div>

    <script>
        // Logika JavaScript hanya dijalankan jika pengguna sudah terautentikasi
        @auth
        function toggleSubMenu(id, button) {
            const submenu = document.getElementById(id);
            const icon = button.querySelector('.fa-chevron-down');
            const isActive = submenu.classList.contains('active');
            
            document.querySelectorAll('.submenu.active').forEach(sm => {
                const parentButton = sm.previousElementSibling;
                sm.classList.remove('active');
                if (parentButton) {
                    parentButton.classList.remove('active');
                    parentButton.querySelector('.fa-chevron-down').classList.remove('rotated');
                }
            });

            if (!isActive) {
                submenu.classList.add('active');
                button.classList.add('active');
                icon.classList.add('rotated');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const toggleButton = document.getElementById('toggle-sidebar');
            const hamburgerIcon = document.getElementById('hamburger-icon');
            const overlay = document.getElementById('overlay');
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdownMenu = document.getElementById('user-dropdown-menu');
            const currentPath = window.location.pathname;

            toggleButton.addEventListener('click', () => {
                const isMobile = window.innerWidth <= 768;

                if (isMobile) {
                    sidebar.classList.toggle('opened');
                    overlay.classList.toggle('visible');
                } else {
                    sidebar.classList.toggle('closed');
                    mainContent.classList.toggle('full-width');
                }
                hamburgerIcon.classList.toggle('open');
            });
            
            userMenuButton.addEventListener('click', (event) => {
                event.stopPropagation();
                userDropdownMenu.classList.toggle('show');
                userMenuButton.querySelector('.fa-chevron-down').classList.toggle('rotated');
            });
            
            window.addEventListener('click', (event) => {
                if (!userDropdownMenu.contains(event.target) && !userMenuButton.contains(event.target)) {
                    userDropdownMenu.classList.remove('show');
                    userMenuButton.querySelector('.fa-chevron-down').classList.remove('rotated');
                }
            });

            overlay.addEventListener('click', () => {
                sidebar.classList.remove('opened');
                hamburgerIcon.classList.remove('open');
                overlay.classList.remove('visible');
            });
            
            const allLinks = document.querySelectorAll('.nav-link, .submenu-link');
            allLinks.forEach(link => {
                const linkPath = new URL(link.href).pathname;
                if (linkPath === currentPath) {
                    link.classList.add('active');
                    
                    const parentSubmenu = link.closest('.submenu');
                    if (parentSubmenu) {
                        parentSubmenu.classList.add('active');
                        const parentButton = parentSubmenu.previousElementSibling;
                        if (parentButton) {
                            parentButton.classList.add('active');
                            parentButton.querySelector('.fa-chevron-down').classList.add('rotated');
                        }
                    }
                }
            });
            
            window.addEventListener('resize', () => {
                const isMobile = window.innerWidth <= 768;
                if (!isMobile) {
                    sidebar.classList.remove('opened');
                    overlay.classList.remove('visible');
                    if (!sidebar.classList.contains('closed') && mainContent.classList.contains('full-width')) {
                        mainContent.classList.remove('full-width');
                    }
                }
            });
        });
        @endauth
    </script>
</body>
</html>
