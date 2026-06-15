// ======================================================
// BOOKING CUSTOMER - MEY SALON
// File aktif untuk halaman user/booking.php
// Mengatur: section, kalender, jam, keranjang, range harga, dan modal booking.
// ======================================================

// Menyimpan data keranjang layanan
let cart = [];

// Menyimpan bulan kalender aktif
let calendarDate = new Date();

// Menyimpan tanggal dan jam booking yang dipilih
let selectedBookingDate = "";
let selectedBookingTime = "";

// NAVBAR DAN SECTION
// Menampilkan section yang dipilih
function showSection(sectionName) {
  const sections = document.querySelectorAll(".content-section");
  const mobileMenu = document.getElementById("mobile-menu");
  const menuIcon = document.getElementById("menu-icon");

  sections.forEach((section) => {
    section.classList.add("hidden");
  });

  const targetSection = document.getElementById(`section-${sectionName}`);

  if (targetSection) {
    targetSection.classList.remove("hidden");
  }

  if (mobileMenu) {
    mobileMenu.classList.add("hidden");
  }

  if (menuIcon) {
    menuIcon.className = "fa-solid fa-bars-staggered text-2xl";
  }

  window.scrollTo({
    top: 0,
    behavior: "smooth",
  });
}

// Membuka dan menutup menu mobile
function toggleMobileMenu() {
  const mobileMenu = document.getElementById("mobile-menu");
  const menuIcon = document.getElementById("menu-icon");

  if (!mobileMenu || !menuIcon) return;

  mobileMenu.classList.toggle("hidden");

  menuIcon.className = mobileMenu.classList.contains("hidden")
    ? "fa-solid fa-bars-staggered text-2xl"
    : "fa-solid fa-xmark text-2xl";
}

// FORMAT DATA
// Format tanggal menjadi YYYY-MM-DD
function formatDateKey(year, month, day) {
  const monthText = String(month + 1).padStart(2, "0");
  const dayText = String(day).padStart(2, "0");

  return `${year}-${monthText}-${dayText}`;
}

// Format rupiah
function formatRupiah(value) {
  return "Rp " + Number(value).toLocaleString("id-ID");
}

// Format tanggal Indonesia
function formatTanggalIndonesia(dateKey) {
  const dateObject = new Date(dateKey + "T00:00:00");

  return dateObject.toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });
}

// Mengatur style kalender agar stabil
function injectCalendarDateSizeStyle() {
  if (document.getElementById("calendar-date-size-style")) return;

  const style = document.createElement("style");
  style.id = "calendar-date-size-style";
  style.textContent = `
    #calendar-days {
      display: grid;
      grid-template-columns: repeat(7, minmax(0, 1fr));
      gap: 6px !important;
    }

    .calendar-day {
      width: 100% !important;
      height: 38px !important;
      min-height: 38px !important;
      border-radius: 12px !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      cursor: pointer;
      font-size: 13px !important;
      font-weight: 700 !important;
      line-height: 1 !important;
      transition: 0.25s;
    }

    .calendar-day-empty {
      pointer-events: none;
    }

    @media (max-width: 639px) {
      .calendar-day {
        height: 34px !important;
        min-height: 34px !important;
        border-radius: 10px !important;
        font-size: 12px !important;
      }
    }

    .calendar-day-booked {
      background: #111827 !important;
      color: #ffffff !important;
      border-color: #111827 !important;
    }

    .calendar-day-selected {
      background: #ec4899 !important;
      color: #ffffff !important;
      border-color: #ec4899 !important;
    }

    .calendar-day-today {
      border: 2px solid #ec4899 !important;
    }

    .time-slot-button {
      width: 100%;
      min-height: 38px;
      padding: 8px 6px;
      font-size: 12px;
      font-weight: 800;
      border-radius: 12px;
      border: 1px solid #fbcfe8;
      background: #ffffff;
      color: #374151;
      transition: 0.25s;
    }

    .time-slot-button:hover {
      background: #fdf2f8;
    }

    .time-slot-disabled {
      border-color: #f3f4f6 !important;
      background: #f3f4f6 !important;
      color: #d1d5db !important;
      cursor: not-allowed !important;
    }

    .time-slot-booked {
      border-color: #111827 !important;
      background: #111827 !important;
      color: #ffffff !important;
      cursor: not-allowed !important;
    }

    .time-selected {
      background: #ec4899 !important;
      color: #ffffff !important;
      border-color: #ec4899 !important;
    }
  `;

  document.head.appendChild(style);
}


