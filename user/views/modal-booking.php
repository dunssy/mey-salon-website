<?php
// Mengambil data user dari booking-controller.php
global $user;
?>

<!-- Modal konfirmasi booking dengan QRIS dan upload bukti pembayaran -->
<div id="booking-modal" class="fixed inset-0 z-[100] hidden">

    <!-- Overlay modal -->
    <div onclick="closeBookingModal()" class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>

    <!-- Box modal -->
    <div class="relative w-[94%] max-w-4xl mx-auto mt-4 sm:mt-6 mb-4 sm:mb-6 bg-white rounded-[1.5rem] sm:rounded-[2rem] shadow-2xl border border-pink-100 overflow-hidden max-h-[92vh] flex flex-col">

        <!-- Header modal -->
        <div class="p-4 sm:p-5 md:p-6 border-b border-pink-50 flex justify-between items-start gap-4">
            <div>
                <h3 class="text-xl font-bold text-gray-800">
                    Konfirmasi Booking
                </h3>
            </div>

            <!-- Tombol tutup modal -->
            <button 
                type="button"
                onclick="closeBookingModal()" 
                class="w-10 h-10 bg-pink-50 text-pink-600 rounded-full hover:bg-pink-100 transition shrink-0"
            >
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <!-- Form booking -->
        <form action="" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-4 sm:p-5 md:p-6">

            <!-- Input hidden untuk controller -->
            <input type="hidden" name="tanggal_booking" id="form-tanggal-booking">
            <input type="hidden" name="jam_mulai" id="form-jam-mulai">
            <input type="hidden" name="layanan_terpilih" id="form-layanan-terpilih">
            <input type="hidden" name="total_dp" id="form-total-dp">

            <!-- Layout modal -->
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_290px] gap-5 lg:gap-6 items-start">

                <!-- Kolom kiri detail booking -->
                <div class="space-y-5">

                    <!-- Data pelanggan -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Nama pelanggan -->
                        <div class="p-4 bg-pink-50/50 rounded-2xl border border-pink-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Nama
                            </p>

                            <p class="text-sm font-bold text-gray-800">
                                <?= htmlspecialchars($user['nama'] ?? '-'); ?>
                            </p>
                        </div>

                        <!-- Nomor HP pelanggan -->
                        <div class="p-4 bg-pink-50/50 rounded-2xl border border-pink-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                No HP
                            </p>

                            <p class="text-sm font-bold text-gray-800">
                                <?= htmlspecialchars($user['no_hp'] ?? '-'); ?>
                            </p>
                        </div>
                    </div>

                    <!-- Data jadwal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Tanggal booking -->
                        <div class="p-4 bg-white rounded-2xl border border-pink-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Tanggal Booking
                            </p>

                            <p id="modal-date" class="text-sm font-bold text-gray-800">
                                -
                            </p>
                        </div>

                        <!-- Jam booking -->
                        <div class="p-4 bg-white rounded-2xl border border-pink-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Jam Booking
                            </p>

                            <p id="modal-time" class="text-sm font-bold text-gray-800">
                                -
                            </p>
                        </div>
                    </div>

                    <!-- Layanan dipilih -->
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">
                            Layanan Dipilih
                        </p>

                        <div id="modal-service-list" class="space-y-3"></div>
                    </div>

                    <!-- Ringkasan pembayaran -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Total pembayaran -->
                        <div class="p-4 bg-pink-50/60 rounded-2xl border border-pink-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Range Harga
                            </p>

                            <p id="modal-total-price" class="text-lg font-bold text-pink-600">
                                Rp 0
                            </p>

                            <p class="text-[10px] text-gray-400 mt-1">
                                Gabungan harga minimal dan maksimal dari semua layanan.
                            </p>
                        </div>

                        <!-- Estimasi durasi -->
                        <div class="p-4 bg-pink-50/60 rounded-2xl border border-pink-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                Estimasi
                            </p>

                            <p id="modal-total-duration" class="text-lg font-bold text-gray-800">
                                0 Menit
                            </p>
                        </div>


                    </div>


                    <!-- Pembayaran DP customer -->
                    <div class="p-4 bg-white rounded-2xl border border-pink-100 space-y-4">
                        <div>
                            <h4 class="text-sm font-bold text-gray-800">
                                Pembayaran DP
                            </h4>

                            <p class="text-xs text-gray-400 mt-1">
                                Isi nominal DP, upload bukti, lalu centang konfirmasi.
                            </p>
                        </div>

                        <!-- Input nominal DP customer -->
                        <div class="p-4 bg-pink-50/40 rounded-2xl border border-pink-100">
                        <label for="input-total-dp-qris" class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Nominal DP
                        </label>

                        <div class="relative mt-2">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-bold text-pink-600">
                                Rp
                            </span>

                            <input
                                type="number"
                                id="input-total-dp-qris"
                                min="50000"
                                step="1000"
                                required
                                placeholder="Minimal 50000"
                                class="w-full pl-11 pr-4 py-3 rounded-xl bg-pink-50/40 border border-pink-100 text-sm font-bold text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-400"
                                oninput="syncDpFromQrisInput(); toggleBookingSubmitQris();"
                            >
                        </div>

                        <p class="text-[11px] text-gray-400 mt-2 leading-relaxed">
                            Minimal DP <b class="text-pink-600">Rp 50.000</b>. Total harga akhir akan ditentukan admin.
                        </p>
                    </div> 

                        <!-- Upload bukti pembayaran -->
                        <div class="p-4 bg-pink-50/40 rounded-2xl border border-pink-100">
                        <label for="bukti_pembayaran" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Upload Bukti Pembayaran
                        </label>

                        <input 
                            type="file"
                            name="bukti_pembayaran"
                            id="bukti_pembayaran"
                            accept=".jpg,.jpeg,.png,.webp,.pdf"
                            required
                            onchange="previewBuktiPembayaran(this); toggleBookingSubmitQris();"
                            class="mt-3 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-pink-50 file:text-pink-600 hover:file:bg-pink-100"
                        >

                        <p class="text-[11px] text-gray-400 mt-2">
                            Format JPG, JPEG, PNG, WEBP, atau PDF. Maksimal 2MB.
                        </p>

                        <!-- Preview bukti pembayaran -->
                        <div id="preview-bukti-wrapper" class="hidden mt-4">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">
                                Preview Bukti
                            </p>

                            <img 
                                id="preview-bukti-image"
                                src=""
                                alt="Preview Bukti Pembayaran"
                                class="hidden max-h-52 rounded-2xl border border-pink-100 object-contain bg-pink-50/30"
                            >

                            <div id="preview-bukti-file" class="hidden p-3 rounded-2xl bg-pink-50 text-pink-600 text-xs font-bold border border-pink-100"></div>
                        </div>
                    </div>

                        <!-- Checklist konfirmasi -->
                        <label class="flex items-start gap-3 text-xs text-gray-500 cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="qris-confirm-check"
                            class="mt-0.5 w-4 h-4 accent-pink-600"
                            onchange="toggleBookingSubmitQris()"
                        >

                        <span>
                            Saya sudah membayar DP sesuai nominal yang saya input dan mengupload bukti pembayaran yang benar.
                        </span>
                    </label>

                        <!-- Catatan admin -->
                        <div class="p-4 bg-yellow-50 border border-yellow-100 text-yellow-700 rounded-2xl text-xs leading-relaxed">
                            <b>Catatan:</b> Admin akan mengecek bukti pembayaran DP QRIS terlebih dahulu. Total harga akhir akan diinput admin.
                        </div>
                    </div>

                    <!-- Catatan opsional -->
                    <div>
                        <label for="catatan" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                            Catatan Opsional
                        </label>

                        <textarea 
                            name="catatan" 
                            id="catatan" 
                            rows="3" 
                            placeholder="Contoh: rambut panjang, ingin warna tertentu, atau request model..."
                            class="mt-2 w-full px-4 py-3 bg-pink-50/20 border border-pink-100 rounded-2xl text-sm outline-none focus:border-pink-400 resize-none"
                        ></textarea>
                    </div>
                </div>

                <!-- Kolom kanan QRIS -->
                <aside class="bg-pink-50/50 border border-pink-100 rounded-[1.5rem] sm:rounded-[1.8rem] p-4 sm:p-5 lg:sticky lg:top-0">

                    <!-- Header QRIS -->
                    <div class="text-center mb-4">
                        <div class="w-12 h-12 mx-auto bg-white text-pink-600 rounded-2xl flex items-center justify-center border border-pink-100">
                            <i class="fa-solid fa-qrcode text-xl"></i>
                        </div>

                        <h4 class="text-lg font-bold text-gray-800 mt-3">
                            Bayar via QRIS
                        </h4>

                        <p class="text-[11px] text-gray-400 mt-1">
                            Scan atau download QRIS untuk pembayaran DP.
                        </p>
                    </div>

                    <!-- Gambar QRIS -->
                    <div class="bg-white rounded-2xl p-3 border border-pink-100">
                        <img 
                            src="../layout/images/qris.jpeg" 
                            alt="QRIS Mey Salon"
                            class="w-full rounded-xl border border-gray-100"
                        >
                    </div>

                    <!-- Download QRIS -->
                    <a 
                        href="../layout/images/qris.jpeg"
                        download="qris-mey-salon.jpeg"
                        class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-white text-pink-600 text-sm font-bold rounded-2xl border border-pink-100 hover:bg-pink-50 transition"
                    >
                        <i class="fa-solid fa-download"></i>
                        <span>Download QRIS</span>
                    </a>

