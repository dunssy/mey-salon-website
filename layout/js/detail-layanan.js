// ======================================================
// DETAIL LAYANAN ADMIN - MEY SALON
// Script dipisah sesuai halaman agar mudah dirawat.
// ======================================================


    // Fungsi membuka modal bahan tambahan
    function openBahanModal() {
        document.getElementById('bahan-modal').classList.remove('hidden');
        document.getElementById('bahan-modal').classList.add('flex');
    }

    // Fungsi menutup modal bahan tambahan
    function closeBahanModal() {
        document.getElementById('bahan-modal').classList.add('hidden');
        document.getElementById('bahan-modal').classList.remove('flex');
    }

    // Fungsi membuka modal tambah paket stok
    function openTambahPaketModal() {
        document.getElementById('tambah-paket-modal').classList.remove('hidden');
        document.getElementById('tambah-paket-modal').classList.add('flex');
    }

    // Fungsi menutup modal tambah paket stok
    function closeTambahPaketModal() {
        document.getElementById('tambah-paket-modal').classList.add('hidden');
        document.getElementById('tambah-paket-modal').classList.remove('flex');
        document.getElementById('id_barang_tambah').value = '';
        document.getElementById('jumlah_stok_tambah').value = '';
    }

    // Fungsi membuka modal edit paket stok
    function openEditPaketModal(idPaket, jumlahStok, namaBarang) {
        document.getElementById('edit-paket-modal').classList.remove('hidden');
        document.getElementById('edit-paket-modal').classList.add('flex');
        document.getElementById('edit_id_paket').value = idPaket;
        document.getElementById('jumlah_stok_edit').value = jumlahStok;
        document.getElementById('edit_nama_barang').value = namaBarang;
    }

    // Fungsi menutup modal edit paket stok
    function closeEditPaketModal() {
        document.getElementById('edit-paket-modal').classList.add('hidden');
        document.getElementById('edit-paket-modal').classList.remove('flex');
        document.getElementById('edit_id_paket').value = '';
        document.getElementById('jumlah_stok_edit').value = '';
        document.getElementById('edit_nama_barang').value = '';
    }