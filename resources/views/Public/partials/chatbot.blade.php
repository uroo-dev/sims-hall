{{--
    Partial ini HANYA menyertakan widget Nanya AI.

    Global modal (`#global-modal`, `#modal-content`, `#modal-title`,
    `#modal-body`) TIDAK lagi disertakan di sini. Layout
    `Public/layout/app.blade.php` sudah menyediakan modal tersebut beserta
    logika openModal()/closeModal()-nya, sehingga menyalinnya di partial
    akan menghasilkan dua elemen dengan `id` yang sama. Karena
    `document.getElementById()` hanya mengambil elemen pertama, tombol
    yang Menggunakan ID itu akan selalu controlling modal yang salah.
--}}
<!-- CHATBOT "NANYA AI" -->
<x-chatbot />