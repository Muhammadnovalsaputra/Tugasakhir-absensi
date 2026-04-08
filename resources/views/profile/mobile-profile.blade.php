<x-app-layout>
    <div class="bg-gray-50 min-h-screen pb-24">
        <div class="max-w-md mx-auto bg-white flex items-center px-6 py-4 shadow-sm">
            @if($currentTab !== 'main')
                <a href="{{ route('profile.app') }}" class="mr-4 text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            @endif
            <h1 class="text-xl font-bold text-gray-800">
                {{ $currentTab === 'email' ? 'Email Setting' : ($currentTab === 'account' ? 'Account Setting' : 'Profile') }}
            </h1>
        </div>

        <div class="max-w-md mx-auto px-5">
            @if($currentTab === 'main')
                <div class="flex flex-col items-center pt-8 pb-4">
                    <div class="relative inline-block">
                        <div class="w-28 h-28 rounded-full border-4 border-white shadow-md overflow-hidden bg-gray-200">
                            <img src="{{ $user->photo ? asset('storage/'.$user->photo) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}" class="w-full h-full object-cover">
                        </div>
                    </div>
                    <h2 class="mt-4 text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-xs uppercase tracking-wider font-semibold">{{ $user->role }}</p>
                </div>

                <div class="mt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Settings</h3>
                    <div class="bg-white rounded-[2rem] shadow-sm overflow-hidden border border-gray-100">
                        <a href="?tab=email" class="flex items-center justify-between p-4 hover:bg-gray-50 border-b border-gray-50 transition">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center mr-4 text-xl">📩</div>
                                <span class="font-semibold text-gray-700">Email Setting</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>

                        <a href="?tab=account" class="flex items-center justify-between p-4 hover:bg-gray-50 border-b border-gray-50 transition">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-50 rounded-full flex items-center justify-center mr-4 text-xl">🔒</div>
                                <span class="font-semibold text-gray-700">Account Security</span>
                            </div>
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-between p-4 hover:bg-red-50 transition text-red-600">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-50 rounded-full flex items-center justify-center mr-4 text-xl">🚪</div>
                                    <span class="font-semibold">Log Out</span>
                                </div>
                                <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

            @elseif($currentTab === 'email')
                <div class="mt-6 p-6 bg-white rounded-3xl shadow-sm border border-gray-100">
                    <div class="max-w-xl text-sm">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

            @elseif($currentTab === 'account')
                <div class="mt-6 space-y-6">
                    <div class="p-6 bg-white rounded-3xl shadow-sm border border-gray-100 text-sm">
                        @include('profile.partials.update-password-form')
                    </div>
                    <div class="p-6 bg-white rounded-3xl shadow-sm border border-red-50 text-sm">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('inc.bottom')
</x-app-layout>