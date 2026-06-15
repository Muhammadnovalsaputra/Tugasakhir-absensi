<aside
    
    :class="collapsed ? 'w-[68px]' : 'w-64'"
    class="bg-blue-600 text-white flex-shrink-0 hidden md:flex flex-col shadow-xl sticky top-0 h-screen z-50 transition-all duration-300 ease-in-out overflow-hidden"
>
    {{-- ===== HEADER ===== --}}
    <div class="flex items-center justify-between px-4 py-5 border-b border-blue-500/50 flex-shrink-0">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
            </div>
            <div x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-100"
                 x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity duration-100" x-transition:leave-end="opacity-0"
                 class="overflow-hidden">
                <h1 class="text-sm font-bold tracking-wide leading-tight whitespace-nowrap">Sistem Absensi</h1>
                <p class="text-[10px] text-blue-200 font-light whitespace-nowrap">CV SINAR YAFIQ KAMIL TEHNIK</p>
            </div>
        </div>

        {{-- Tombol Toggle --}}
        <button @click="collapsed = !collapsed"
                class="flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg bg-blue-500/50 hover:bg-blue-500 transition duration-200 ml-1">
            <svg :class="collapsed ? 'rotate-180' : ''"
                 class="w-4 h-4 text-white transition-transform duration-300"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7M18 19l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    
    <nav class="flex-1 px-2 py-4 space-y-0.5 overflow-y-auto overflow-x-hidden">


        <p x-show="!collapsed" class="text-[9px] uppercase font-bold text-blue-200 px-3 pb-1 pt-1 tracking-widest whitespace-nowrap">
            Main Menu
        </p>

        {{-- ✅ FIX: route('dashboard') → route('pimpinan.dashboard') --}}
        <a href="{{ route('pimpinan.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200 group
                  {{ request()->routeIs('pimpinan.dashboard') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'hover:bg-blue-500 text-white' }}"
           :class="collapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="text-sm whitespace-nowrap">Dashboard</span>
        </a>

        <a href="{{ route('pimpinan.pengajuanCuti.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200
                  {{ request()->routeIs('pimpinan.pengajuanCuti.*') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'hover:bg-blue-500 text-white' }}"
           :class="collapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="text-sm whitespace-nowrap">Pengajuan Cuti</span>
        </a>

        
        <div class="pt-3">
            <p x-show="!collapsed" class="text-[9px] uppercase font-bold text-blue-200 px-3 pb-1 tracking-widest whitespace-nowrap">
                Laporan
            </p>
        </div>

        <a href="{{ route('pimpinan.rekapAbsensi.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200
                  {{ request()->routeIs('pimpinan.rekapAbsensi.*') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'hover:bg-blue-500 text-white' }}"
           :class="collapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="text-sm whitespace-nowrap">Rekap Absensi</span>
        </a>

        <div class="pt-3">
            <p x-show="!collapsed" class="text-[9px] uppercase font-bold text-blue-200 px-3 pb-1 tracking-widest whitespace-nowrap">
                Absensi
            </p>
        </div>

       
        @php 
            // Mengambil data count sekali saja di atas agar query efisien
            $pendingCorrections = \App\Models\AttendanceCorrection::where('status', 'Pending')->count(); 
        @endphp

        <a href="{{ route('pimpinan.koreksiAbsen.index') }}"
        class="flex items-center gap-3 px-3.5 py-3 rounded-xl transition-all duration-200 group relative
                {{ request()->routeIs('pimpinan.koreksiAbsen.*') 
                    ? 'bg-white text-indigo-600 shadow-sm shadow-indigo-100 font-semibold' 
                    : 'hover:bg-blue-500 text-800 text-white/80 hover:text-white' }}"
        :class="collapsed ? 'justify-center' : 'justify-between'">
            
            <div class="flex items-center gap-3">
                {{-- Sisi Ikon --}}
                <div class="relative flex-shrink-0 flex items-center justify-center">
                    <svg class="w-5 h-5 transition-transform duration-200 group-hover:scale-105" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    
                    {{-- Badge Bulat Kecil (Hanya muncul saat SIDEBAR MENGECIL & ada pending) --}}
                    @if($pendingCorrections > 0)
                        <span x-show="collapsed" 
                            class="absolute -top-1 -right-1 bg-rose-500 rounded-full w-2.5 h-2.5 ring-2 {{ request()->routeIs('pimpinan.koreksiAbsen.*') ? 'ring-white' : 'ring-slate-900 group-hover:ring-slate-800' }} flex items-center justify-center">
                            <span class="absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75 animate-ping"></span>
                        </span>
                    @endif
                </div>
                
                {{-- Sisi Teks Menu --}}
                <span x-show="!collapsed" 
                    x-transition:enter="transition-opacity duration-200 delay-75"
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-75" 
                    x-transition:leave-end="opacity-0"
                    class="text-sm whitespace-nowrap tracking-wide">
                    Validasi Absensi
                </span>
            </div>

            {{-- Kapsul Notifikasi Kanan (Hanya muncul saat SIDEBAR TERBUKA & ada pending) --}}
            @if($pendingCorrections > 0)
                <div x-show="!collapsed"
                    x-transition:enter="transition-opacity duration-200 delay-100"
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100"
                    class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center font-bold text-[10px] tracking-wider px-2 py-0.5 rounded-full min-w-[20px] h-5 leading-none transition-colors duration-200
                                {{ request()->routeIs('pimpinan.koreksiAbsen.*') 
                                    ? 'bg-indigo-50 text-indigo-600' 
                                    : 'bg-rose-500 text-white shadow-sm' }}">
                        {{ $pendingCorrections }}
                    </span>
                </div>
            @endif
        </a>

        {{-- Grup: Jam Absen --}}
        <div class="pt-3">
            <p x-show="!collapsed" class="text-[9px] uppercase font-bold text-blue-200 px-3 pb-1 tracking-widest whitespace-nowrap">
                Jam Absen
            </p>
        </div>

        <a href="{{ route('pimpinan.settingAbsensi.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200
                  {{ request()->routeIs('pimpinan.settingAbsensi.*') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'hover:bg-blue-500 text-white' }}"
           :class="collapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="text-sm whitespace-nowrap">Setting Absensi</span>
        </a>

        {{-- Grup: Kelola Akun --}}
        <div class="pt-3">
            <p x-show="!collapsed" class="text-[9px] uppercase font-bold text-blue-200 px-3 pb-1 tracking-widest whitespace-nowrap">
                Kelola Akun
            </p>
        </div>

        {{-- ✅ FIX: routeIs('kelolaKaryawan.*') → routeIs('pimpinan.kelolaKaryawan.*') --}}
        <a href="{{ route('pimpinan.kelolaKaryawan.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200
                  {{ request()->routeIs('pimpinan.kelolaKaryawan.*') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'hover:bg-blue-500 text-white' }}"
           :class="collapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="text-sm whitespace-nowrap">Kelola Akun User</span>
        </a>

        {{-- Grup: Pengaturan --}}
        <div class="pt-3">
            <p x-show="!collapsed" class="text-[9px] uppercase font-bold text-blue-200 px-3 pb-1 tracking-widest whitespace-nowrap">
                Pengaturan
            </p>
        </div>

        <a href="{{ route('profile.edit') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition duration-200
                  {{ request()->routeIs('profile.edit') ? 'bg-white text-blue-600 shadow-md font-semibold' : 'hover:bg-blue-500 text-white' }}"
           :class="collapsed ? 'justify-center' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                  x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                  x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                  class="text-sm whitespace-nowrap">Profile Settings</span>
        </a>

    </nav>

    {{-- ===== LOGOUT ===== --}}
    <div class="px-2 py-3 border-t border-blue-500/50 flex-shrink-0">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 px-3 py-2.5 w-full rounded-xl transition duration-200 text-blue-100 hover:bg-red-500 hover:text-white"
                    :class="collapsed ? 'justify-center' : ''">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                <span x-show="!collapsed" x-transition:enter="transition-opacity duration-200 delay-75"
                      x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                      x-transition:leave="transition-opacity duration-75" x-transition:leave-end="opacity-0"
                      class="text-sm whitespace-nowrap">Keluar Aplikasi</span>
            </button>
        </form>
    </div>
</aside>