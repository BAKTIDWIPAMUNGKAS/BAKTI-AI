$(document).ready(function() {
    $('#contactForm').on('submit', function(event) {
        event.preventDefault(); // Hentikan pengiriman sementara untuk validasi

        let isValid = true;
        clearErrors(); // Hapus pesan error sebelumnya

        // Validasi Nama Lengkap
        const name = $('#name').val().trim();
        const namePattern = /^[A-Za-z\s]+$/; // Hanya huruf dan spasi
        if (name === '') {
            showError($('#name'), 'Nama lengkap wajib diisi.');
            isValid = false;
        } else if (!namePattern.test(name)) {
            showError($('#name'), 'Nama lengkap hanya boleh mengandung huruf dan spasi.');
            isValid = false;
        } else if (name.length > 50) {
            showError($('#name'), 'Nama lengkap maksimal 50 karakter.');
            isValid = false;
        }

        // Validasi Email
        const email = $('#email').val().trim();
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email === '') {
            showError($('#email'), 'Email wajib diisi.');
            isValid = false;
        } else if (!emailPattern.test(email)) {
            showError($('#email'), 'Email tidak valid.');
            isValid = false;
        }

        // Validasi Nomor Handphone
        const phone = $('#phone').val().trim();
        const phonePattern = /^[0-9]{10,13}$/; // Hanya angka, 10-13 digit
        if (phone === '') {
            showError($('#phone'), 'Nomor handphone wajib diisi.');
            isValid = false;
        } else if (!phonePattern.test(phone)) {
            showError($('#phone'), 'Nomor handphone tidak valid. Harus 10-13 digit angka.');
            isValid = false;
        }

        // Validasi Pesan
        const message = $('#message').val().trim();
        if (message === '') {
            showError($('#message'), 'Pesan wajib diisi.');
            isValid = false;
        } else if (message.length > 500) {
            showError($('#message'), 'Pesan maksimal 500 karakter.');
            isValid = false;
        }

        // Cek parameter URL untuk menampilkan pesan sukses
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');

        // Jika semua validasi berhasil, formulir dapat dikirim
        if (isValid) {
            alert('Pesan Anda telah berhasil dikirim!');
            // Hapus parameter dari URL tanpa reload
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    });

    // Fungsi untuk menampilkan pesan error
    function showError(input, message) {
        const errorElement = $('<div>').addClass('error').text(message);
        input.after(errorElement);
    }

    // Fungsi untuk menghapus pesan error
    function clearErrors() {
        $('.error').remove();
    }
});

function toggleMenu() {
    const navLinks = document.querySelector('.nav-links');
    navLinks.classList.toggle('active');
}