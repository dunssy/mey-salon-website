<?php 
// Cek apakah session sudah aktif sebelum memanggil session_start()
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// jika role bukan admin 
if ($_SESSION['role'] !== 'Administrator') {
    // redirect ke halaman login
    header("Location: ../login.php");    
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?>,<?php echo $_SESSION['role']; ?> - Mey Salon</title>
    <link href="../src/output.css" rel="stylesheet">
    <!-- favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="../layout/images/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../layout/images/favicon_io/favicon-32x32.png">
    
     <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fdf2f8;
        }
        .sidebar-active {
            background-color: #fce7f3;
            color: #be185d;
            border-right: 4px solid #be185d;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }
        ::-webkit-scrollbar-thumb {
            background: #fbcfe8;
            border-radius: 10px;
        }
        /* Dropdown Animation */
        .dropdown-animate {
            transform-origin: top right;
            transition: all 0.2s ease-out;
        }
    </style>

    <!-- MEY SALON PAGE LOADER -->
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
        .mey-loader-brand {
            font-family: 'Inter', sans-serif;
            text-align: center;
            margin-bottom: 28px;
        }
        .mey-loader-brand-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #be185d;
            letter-spacing: 2px;
        }
        .mey-loader-brand-sub {
            font-size: 0.65rem;
            color: #f472b6;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 3px;
        }
        .mey-spinner-wrap {
            position: relative;
            width: 68px;
            height: 68px;
            margin-bottom: 22px;
        }
        .mey-spinner-svg {
            position: absolute;
            inset: 0;
            animation: mey-spin 1.3s linear infinite;
        }
        .mey-spinner-icon {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            animation: mey-pulse 1.3s ease-in-out infinite;
        }
        .mey-dots {
            display: flex;
            gap: 8px;
            align-items: center;
        }
        .mey-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #ec4899;
            animation: mey-bounce 1.2s ease-in-out infinite;
        }
        .mey-dot:nth-child(2) { background: #f472b6; animation-delay: 0.2s; }
        .mey-dot:nth-child(3) { background: #fbcfe8; animation-delay: 0.4s; }
        .mey-loader-text {
            margin-top: 18px;
            font-family: 'Inter', sans-serif;
            font-size: 0.78rem;
            color: #db2777;
            letter-spacing: 1px;
            opacity: 0.8;
        }
        @keyframes mey-spin    { to { transform: rotate(360deg); } }
        @keyframes mey-bounce  { 0%,100%{transform:translateY(0);opacity:.5} 50%{transform:translateY(-7px);opacity:1} }
        @keyframes mey-pulse   { 0%,100%{transform:scale(1);opacity:.9} 50%{transform:scale(1.15);opacity:1} }
    </style>
    <script>
        // Inject loader ke <body> saat DOM siap
        document.addEventListener('DOMContentLoaded', function () {
            var el = document.createElement('div');
            el.id = 'mey-page-loader';
            el.innerHTML = '<div class="mey-loader-brand"><div class="mey-loader-brand-title">Mey Salon</div><div class="mey-loader-brand-sub">Admin Panel</div></div>'
                + '<div class="mey-spinner-wrap">'
                + '<svg class="mey-spinner-svg" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg">'
                + '<circle cx="34" cy="34" r="30" stroke="#fce7f3" stroke-width="4"/>'
                + '<circle cx="34" cy="34" r="30" stroke="url(#meyG)" stroke-width="4" stroke-linecap="round" stroke-dasharray="48 142"/>'
                + '<defs><linearGradient id="meyG" x1="0" y1="0" x2="68" y2="68" gradientUnits="userSpaceOnUse"><stop offset="0%" stop-color="#ec4899"/><stop offset="100%" stop-color="#f9a8d4"/></linearGradient></defs></svg>'
                + '<div class="mey-spinner-icon">💅</div></div>'
                + '<div class="mey-dots"><span class="mey-dot"></span><span class="mey-dot"></span><span class="mey-dot"></span></div>'
                + '<p class="mey-loader-text">Memuat panel admin...</p>';
            if (document.body) {
                document.body.insertBefore(el, document.body.firstChild);
            }
        }, { once: true });

        // Sembunyikan loader setelah semua aset selesai
        (function () {
            function hide() {
                var el = document.getElementById('mey-page-loader');
                if (el) {
                    el.classList.add('mey-loader-hidden');
                    setTimeout(function () { if (el && el.parentNode) el.parentNode.removeChild(el); }, 600);
                }
            }
            if (document.readyState === 'complete') {
                setTimeout(hide, 350);
            } else {
                window.addEventListener('load', function () { setTimeout(hide, 350); });
            }
            setTimeout(hide, 7000); // fallback
        })();
    </script>
    <!-- MEY SALON END LOADLOADER -->
</head>