// KALENDER BOOKING
// Menampilkan kalender booking
function renderCalendar() {
  injectCalendarDateSizeStyle();

  const calendarDays = document.getElementById("calendar-days");
  const calendarTitle = document.getElementById("calendar-month-title");

  if (!calendarDays || !calendarTitle) return;

  const year = calendarDate.getFullYear();
  const month = calendarDate.getMonth();

  const firstDay = new Date(year, month, 1).getDay();
  const totalDays = new Date(year, month + 1, 0).getDate();

  const today = new Date();
  today.setHours(0, 0, 0, 0);

  const todayKey = formatDateKey(
    today.getFullYear(),
    today.getMonth(),
    today.getDate(),
  );

  calendarTitle.textContent = calendarDate.toLocaleDateString("id-ID", {
    month: "long",
    year: "numeric",
  });

  calendarDays.innerHTML = "";

  // Mengisi kotak kosong sebelum tanggal pertama
  for (let i = 0; i < firstDay; i++) {
    const emptyDay = document.createElement("div");
    emptyDay.className = "calendar-day calendar-day-empty";
    calendarDays.appendChild(emptyDay);
  }

  // Menampilkan tanggal bulan aktif saja
  for (let day = 1; day <= totalDays; day++) {
    const dateKey = formatDateKey(year, month, day);
    const currentDate = new Date(year, month, day);
    currentDate.setHours(0, 0, 0, 0);
    const isWednesday = currentDate.getDay() === 3;

    const dayButton = document.createElement("button");

    dayButton.type = "button";
    dayButton.textContent = day;
    dayButton.dataset.date = dateKey;
    dayButton.className = "calendar-day calendar-day-available";

    // Tanggal sebelum hari ini dan hari Rabu tidak bisa dipilih
    if (currentDate < today || isWednesday) {
      dayButton.disabled = true;
      dayButton.className = "calendar-day calendar-day-disabled";

      if (isWednesday) {
        dayButton.title = "Salon libur setiap hari Rabu";
        dayButton.classList.add("line-through");
      }

      calendarDays.appendChild(dayButton);
      continue;
    }

    // Tanggal hari ini tetap bisa dipilih
    if (dateKey === todayKey) {
      dayButton.classList.add("calendar-day-today");
    }

    // Tanggal yang sudah ada booking
    if (hasBookedSchedulesForDate(dateKey)) {
      dayButton.classList.add("calendar-day-booked");
      dayButton.title = "Tanggal ini sudah memiliki booking, tetapi masih bisa dipilih jika ada jam kosong";
    }

    // Tanggal yang sedang dipilih
    if (selectedBookingDate === dateKey) {
      dayButton.classList.add("calendar-day-selected");
    }

    // Klik tanggal
    dayButton.onclick = function () {
      selectBookingDate(dateKey);
    };

    calendarDays.appendChild(dayButton);
  }
}

// Memilih tanggal booking
function selectBookingDate(dateKey) {
  // Menolak tanggal Rabu karena salon libur
  if (isWednesdayClosedBooking(dateKey)) {
    selectedBookingDate = "";
    selectedBookingTime = "";
    renderCalendar();
    renderTimeSlots();
    updateCartUI();

    if (typeof showToast === "function") {
      showToast("Salon libur setiap hari Rabu");
    } else {
      alert("Salon libur setiap hari Rabu");
    }

    return;
  }

  selectedBookingDate = dateKey;
  selectedBookingTime = "";

  const selectedDateText = document.getElementById("selected-date-text");
  const selectedDateStatus = document.getElementById("selected-date-status");
  const summaryDate = document.getElementById("summary-date");
  const summaryTime = document.getElementById("summary-time");

  const formattedDate = formatTanggalIndonesia(dateKey);

  if (selectedDateText) {
    selectedDateText.textContent = formattedDate;
  }

  if (summaryDate) {
    summaryDate.textContent = formattedDate;
  }

  if (summaryTime) {
    summaryTime.textContent = "-";
  }

  if (selectedDateStatus) {
    if (hasBookedSchedulesForDate(dateKey)) {
      const bookedItems = getBookedItemsForDate(dateKey);

      selectedDateStatus.innerHTML = `
                Sudah ada booking:<br>
                ${bookedItems.map((item) => `• ${formatBookedScheduleInfo(item)}`).join("<br>")}
            `;
    } else {
      selectedDateStatus.textContent =
        "Tanggal ini masih tersedia untuk booking.";
    }
  }

  renderCalendar();
  renderTimeSlots();
  updateCartUI();

  if (selectedBookingDate) {
    renderTimeSlots();
  }

  if (typeof showCheckoutCartPopup === "function") {
    showCheckoutCartPopup();
  }
}

