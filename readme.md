<div align="center">

```
  ✂️  M E Y   S A L O N
  Beauty & Care Management System
```

![PHP](https://img.shields.io/badge/PHP-8%2B-blue?style=flat-square&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange?style=flat-square&logo=mysql)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-CSS-38bdf8?style=flat-square&logo=tailwindcss)
![SweetAlert2](https://img.shields.io/badge/SweetAlert2-Notifikasi-pink?style=flat-square)
![Font Awesome](https://img.shields.io/badge/Font_Awesome-6-339af0?style=flat-square&logo=fontawesome)

**Sistem manajemen salon lengkap — booking online, admin panel, dan laporan keuangan.**

</div>

---

## ✨ Keunggulan

| Fitur | Keterangan |
|-------|-----------|
| 📅 **Booking Online** | Customer pilih tanggal, jam & layanan langsung dari web. Hari Rabu otomatis nonaktif. |
| 💳 **Sistem DP Fleksibel** | Customer input nominal DP sendiri, minimal Rp 50.000. Validasi di frontend & backend. |
| 📊 **Laporan Keuangan** | Rekap pendapatan booking dan walk-in. Export ke Excel/PDF dari panel admin. |
| 🖼️ **Manajemen Layanan** | Upload foto layanan, atur range harga (min–maks), dan keterangan harga. |
| 🌙 **Dark Mode** | Landing page mendukung tema gelap dan terang dengan satu klik. |
| ⚡ **Page Loader Animasi** | Loader elegan berlogo Mey Salon tampil saat aset belum selesai dimuat. |
| 🔒 **Autentikasi Role** | Hak akses terpisah untuk Customer dan Administrator. |
| 📦 **Manajemen Stok** | Kelola produk salon, tambah stok, dan pantau pengeluaran bahan. |

---

## 📋 Fitur Lengkap

### 👤 Sisi Customer
- Landing page dengan daftar layanan beserta range harga
- Kalender booking interaktif (pilih tanggal & jam)
- Input nominal DP dengan validasi minimal Rp 50.000
- Cek status booking secara real-time
- Notifikasi email konfirmasi booking

### 🛠️ Sisi Admin
- Dashboard ringkasan (booking hari ini, pendapatan, dll.)
- Kelola booking: **Approve** → **Done** dengan total pembayaran final
- Manajemen layanan: tambah, edit, hapus + upload foto
- Manajemen stok produk & restok
- Data user (customer)
- Pencatatan pendapatan **walk-in**
- Laporan keuangan & export data

---

## 🚀 Cara Instalasi

### Persyaratan
- **PHP** 8.0 atau lebih baru
- **MySQL** 5.7+ / MariaDB 10+
- **Web Server**: Apache (XAMPP / Laragon / Wamp)
- **Browser** modern (Chrome, Firefox, Edge)

### Langkah Instalasi

**1. Clone atau ekstrak project**
```bash
# Letakkan folder di dalam htdocs/ atau www/
/xampp/htdocs/mey-salon-website/
```

**2. Buat database MySQL**
```sql
-- Buka phpMyAdmin, buat database baru, lalu import file .sql
CREATE DATABASE mey_salon;
-- Import: File → Import → pilih file .sql yang disertakan
```

**3. Konfigurasi koneksi database**
```php
// Edit file: config/koneksi.php
$host     = 'localhost';
$user     = 'root';        // sesuaikan
$password = '';            // sesuaikan
$database = 'mey_salon';  // nama database yang dibuat
```

**4. Buat folder upload**
```bash
# Buat folder dan beri izin tulis
mkdir -p uploads/layanan
chmod 755 uploads/layanan
```

**5. Akses di browser**
```
http://localhost/mey-salon-website/
```
> Setelah update file, selalu tekan **Ctrl + F5** untuk menghapus cache browser.

---

## 📁 Struktur File

```
mey-salon-website/
├── config/
│   ├── app.php              # Entry point konfigurasi
│   ├── koneksi.php          # Koneksi database
│   ├── controller.php       # ✅ PATCH — tambah/edit layanan (harga, foto)
│   └── email.php            # Konfigurasi pengiriman email
│
├── admin/
│   ├── dashboard-admin.php
│   ├── data-booking.php
│   ├── detail-booking.php   # ✅ PATCH — proses Done Booking & total_bayar
│   ├── data-layanan.php
│   ├── tambah-layanan.php
│   ├── edit-layanan.php
│   ├── data-stok.php
│   ├── restok.php
│   ├── data-user.php
│   ├── pendapatan-walkin.php # ✅ PATCH — insert walk-in tanpa tambahan_harga
│   ├── pengeluaran.php
│   ├── data-laporan.php
│   └── export-laporan.php
│
├── user/
│   ├── booking-controller.php # ✅ PATCH — total_dp dari input, validasi Rp 50rb
│   └── views/
│       └── section-layanan.php # ✅ PATCH — data-price-max dobel dihapus
│
├── layout/
│   ├── header.php           # Header admin (sudah terintegrasi loader)
│   ├── header-user.php      # Header user (sudah terintegrasi loader)
│   ├── loader.php           # 🆕 NEW — komponen page loader animasi
│   ├── navbar.php
│   ├── navbar-user.php
│   ├── footer.php
│   ├── js/
│   │   ├── booking-script.js   # ✅ PATCH — disable jam, cek bentrok, range harga
│   │   ├── booking-detail.js   # Detail booking admin
│   │   ├── detail-layanan.js   # Detail layanan admin
│   │   ├── main.js             # Landing/user UI
│   │   └── main-admin.js       # Admin UI
│   └── css/
│       ├── style.css           # Styling user/landing
│       └── style-admin.css     # Styling admin
│
├── uploads/
│   └── layanan/             # ⚠️ Harus bisa ditulis server (chmod 755)
│
└── index.php                # Landing page utama
```

---

## ⚠️ Catatan Penting

> **Folder Upload** — Pastikan `uploads/layanan/` ada dan bisa ditulis server. Tanpa ini, upload foto layanan akan gagal.

> **Path JS** — Jika halaman user masih memanggil `../layout/js/booking-script.js`, pastikan file tersebut berada di `layout/js/`.

> **Cache Browser** — Setelah mengganti file JS atau CSS, tekan **Ctrl + F5** (atau Cmd + Shift + R di Mac) agar perubahan langsung terlihat.

> **Konfigurasi Email** — Edit `config/email.php` dengan kredensial SMTP jika ingin notifikasi email aktif.

---

## 🔄 Changelog Patch Final

- `config/controller.php` — `tambah_layanan` & `edit_layanan` menyimpan `harga_min`, `harga_max`, `keterangan_harga`, `gambar_layanan`. Upload gambar masuk ke `uploads/layanan/`.
- `user/booking-controller.php` — `total_dp` dari input customer, bukan dari `SUM(harga_min)`. Validasi minimal DP Rp 50.000. Jadwal dikirim ke JS sebagai objek `{jam_mulai, jam_selesai, layanan, status}`.
- `user/views/section-layanan.php` — Atribut `data-price-max` dobel dihapus. Gambar layanan membaca dari `uploads/layanan/` atau `layout/images/`.
- `layout/js/booking-script.js` — Fungsi disable jam dirapikan. Cek bentrok pakai `jam_mulai` & `jam_selesai`. Hari Rabu otomatis nonaktif. Range harga pakai `harga_min` & `harga_max`.
- `admin/detail-booking.php` — Proses Done Booking menyimpan total final ke `transaksi.total_bayar`. Status menjadi `Done`. Logika `sisa_pembayaran`, `status_pembayaran`, `tanggal_pelunasan` lama dihapus.
- `admin/pendapatan-walkin.php` — Insert walk-in tidak lagi memakai kolom `tambahan_harga`.

---

<div align="center">
TEAM ATAH - ALIF TEGAR AHMAD HILMAN DIRECT BY AMAZING PROJEK 1 
</div>
