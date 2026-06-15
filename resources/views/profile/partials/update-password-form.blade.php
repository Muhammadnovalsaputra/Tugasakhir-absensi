<section class="max-w-xl mx-auto bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mt-6">
    {{-- Header Section --}}
    <header class="p-6 bg-slate-50 border-b border-slate-100">
        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
            {{ __('Keamanan Akun') }}
        </h3>
        <p class="mt-1 text-xs text-slate-400">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang kuat dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="p-6 space-y-5">
        @csrf
        @method('put')

        <div class="space-y-1.5">
            <label for="update_password_current_password" class="text-[11px] font-bold text-slate-500 tracking-wider uppercase ml-1">
                {{ __('Kata Sandi Saat Ini') }}
            </label>
            <div class="relative">
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 shadow-sm transition-all duration-200 outline-none" 
                    autocomplete="current-password" placeholder="••••••••" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>
        <div class="space-y-1.5">
            <label for="update_password_password" class="text-[11px] font-bold text-slate-500 tracking-wider uppercase ml-1">
                {{ __('Kata Sandi Baru') }}
            </label>
            <div class="relative">
                <input id="update_password_password" name="password" type="password" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 shadow-sm transition-all duration-200 outline-none" 
                    autocomplete="new-password" placeholder="Min. 8 karakter kombinasi" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>
        <div class="space-y-1.5">
            <label for="update_password_password_confirmation" class="text-[11px] font-bold text-slate-500 tracking-wider uppercase ml-1">
                {{ __('Konfirmasi Kata Sandi Baru') }}
            </label>
            <div class="relative">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 shadow-sm transition-all duration-200 outline-none" 
                    autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-indigo-100 active:scale-[0.98] transition-all duration-200">
                {{ __('Perbarui Kata Sandi') }}
            </button>
            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" x-show="show" x-transition.out.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)" 
                    class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-slate-900/95 backdrop-blur text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow-2xl z-50 flex items-center gap-2 border border-slate-800">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
                    {{ __('Password berhasil diubah!') }}
                </div>
            @endif
        </div>
    </form>
</section>