// Mengganti bulan kalender
function changeMonth(step) {
  calendarDate.setMonth(calendarDate.getMonth() + step);
  renderCalendar();
}

// Mengubah jam HH:MM menjadi menit
function timeToMinutes(timeText) {
  const match = String(timeText || "").match(/(\d{1,2}):(\d{2})/);

  if (!match) return null;

  return Number(match[1]) * 60 + Number(match[2]);
}

// Merapikan format jam menjadi HH:MM
function normalizeTimeText(timeText) {
  const match = String(timeText || "").match(/(\d{1,2}):(\d{2})/);

  if (!match) return "";

  return String(match[1]).padStart(2, "0") + ":" + String(match[2]).padStart(2, "0");
}

// Mengambil tanggal pendek YYYY-MM-DD dari berbagai format
function normalizeDateKey(dateValue) {
  const text = String(dateValue || "").trim();

  if (!text) return "";

  const match = text.match(/\d{4}-\d{2}-\d{2}/);

  if (match) return match[0];

  const dateObject = new Date(text);

  if (isNaN(dateObject.getTime())) return text;

  return formatDateKey(
    dateObject.getFullYear(),
    dateObject.getMonth(),
    dateObject.getDate(),
  );
}

// Mengambil sumber jadwal booking dari PHP secara aman
function getBookedSchedulesSource() {
  if (typeof bookedSchedules !== "undefined" && bookedSchedules) {
    return bookedSchedules;
  }

  if (window.bookedSchedules) {
    return window.bookedSchedules;
  }

  return {};
}

// Mengambil daftar booking pada tanggal tertentu secara aman
function getBookedItemsForDate(dateKey) {
  const targetDate = normalizeDateKey(dateKey);
  const schedules = getBookedSchedulesSource();

  if (!targetDate || !schedules) return [];

  // Format utama dari PHP: { "2026-06-10": [ {jam_mulai, jam_selesai}, ... ] }
  if (!Array.isArray(schedules) && typeof schedules === "object") {
    const directValue = schedules[targetDate] || schedules[dateKey];

    if (Array.isArray(directValue)) {
      return directValue.filter(Boolean);
    }

    if (directValue && typeof directValue === "object") {
      return [directValue];
    }

    if (typeof directValue === "string" && directValue.trim() !== "") {
      return [directValue];
    }

    // Fallback jika key dari database mengandung jam, contoh: 2026-06-10 00:00:00
    const matchedKey = Object.keys(schedules).find(function (key) {
      return normalizeDateKey(key) === targetDate;
    });

    if (matchedKey) {
      const matchedValue = schedules[matchedKey];

      if (Array.isArray(matchedValue)) {
        return matchedValue.filter(Boolean);
      }

      if (matchedValue && typeof matchedValue === "object") {
        return [matchedValue];
      }

      if (typeof matchedValue === "string" && matchedValue.trim() !== "") {
        return [matchedValue];
      }
    }
  }

  // Fallback jika PHP mengirim array datar: [ {tanggal_booking, jam_mulai, jam_selesai}, ... ]
  if (Array.isArray(schedules)) {
    return schedules.filter(function (item) {
      if (!item) return false;

      if (typeof item === "object") {
        return normalizeDateKey(
          item.tanggal_booking ||
          item.tanggal ||
          item.date ||
          item.booking_date ||
          "",
        ) === targetDate;
      }

      return String(item).includes(targetDate);
    });
  }

  return [];
}

// Mengecek apakah tanggal mempunyai booking aktif
function hasBookedSchedulesForDate(dateKey) {
  return getBookedItemsForDate(dateKey).length > 0;
}

