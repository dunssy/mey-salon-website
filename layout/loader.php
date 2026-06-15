<!-- =============================================
     MEY SALON - PAGE LOADER
     Include file ini setelah tag <body> opening
     di header.php dan header-user.php
     ============================================= -->

<!-- Overlay Loader -->
<div id="mey-page-loader" style="
    position: fixed;
    inset: 0;
    z-index: 99999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #fff5f8 0%, #fce7f3 50%, #fff0f6 100%);
    transition: opacity 0.5s ease, visibility 0.5s ease;
">
    <!-- Logo / Brand -->
    <div style="margin-bottom: 28px; text-align: center;">
        <div style="
            font-family: 'Playfair Display', 'Georgia', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #be185d;
            letter-spacing: 2px;
            line-height: 1;
        ">Mey Salon</div>
        <div style="
            font-family: 'Inter', 'Montserrat', sans-serif;
            font-size: 0.7rem;
            color: #f472b6;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 4px;
        ">Beauty & Care</div>
    </div>

    <!-- Animasi Scissor + Shimmer Dots -->
    <div style="position: relative; width: 72px; height: 72px; margin-bottom: 24px;">
        <!-- Lingkaran luar berputar -->
        <svg style="
            position: absolute;
            inset: 0;
            animation: mey-spin 1.4s linear infinite;
        " viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
            <circle cx="36" cy="36" r="32" stroke="#fce7f3" stroke-width="4"/>
            <circle cx="36" cy="36" r="32"
                stroke="url(#meyGrad)"
                stroke-width="4"
                stroke-linecap="round"
                stroke-dasharray="50 150"
                stroke-dashoffset="0"
            />
            <defs>
                <linearGradient id="meyGrad" x1="0" y1="0" x2="72" y2="72" gradientUnits="userSpaceOnUse">
                    <stop offset="0%" stop-color="#ec4899"/>
                    <stop offset="100%" stop-color="#f9a8d4"/>
                </linearGradient>
            </defs>
        </svg>

        <!-- Icon gunting / bunga di tengah -->
        <div style="
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            animation: mey-pulse 1.4s ease-in-out infinite;
        ">✂️</div>
    </div>

    <!-- Titik loading -->
    <div style="display: flex; gap: 8px; align-items: center;">
        <span style="
            width: 8px; height: 8px; border-radius: 50%;
            background: #ec4899;
            display: inline-block;
            animation: mey-bounce 1.2s ease-in-out infinite;
            animation-delay: 0s;
        "></span>
        <span style="
            width: 8px; height: 8px; border-radius: 50%;
            background: #f472b6;
            display: inline-block;
            animation: mey-bounce 1.2s ease-in-out infinite;
            animation-delay: 0.2s;
        "></span>
        <span style="
            width: 8px; height: 8px; border-radius: 50%;
            background: #fbcfe8;
            display: inline-block;
            animation: mey-bounce 1.2s ease-in-out infinite;
            animation-delay: 0.4s;
        "></span>
    </div>

    <!-- Teks loading -->
    <p style="
        margin-top: 20px;
        font-family: 'Inter', 'Montserrat', sans-serif;
        font-size: 0.8rem;
        color: #db2777;
        letter-spacing: 1px;
        opacity: 0.8;
    ">Memuat halaman...</p>
</div>

<style>
    @keyframes mey-spin {
        to { transform: rotate(360deg); }
    }
    @keyframes mey-bounce {
        0%, 100% { transform: translateY(0); opacity: 0.5; }
        50%       { transform: translateY(-8px); opacity: 1; }
    }
    @keyframes mey-pulse {
        0%, 100% { transform: scale(1); opacity: 0.9; }
        50%       { transform: scale(1.15); opacity: 1; }
    }

    /* Sembunyikan loader setelah fade */
    #mey-page-loader.mey-loader-hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }
</style>

<script>
    (function () {
        var loader = document.getElementById('mey-page-loader');

        function hideLoader() {
            if (loader) {
                loader.classList.add('mey-loader-hidden');
                // Hapus dari DOM setelah animasi selesai
                setTimeout(function () {
                    if (loader && loader.parentNode) {
                        loader.parentNode.removeChild(loader);
                    }
                }, 600);
            }
        }

        // Sembunyikan saat halaman sudah selesai dimuat
        if (document.readyState === 'complete') {
            // Sudah selesai (mis. cache), tunda sedikit biar terlihat
            setTimeout(hideLoader, 300);
        } else {
            window.addEventListener('load', function () {
                setTimeout(hideLoader, 300);
            });
        }

        // Fallback: paksa sembunyikan setelah 6 detik
        setTimeout(hideLoader, 6000);
    })();
</script>
