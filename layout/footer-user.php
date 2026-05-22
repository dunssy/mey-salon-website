<!-- Toast pesan -->
<div id="toast" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-5 py-3 rounded-2xl text-sm font-semibold shadow-xl hidden z-[120]">
    Pesan
</div>

<!-- Data booking dari PHP ke JavaScript -->
<script>
    const bookedSchedules = <?= json_encode($jadwal_booking ?? []); ?>;
</script>

<!-- Memanggil script user booking -->
<script src="../layout/js/booking-script.js"></script>

</body>
</html>