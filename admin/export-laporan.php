<?php
// Memanggil koneksi database
include "../config/app.php";

// Menggunakan koneksi database
global $koneksi;

// Mengecek kolom walk-in di tabel transaksi
$cek_nama_walkin = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'nama_pelanggan_walkin'");
$cek_layanan_manual = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi LIKE 'layanan_manual'");

$kolom_nama_walkin_ada = mysqli_num_rows($cek_nama_walkin) > 0;
$kolom_layanan_manual_ada = mysqli_num_rows($cek_layanan_manual) > 0;

// Menyiapkan query kolom walk-in agar data walk-in bisa terbaca di export
$select_nama_walkin = $kolom_nama_walkin_ada ? "t.nama_pelanggan_walkin" : "NULL";
$select_layanan_manual = $kolom_layanan_manual_ada ? "t.layanan_manual" : "NULL";

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
        COALESCE(u.nama, $select_nama_walkin, 'Pelanggan Datang Langsung') AS nama_pelanggan,
        COALESCE(
            GROUP_CONCAT(DISTINCT l_transaksi.nama_layanan SEPARATOR ', '),
            GROUP_CONCAT(DISTINCT l_booking.nama_layanan SEPARATOR ', '),
            $select_layanan_manual,
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
$total_transaksi = 0;
$total_booking_transaksi = 0;
$total_walkin = 0;
$total_data_pengeluaran = 0;

// Mengolah data pendapatan
if ($query_pendapatan) {
    while ($row = mysqli_fetch_assoc($query_pendapatan)) {
        $data_pendapatan[] = $row;
        $total_pendapatan += (int) $row['total_bayar'];
        $total_transaksi++;

        if ($row['jenis_pelanggan'] === 'walk-in') {
            $total_walkin++;
        } else {
            $total_booking_transaksi++;
        }
    }
}

// Mengolah data restok
if ($query_restok) {
    while ($row = mysqli_fetch_assoc($query_restok)) {
        $data_restok[] = $row;
        $total_pengeluaran += (int) $row['total_harga_restok'];
        $total_data_pengeluaran++;
    }
}

// Mengolah data pengeluaran manual
if ($query_pengeluaran) {
    while ($row = mysqli_fetch_assoc($query_pengeluaran)) {
        $data_pengeluaran[] = $row;
        $total_pengeluaran += (int) $row['jumlah_pengeluaran'];
        $total_data_pengeluaran++;
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
    <!-- Mengatur karakter halaman -->
    <meta charset="UTF-8">

    <!-- Judul dokumen -->
    <title>Laporan Keuangan Mey Salon</title>

    <!-- Style laporan resmi -->
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #2B2424;
            margin: 0;
            padding: 28px;
            background: #ffffff;
        }

        .page {
            width: 100%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .print-action {
            margin-bottom: 18px;
            text-align: right;
        }

        .button-print {
            padding: 10px 16px;
            background: #C75C7A;
            color: #ffffff;
            border: 0;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 12px;
        }

        .letterhead {
            width: 100%;
            border-bottom: 3px solid #C75C7A;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }

        .letterhead-table {
            width: 100%;
            border-collapse: collapse;
        }

        .letterhead-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }

        .logo-box {
            width: 78px;
            height: 78px;
            border-radius: 14px;
            background: #FDEAF1;
            color: #C75C7A;
            font-size: 34px;
            font-weight: bold;
            text-align: center;
            line-height: 78px;
            border: 1px solid #F7D6E4;
        }

        .company-name {
            margin: 0;
            font-size: 26px;
            font-weight: 800;
            color: #C75C7A;
            letter-spacing: 0.3px;
        }

        .company-subtitle {
            margin: 4px 0 0 0;
            font-size: 13px;
            color: #7A6F6F;
        }

        .company-address {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #7A6F6F;
            line-height: 1.5;
        }

        .document-title {
            text-align: center;
            margin: 22px 0 18px 0;
        }

        .document-title h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #2B2424;
        }

        .document-title p {
            margin: 6px 0 0 0;
            font-size: 13px;
            color: #7A6F6F;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 12px;
        }

        .meta-table td {
            border: none;
            padding: 3px 0;
        }

        .meta-label {
            width: 150px;
            color: #7A6F6F;
            font-weight: bold;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 22px;
            font-size: 12px;
        }

        .summary-table th {
            background: #C75C7A;
            color: #ffffff;
            border: 1px solid #C75C7A;
            padding: 10px;
            text-align: center;
            text-transform: uppercase;
            font-size: 11px;
        }

        .summary-table td {
            border: 1px solid #F7D6E4;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            background: #FFF7FA;
        }

        .section-title {
            margin: 22px 0 8px 0;
            font-size: 14px;
            font-weight: bold;
            color: #2B2424;
            text-transform: uppercase;
            border-left: 5px solid #C75C7A;
            padding-left: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 11px;
        }

        .data-table th {
            background: #EFA9BF;
            color: #ffffff;
            border: 1px solid #D98AA4;
            padding: 8px;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
        }

        .data-table td {
            border: 1px solid #EAD8D0;
            padding: 8px;
            vertical-align: top;
            line-height: 1.45;
        }

        .data-table tr:nth-child(even) td {
            background: #FFF7FA;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .income {
            color: #15803d;
            font-weight: bold;
        }

        .expense {
            color: #dc2626;
            font-weight: bold;
        }

        .profit {
            color: #C75C7A;
            font-weight: bold;
        }

        .empty-row {
            text-align: center;
            color: #7A6F6F;
            font-style: italic;
        }

        .signature-wrapper {
            width: 100%;
            margin-top: 34px;
            border-collapse: collapse;
        }

        .signature-wrapper td {
            border: none;
            vertical-align: top;
            width: 50%;
            padding-top: 12px;
            font-size: 12px;
        }

        .signature-box {
            text-align: center;
            min-height: 120px;
        }

        .signature-space {
            height: 70px;
        }

        .footer-note {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #EAD8D0;
            font-size: 10px;
            color: #7A6F6F;
            text-align: center;
        }

        @media print {
            body {
                padding: 0;
                margin: 0;
            }

            .page {
                max-width: 100%;
            }

            .print-action {
                display: none;
            }

            .data-table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }
    </style>
</head>

<body>
    <div class="page">

        <?php if ($format === 'pdf') : ?>
            <!-- Tombol cetak PDF -->
            <div class="print-action">
                <button class="button-print" onclick="window.print()">
                    Cetak / Simpan PDF
                </button>
            </div>

            <!-- Otomatis membuka print -->
            <script>
                window.onload = function () {
                    window.print();
                };
            </script>
        <?php endif; ?>

        <!-- Kop surat laporan -->
        <div class="letterhead">
            <table class="letterhead-table">
                <tr>
                    <td style="width: 90px;">
                        <div class="logo-box">M</div>
                    </td>

                    <td>
                        <h1 class="company-name">Mey Salon</h1>
                        <p class="company-subtitle">Laporan Keuangan Salon</p>
                        <p class="company-address">
                            Jl. D. Kartawigenda Gg. Palabuan No.27, Karanganyar, Kec. Subang, Kabupaten Subang, Jawa Barat 41211
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Judul laporan -->
        <div class="document-title">
            <h2>Laporan Keuangan Mey Salon</h2>
            <p>Periode: <?= htmlspecialchars($label_periode); ?></p>
        </div>

        <?php if (!$kolom_nama_walkin_ada || !$kolom_layanan_manual_ada) : ?>
            <p style="padding:10px; background:#fff7ed; border:1px solid #fed7aa; color:#9a3412; font-size:12px; margin-bottom:14px;">
                <strong>Perhatian:</strong>
                Kolom nama_pelanggan_walkin atau layanan_manual belum tersedia di tabel transaksi,
                sehingga detail nama/layanan walk-in belum bisa tampil lengkap.
            </p>
        <?php endif; ?>

        <!-- Informasi laporan -->
        <table class="meta-table">
            <tr>
                <td class="meta-label">Jenis Laporan</td>
                <td>: <?= ucfirst(htmlspecialchars($jenis_laporan)); ?></td>
            </tr>

            <tr>
                <td class="meta-label">Periode</td>
                <td>: <?= htmlspecialchars($label_periode); ?></td>
            </tr>

            <tr>
                <td class="meta-label">Tanggal Cetak</td>
                <td>: <?= date('d F Y H:i'); ?></td>
            </tr>
        </table>

        <!-- Ringkasan laporan -->
        <table class="summary-table">
            <thead>
                <tr>
                    <th>Total Pendapatan</th>
                    <th>Total Pengeluaran</th>
                    <th>Laba Bersih</th>
                    <th>Data Transaksi</th>
                    <th>Data Pengeluaran</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="income">Rp <?= number_format($total_pendapatan, 0, ',', '.'); ?></td>
                    <td class="expense">Rp <?= number_format($total_pengeluaran, 0, ',', '.'); ?></td>
                    <td class="profit">Rp <?= number_format($laba_bersih, 0, ',', '.'); ?></td>
                    <td><?= (int) $total_transaksi; ?></td>
                    <td><?= (int) $total_data_pengeluaran; ?></td>
                </tr>
            </tbody>
        </table>

        <!-- Tabel pendapatan -->
        <h3 class="section-title">Pendapatan Transaksi</h3>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 90px;">Invoice</th>
                    <th>Pelanggan</th>
                    <th style="width: 90px;">Jenis</th>
                    <th>Layanan</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th style="width: 120px;" class="text-right">Total Bayar</th>
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
                            <td class="text-right income">Rp <?= number_format($pendapatan['total_bayar'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="empty-row">
                            Belum ada pendapatan pada periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tabel pengeluaran -->
        <h3 class="section-title">Pengeluaran</h3>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 90px;">Jenis</th>
                    <th>Keterangan</th>
                    <th style="width: 120px;">Tanggal</th>
                    <th style="width: 120px;" class="text-right">Total</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($data_restok as $restok) : ?>
                    <tr>
                        <td class="expense">Restok</td>
                        <td>
                            <strong><?= htmlspecialchars($restok['nama_barang']); ?></strong><br>
                            Jumlah tambah: <?= (int) $restok['jumlah_tambah']; ?>
                        </td>
                        <td><?= date('d M Y H:i', strtotime($restok['tanggal_restok'])); ?></td>
                        <td class="text-right expense">Rp <?= number_format($restok['total_harga_restok'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php foreach ($data_pengeluaran as $pengeluaran) : ?>
                    <tr>
                        <td class="expense">Manual</td>
                        <td>
                            <strong><?= htmlspecialchars($pengeluaran['jenis_pengeluaran']); ?></strong>

                            <?php if (!empty($pengeluaran['keterangan_pengeluaran'])) : ?>
                                <br>
                                <?= htmlspecialchars($pengeluaran['keterangan_pengeluaran']); ?>
                            <?php endif; ?>
                        </td>
                        <td><?= date('d M Y H:i', strtotime($pengeluaran['tanggal_pengeluaran'])); ?></td>
                        <td class="text-right expense">Rp <?= number_format($pengeluaran['jumlah_pengeluaran'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($data_restok) && empty($data_pengeluaran)) : ?>
                    <tr>
                        <td colspan="4" class="empty-row">
                            Belum ada pengeluaran pada periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Tanda tangan laporan -->
        <table class="signature-wrapper">
            <tr>
                <td></td>

                <td>
                    <div class="signature-box">
                        <p>Subang, <?= date('d F Y'); ?></p>
                        <p><strong>Admin Mey Salon</strong></p>
                        <div class="signature-space"></div>
                        <p>(________________________)</p>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Catatan footer -->
        <div class="footer-note">
            Dokumen ini dicetak otomatis melalui Sistem Informasi Mey Salon.
        </div>
    </div>
</body>
</html>
