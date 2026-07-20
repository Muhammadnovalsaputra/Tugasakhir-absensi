<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        @laravelPWA

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">

    {{-- x-data di sini agar sidebar & konten bisa berbagi state collapsed --}}
    <div class="flex min-h-screen" x-data="{ collapsed: false }">

        {{-- Sidebar HANYA untuk Pimpinan --}}
        @if(Auth::check() && Auth::user()->role == 'Pimpinan')
        <aside
            :class="collapsed ? 'w-[68px]' : 'w-64'"
            class="bg-blue-600 text-white hidden md:flex flex-col flex-shrink-0 sticky top-0 h-screen z-50 transition-all duration-300 ease-in-out overflow-hidden"
        >
            @include('inc.sidebar')
        </aside>
        @endif

        {{-- Konten utama: flex-1 agar otomatis mengisi sisa ruang --}}
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300">

            {{-- Header HANYA untuk Pimpinan --}}
            @if(Auth::check() && Auth::user()->role == 'Pimpinan')
                @include('inc.header')
            @endif

            {{-- Padding bottom diaktifkan untuk SEMUA user selain Pimpinan --}}
            <main class="flex-1 {{ Auth::check() && Auth::user()->role != 'Pimpinan' ? 'pb-20' : '' }}">
                {{ $slot }}
            </main>

            {{-- Footer Navbar muncul untuk SEMUA user selain Pimpinan (Admin, Teknisi, Finance otomatis dapat) --}}
            @if(Auth::check() && Auth::user()->role != 'Pimpinan')
                @include('inc.bottom')
            @endif
        </div>

    </div>
    @stack('scripts')
    </body>
</html>