</aside>
            </div>

            <!-- Tombol submit booking -->
            <button 
                id="btn-submit-booking-qris"
                type="submit" 
                name="konfirmasi_booking"
                disabled
                class="mt-6 w-full py-4 bg-pink-600 text-white font-bold rounded-2xl hover:bg-pink-700 transition-all shadow-lg shadow-pink-100 disabled:opacity-40 disabled:cursor-not-allowed"
            >
                Konfirmasi Booking & Kirim Bukti DP
            </button>
        </form>
    </div>
</div>

<!-- Script modal booking -->
<script>
    // Format angka ke rupiah
    function formatRupiahModalQris(number) {
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(Number(number || 0));
    }

    // Mengambil harga minimum layanan
    function getServiceMinPriceQris(item) {
        const itemId = Number(item.id || item.id_layanan || item.service_id || 0);

        const directMin = Number(
            item.price ||
            item.harga ||
            item.harga_min ||
            item.harga_layanan ||
            item.min_price ||
            0
        );

        if (directMin > 0) {
            return directMin;
        }

        if (itemId > 0 && window.servicePriceRangeMap && window.servicePriceRangeMap[itemId]) {
            const mappedMin = Number(window.servicePriceRangeMap[itemId].min || 0);

            if (mappedMin > 0) {
                return mappedMin;
            }
        }

        if (itemId > 0) {
            const checkbox = document.querySelector('.service-checkbox[data-id="' + itemId + '"]');

            if (checkbox) {
                const checkboxMin = Number(checkbox.dataset.priceMin || checkbox.dataset.price || 0);

                if (checkboxMin > 0) {
                    return checkboxMin;
                }
            }
        }

        return 0;
    }

    // Mengambil harga maksimum layanan
    function getServiceMaxPriceQris(item) {
        const itemId = Number(item.id || item.id_layanan || item.service_id || 0);
        const minPrice = getServiceMinPriceQris(item);

        if (itemId > 0 && window.servicePriceRangeMap && window.servicePriceRangeMap[itemId]) {
            const mappedMax = Number(window.servicePriceRangeMap[itemId].max || 0);

            if (mappedMax > minPrice) {
                return mappedMax;
            }
        }

        if (itemId > 0) {
            const checkbox = document.querySelector('.service-checkbox[data-id="' + itemId + '"]');

            if (checkbox) {
                const checkboxMax = Number(
                    checkbox.dataset.priceMax ||
                    checkbox.dataset.hargaMax ||
                    checkbox.dataset.maxPrice ||
                    0
                );

                if (checkboxMax > minPrice) {
                    return checkboxMax;
                }
            }
        }

        const directMax = Number(
            item.priceMax ||
            item.price_max ||
            item.harga_max ||
            item.max_price ||
            item.hargaMaksimal ||
            item.harga_maksimal ||
            0
        );

        if (directMax > minPrice) {
            return directMax;
        }

        return minPrice;
    }

    // Format range harga layanan
    function formatRangeHargaQris(minPrice, maxPrice) {
        if (!maxPrice || Number(maxPrice) <= Number(minPrice)) {
            return formatRupiahModalQris(minPrice);
        }

        return formatRupiahModalQris(minPrice) + ' - ' + formatRupiahModalQris(maxPrice);
    }

    // Format tanggal Indonesia
    function formatTanggalModalQris(dateKey) {
        if (!dateKey) return '-';

        if (typeof formatTanggalIndonesia === 'function') {
            return formatTanggalIndonesia(dateKey);
        }

        const date = new Date(dateKey + 'T00:00:00');

        if (isNaN(date.getTime())) return dateKey;

        return date.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
    }

    // Preview bukti pembayaran
    function previewBuktiPembayaran(input) {
        const wrapper = document.getElementById('preview-bukti-wrapper');
        const image = document.getElementById('preview-bukti-image');
        const fileBox = document.getElementById('preview-bukti-file');

        if (!wrapper || !image || !fileBox) return;

        image.classList.add('hidden');
        fileBox.classList.add('hidden');
        image.src = '';
        fileBox.innerText = '';

        if (!input.files || !input.files[0]) {
            wrapper.classList.add('hidden');
            return;
        }

        const file = input.files[0];
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];

        if (!allowedTypes.includes(file.type)) {
            alert('Format bukti pembayaran harus JPG, JPEG, PNG, WEBP, atau PDF.');
            input.value = '';
            wrapper.classList.add('hidden');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran bukti pembayaran maksimal 2MB.');
            input.value = '';
            wrapper.classList.add('hidden');
            return;
        }

        wrapper.classList.remove('hidden');

        if (file.type.startsWith('image/')) {
            image.src = URL.createObjectURL(file);
            image.classList.remove('hidden');
        } else {
            fileBox.innerText = 'File PDF dipilih: ' + file.name;
            fileBox.classList.remove('hidden');
        }
    }

    // Mengambil layanan terpilih dari script booking
    function getSelectedServicesQris() {
        if (typeof cart !== 'undefined' && Array.isArray(cart)) return cart;
        if (Array.isArray(window.cart)) return window.cart;
        if (Array.isArray(window.selectedServices)) return window.selectedServices;
        if (Array.isArray(window.cartItems)) return window.cartItems;
        if (Array.isArray(window.selectedLayanan)) return window.selectedLayanan;

        return [];
    }

    // Mengambil tanggal booking
    function getSelectedDateQris() {
        if (typeof selectedBookingDate !== 'undefined' && selectedBookingDate) return selectedBookingDate;

        const formDate = document.getElementById('form-tanggal-booking')?.value;
        return formDate || '';
    }

    // Mengambil jam booking
    function getSelectedTimeQris() {
        if (typeof selectedBookingTime !== 'undefined' && selectedBookingTime) return selectedBookingTime;

        const formTime = document.getElementById('form-jam-mulai')?.value;
        return formTime || '';
    }

    // Menghitung total harga minimum layanan
    function getTotalPaymentQris(services) {
        if (typeof getTotalPrice === 'function') {
            return Number(getTotalPrice() || 0);
        }

        return services.reduce((total, item) => {
            return total + getServiceMinPriceQris(item);
        }, 0);
    }

    // Menghitung total harga maksimum layanan
    function getTotalMaxPaymentQris(services) {
        return services.reduce((total, item) => {
            return total + getServiceMaxPriceQris(item);
        }, 0);
    }

    // Mengambil nominal DP dari input customer
    function getCustomerDpQris() {
        const inputDp = document.getElementById('input-total-dp-qris');

        return inputDp ? Number(inputDp.value || 0) : 0;
    }

    // Sinkron DP dari input QRIS ke hidden input form
    function syncDpFromQrisInput() {
        const totalDp = getCustomerDpQris();
        const hiddenDp = document.getElementById('form-total-dp');

        if (hiddenDp) {
            hiddenDp.value = totalDp;
        }

        return totalDp;
    }

    // Validasi nominal DP minimal 50.000
    function validateCustomerDpQris() {
        const totalDp = getCustomerDpQris();

        if (totalDp < 50000) {
            alert('Minimal DP adalah Rp 50.000.');
            return false;
        }

        syncDpFromQrisInput();
        return true;
    }

    // Menghitung total durasi
    function getTotalDurationQris(services) {
        if (typeof getTotalDuration === 'function') {
            return Number(getTotalDuration() || 0);
        }

        return services.reduce((total, item) => {
            return total + Number(item.duration || item.durasi || item.durasi_layanan || 0);
        }, 0);
    }

    // Mengaktifkan tombol submit jika bukti dan checkbox lengkap
    function toggleBookingSubmitQris() {
        const check = document.getElementById('qris-confirm-check');
        const btn = document.getElementById('btn-submit-booking-qris');
        const bukti = document.getElementById('bukti_pembayaran');
        const inputDp = document.getElementById('input-total-dp-qris');

        if (!check || !btn || !bukti) return;

        btn.disabled = !(check.checked && bukti.files.length > 0 && getCustomerDpQris() >= 50000);
    }

    // Membuka modal booking
    function openBookingModal() {
        const modal = document.getElementById('booking-modal');
        const services = getSelectedServicesQris();
        const tanggalBooking = getSelectedDateQris();
        const jamMulai = getSelectedTimeQris();

        if (!modal) return;

        if (!services || services.length === 0) {
            if (typeof showToast === 'function') showToast('Pilih layanan terlebih dahulu');
            else alert('Silakan pilih layanan terlebih dahulu.');
            return;
        }

        if (!tanggalBooking) {
            if (typeof showToast === 'function') showToast('Pilih tanggal booking');
            else alert('Silakan pilih tanggal booking terlebih dahulu.');
            return;
        }

        if (!jamMulai) {
            if (typeof showToast === 'function') showToast('Pilih jam booking');
            else alert('Silakan pilih jam booking terlebih dahulu.');
            return;
        }

        const totalPayment = getTotalPaymentQris(services);
        const totalMaxPayment = getTotalMaxPaymentQris(services);
        const totalDurasi = getTotalDurationQris(services);
        const layananIds = services.map(item => Number(item.id || item.id_layanan || item.service_id)).filter(Boolean);
        const jamForm = String(jamMulai).length === 5 ? jamMulai + ':00' : jamMulai;

        document.getElementById('form-tanggal-booking').value = tanggalBooking;
        document.getElementById('form-jam-mulai').value = jamForm;
        document.getElementById('form-layanan-terpilih').value = JSON.stringify(layananIds);
        document.getElementById('form-total-dp').value = getCustomerDpQris();

        document.getElementById('modal-date').innerText = formatTanggalModalQris(tanggalBooking);
        document.getElementById('modal-time').innerText = jamMulai;

        const serviceList = document.getElementById('modal-service-list');

        if (serviceList) {
            serviceList.innerHTML = services.map(item => {
                const nama = item.name || item.nama || item.nama_layanan || 'Layanan';
                const hargaMin = getServiceMinPriceQris(item);
                const hargaMax = getServiceMaxPriceQris(item);
                const durasi = Number(item.duration || item.durasi || item.durasi_layanan || 0);

                return `
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 p-4 bg-white border border-pink-100 rounded-2xl">
                        <div>
                            <p class="text-sm font-bold text-gray-800">${nama}</p>
                            <p class="text-xs text-gray-400 mt-1">Estimasi ${durasi} menit</p>
                        </div>

                        <div class="sm:text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                                Range Harga
                            </p>

                            <p class="text-sm font-bold text-pink-600 whitespace-nowrap mt-1">
                                ${formatRangeHargaQris(hargaMin, hargaMax)}
                            </p>
                        </div>
                    </div>
                `;
            }).join('');
        }

        document.getElementById('modal-total-price').innerText = formatRangeHargaQris(totalPayment, totalMaxPayment);
        
        document.getElementById('modal-total-duration').innerText = totalDurasi + ' Menit';

        const check = document.getElementById('qris-confirm-check');
        const btn = document.getElementById('btn-submit-booking-qris');
        const bukti = document.getElementById('bukti_pembayaran');
        const inputDp = document.getElementById('input-total-dp-qris');
        const previewWrapper = document.getElementById('preview-bukti-wrapper');
        const previewImage = document.getElementById('preview-bukti-image');
        const previewFile = document.getElementById('preview-bukti-file');

        if (check) check.checked = false;
        if (btn) btn.disabled = true;
        if (bukti) bukti.value = '';
        if (inputDp) inputDp.value = '';
        if (previewWrapper) previewWrapper.classList.add('hidden');
        if (previewImage) {
            previewImage.src = '';
            previewImage.classList.add('hidden');
        }
        if (previewFile) {
            previewFile.innerText = '';
            previewFile.classList.add('hidden');
        }

        modal.classList.remove('hidden');
    }

    // Validasi terakhir sebelum submit booking
    document.addEventListener('submit', function (event) {
        const form = event.target;

        if (!form || !form.querySelector('#form-total-dp')) return;

        if (!validateCustomerDpQris()) {
            event.preventDefault();
            event.stopImmediatePropagation();
            return;
        }

        document.getElementById('form-total-dp').value = getCustomerDpQris();
    }, true);

    // Sinkron validasi saat input DP berubah
    document.getElementById('input-total-dp-qris')?.addEventListener('input', function () {
        syncDpFromQrisInput();
        toggleBookingSubmitQris();
    });

    // Sinkron validasi saat upload bukti berubah
    document.getElementById('bukti_pembayaran')?.addEventListener('change', function () {
        toggleBookingSubmitQris();
    });

    // Menutup modal booking
    function closeBookingModal() {
        const modal = document.getElementById('booking-modal');

        if (modal) {
            modal.classList.add('hidden');
        }
    }
</script>