// Mengambil range jam booking dari data bookedSchedules
function getBookedTimeRange(item) {
  if (typeof item === "object" && item !== null) {
    const start =
      item.jam_mulai ||
      item.jam_start ||
      item.start ||
      item.mulai ||
      item.jam ||
      item.time ||
      "";

    const end =
      item.jam_selesai ||
      item.jam_end ||
      item.end ||
      item.selesai ||
      item.finish ||
      "";

    return {
      start: normalizeTimeText(start),
      end: normalizeTimeText(end),
    };
  }

  const text = String(item || "");
  const times = text.match(/\d{1,2}:\d{2}/g) || [];

  return {
    start: normalizeTimeText(times[0] || ""),
    end: normalizeTimeText(times[1] || ""),
  };
}

// Mengambil menit mulai dan selesai booking lama
function getBookedMinutesRange(bookedItem) {
  const range = getBookedTimeRange(bookedItem);
  const start = timeToMinutes(range.start);
  let end = timeToMinutes(range.end);

  if (start === null) {
    return { start: null, end: null };
  }

  // Jika data lama belum punya jam_selesai, anggap durasinya 60 menit agar jam tetap muncul.
  if (end === null || end <= start) {
    end = start + 60;
  }

  return { start, end };
}

// Mengecek apakah titik jam berada di dalam rentang booking lama
function isTimeInsideBookedRange(time, bookedItem) {
  const slot = timeToMinutes(time);
  const booked = getBookedMinutesRange(bookedItem);

  if (slot === null || booked.start === null || booked.end === null) {
    return false;
  }

  // Contoh booking 10:00 - 12:00: 10:00 dan 11:00 disable, 12:00 tetap boleh.
  return slot >= booked.start && slot < booked.end;
}

// Mengecek apakah dua range waktu saling bentrok
function isRangeOverlap(startA, endA, startB, endB) {
  if (startA === null || endA === null || startB === null || endB === null) {
    return false;
  }

  return startA < endB && endA > startB;
}

// Mengambil estimasi durasi dari layanan yang dipilih
function getSelectedServiceDurationForSlot() {
  if (typeof getTotalDuration === "function") {
    const duration = Number(getTotalDuration() || 0);

    if (duration > 0) {
      return duration;
    }
  }

  if (typeof cart !== "undefined" && Array.isArray(cart)) {
    const duration = cart.reduce((total, item) => {
      return total + Number(item.duration || item.durasi || item.durasi_layanan || 0);
    }, 0);

    if (duration > 0) {
      return duration;
    }
  }

  // Kalau customer belum pilih layanan, jam tetap muncul.
  // Default 60 menit hanya untuk mengecek bentrok dasar.
  return 60;
}

// Mengecek apakah jam baru bentrok dengan booking lama berdasarkan estimasi waktu layanan
function isTimeBlockedByEstimatedDuration(time, bookedItem, selectedDuration) {
  const slotStart = timeToMinutes(time);
  const duration = Math.max(Number(selectedDuration || 0), 60);
  const slotEnd = slotStart === null ? null : slotStart + duration;
  const booked = getBookedMinutesRange(bookedItem);

  if (slotStart === null || slotEnd === null || booked.start === null || booked.end === null) {
    return false;
  }

  return isRangeOverlap(slotStart, slotEnd, booked.start, booked.end);
}

// Mengecek apakah tombol jam harus dinonaktifkan karena range booking lama
function isTimeBlockedByBookedSchedule(time, bookedItem, selectedDuration) {
  return (
    isTimeInsideBookedRange(time, bookedItem) ||
    isTimeBlockedByEstimatedDuration(time, bookedItem, selectedDuration)
  );
}

// Mengecek apakah jam + estimasi layanan melewati jam tutup salon
function isBeyondClosingTime(time, selectedDuration) {
  const start = timeToMinutes(time);
  const duration = Math.max(Number(selectedDuration || 0), 60);
  const end = start === null ? null : start + duration;
  const closing = timeToMinutes("21:00");

  return end !== null && closing !== null && end > closing;
}

