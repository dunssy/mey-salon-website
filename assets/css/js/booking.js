document.addEventListener("DOMContentLoaded", function () {
  const checkboxes = document.querySelectorAll('input[name="layanan[]"]');
  const totalHargaEl = document.getElementById("totalHarga");
  const totalDurasiEl = document.getElementById("totalDurasi");

  if (!checkboxes.length || !totalHargaEl || !totalDurasiEl) {
    return;
  }

  function formatRupiah(angka) {
    return "Rp" + angka.toLocaleString("id-ID");
  }

  function hitungTotal() {
    let totalHarga = 0;
    let totalDurasi = 0;

    checkboxes.forEach(function (checkbox) {
      if (checkbox.checked) {
        totalHarga += parseInt(checkbox.dataset.harga);
        totalDurasi += parseInt(checkbox.dataset.durasi);
      }
    });

    totalHargaEl.textContent = formatRupiah(totalHarga);
    totalDurasiEl.textContent = totalDurasi + " menit";
  }

  checkboxes.forEach(function (checkbox) {
    checkbox.addEventListener("change", hitungTotal);
  });
});
