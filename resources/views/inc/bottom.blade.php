<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 px-6 py-3 flex justify-between items-center z-50 shadow-[0_-5px_20px_rgba(0,0,0,0.05)]">
    <a href="{{ route('dashboard') }}" 
       class="flex flex-col items-center {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-400 hover:text-blue-500' }} transition">
        <svg class="w-6 h-6" fill="{{ request()->routeIs('dashboard') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
        </svg>
        <span class="text-[10px] {{ request()->routeIs('dashboard') ? 'font-bold' : 'font-medium' }} mt-1">Beranda</span>
    </a>

    <a href="{{ route('karyawan.pengajuanCuti.index') }}" 
       class="flex flex-col items-center {{ request()->routeIs('karyawan.pengajuanCuti.*') ? 'text-blue-600' : 'text-gray-400 hover:text-blue-500' }} transition">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        <span class="text-[10px] {{ request()->routeIs('karyawan.pengajuanCuti.*') ? 'font-bold' : 'font-medium' }} mt-1">Pengajuan Cuti</span>
    </a>

    <a href="{{ route('profile.app') }}" 
       class="flex flex-col items-center {{ request()->routeIs('profile.app') ? 'text-blue-600' : 'text-gray-400 hover:text-blue-500' }} transition">
        <svg class="w-6 h-6" fill="{{ request()->routeIs('profile.app') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
        <span class="text-[10px] {{ request()->routeIs('profile.app') ? 'font-bold' : 'font-medium' }} mt-1">Profile</span>
    </a>
</div>