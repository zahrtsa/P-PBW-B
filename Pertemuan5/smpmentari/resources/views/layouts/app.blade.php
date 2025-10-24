<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Kita gunakan @yield agar judul halaman bisa dinamis --}}
        <title>@yield('title', config('app.name', 'Laravel'))</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">

        {{-- Menggunakan file navigasi cerdas yang sudah kita perbaiki --}}
        @include('layouts.navigation')

        <main class="container mx-auto my-8 px-4">
            {{-- Menggunakan @yield agar kompatibel dengan halaman Home & Buku Tamu --}}
            @yield('content')
        </main>

        {{-- Menambahkan kembali footer kita yang hilang --}}
        @include('layouts.partials.footer')

    </body>
</html>