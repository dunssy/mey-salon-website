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
     <!-- ico mey salon -->
     <!-- untuk android -->
    <link rel="apple-touch-icon" sizes="180x180" href="layout/images/favicon_io/apple-touch-icon.png">
    <!-- untuk desktop -->
    <link rel="icon" type="image/png" sizes="32x32" href="layout/images/favicon_io/favicon-32x32.png">
    <link rel="icon" href="layout/images/favicon_io/favicon.ico" type="image/x-icon" />
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
</head>