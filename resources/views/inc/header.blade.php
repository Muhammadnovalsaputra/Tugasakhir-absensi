<header class="bg-white shadow-sm p-4 flex justify-between items-center px-8">
    <div>
        <h2 class="text-xl font-bold text-gray-800">
            @yield('title', 'Dashboard')
        </h2>
        <p class="text-sm text-gray-500">
            {{ now()->translatedFormat('l, d F Y') }} | <span id="clock">{{ now()->format('H:i') }}</span>
        </p>
    </div>
    <div class="flex items-center gap-4">
        <button class="text-gray-400 hover:text-gray-600 relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500 ring-2 ring-white"></span>
        </button>
        <div class="flex items-center gap-3 border-l pl-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-700">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 uppercase">{{ Auth::user()->role ?? 'Pimpinan' }}</p>
            </div>
            <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold shadow-inner">
                @if(Auth::user()->photo)
                <img src="{{asset('storage/'. Auth::user()->photo)}}" alt="profile"
                class="w-full h-full object-cover rounded-full">
                @else
                {{substr(Auth::user()->name, 0, 1)}}
                @endif
            </div>
        </div>
    </div>
</header>