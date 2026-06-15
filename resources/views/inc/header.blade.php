<header class="bg-white border-b border-gray-100 h-20 flex justify-between items-center px-8 sticky top-0 z-30">
    <div>
        <h2 class="text-xl font-black text-gray-800 tracking-tight">
            @yield('title', 'Sistem Absensi')
        </h2>
    </div>

    <div class="flex items-center gap-4">
        

        <div class="hidden sm:flex sm:items-center">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center gap-3 focus:outline-none transition duration-150 ease-in-out">
                        <div class="text-right hidden md:block">
                            <p class="text-sm font-black text-gray-800 leading-none">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest mt-1">{{ Auth::user()->role }}</p>
                        </div>
                        
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mt-2 text-blue-600 font-black border border-blue-100 overflow-hidden">
                            @if(Auth::user()->photo)
                                <img src="{{ asset('storage/'. Auth::user()->photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr(Auth::user()->name, 0, 1) }}
                            @endif
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        {{ __('Profile Settings') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>