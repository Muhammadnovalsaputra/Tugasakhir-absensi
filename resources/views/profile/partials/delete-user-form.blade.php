<section class="space-y-6">
    <header>
        <h3 class="text-sm font-bold text-red-600 uppercase tracking-wider">
            {{ __('Bahaya: Hapus Akun') }}
        </h3>

        <p class="mt-1 text-xs text-gray-500 leading-relaxed">
            {{ __('Setelah akun dihapus, semua data akan hilang permanen. Harap cadangkan data penting sebelum melanjutkan.') }}
        </p>
    </header>

    <button 
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="w-full bg-red-50 text-red-600 font-bold py-4 rounded-2xl border border-red-100 active:scale-95 transition-all text-sm"
    >
        {{ __('Hapus Akun Saya') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 bg-white rounded-[2rem]">
            @csrf
            @method('delete')

            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h2 class="text-lg font-bold text-gray-800">
                    {{ __('Konfirmasi Hapus Akun') }}
                </h2>
                <p class="mt-2 text-xs text-gray-500">
                    {{ __('Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.') }}
                </p>
            </div>

            <div class="mt-4">
                <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Kata Sandi Anda</label>
                <input 
                    id="password"
                    name="password"
                    type="password"
                    class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-red-500 py-3 px-4 shadow-sm"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs" />
            </div>

            <div class="mt-8 flex flex-col gap-3">
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-100 active:scale-95 transition-all">
                    {{ __('Ya, Hapus Permanen') }}
                </button>
                
                <button type="button" x-on:click="$dispatch('close')" class="w-full bg-gray-100 text-gray-500 font-bold py-3 rounded-2xl text-sm">
                    {{ __('Batal') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>