// Format info booking agar object dari PHP tidak tampil sebagai [object Object]
function formatBookedScheduleInfo(item) {
  if (typeof item === "object" && item !== null) {
    const range = getBookedTimeRange(item);
    const layanan = item.layanan || item.nama_layanan || item.service || "Booking";
    const status = item.status || item.status_booking || "";
    const statusText = status ? ` (${status})` : "";

    return `${range.start || "--:--"} - ${range.end || "--:--"} ${layanan}${statusText}`;
  }

  return String(item || "Booking");
}

// Mengecek salon libur hari Rabu
function isWednesdayClosedBooking(dateKey) {
  if (!dateKey) return false;

  const dateObject = new Date(dateKey + "T00:00:00");

  if (isNaN(dateObject.getTime())) return false;

  return dateObject.getDay() === 3;
}

// PILIH JAM BOOKING
// Menampilkan pilihan jam dari 10 pagi sampai 9 malam
function renderTimeSlots() {
  const timeSlots = document.getElementById("time-slots");

  if (!timeSlots) return;

  timeSlots.innerHTML = "";

  if (!selectedBookingDate) {
    timeSlots.innerHTML = `
      <div class="col-span-3 p-4 bg-pink-50 text-pink-600 text-xs font-bold rounded-2xl text-center border border-pink-100">
        Pilih tanggal terlebih dahulu.
      </div>
    `;
    return;
  }

  // Jika tanggal yang dipilih Rabu, jam tidak ditampilkan karena salon libur.
  if (isWednesdayClosedBooking(selectedBookingDate)) {
    timeSlots.innerHTML = `
      <div class="col-span-3 p-4 bg-red-50 text-red-500 text-xs font-bold rounded-2xl text-center border border-red-100">
        Salon libur setiap hari Rabu.
      </div>
    `;
    return;
  }

  const now = new Date();

  const todayKey = formatDateKey(
    now.getFullYear(),
    now.getMonth(),
    now.getDate(),
  );

  const selectedDuration = getSelectedServiceDurationForSlot();
  const bookedItems = getBookedItemsForDate(selectedBookingDate);

  // Jam tetap selalu dibuat. Jika ada booking, hanya jam yang bentrok yang disable.
  for (let hour = 10; hour <= 21; hour++) {
    const time = String(hour).padStart(2, "0") + ":00";
    const timeButton = document.createElement("button");

    timeButton.type = "button";
    timeButton.textContent = time;
    timeButton.dataset.time = time;
    timeButton.className = "time-slot-button";

    const isPastTime =
      selectedBookingDate === todayKey && hour <= now.getHours() + 1;

    const isBookedTime = bookedItems.some(function (item) {
      return isTimeBlockedByBookedSchedule(time, item, selectedDuration);
    });

    const isAfterClosing = isBeyondClosingTime(time, selectedDuration);

    if (isPastTime || isBookedTime || isAfterClosing) {
      timeButton.disabled = true;
      timeButton.classList.add("time-slot-disabled");

      if (isBookedTime) {
        timeButton.classList.add("time-slot-booked");
        timeButton.title = "Jam ini bentrok dengan rentang booking yang sudah ada.";
      } else if (isPastTime) {
        timeButton.title = "Jam sudah lewat atau terlalu dekat.";
      } else if (isAfterClosing) {
        timeButton.title = "Jam tidak cukup karena melewati jam tutup salon.";
      }
    }

    if (selectedBookingTime === time) {
      timeButton.classList.add("time-selected");
    }

    timeButton.onclick = function () {
      if (timeButton.disabled) return;

      selectedBookingTime = time;

      const summaryTime = document.getElementById("summary-time");

      if (summaryTime) {
        summaryTime.textContent = time;
      }

      renderTimeSlots();
      updateCartUI();

      if (typeof showCheckoutCartPopup === "function") {
        showCheckoutCartPopup();
      }
    };

    timeSlots.appendChild(timeButton);
  }
}

// Mengambil harga minimum layanan
function getItemMinPrice(item) {
  return Number(
    item.price || item.harga || item.harga_min || item.harga_layanan || 0,
  );
}

// Mengambil harga maksimum layanan
function getItemMaxPrice(item) {
  const directMax = Number(
    item.priceMax ||
      item.price_max ||
      item.harga_max ||
      item.max_price ||
      item.harga_maksimal ||
      0,
  );

  if (directMax > 0) {
    return directMax;
  }

  return getItemMinPrice(item);
}

