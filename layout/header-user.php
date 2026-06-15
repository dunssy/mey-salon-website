<?php
// Cek apakah session sudah aktif sebelum memanggil session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// jika role bukan admin 
if ($_SESSION['role'] !== 'Customer') {
    // redirect ke halaman login
    header("Location: ../login.php");    
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Metadata dasar -->
    <meta charset="UTF-8">

    <!-- Tampilan responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- ico mey salon -->
        <!-- untuk android -->
    <link rel="apple-touch-icon" sizes="180x180" href="../layout/images/favicon_io/apple-touch-icon.png">
    <!-- untuk desktop -->
    <link rel="icon" type="image/png" sizes="32x32" href="../layout/images/favicon_io/favicon-32x32.png">
    <link rel="icon" href="../layout/images/favicon_io/favicon.ico" type="image/x-icon" />
    <!-- Judul halaman -->
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Mey Salon'; ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <!-- SweetAlert2 -->
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Style halaman user -->
    <style>
        /* Font utama */
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        /* Navbar transparan */
        .glass-nav {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
        }

        /* Section user */
        .content-section {
            animation: fadeIn 0.25s ease-out;
        }

        /* Tombol tanggal kalender */
        .calendar-day {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 0.85rem;
            font-size: 11px;
            font-weight: 800;
            transition: all 0.2s ease;
        }

        /* Tanggal kosong */
        .calendar-day-empty {
            opacity: 0;
            pointer-events: none;
        }

        /* Tanggal tersedia */
        .calendar-day-available {
            background: #ffffff;
            border: 1px solid #fbcfe8;
            color: #374151;
        }

        /* Tanggal ada booking */
        .calendar-day-booked {
            background: #db2777;
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(219, 39, 119, 0.25);
        }

        /* Tanggal hari ini */
        .calendar-day-today {
            border: 2px solid #db2777 !important;
            color: #db2777 !important;
            background: #ffffff !important;
        }

        /* Tanggal sebelum hari ini */
        .calendar-day-disabled {
            background: #f3f4f6 !important;
            color: #cbd5e1 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
        }

        /* Tanggal dipilih */
        .calendar-day-selected {
            background: #111827 !important;
            color: #ffffff !important;
        }

        /* Jam dipilih */
        .time-selected {
            background: #db2777 !important;
            color: #ffffff !important;
            border-color: #db2777 !important;
        }

        /* Toast pesan */
        #toast {
            max-width: calc(100vw - 24px);
        }

        /* Animasi fade */
        .animate-fade-in {
            animation: fadeIn 0.25s ease-out;
        }

        /* Keyframe fade */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Kalender mobile */
        @media (max-width: 640px) {
            .calendar-day {
                border-radius: 0.7rem;
                font-size: 10px;
            }
        }
    </style>

    <!-- MEY SALON PAGE LOADER (User) -->
    <style>
        #mey-page-loader {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #fff5f8 0%, #fce7f3 60%, #fdf2f8 100%);
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #mey-page-loader.mey-loader-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .mey-loader-brand { font-family: 'Inter', sans-serif; text-align: center; margin-bottom: 28px; }
        .mey-loader-brand-title { font-size: 2rem; font-weight: 700; color: #be185d; letter-spacing: 2px; }
        .mey-loader-brand-sub { font-size: 0.65rem; color: #f472b6; letter-spacing: 4px; text-transform: uppercase; margin-top: 3px; }
        .mey-spinner-wrap { position: relative; width: 72px; height: 72px; margin-bottom: 22px; }
        .mey-spinner-svg { position: absolute; inset: 0; animation: mey-spin 1.4s linear infinite; }
        .mey-spinner-icon { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; animation: mey-pulse 1.4s ease-in-out infinite; }
        .mey-dots { display: flex; gap: 8px; align-items: center; }
        .mey-dot { width: 8px; height: 8px; border-radius: 50%; background: #ec4899; animation: mey-bounce 1.2s ease-in-out infinite; }
        .mey-dot:nth-child(2) { background: #f472b6; animation-delay: .2s; }
        .mey-dot:nth-child(3) { background: #fbcfe8; animation-delay: .4s; }
        .mey-loader-text { margin-top: 18px; font-family: 'Inter', sans-serif; font-size: 0.78rem; color: #db2777; letter-spacing: 1px; opacity: 0.8; }
        @keyframes mey-spin    { to { transform: rotate(360deg); } }
        @keyframes mey-bounce  { 0%,100%{transform:translateY(0);opacity:.5} 50%{transform:translateY(-7px);opacity:1} }
        @keyframes mey-pulse   { 0%,100%{transform:scale(1);opacity:.9} 50%{transform:scale(1.15);opacity:1} }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.createElement('div');
            el.id = 'mey-page-loader';
            el.innerHTML = '<div class="mey-loader-brand"><div class="mey-loader-brand-title">Mey Salon</div><div class="mey-loader-brand-sub">Beauty &amp; Care</div></div>'
                + '<div class="mey-spinner-wrap">'
                + '<svg class="mey-spinner-svg" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">'
                + '<circle cx="36" cy="36" r="32" stroke="#fce7f3" stroke-width="4"/>'
                + '<circle cx="36" cy="36" r="32" stroke="url(#meyGU)" stroke-width="4" stroke-linecap="round" stroke-dasharray="50 150"/>'
                + '<defs><linearGradient id="meyGU" x1="0" y1="0" x2="72" y2="72" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#ec4899"/><stop offset="100%" stop-color="#f9a8d4"/></linearGradient></defs></svg>'
                + '<div class="mey-spinner-icon">✂️</div></div>'
                + '<div class="mey-dots"><span class="mey-dot"></span><span class="mey-dot"></span><span class="mey-dot"></span></div>'
                + '<p class="mey-loader-text">Memuat halaman...</p>';
            if (document.body) document.body.insertBefore(el, document.body.firstChild);
        }, { once: true });

        (function () {
            function hide() {
                var el = document.getElementById('mey-page-loader');
                if (el) {
                    el.classList.add('mey-loader-hidden');
                    setTimeout(function () { if (el && el.parentNode) el.parentNode.removeChild(el); }, 600);
                }
            }
            if (document.readyState === 'complete') { setTimeout(hide, 350); }
            else { window.addEventListener('load', function () { setTimeout(hide, 350); }); }
            setTimeout(hide, 7000);
        })();
    </script>
    <!-- MEY SALON END LOAD LOADER (USER) -->
</head>
