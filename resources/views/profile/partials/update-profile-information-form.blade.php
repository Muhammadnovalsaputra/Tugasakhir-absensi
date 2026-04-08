<section>
    <header class="mb-6">
        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider">
            {{ __('Informasi Profil') }}
        </h3>
        <p class="mt-1 text-xs text-gray-500">
            {{ __("Perbarui nama akun dan alamat email Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex flex-col items-center bg-gray-50 p-4 rounded-3xl border border-gray-100">
            <div class="relative group">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-sm bg-white">
                    @if($user->photo)
                        <img id="photo-preview" src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover">
                    @else
                        <img id="photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff" class="w-full h-full object-cover">
                    @endif
                </div>
                <label for="photo" class="absolute bottom-0 right-0 bg-blue-600 text-white p-2 rounded-full shadow-lg cursor-pointer active:scale-90 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </label>
            </div>
            <input id="photo" name="photo" type="file" class="hidden" accept="image/*" />
            <p class="mt-2 text-[10px] text-gray-400 font-medium italic">Klik ikon kamera untuk ganti foto</p>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div>
            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Nama Lengkap</label>
            <input id="name" name="name" type="text" 
                class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 shadow-sm" 
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Alamat Email</label>
            <input id="email" name="email" type="email" 
                class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500 py-3 px-4 shadow-sm" 
                value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-orange-50 rounded-xl border border-orange-100">
                    <p class="text-xs text-orange-700">
                        {{ __('Email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="font-bold underline ml-1">Kirim ulang.</button>
                    </p>
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-100 active:scale-95 transition-all">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" 
                    class="fixed bottom-24 left-1/2 -translate-x-1/2 bg-gray-800 text-white px-4 py-2 rounded-full text-xs shadow-xl z-50">
                    {{ __('Berhasil disimpan!') }}
                </div>
            @endif
        </div>
    </form>
</section>

<script>
    document.getElementById('photo').onchange = evt => {
        const [file] = evt.target.files;
        if (file) {
            document.getElementById('photo-preview').src = URL.createObjectURL(file);
        }
    }
</script>