// Format range harga
function formatRangeRupiah(minValue, maxValue) {
  const minPrice = Number(minValue || 0);
  const maxPrice = Number(maxValue || minPrice || 0);

  if (maxPrice > minPrice) {
    return `${formatRupiah(minPrice)} - ${formatRupiah(maxPrice)}`;
  }

  return formatRupiah(minPrice);
}

// KERANJANG BOOKING
// Menambahkan layanan ke keranjang
function addToCart(id, name, price, duration, priceMax = price) {
  const existingItem = cart.find((item) => item.id === id);

  if (existingItem) {
    showToast("Layanan sudah ada di keranjang");
    return;
  }

  cart.push({
    id: id,
    name: name,
    price: Number(price),
    priceMax: Number(priceMax || price),
    harga_max: Number(priceMax || price),
    duration: Number(duration),
  });

  updateCartUI();

  if (selectedBookingDate) {
    renderTimeSlots();
  }

  if (typeof showCheckoutCartPopup === "function") {
    showCheckoutCartPopup();
  }

  showToast("Layanan ditambahkan");
}

// Menghapus layanan dari keranjang
function removeFromCart(id) {
  cart = cart.filter((item) => item.id !== id);

  updateCartUI();

  if (typeof showCheckoutCartPopup === "function") {
    showCheckoutCartPopup();
  }

  showToast("Layanan dihapus");
}

// Menghitung total harga
function getTotalPrice() {
  return cart.reduce((total, item) => total + Number(item.price), 0);
}

// Menghitung total harga maksimal
function getTotalMaxPrice() {
  return cart.reduce((total, item) => total + getItemMaxPrice(item), 0);
}

// Menghitung total durasi
function getTotalDuration() {
  return cart.reduce((total, item) => total + Number(item.duration), 0);
}

// Memperbarui tampilan keranjang bawah
function updateCartUI() {
  const cartItemsContainer = document.getElementById("cart-items-container");
  const emptyCartMsg = document.getElementById("empty-cart-msg");
  const cartItemCount = document.getElementById("cart-item-count");
  const cartTotalPrice = document.getElementById("cart-total-price");
  const cartTotalDuration = document.getElementById("cart-total-duration");
  const buttonConfirm = document.getElementById("btn-open-confirm");

  if (!cartItemsContainer || !emptyCartMsg) return;

  const totalPrice = getTotalPrice();
  const totalMaxPrice =
    typeof getTotalMaxPrice === "function" ? getTotalMaxPrice() : totalPrice;
  const totalDuration = getTotalDuration();

  // Menghapus item lama
  cartItemsContainer.querySelectorAll(".cart-item").forEach((item) => {
    item.remove();
  });

  // Menampilkan item keranjang
  cart.forEach((item) => {
    const itemElement = document.createElement("div");

    itemElement.className =
      "cart-item bg-pink-50/40 border border-pink-100 rounded-2xl p-4 flex justify-between items-center animate-fade-in";

    itemElement.innerHTML = `
            <div>
                <h4 class="font-bold text-sm text-gray-800">${item.name}</h4>
                <p class="text-xs text-gray-400 mt-1">${item.duration} menit</p>
                <p class="text-sm font-bold text-pink-600 mt-1">
                    ${formatRangeRupiah(getItemMinPrice(item), getItemMaxPrice(item))}
                </p>
            </div>

            <button 
                type="button" 
                onclick="removeFromCart(${item.id})" 
                class="w-9 h-9 flex items-center justify-center rounded-xl bg-white text-red-500 hover:bg-red-50 transition"
            >
                <i class="fa-solid fa-trash text-xs"></i>
            </button>
        `;

    cartItemsContainer.appendChild(itemElement);
  });

  // Menampilkan atau menyembunyikan pesan kosong
  if (cart.length > 0) {
    emptyCartMsg.classList.add("hidden");
  } else {
    emptyCartMsg.classList.remove("hidden");
  }

  // Mengubah jumlah layanan
  if (cartItemCount) {
    cartItemCount.textContent = `${cart.length} layanan dipilih`;
  }

  // Mengubah total harga
  if (cartTotalPrice) {
    cartTotalPrice.textContent = formatRangeRupiah(totalPrice, totalMaxPrice);
  }

  // Mengubah total durasi
  if (cartTotalDuration) {
    cartTotalDuration.textContent = `${totalDuration} Menit`;
  }

  // Tombol konfirmasi aktif jika tanggal, jam, dan layanan sudah dipilih
  if (buttonConfirm) {
    buttonConfirm.disabled =
      cart.length === 0 ||
      selectedBookingDate === "" ||
      selectedBookingTime === "";
  }
}

