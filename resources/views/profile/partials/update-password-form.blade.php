<section>
    <header class="mb-6">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
            {{ __('Keamanan Akun') }}
        </h3>
        <p class="mt-1 text-xs text-gray-500">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">{{ __('Kata Sandi Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" 
                class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 shadow-sm" 
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">{{ __('Kata Sandi Baru') }}</label>
            <input id="update_password_password" name="password" type="password" 
                class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 shadow-sm" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 shadow-sm" 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 active:scale-95 transition-all">
                {{ __('Perbarui Kata Sandi') }}
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" 
                    class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full text-xs shadow-xl z-50">
                    {{ __('Password berhasil diubah!') }}
                </div>
            @endif
        </div>
    </form>
</section>