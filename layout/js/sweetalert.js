
    // SWEETALERT LOGOUT
    function confirmLogout(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../logout.php';
            }
        });
    }

 
    function konfirmasiBooking(event) {
        event.preventDefault(); 

        swal.fire({
            title: 'konfirmasi booking pelanggan ini?',
            icon: 'question',
            buttons: true,
            showCancelButton: true,
            confirmButtonColor: '#104cff',
            cancelButtonColor: '#c8c7c7',

        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with booking confirmation
                swal.fire("Booking berhasil dikonfirmasi!", {
                    icon: "success",
                });
            }
        });
    }

    function userBooking(event){
        event.preventDefault();
        swal.fire({
            title: 'Booking berhasil dibuat! Silakan tunggu admin mengecek bukti pembayaran QRIS.',
            icon: 'success',
            buttons: true,
            confirmButtonColor: '#104cff',
        });
    }

    function userCancelBooking(event){
        event.preventDefault();
        swal.fire({
            title: 'Apakah Anda yakin ingin membatalkan booking ini?',
            icon: 'question',
            buttons: true,
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#c8c7c7',
        }).then((result) => {
           if (result.isConfirmed) {
                // Proceed with booking confirmation
                swal.fire("Booking berhasil di batalkan!", {
                    icon: "success",
                });
            }
        });
    }