// MODAL KONFIRMASI BOOKING
// Membuka modal konfirmasi booking
function openBookingModal() {
  if (cart.length === 0) {
    showToast("Pilih layanan terlebih dahulu");
    return;
  }

  if (selectedBookingDate === "") {
    showToast("Pilih tanggal booking");
    return;
  }

  if (selectedBookingTime === "") {
    showToast("Pilih jam booking");
    return;
  }

  const bookingModal = document.getElementById("booking-modal");
  const modalDate = document.getElementById("modal-date");
  const modalTime = document.getElementById("modal-time");
  const modalServiceList = document.getElementById("modal-service-list");
  const modalTotalPrice = document.getElementById("modal-total-price");
  const modalTotalDuration = document.getElementById("modal-total-duration");

  const formTanggalBooking = document.getElementById("form-tanggal-booking");
  const formJamMulai = document.getElementById("form-jam-mulai");
  const formLayananTerpilih = document.getElementById("form-layanan-terpilih");

  if (!bookingModal) return;

  const formattedDate = formatTanggalIndonesia(selectedBookingDate);
  const totalPrice = getTotalPrice();
  const totalMaxPrice =
    typeof getTotalMaxPrice === "function" ? getTotalMaxPrice() : totalPrice;
  const totalDuration = getTotalDuration();

  // Mengisi informasi modal
  if (modalDate) {
    modalDate.textContent = formattedDate;
  }

  if (modalTime) {
    modalTime.textContent = selectedBookingTime;
  }

  if (modalTotalPrice) {
    modalTotalPrice.textContent = formatRangeRupiah(totalPrice, totalMaxPrice);
  }

  if (modalTotalDuration) {
    modalTotalDuration.textContent = `${totalDuration} Menit`;
  }

  // Mengisi input hidden form
  if (formTanggalBooking) {
    formTanggalBooking.value = selectedBookingDate;
  }

  if (formJamMulai) {
    formJamMulai.value = selectedBookingTime + ":00";
  }

  if (formLayananTerpilih) {
    formLayananTerpilih.value = JSON.stringify(cart.map((item) => item.id));
  }

  // Menampilkan layanan yang dipilih di modal
  if (modalServiceList) {
    modalServiceList.innerHTML = "";

    cart.forEach((item) => {
      const serviceItem = document.createElement("div");

      serviceItem.className =
        "p-4 bg-white rounded-2xl border border-pink-100 flex justify-between items-center";

      serviceItem.innerHTML = `
                <div>
                    <p class="text-sm font-bold text-gray-800">${item.name}</p>
                    <p class="text-xs text-gray-400">${item.duration} menit</p>
                </div>

                <p class="text-sm font-bold text-pink-600">
                    ${formatRangeRupiah(getItemMinPrice(item), getItemMaxPrice(item))}
                </p>
            `;

      modalServiceList.appendChild(serviceItem);
    });
  }

  bookingModal.classList.remove("hidden");
}

// Menutup modal konfirmasi booking
function closeBookingModal() {
  const bookingModal = document.getElementById("booking-modal");

  if (bookingModal) {
    bookingModal.classList.add("hidden");
  }
}

// TOAST
// Menampilkan toast pesan
function showToast(message) {
  const toast = document.getElementById("toast");

  if (!toast) return;

  toast.textContent = message;
  toast.classList.remove("hidden");

  setTimeout(() => {
    toast.classList.add("hidden");
  }, 2500);
}

// INIT
// Menjalankan halaman awal
document.addEventListener("DOMContentLoaded", function () {
  injectCalendarDateSizeStyle();
  renderCalendar();
  renderTimeSlots();
  updateCartUI();

  if (typeof showCheckoutCartPopup === "function") {
    showCheckoutCartPopup();
  }
  showSection("layanan");
});


// Membantu debug dari browser console jika dibutuhkan
window.getBookedItemsForDate = getBookedItemsForDate;
window.renderTimeSlots = renderTimeSlots;
window.selectBookingDate = selectBookingDate;
