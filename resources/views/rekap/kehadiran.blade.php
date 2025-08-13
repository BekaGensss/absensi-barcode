@extends('layouts.app')

@section('title', 'Rekap Kehadiran Harian')

@section('content')
    <style>
        /* Gaya kustom untuk Flatpickr */
        .flatpickr-calendar {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            font-family: 'Inter', sans-serif;
            color: #1f2937;
        }

        .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day:hover, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected.inRange:focus, .flatpickr-day.startRange.inRange:focus, .flatpickr-day.endRange.inRange:focus {
            background: #2dd4bf; /* bg-teal-400 */
            border-color: #2dd4bf;
            color: #fff;
            box-shadow: 0 4px 10px rgba(45, 212, 191, 0.2);
        }
        
        /* Loading spinner kustom */
        .loading-spinner {
            width: 1.25rem;
            height: 1.25rem;
            border: 3px solid rgba(255, 255, 255, 0.7);
            border-bottom-color: #fff;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Animasi Kustom */
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

        @keyframes bounce-subtle {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-5px);
            }
        }
        .animate-bounce-subtle {
            animation: bounce-subtle 1s infinite;
        }

    </style>

    <div class="p-4 sm:p-8">
        <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 border-t-8 border-teal-500 animate-slide-in-up">
            <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 mb-8 text-center animate-slide-in-up" style="animation-delay: 0.2s; font-family: 'Inter', sans-serif;">
                Rekapitulasi Kehadiran Harian
            </h1>

            <form action="{{ route('kehadiran') }}" method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 items-end animate-slide-in-up" style="animation-delay: 0.4s;">
                <div class="w-full relative">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">Pilih Kelas</label>
                    <div class="relative mt-1">
                        <select name="kelas_id" id="kelas_id" class="block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ ($kelas_id ?? '') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                        <i class="fas fa-chalkboard-teacher absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                </div>
                <div class="w-full relative">
                    <label for="tanggal" class="block text-sm font-medium text-gray-700">Pilih Tanggal</label>
                    <div class="relative mt-1">
                        <input type="text" name="tanggal" id="tanggal" value="{{ $tanggal ?? '' }}" placeholder="Pilih Tanggal" class="flatpickr block w-full rounded-lg border border-gray-300 shadow-sm pl-10 pr-4 py-2 text-gray-900 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all duration-300 hover:border-gray-400 cursor-pointer">
                        <i class="fas fa-calendar-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none transition duration-300"></i>
                    </div>
                </div>
                <div class="w-full flex items-end">
                    <button type="submit" id="filter-button" class="w-full bg-teal-500 text-white px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 hover:bg-teal-600 hover:shadow-lg flex items-center justify-center space-x-2">
                        <span class="loading-spinner hidden"></span>
                        <span id="button-text"><i class="fas fa-filter mr-2"></i> Filter Data</span>
                    </button>
                </div>
                <div class="w-full flex items-end">
                    <button type="button" onclick="document.getElementById('export-excel-form').submit();" class="w-full bg-green-500 text-white px-6 py-2.5 rounded-full font-semibold transition-all duration-300 hover:bg-green-600 hover:shadow-lg flex items-center justify-center space-x-2 text-sm">
                        <i class="fas fa-file-excel mr-2"></i>
                        <span>Ekspor Excel</span>
                    </button>
                </div>
            </form>

            @if($absensi->isEmpty() && ($kelas_id ?? null) && ($tanggal ?? null))
                <div class="p-8 mb-4 text-center bg-yellow-50 rounded-xl border border-yellow-200 shadow-md animate-slide-in-up" style="animation-delay: 0.6s;">
                    <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4 animate-bounce-subtle"></i>
                    <p class="text-xl font-semibold text-yellow-800">Ups! Tidak ada data absensi.</p>
                    <p class="text-gray-600 mt-2">Belum ada absensi yang tercatat untuk kelas dan tanggal yang Anda pilih. Silakan periksa kembali filter Anda.</p>
                </div>
            @elseif(!($kelas_id ?? null) && !($tanggal ?? null))
                <div class="p-8 mb-4 text-center bg-teal-50 rounded-xl border border-teal-200 shadow-md animate-slide-in-up" style="animation-delay: 0.6s;">
                    <i class="fas fa-filter text-4xl text-teal-500 mb-4 animate-bounce-subtle"></i>
                    <p class="text-xl font-semibold text-teal-800">Mulai Rekap Absensi</p>
                    <p class="text-gray-600 mt-2">Silakan pilih kelas dan tanggal di atas untuk menampilkan data kehadiran.</p>
                </div>
            @else
                <div class="overflow-x-auto rounded-xl shadow-lg border border-gray-200 animate-slide-in-up" style="animation-delay: 0.6s;">
                    <table class="min-w-full bg-white table-auto rounded-xl overflow-hidden border-collapse">
                        <thead class="bg-gray-100 border-b border-gray-200 sticky top-0 z-10">
                            <tr class="text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-4 px-3 sm:px-6 text-left font-bold">No</th>
                                <th class="py-4 px-3 sm:px-6 text-left font-bold">Nama Siswa</th>
                                <th class="py-4 px-3 sm:px-6 text-left font-bold">NISN</th>
                                <th class="py-4 px-3 sm:px-6 text-left font-bold">Status</th>
                                <th class="py-4 px-3 sm:px-6 text-left font-bold">Waktu Absen</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 text-sm sm:text-base font-light">
                            @foreach($absensi as $item)
                                <tr class="border-b border-gray-100 hover:bg-teal-50 transition duration-200 transform hover:scale-[1.01] hover:shadow-md">
                                    <td class="py-3 px-3 sm:px-6 text-left whitespace-nowrap">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-3 sm:px-6 text-left">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                                    <td class="py-3 px-3 sm:px-6 text-left">{{ $item->siswa->nisn ?? '-' }}</td>
                                    <td class="py-3 px-3 sm:px-6 text-left">
                                        @php
                                            $statusClass = [
                                                'Hadir' => 'bg-green-100 text-green-700',
                                                'Sakit' => 'bg-yellow-100 text-yellow-700',
                                                'Izin' => 'bg-sky-100 text-sky-700',
                                                'Alfa' => 'bg-red-100 text-red-700',
                                            ][$item->status] ?? 'bg-gray-100 text-gray-700';
                                        @endphp
                                        <span class="py-1 px-3 rounded-full text-sm font-semibold {{ $statusClass }} transition-colors duration-200">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 sm:px-6 text-left">{{ \Carbon\Carbon::parse($item->waktu_absen)->format('H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <form id="export-excel-form" action="{{ route('absensi.export.excel') }}" method="GET" class="hidden">
        <input type="hidden" name="kelas_id" value="{{ $kelas_id ?? '' }}">
        <input type="hidden" name="tanggal" value="{{ $tanggal ?? '' }}">
    </form>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi Flatpickr
            flatpickr("#tanggal", {
                dateFormat: "Y-m-d",
                defaultDate: "{{ $tanggal ?? '' }}",
                altInput: true,
                altFormat: "d/m/Y",
                locale: "id",
                disableMobile: "true",
                onReady: function(selectedDates, dateStr, instance) {
                    instance.calendarContainer.classList.add('bg-white', 'p-4', 'rounded-xl', 'shadow-xl');
                }
            });

            const form = document.querySelector('form');
            const filterButton = document.getElementById('filter-button');
            const buttonText = document.getElementById('button-text');
            const loadingSpinner = filterButton.querySelector('.loading-spinner');

            // Tangani submit formulir
            form.addEventListener('submit', function() {
                filterButton.disabled = true;
                filterButton.classList.remove('bg-teal-500', 'hover:bg-teal-600');
                filterButton.classList.add('bg-teal-300', 'cursor-not-allowed');

                loadingSpinner.classList.remove('hidden');
                buttonText.innerHTML = 'Memuat...';
            });

            // Atur ulang tombol saat halaman dimuat kembali dari cache browser
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    filterButton.disabled = false;
                    filterButton.classList.remove('bg-teal-300', 'cursor-not-allowed');
                    filterButton.classList.add('bg-teal-500', 'hover:bg-teal-600');
                    loadingSpinner.classList.add('hidden');
                    buttonText.innerHTML = '<i class="fas fa-filter mr-2"></i> Filter Data';
                }
            });
        });
    </script>
@endsection