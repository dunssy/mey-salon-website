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
  const dateObject = new Date(dateKey);

  return dateObject.toLocaleDateString("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  });
}

// KALENDER BOOKING
// Menampilkan kalender booking
function renderCalendar() {
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

    const dayButton = document.createElement("button");

    dayButton.type = "button";
    dayButton.textContent = day;
    dayButton.className = "calendar-day calendar-day-available";

    // Tanggal sebelum hari ini tidak bisa dipilih
    if (currentDate < today) {
      dayButton.disabled = true;
      dayButton.className = "calendar-day calendar-day-disabled";
      calendarDays.appendChild(dayButton);
      continue;
    }

    // Tanggal hari ini tetap bisa dipilih
    if (dateKey === todayKey) {
      dayButton.classList.add("calendar-day-today");
    }

    // Tanggal yang sudah ada booking
    if (typeof bookedSchedules !== "undefined" && bookedSchedules[dateKey]) {
      dayButton.classList.add("calendar-day-booked");
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
    if (typeof bookedSchedules !== "undefined" && bookedSchedules[dateKey]) {
      selectedDateStatus.innerHTML = `
                Sudah ada booking:<br>
                ${bookedSchedules[dateKey].map((item) => `• ${item}`).join("<br>")}
            `;
    } else {
      selectedDateStatus.textContent =
        "Tanggal ini masih tersedia untuk booking.";
    }
  }

  renderCalendar();
  renderTimeSlots();
  updateCartUI();
}

// Mengganti bulan kalender
function changeMonth(step) {
  calendarDate.setMonth(calendarDate.getMonth() + step);
  renderCalendar();
}

// PILIH JAM BOOKING
// Menampilkan pilihan jam dari 10 pagi sampai 9 malam
function renderTimeSlots() {
  const timeSlots = document.getElementById("time-slots");

  if (!timeSlots) return;

  timeSlots.innerHTML = "";

  // Mengambil waktu sekarang
  const now = new Date();

  // Membuat format tanggal hari ini
  const todayKey = formatDateKey(
    now.getFullYear(),
    now.getMonth(),
    now.getDate(),
  );

  // Menampilkan jam dari 10:00 sampai 21:00
  for (let hour = 10; hour <= 21; hour++) {
    const time = String(hour).padStart(2, "0") + ":00";
    const timeButton = document.createElement("button");

    timeButton.type = "button";
    timeButton.textContent = time;
    timeButton.className =
      "px-3 py-2 text-xs font-bold border border-pink-100 rounded-xl bg-white hover:bg-pink-50 transition";

    // Mengecek apakah jam sudah lewat atau terlalu dekat jika tanggalnya hari ini
    const isPastTime =
      selectedBookingDate === todayKey && hour <= now.getHours() + 1;

    // Mengecek apakah jam sudah terisi booking
    let isBookedTime = false;

    if (
      selectedBookingDate &&
      typeof bookedSchedules !== "undefined" &&
      bookedSchedules[selectedBookingDate]
    ) {
      isBookedTime = bookedSchedules[selectedBookingDate].some((item) => {
        return item.startsWith(time);
      });
    }

    // Menonaktifkan jam jika sudah lewat atau sudah dibooking
    if (isPastTime || isBookedTime) {
      timeButton.disabled = true;
      timeButton.className =
        "px-3 py-2 text-xs font-bold border border-gray-100 rounded-xl bg-gray-100 text-gray-300 cursor-not-allowed";

      if (isPastTime) {
        timeButton.title = "Jam sudah lewat atau terlalu dekat";
      }

      if (isBookedTime) {
        timeButton.title = "Jam sudah terisi booking";
      }
    }

    // Memberi tanda pada jam yang sedang dipilih
    if (selectedBookingTime === time) {
      timeButton.classList.add("time-selected");
    }

    // Memilih jam booking
    timeButton.onclick = function () {
      selectedBookingTime = time;

      const summaryTime = document.getElementById("summary-time");

      if (summaryTime) {
        summaryTime.textContent = time;
      }

      renderTimeSlots();
      updateCartUI();
    };

    timeSlots.appendChild(timeButton);
  }
}

// KERANJANG BOOKING
// Menambahkan layanan ke keranjang
function addToCart(id, name, price, duration) {
  const existingItem = cart.find((item) => item.id === id);

  if (existingItem) {
    showToast("Layanan sudah ada di keranjang");
    return;
  }

  cart.push({
    id: id,
    name: name,
    price: Number(price),
    duration: Number(duration),
  });

  updateCartUI();
  showToast("Layanan ditambahkan");
}

// Menghapus layanan dari keranjang
function removeFromCart(id) {
  cart = cart.filter((item) => item.id !== id);

  updateCartUI();
  showToast("Layanan dihapus");
}

// Menghitung total harga
function getTotalPrice() {
  return cart.reduce((total, item) => total + Number(item.price), 0);
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
                <p class="text-sm font-bold text-pink-600 mt-1">${formatRupiah(item.price)}</p>
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
    cartTotalPrice.textContent = formatRupiah(totalPrice);
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
  const totalDuration = getTotalDuration();

  // Mengisi informasi modal
  if (modalDate) {
    modalDate.textContent = formattedDate;
  }

  if (modalTime) {
    modalTime.textContent = selectedBookingTime;
  }

  if (modalTotalPrice) {
    modalTotalPrice.textContent = formatRupiah(totalPrice);
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
                    ${formatRupiah(item.price)}
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
  renderCalendar();
  renderTimeSlots();
  updateCartUI();
  showSection("layanan");
});
