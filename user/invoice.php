<?php
// Memanggil koneksi database
include "../config/app.php";
global $koneksi;

// Mengambil id booking dari URL
$id_booking = isset($_GET['id_booking']) ? (int) $_GET['id_booking'] : 0;

// Mengecek id booking valid
if ($id_booking <= 0) {
    die("ID booking tidak valid.");
}

// Mengambil data booking, user, dan transaksi
$query = mysqli_query(
    $koneksi,
    "SELECT
        b.*,
        u.nama,
        u.email,
        u.no_hp,
        t.id_transaksi,
        t.total_bayar,
        t.tanggal_transaksi,
        t.catatan_tambahan
     FROM booking b
     LEFT JOIN user u ON b.id_user = u.id_user
     LEFT JOIN transaksi t ON b.id_booking = t.id_booking
     WHERE b.id_booking = $id_booking
     LIMIT 1"
);

// Mengecek query
if (!$query) {
    die("Error Query : " . mysqli_error($koneksi));
}

// Mengambil data booking
$booking = mysqli_fetch_assoc($query);

// Mengecek data booking ditemukan
if (!$booking) {
    die("Data booking tidak ditemukan.");
}

// Mengatur nilai pembayaran
$total_harga = isset($booking['total_bayar']) ? (int) $booking['total_bayar'] : 0;
$total_dp = isset($booking['total_dp']) ? (int) $booking['total_dp'] : 0;

// Mengambil layanan booking
$query_layanan = mysqli_query(
    $koneksi,
    "SELECT
        l.nama_layanan,
        l.harga_min,
        l.harga_max,
        l.durasi_layanan
     FROM booking_detail bd
     JOIN layanan l ON bd.id_layanan = l.id_layanan
     WHERE bd.id_booking = $id_booking
     ORDER BY l.nama_layanan ASC"
);

// Menyiapkan list layanan
$layanan_booking = [];

if ($query_layanan) {
    while ($layanan = mysqli_fetch_assoc($query_layanan)) {
        $layanan_booking[] = $layanan;
    }
}

// Format rupiah
function rupiah_invoice($nilai)
{
    return 'Rp ' . number_format((int) $nilai, 0, ',', '.');
}

