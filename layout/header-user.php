<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Mengatur karakter dan responsive halaman -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Mey Salon'; ?></title>

    <!-- Memanggil Tailwind CSS -->
    <link href="../src/output.css" rel="stylesheet">

    <!-- Memanggil font dan icon -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Style halaman user -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
        }

        .calendar-day {
            width: 100%;
            aspect-ratio: 1 / 1;
            border-radius: 0.9rem;
            font-size: 12px;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .calendar-day-empty {
            opacity: 0;
            pointer-events: none;
        }

        .calendar-day-available {
            background: white;
            border: 1px solid #fbcfe8;
            color: #374151;
        }

        .calendar-day-booked {
            background: #db2777;
            color: white;
            box-shadow: 0 8px 20px rgba(219, 39, 119, 0.25);
        }

        .calendar-day-selected {
            background: #111827 !important;
            color: white !important;
        }

        .calendar-day-today {
            border: 2px solid #db2777 !important;
            color: #db2777 !important;
            background: #fff !important;
        }

        /* Style tanggal sebelum hari ini */
        .calendar-day-disabled {
            background: #f3f4f6 !important;
            color: #cbd5e1 !important;
            cursor: not-allowed !important;
            box-shadow: none !important;
        }

        /* Style tanggal dipilih */
        .calendar-day-selected {
            background: #111827 !important;
            color: white !important;
        }

        /* Style tanggal yang ada booking */
        .calendar-day-booked {
            background: #db2777;
            color: white;
            box-shadow: 0 8px 20px rgba(219, 39, 119, 0.25);
        }

        .time-selected {
            background: #db2777 !important;
            color: white !important;
            border-color: #db2777 !important;
        }

        .animate-fade-in {
            animation: fadeIn 0.25s ease-out;
        }

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
    </style>
</head>