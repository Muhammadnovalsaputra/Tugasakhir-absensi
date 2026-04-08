<aside class="w-64 bg-blue-600 text-white flex-shrink-0 hidden md:flex flex-col shadow-xl sticky top-0 h-screen z-50">
    <div class="p-6 border-b border-blue-500/50">
        <h1 class="text-xl font-bold tracking-wider">Sistem Absensi</h1>
        <p class="text-xs opacity-75 font-light">CV SINAR YAFIQ KAMIL TEHNIK</p>
    </div>

    <nav class="flex-1 px-4 space-y-1 mt-6">
        <p class="text-[10px] uppercase font-bold text-blue-200 mb-2 px-3 tracking-widest">Main Menu</p>
        
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 p-3 rounded-xl transition duration-200 {{ request()->routeIs('pimpinan.dashboard') ? 'bg-white text-blue-600 shadow-md font-bold' : 'hover:bg-blue-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
            Dashboard
        </a>

        <a href="{{ route('pimpinan.pengajuanCuti.index') }}" 
           class="flex items-center gap-3 p-3 rounded-xl transition duration-200 {{ request()->routeIs('pimpinan.pengajuanCuti.*') ? 'bg-white text-blue-600 shadow-md font-bold' : 'hover:bg-blue-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            Pengajuan Cuti
        </a>

        <p class="text-[10px] uppercase font-bold text-blue-200 mt-6 mb-2 px-3 tracking-widest">Laporan</p>
        
        <a href="{{route('pimpinan.rekapAbsensi.index')}}"
         class="flex items-center gap-3 p-3 rounded-xl transition duration-200 {{ request()->routeIs('pimpinan.rekapAbsensi.*') ? 'bg-white text-blue-600 shadow-md font-bold' : 'hover:bg-blue-500' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            Rekap Absensi
        </a>

    <div x-data="{ open: false }" class="relative">
    <p class="text-[10px] uppercase font-bold text-blue-200 mt-6 mb-2 px-3 tracking-widest">Pengaturan</p>

    <button @click="open = !open" @click.away="open = false"
       class="flex items-center justify-between w-full p-3 rounded-xl transition duration-200 hover:bg-blue-500 {{ request()->routeIs('profile.*') ? 'bg-blue text-white-600 shadow-md font-bold' : '' }}">
        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.559.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.894.149c-.424.07-.764.383-.929.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.398.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.527-.737c.25-.35.272-.806.108-1.204-.165-.397-.506-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 0 1 .12-1.45l.773-.773a1.125 1.125 0 0 1 1.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
            <span>Pengaturan</span>
        </div>
        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
    </button>

    <div x-show="open" 
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 scale-95 translate-x-[-10px]"
     x-transition:enter-end="opacity-100 scale-100 translate-x-0"
     class="absolute left-full top-0 ml-2 w-56 bg-blue-700 rounded-2xl shadow-2xl border border-blue-500 py-2 z-50 text-white">
    
    <p class="px-4 py-2 text-xs font-bold text-blue-300 uppercase tracking-widest border-b border-blue-500/50 mb-1">Pengaturan</p>
    
    <a href="{{ route('profile.edit') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-xl transition text-sm {{ request()->routeIs('profile.edit') ? 'bg-white text-blue-600 font-bold' : 'hover:bg-blue-500 text-white' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        Profile Settings
    </a>

    <a href="{{ route('pimpinan.settingAbsensi.index') }}" 
       class="flex items-center gap-3 px-4 py-3 transition text-sm rounded-xl {{ request()->routeIs('pimpinan.settingAbsensi.*') ? 'bg-white text-blue-600 font-bold' : 'hover:bg-blue-500 text-white' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        Setting Absensi
    </a>    

    <a href="{{ route('kelolaKaryawan.index') }}" 
       class="flex items-center gap-3 px-4 py-3 transition text-sm rounded-xl {{ request()->routeIs('kelolaKaryawan.*') ? 'bg-white text-blue-600 font-bold' : 'hover:bg-blue-500 text-white' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
        </svg>
        Kelola Karyawan
    </a>
</div>
</div>
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