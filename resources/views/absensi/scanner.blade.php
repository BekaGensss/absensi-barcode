@extends('layouts.app')

@section('title', 'Pemindai Absensi QR')

@section('content')
<style>
    /* Keyframes khusus untuk animasi masuk yang lebih halus */
    @keyframes slide-in-up {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-in-up {
        animation: slide-in-up 0.7s ease-out forwards;
    }
    
    /* Animasi scale in */
    @keyframes scale-in {
        from { opacity: 0; transform: scale(0.8); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-scale-in {
        animation: scale-in 0.6s ease-out 0.2s forwards;
    }
    
    /* Animasi pulse untuk pesan hasil */
    @keyframes pulse-result {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-pulse-result {
        animation: pulse-result 1.5s ease-in-out infinite;
    }

    /* Animasi titik loading */
    @keyframes loading-dots {
        0%, 20% { opacity: 0; }
        40% { opacity: 1; }
        100% { opacity: 0; }
    }
    .loading-text .loading-dot:nth-child(1) {
        animation: loading-dots 1s infinite;
    }
    .loading-text .loading-dot:nth-child(2) {
        animation: loading-dots 1s infinite 0.2s;
    }
    .loading-text .loading-dot:nth-child(3) {
        animation: loading-dots 1s infinite 0.4s;
    }
</style>

<div class="p-4 sm:p-8 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-3xl shadow-xl p-6 sm:p-10 text-center border-t-8 border-teal-500 animate-slide-in-up">
            <div class="flex flex-col items-center justify-center mb-6">
                <img src="{{ asset('images/Logo_Bk.png') }}" alt="Logo Sekolah" class="h-16 w-16 mb-4 animate-scale-in">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-800 leading-tight animate-slide-in-up" style="font-family: 'Inter', sans-serif; animation-delay: 0.2s;">
                    Pemindai Absensi
                </h1>
                <p class="text-gray-600 mt-2 text-sm animate-slide-in-up" style="animation-delay: 0.4s;">
                    Arahkan kamera ke QR Code Anda.
                </p>
            </div>

            <div id="qr-reader-container" class="relative w-full max-w-sm mx-auto p-4 border border-gray-300 rounded-3xl shadow-inner bg-gray-50 animate-slide-in-up" style="animation-delay: 0.6s;">
                <div id="qr-reader" class="overflow-hidden rounded-2xl"></div>
                <div id="qr-reader-message" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 backdrop-blur-sm z-10 transition-opacity duration-300 opacity-100">
                    <p class="text-lg text-gray-700 font-medium loading-text">Memuat kamera<span class="loading-dot">.</span><span class="loading-dot">.</span><span class="loading-dot">.</span></p>
                </div>
            </div>

            <div id="qr-reader-results" class="mt-8 min-h-[5rem] flex flex-col items-center justify-center animate-slide-in-up" style="animation-delay: 0.8s;">
                <div id="result-message" class="transition-opacity duration-500"></div>
                <button id="rescan-button" class="mt-4 px-6 py-3 bg-teal-500 text-white font-semibold rounded-full shadow-lg transition-all duration-300 hover:bg-teal-600 hover:shadow-xl focus:outline-none hidden flex items-center space-x-2 transform hover:scale-105">
                    <i class="fas fa-redo-alt mr-2"></i>
                    <span>Scan Ulang</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Skrip html5-qrcode dari CDN --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const qrReaderMessageDiv = document.getElementById('qr-reader-message');
        const resultMessageDiv = document.getElementById('result-message');
        const rescanButton = document.getElementById('rescan-button');
        const html5QrCode = new Html5Qrcode("qr-reader");

        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            supportedScanFormats: [Html5QrcodeSupportedFormats.QR_CODE],
        };

        const startScanner = () => {
            // Tampilkan pesan loading
            qrReaderMessageDiv.style.opacity = '1';
            qrReaderMessageDiv.style.pointerEvents = 'auto';
            
            html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
                .then(() => {
                    qrReaderMessageDiv.style.opacity = '0';
                    qrReaderMessageDiv.style.pointerEvents = 'none';
                    rescanButton.classList.add('hidden');
                })
                .catch((err) => {
                    console.error("Gagal memulai pemindai QR Code.", err);
                    qrReaderMessageDiv.innerHTML = `<p class="text-red-600 font-medium">Gagal mengakses kamera. Pastikan Anda memberikan izin.</p>`;
                    qrReaderMessageDiv.style.opacity = '1';
                    qrReaderMessageDiv.style.pointerEvents = 'auto';
                });
        }
        
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            console.log(`Scan result: ${decodedText}`);
            
            // Hentikan pemindai untuk mencegah pemindaian berulang
            html5QrCode.stop().then(() => {
                console.log("QR Code pemindai dihentikan.");
                
                // Kirim data ke server
                fetch('{{ route('absensi-qr.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ nisn: decodedText })
                })
                .then(response => response.json())
                .then(data => {
                    let messageHtml = '';
                    if (data.success) {
                        messageHtml = `<div class="p-4 bg-green-50 border border-green-300 text-green-800 rounded-xl shadow-md animated-fade-in">
                                            <i class="fas fa-check-circle text-2xl text-green-500 mb-2"></i>
                                            <p class="font-semibold text-lg animate-pulse-result">${data.message}</p>
                                            <p class="text-gray-700 mt-2">Siswa: ${data.siswa.nama_siswa} (${data.siswa.nisn})</p>
                                        </div>`;
                    } else {
                        messageHtml = `<div class="p-4 bg-red-50 border border-red-300 text-red-800 rounded-xl shadow-md animated-fade-in">
                                            <i class="fas fa-exclamation-triangle text-2xl text-red-500 mb-2"></i>
                                            <p class="font-semibold text-lg animate-pulse-result">${data.message}</p>
                                        </div>`;
                    }
                    resultMessageDiv.innerHTML = messageHtml;
                    rescanButton.classList.remove('hidden'); // Tampilkan tombol scan ulang
                })
                .catch(error => {
                    resultMessageDiv.innerHTML = `<div class="p-4 bg-red-50 border border-red-300 text-red-800 rounded-xl shadow-md animated-fade-in">
                                                    <i class="fas fa-exclamation-triangle text-2xl text-red-500 mb-2"></i>
                                                    <p class="font-semibold text-lg">Terjadi kesalahan. Coba lagi.</p>
                                                  </div>`;
                    console.error('Error:', error);
                    rescanButton.classList.remove('hidden'); // Tampilkan tombol scan ulang
                });
            }).catch(err => {
                console.error("Gagal menghentikan pemindai.", err);
            });
        };
        
        // Mulai pemindai saat halaman dimuat
        startScanner();
        
        // Event listener untuk tombol scan ulang
        rescanButton.addEventListener('click', () => {
            resultMessageDiv.innerHTML = '';
            startScanner();
        });
    });
</script>
@endsection