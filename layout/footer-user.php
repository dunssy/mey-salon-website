<!-- Toast pesan -->
<div 
    id="toast" 
    class="fixed bottom-24 sm:bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-4 sm:px-5 py-3 rounded-2xl text-xs sm:text-sm font-semibold shadow-xl hidden z-[120] text-center"
>
    Pesan
</div>

<!-- Data booking dari PHP ke JavaScript -->
<script>
    // Menyimpan data jadwal booking dari PHP
    const bookedSchedules = <?= json_encode($jadwal_booking ?? [], JSON_UNESCAPED_UNICODE); ?>;
</script>

<!-- Script user booking -->
<script src="../layout/js/booking-script.js"></script>

</body>
</html>
