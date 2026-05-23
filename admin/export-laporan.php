<?php
// Memanggil koneksi database
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengambil filter laporan
$jenis_laporan = isset($_GET['jenis']) ? $_GET['jenis'] : 'harian';
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('Y-m');
$format = isset($_GET['format']) ? $_GET['format'] : 'excel';

// Mengamankan nilai filter
$jenis_laporan = $jenis_laporan === 'bulanan' ? 'bulanan' : 'harian';
$format = $format === 'pdf' ? 'pdf' : 'excel';
$tanggal = mysqli_real_escape_string($koneksi, $tanggal);
$bulan = mysqli_real_escape_string($koneksi, $bulan);

// Menentukan filter berdasarkan jenis laporan
if ($jenis_laporan === 'bulanan') {
    $where_transaksi = "DATE_FORMAT(t.tanggal_transaksi, '%Y-%m') = '$bulan'";
    $where_restok = "DATE_FORMAT(r.tanggal_restok, '%Y-%m') = '$bulan'";
    $where_pengeluaran = "DATE_FORMAT(p.tanggal_pengeluaran, '%Y-%m') = '$bulan'";
    $label_periode = date('F Y', strtotime($bulan . '-01'));
} else {
    $where_transaksi = "DATE(t.tanggal_transaksi) = '$tanggal'";
    $where_restok = "DATE(r.tanggal_restok) = '$tanggal'";
    $where_pengeluaran = "DATE(p.tanggal_pengeluaran) = '$tanggal'";
    $label_periode = date('d F Y', strtotime($tanggal));
}

