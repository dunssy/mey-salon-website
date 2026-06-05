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

    <!-- Judul halaman -->
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Mey Salon'; ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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
</head>
