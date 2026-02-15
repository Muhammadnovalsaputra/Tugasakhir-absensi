<aside class="w-64 bg-blue-600 text-white flex-shrink-0 hidden md:flex flex-col shadow-xl">
    <div class="p-6 border-b border-blue-500/50">
        <h1 class="text-xl font-bold tracking-wider">Sistem Absensi</h1>
        <p class="text-xs opacity-75 font-light">CV Sinar Yafiq KT</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 mt-6">
        <p class="text-[10px] uppercase font-bold text-blue-200 mb-2 px-3 tracking-widest">Main Menu</p>
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 p-3 rounded-xl transition duration-200 {{ request()->routeIs('dashboard') ? 'bg-white text-blue-600 shadow-md font-bold' : 'hover:bg-blue-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard
        </a>

        <a href="{{ route('pimpinan.pengajuanCuti.index') }}" 
           class="flex items-center gap-3 p-3 rounded-xl transition duration-200 {{ request()->routeIs('pimpinan.pengajuanCuti.*') ? 'bg-white text-blue-600 shadow-md font-bold' : 'hover:bg-blue-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            Pengajuan Cuti
        </a>

        <a href="{{ route('kelolaKaryawan.index') }}" 
        class="flex items-center gap-3 p-3 rounded-xl transition duration-200 {{ request()->routeIs('kelolaKaryawan.*') ? 'bg-white text-blue-600 shadow-md font-bold' : 'hover:bg-blue-500' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
         <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
        Kelola Karyawan
        </a>

        <p class="text-[10px] uppercase font-bold text-blue-200 mt-6 mb-2 px-3 tracking-widest">Laporan</p>
        
        <a href="#" class="flex items-center gap-3 p-3 hover:bg-blue-500 rounded-xl transition duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Rekap Absensi
        </a>
    </nav>

    <div class="p-4 border-t border-blue-500">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-3 p-3 w-full hover:bg-red-500 hover:text-white rounded-xl transition duration-200 text-blue-100 text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                Keluar Aplikasi
            </button>
        </form>
    </div>
</aside>