// Format tanggal
function tanggal_invoice($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    return date('d M Y', strtotime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Booking #<?= (int) $booking['id_booking']; ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #FFF7FA;
            color: #2B2424;
        }

        .invoice {
            max-width: 850px;
            margin: auto;
            background: #ffffff;
            border: 1px solid #F7D6E4;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 14px 40px rgba(199, 92, 122, 0.12);
        }

        .header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
            padding-bottom: 22px;
            border-bottom: 2px solid #FDEAF1;
        }

        .brand {
            color: #C75C7A;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .subtitle {
            margin-top: 6px;
            font-size: 13px;
            color: #B77B8E;
            font-weight: 700;
        }

        .invoice-number {
            text-align: right;
        }

        .invoice-number strong {
            display: block;
            font-size: 18px;
            color: #2B2424;
        }

        .invoice-number span {
            display: block;
            margin-top: 6px;
            font-size: 12px;
            color: #7A6F6F;
        }

        .section-title {
            margin-top: 26px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 800;
            color: #C75C7A;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .info-box {
            padding: 14px;
            border: 1px solid #F7D6E4;
            border-radius: 16px;
            background: #FFF7FA;
        }

        .label {
            display: block;
            margin-bottom: 6px;
            font-size: 10px;
            font-weight: 800;
            color: #B77B8E;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .value {
            font-size: 14px;
            font-weight: 700;
            color: #2B2424;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border: 1px solid #F7D6E4;
            border-radius: 16px;
        }

        thead {
            background: #FDEAF1;
        }

        th,
        td {
            padding: 13px 14px;
            border-bottom: 1px solid #F7D6E4;
            font-size: 13px;
            text-align: left;
        }

        th {
            color: #C75C7A;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .right {
            text-align: right;
        }

        .payment-table td:first-child {
            color: #7A6F6F;
        }

        .payment-table .total-row td {
            font-size: 17px;
            font-weight: 800;
            color: #C75C7A;
            background: #FFF7FA;
        }
        .note {
            margin-top: 16px;
            padding: 14px;
            border-radius: 16px;
            background: #FFF7FA;
            border: 1px solid #F7D6E4;
            color: #7A6F6F;
            font-size: 12px;
            line-height: 1.6;
        }

        .print-btn {
            margin-top: 26px;
            padding: 13px 20px;
            border: none;
            border-radius: 14px;
            background: #C75C7A;
            color: #ffffff;
            cursor: pointer;
            font-size: 13px;
            font-weight: 800;
        }

        .print-btn:hover {
            background: #B14F6C;
        }

        @media (max-width: 640px) {
            body {
                padding: 14px;
            }

            .invoice {
                padding: 20px;
                border-radius: 20px;
            }

            .header,
            .info-grid {
                grid-template-columns: 1fr;
                flex-direction: column;
            }

            .invoice-number {
                text-align: left;
            }

            th,
            td {
                padding: 11px 10px;
                font-size: 12px;
            }
        }

        @media print {
            body {
                padding: 0;
                background: #ffffff;
            }

            .invoice {
                max-width: 100%;
                box-shadow: none;
                border: none;
                border-radius: 0;
            }

            .print-btn {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice">

        <!-- Header invoice -->
        <div class="header">
            <div>
                <div class="brand">MEY SALON</div>
                <div class="subtitle">Invoice Booking Salon</div>
            </div>

            <div class="invoice-number">
                <strong>INV-<?= (int) $booking['id_booking']; ?></strong>
                <span><?= tanggal_invoice($booking['tanggal_transaksi'] ?? date('Y-m-d')); ?></span>
            </div>
        </div>

        <!-- Data pelanggan -->
        <div class="section-title">Data Pelanggan</div>

        <div class="info-grid">
            <div class="info-box">
                <span class="label">Nama</span>
                <span class="value"><?= htmlspecialchars($booking['nama'] ?? '-'); ?></span>
            </div>

            <div class="info-box">
                <span class="label">Email</span>
                <span class="value"><?= htmlspecialchars($booking['email'] ?? '-'); ?></span>
            </div>

            <div class="info-box">
                <span class="label">No HP</span>
                <span class="value"><?= htmlspecialchars($booking['no_hp'] ?? '-'); ?></span>
            </div>

            <div class="info-box">
                <span class="label">Status Booking</span>
                <span class="value"><?= htmlspecialchars($booking['status_booking'] ?? '-'); ?></span>
            </div>

            <div class="info-box">
                <span class="label">Tanggal Booking</span>
                <span class="value"><?= tanggal_invoice($booking['tanggal_booking'] ?? ''); ?></span>
            </div>

            <div class="info-box">
                <span class="label">Jam Booking</span>
                <span class="value">
                    <?= substr($booking['jam_mulai'] ?? '-', 0, 5); ?> - <?= substr($booking['jam_selesai'] ?? '-', 0, 5); ?>
                </span>
            </div>
        </div>

        <!-- Rincian layanan -->
        <div class="section-title">Layanan Booking</div>

        <table>
            <thead>
                <tr>
                    <th>Layanan</th>
                    <th class="right">Durasi</th>
                    <th class="right">Range Harga</th>
                </tr>
            </thead>

            <tbody>
                <?php if (!empty($layanan_booking)) : ?>
                    <?php foreach ($layanan_booking as $layanan) : ?>
                        <?php
                        $harga_min = (int) ($layanan['harga_min'] ?? 0);
                        $harga_max = (int) ($layanan['harga_max'] ?? $harga_min);
                        ?>

                        <tr>
                            <td><?= htmlspecialchars($layanan['nama_layanan']); ?></td>
                            <td class="right"><?= (int) $layanan['durasi_layanan']; ?> menit</td>
                            <td class="right">
                                <?php if ($harga_max > $harga_min) : ?>
                                    <?= rupiah_invoice($harga_min); ?> - <?= rupiah_invoice($harga_max); ?>
                                <?php else : ?>
                                    <?= rupiah_invoice($harga_min); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3" style="text-align:center;color:#B77B8E;">
                            Data layanan tidak ditemukan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Rincian pembayaran -->
        <div class="section-title">Rincian Pembayaran</div>

        <table class="payment-table">
            <tr>
                <td>Total Harga</td>
                <td class="right"><?= rupiah_invoice($total_harga); ?></td>
            </tr>

            <tr>
                <td>DP Dibayar</td>
                <td class="right"><?= rupiah_invoice($total_dp); ?></td>
            </tr>
            <tr class="total-row">
                <td>Total Bayar</td>
                <td class="right"><?= rupiah_invoice($total_harga); ?></td>
            </tr>
        </table>
        <?php if (!empty($booking['catatan_tambahan'])) : ?>
            <div class="note">
                <b>Catatan Admin:</b><br>
                <?= nl2br(htmlspecialchars($booking['catatan_tambahan'])); ?>
            </div>
        <?php endif; ?>

        <br>

        <button class="print-btn" onclick="window.print()">
            Cetak Invoice
        </button>
    </div>
</body>
</html>
