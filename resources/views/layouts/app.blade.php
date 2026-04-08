<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">
        {{-- Sidebar hanya untuk Pimpinan --}}
        @if(Auth::check() && Auth::user()->role == 'Pimpinan')
        <aside class="w-64 bg-blue-600 text-white hidden md:block flex-shrink-0 min-h-screen">
            @include('inc.sidebar')
        </aside>
        @endif

        <div class="flex-1 flex flex-col min-w-0">
            {{-- Header hanya untuk Pimpinan --}}
            @if(Auth::check() && Auth::user()->role == 'Pimpinan')
                @include('inc.header')
            @endif

            {{-- Tambahkan pb-20 jika bukan pimpinan agar konten tidak tertutup footer navbar --}}
            <main class="flex-1 {{ Auth::check() && Auth::user()->role != 'Pimpinan' ? 'pb-20' : '' }}">
                {{ $slot }}
            </main>

            {{-- Footer Navbar hanya untuk Karyawan (Bukan Pimpinan) --}}
            @if(Auth::check() && Auth::user()->role != 'Pimpinan')
                @include('inc.bottom')
            @endif
        </div>
    </div>
</body>
</html>