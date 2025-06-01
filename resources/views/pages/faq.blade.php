@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FAQ - TataFix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>

        header h1 {
            color: #1A6A96;
        }

        /* Mengubah warna dan posisi tanda panah */
        details summary {
            color: #1A6A96;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.10rem;
        }

        details p {
            font-size: 1.125rem; 
        }

        details summary::marker {
            color: #1A6A96;
        }

        /* Menambah jarak antara tanda panah dan teks */
        details summary::after {
            content: "▼";  /* Menambahkan tanda panah */
            font-size: 16px;
            margin-left: 10px;
            color: #1A6A96;
        }

        details[open] summary::after {
            content: "▲";  /* Mengubah tanda panah saat accordion terbuka */
        }
    </style>
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="py-4 px-6">
        <h1 class="text-2xl font-bold">(FAQ) Frequently Asked Questions</h1>
    </header>

    <main class="max-w-4xl mx-auto mt-8 px-4">
        <div class="space-y-4">
            <!-- Accordion item 1 -->
            <details class="bg-white p-4 rounded shadow">
                <summary class="font-semibold cursor-pointer">Apa itu TataFix?</summary>
                <p class="mt-2 text-sm text-gray-700">TataFix adalah platform marketplace yang menghubungkan Anda dengan penyedia layanan perbaikan dan perawatan rumah, 
                    mulai dari tukang hingga teknisi profesional, untuk berbagai kebutuhan ringan hingga berat</p>
            </details>

            <!-- Accordion item 2 -->
            <details class="bg-white p-4 rounded shadow">
                <summary class="font-semibold cursor-pointer">Bagaimana saya bisa memilih penyedia layanan terbaik?</summary>
                <p class="mt-2 text-sm text-gray-700">Di TataFix, Anda dapat melihat profil penyedia layanan yang berisi informasi detail seperti pengalaman, 
                    ulasan dari pelanggan sebelumnya, dan rating.</p>
            </details>

            <!-- Accordion item 3 -->
            <details class="bg-white p-4 rounded shadow">
                <summary class="font-semibold cursor-pointer">Apakah saya bisa berkomunikasi dengan penyedia layanan sebelum pekerjaan dimulai?</summary>
                <p class="mt-2 text-sm text-gray-700">Ya, setelah Anda memilih penyedia layanan, Anda dapat menggunakan fitur chat dalam aplikasi atau situs kami untuk mendiskusikan detail pekerjaan, estimasi biaya, 
                    dan kebutuhan khusus sebelum pekerjaan dimulai.</p>
            </details>

            <!-- Accordion item 4 -->
            <details class="bg-white p-4 rounded shadow">
                <summary class="font-semibold cursor-pointer">Apakah saya bisa memesan layanan untuk orang lain (misalnya, keluarga atau teman)?</summary>
                <p class="mt-2 text-sm text-gray-700">Ya, Anda bisa memesan layanan untuk orang lain dengan mengisi informasi lokasi dan detail yang diperlukan saat melakukan pemesanan.</p>
            </details>

            <!-- Accordion item 5 -->
            <details class="bg-white p-4 rounded shadow">
                <summary class="font-semibold cursor-pointer">Bagaimana sistem pembayaran di TataFix?</summary>
                <p class="mt-2 text-sm text-gray-700">Kami menyediakan berbagai metode pembayaran yang aman, termasuk kartu kredit, e-wallet, dan transfer bank.</p>
            </details>

            <!-- Accordion item 6 -->
            <details class="bg-white p-4 rounded shadow">
                <summary class="font-semibold cursor-pointer">Bagaimana cara menghubungi layanan pelanggan TataFix?</summary>
                <p class="mt-2 text-sm text-gray-700">Jika Anda memiliki pertanyaan lebih lanjut atau membutuhkan bantuan, Anda bisa menghubungi layanan pelanggan kami melalui:</p>
                        <li> Email: support@tatafix.com </li>
                        <li> Telepon: +62876543221 </li>
                        <li> Atau melalui fitur chat di aplikasi TataFix.</li>
            </details>
        </div>
    </main>
</body>
</html>
@endsection
