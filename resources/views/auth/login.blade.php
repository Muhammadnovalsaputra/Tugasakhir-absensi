<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Sinar Yafiq" class="w-24 h-24 object-contain mb-2">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Sinar Yafiq Kamil Teknik</h2>
                <p class="text-sm text-gray-600">Sistem Absensi Management</p>
            </div>

            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
             @endif

            @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
             @endif

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label class="block font-medium text-sm text-gray-700">Email</label>
                    <input type="email" name="email" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required autofocus />
                </div>

                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700">Password</label>
                    <input type="password" name="password" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>