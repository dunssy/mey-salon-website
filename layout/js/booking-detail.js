// ======================================================
// BOOKING DETAIL ADMIN - MEY SALON
// Script dipisah sesuai halaman agar mudah dirawat.
// ======================================================

    // Menyimpan pilihan stok barang dari detail-booking.php
    const stokBarangOptions = window.stokBarangOptions || '<option value="">Pilih Bahan</option>';

    // Menyimpan data kalender booking admin
    const jadwalAdmin = window.jadwalAdmin || {};

    // Menyimpan bulan kalender admin
    let adminCalendarDate = new Date();

    // Membuka popup pending
    function openPendingModal() {
        const modal = document.getElementById('pending-modal');

        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Menutup popup pending
    function closePendingModal() {
        const modal = document.getElementById('pending-modal');

        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Menambahkan baris tambahan bahan
    function tambahBahan() {
        const wrapper = document.getElementById('tambahan-bahan-wrapper');

        const row = document.createElement('div');
        row.className = 'grid grid-cols-1 sm:grid-cols-[1fr_100px_auto] gap-2';

        row.innerHTML = `
            <select 
                name="id_barang_tambahan[]" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
            >
                ${stokBarangOptions}
            </select>

            <input 
                type="number"
                name="jumlah_tambahan[]"
                min="1"
                placeholder="Jumlah"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-200"
            >

            <button 
                type="button"
                onclick="this.parentElement.remove()"
                class="px-3 py-2 bg-red-50 text-red-600 text-xs font-bold rounded-lg hover:bg-red-100 transition"
            >
                Hapus
            </button>
        `;

        wrapper.appendChild(row);
    }


    // Membuat key tanggal format YYYY-MM-DD
    function makeDateKey(year, month, day) {
        const monthText = String(month + 1).padStart(2, '0');
        const dayText = String(day).padStart(2, '0');

        return `${year}-${monthText}-${dayText}`;
    }

    // Render kalender booking admin
    function renderAdminCalendar() {
        const title = document.getElementById('admin-calendar-title');
        const daysContainer = document.getElementById('admin-calendar-days');
        const detailContainer = document.getElementById('admin-calendar-detail');

        if (!title || !daysContainer || !detailContainer) return;

        const year = adminCalendarDate.getFullYear();
        const month = adminCalendarDate.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const totalDays = new Date(year, month + 1, 0).getDate();
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        title.textContent = `${monthNames[month]} ${year}`;
        daysContainer.innerHTML = '';

        for (let blank = 0; blank < firstDay; blank++) {
            daysContainer.innerHTML += `<div></div>`;
        }

        for (let day = 1; day <= totalDays; day++) {
            const dateKey = makeDateKey(year, month, day);
            const dateObject = new Date(dateKey + 'T00:00:00');
            const isWednesday = dateObject.getDay() === 3;
            const hasBooking = Array.isArray(jadwalAdmin[dateKey]) ? jadwalAdmin[dateKey].length > 0 : !!jadwalAdmin[dateKey];

            let className = 'min-h-[34px] rounded-lg text-[11px] font-bold border transition flex flex-col items-center justify-center gap-0.5 ';

            if (isWednesday) {
                className += 'bg-red-50 text-red-400 border-red-100 line-through';
            } else if (hasBooking) {
                className += 'bg-[#C75C7A] text-white border-[#C75C7A] hover:bg-[#B14F6C]';
            } else {
                className += 'bg-white text-gray-600 border-gray-100 hover:bg-gray-50';
            }

            daysContainer.innerHTML += `
                <button type="button" onclick="showAdminCalendarDetail('${dateKey}')" class="${className}">
                    <span>${day}</span>
                    ${hasBooking ? '<span class="w-1.5 h-1.5 rounded-full bg-white"></span>' : ''}
                </button>
            `;
        }

        detailContainer.innerHTML = `
            <div class="text-[11px] text-gray-400 bg-gray-50 border border-gray-100 rounded-xl p-3 text-center">
                Klik tanggal berwarna pink untuk melihat booking.
            </div>
        `;
    }

    // Menampilkan detail booking berdasarkan tanggal
    function showAdminCalendarDetail(dateKey) {
        const detailContainer = document.getElementById('admin-calendar-detail');

        if (!detailContainer) return;

        const list = jadwalAdmin[dateKey] || [];

        if (list.length === 0) {
            detailContainer.innerHTML = `
                <div class="text-[11px] text-gray-400 bg-gray-50 border border-gray-100 rounded-xl p-3 text-center">
                    Tidak ada booking aktif pada tanggal ini.
                </div>
            `;
            return;
        }

        detailContainer.innerHTML = list.map(item => `
            <a href="detail-booking.php?id_booking=${item.id_booking}" class="block p-3 bg-pink-50/50 border border-pink-100 rounded-xl hover:bg-pink-50 transition">
                <div class="flex items-center justify-between gap-3">
                    <p class="text-xs font-bold text-gray-800">${item.jam_mulai} - ${item.jam_selesai}</p>
                    <span class="text-[10px] font-bold text-pink-600 bg-white px-2 py-1 rounded-lg">${item.status}</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">${item.nama}</p>
                <p class="text-xs text-gray-400 mt-1">${item.layanan}</p>
            </a>
        `).join('');
    }

    // Kalender admin bulan sebelumnya
    function adminPrevMonth() {
        adminCalendarDate.setMonth(adminCalendarDate.getMonth() - 1);
        renderAdminCalendar();
    }

    // Kalender admin bulan berikutnya
    function adminNextMonth() {
        adminCalendarDate.setMonth(adminCalendarDate.getMonth() + 1);
        renderAdminCalendar();
    }

    // Menutup popup pending saat klik area luar
    document.addEventListener('click', function (event) {
        const modal = document.getElementById('pending-modal');

        if (!modal || modal.classList.contains('hidden')) return;

        if (event.target === modal) {
            closePendingModal();
        }
    });

    // Menutup popup pending saat tombol escape ditekan
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closePendingModal();
        }
    });

    // Menjalankan kalender saat halaman siap
    document.addEventListener('DOMContentLoaded', function () {
        renderAdminCalendar();
    });