// Mengambil pendapatan transaksi
$query_pendapatan = mysqli_query($koneksi, "
    SELECT 
        t.id_transaksi,
        t.id_booking,
        t.tanggal_transaksi,
        t.total_bayar,
        t.jenis_pelanggan,
        COALESCE(u.nama, 'Pelanggan Datang Langsung') AS nama_pelanggan,
        COALESCE(
            GROUP_CONCAT(DISTINCT l_transaksi.nama_layanan SEPARATOR ', '),
            GROUP_CONCAT(DISTINCT l_booking.nama_layanan SEPARATOR ', '),
            '-'
        ) AS nama_layanan
    FROM transaksi t
    LEFT JOIN booking b ON t.id_booking = b.id_booking
    LEFT JOIN user u ON b.id_user = u.id_user
    LEFT JOIN transaksi_detail td ON t.id_transaksi = td.id_transaksi
    LEFT JOIN layanan l_transaksi ON td.id_layanan = l_transaksi.id_layanan
    LEFT JOIN booking_detail bd ON b.id_booking = bd.id_booking
    LEFT JOIN layanan l_booking ON bd.id_layanan = l_booking.id_layanan
    WHERE $where_transaksi
    GROUP BY t.id_transaksi
    ORDER BY t.tanggal_transaksi DESC
");

// Mengambil restok sebagai pengeluaran
$query_restok = mysqli_query($koneksi, "
    SELECT 
        r.id_restok,
        r.tanggal_restok,
        r.jumlah_tambah,
        r.total_harga_restok,
        s.nama_barang
    FROM restok r
    JOIN stok_barang s ON r.id_barang = s.id_barang
    WHERE $where_restok
    ORDER BY r.tanggal_restok DESC
");

// Mengambil pengeluaran manual
$query_pengeluaran = mysqli_query($koneksi, "
    SELECT 
        p.id_pengeluaran,
        p.jenis_pengeluaran,
        p.jumlah_pengeluaran,
        p.tanggal_pengeluaran,
        p.keterangan_pengeluaran
    FROM pengeluaran p
    WHERE $where_pengeluaran
    ORDER BY p.tanggal_pengeluaran DESC
");

// Menyiapkan data laporan
$data_pendapatan = [];
$data_restok = [];
$data_pengeluaran = [];
$total_pendapatan = 0;
$total_pengeluaran = 0;

if ($query_pendapatan) {
    while ($row = mysqli_fetch_assoc($query_pendapatan)) {
        $data_pendapatan[] = $row;
        $total_pendapatan += (int) $row['total_bayar'];
    }
}

if ($query_restok) {
    while ($row = mysqli_fetch_assoc($query_restok)) {
        $data_restok[] = $row;
        $total_pengeluaran += (int) $row['total_harga_restok'];
    }
}

if ($query_pengeluaran) {
    while ($row = mysqli_fetch_assoc($query_pengeluaran)) {
        $data_pengeluaran[] = $row;
        $total_pengeluaran += (int) $row['jumlah_pengeluaran'];
    }
}

// Menghitung laba bersih
$laba_bersih = $total_pendapatan - $total_pengeluaran;

// Mengatur nama file export
$nama_file = "laporan-mey-salon-" . date('YmdHis');

// Mengatur header export Excel
if ($format === 'excel') {
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=$nama_file.xls");
    header("Pragma: no-cache");
    header("Expires: 0");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Mey Salon</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            margin: 24px;
        }

        h2, h3 {
            margin: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 24px;
        }

        .button-print {
            padding: 8px 14px;
            margin-bottom: 16px;
            background: #db2777;
            color: white;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .summary {
            margin-bottom: 24px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #fce7f3;
        }

        @media print {
            .button-print {
                display: none;
            }

            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <?php if ($format === 'pdf') : ?>
        <button class="button-print" onclick="window.print()">
            Cetak / Simpan PDF
        </button>

        <script>
            window.onload = function () {
                window.print();
            };
        </script>
    <?php endif; ?>

    <div class="header">
        <h2>Laporan Keuangan Mey Salon</h2>
        <p>Periode: <?= htmlspecialchars($label_periode); ?></p>
    </div>

    <div class="summary">
        <p><strong>Total Pendapatan:</strong> Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></p>
        <p><strong>Total Pengeluaran:</strong> Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></p>
        <p><strong>Laba Bersih:</strong> Rp <?= number_format($laba_bersih, 0, ',', '.'); ?></p>
    </div>

    <h3>Pendapatan Transaksi</h3>

    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Pelanggan</th>
                <th>Jenis</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Total Bayar</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($data_pendapatan)) : ?>
                <?php foreach ($data_pendapatan as $pendapatan) : ?>
                    <tr>
                        <td>#TRX-<?= str_pad($pendapatan['id_transaksi'], 4, '0', STR_PAD_LEFT); ?></td>
                        <td><?= htmlspecialchars($pendapatan['nama_pelanggan']); ?></td>
                        <td><?= htmlspecialchars($pendapatan['jenis_pelanggan']); ?></td>
                        <td><?= htmlspecialchars($pendapatan['nama_layanan']); ?></td>
                        <td><?= date('d M Y H:i', strtotime($pendapatan['tanggal_transaksi'])); ?></td>
                        <td>Rp <?= number_format($pendapatan['total_bayar'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="6">Belum ada pendapatan pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Pengeluaran</h3>

    <table>
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th>Tanggal</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($data_restok as $restok) : ?>
                <tr>
                    <td>Restok</td>
                    <td>
                        <?= htmlspecialchars($restok['nama_barang']); ?><br>
                        Jumlah tambah: <?= (int) $restok['jumlah_tambah']; ?>
                    </td>
                    <td><?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?></td>
                    <td>Rp <?= number_format($restok['total_harga_restok'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php foreach ($data_pengeluaran as $pengeluaran) : ?>
                <tr>
                    <td>Manual</td>
                    <td>
                        <?= htmlspecialchars($pengeluaran['jenis_pengeluaran']); ?><br>
                        <?= htmlspecialchars($pengeluaran['keterangan_pengeluaran']); ?>
                    </td>
                    <td><?= date('d M Y H:i', strtotime($pengeluaran['tanggal_pengeluaran'])); ?></td>
                    <td>Rp <?= number_format($pengeluaran['jumlah_pengeluaran'], 0, ',', '.'); ?></td>
                </tr>
            <?php endforeach; ?>

            <?php if (empty($data_restok) && empty($data_pengeluaran)) : ?>
                <tr>
                    <td colspan="4">Belum ada pengeluaran pada periode ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
