<section class="max-w-xl mx-auto bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    {{-- Header Section dengan Background Gradasi Halus --}}
    <header class="p-6 bg-slate-50 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <div>
            <h3 class="text-base font-bold text-slate-800 tracking-wide">
                {{ __('Informasi Profil') }}
            </h3>
            <p class="mt-0.5 text-xs text-slate-400">
                {{ __("Perbarui nama akun, alamat email, dan foto profil Anda.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="p-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- AREA UPLOAD FOTO PROFIL --}}
        <div class="flex flex-col items-center bg-slate-50/70 p-6 rounded-2xl border border-slate-100/80 relative group">
            <div class="relative">
                {{-- Lingkaran Foto --}}
                <div class="w-28 h-28 rounded-full overflow-hidden border-4 border-white shadow-md bg-white ring-1 ring-slate-100 transition-transform duration-300 group-hover:scale-105">
                    @if($user->photo)
                        <img id="photo-preview" src="{{ asset('storage/' . $user->photo) }}" class="w-full h-full object-cover">
                    @else
                        <img id="photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=EEF2FF&color=4F46E5&bold=true" class="w-full h-full object-cover">
                    @endif
                </div>
                
                {{-- Tombol Kamera Hover / Trigger --}}
                <label for="photo" class="absolute bottom-1 right-1 bg-indigo-600 text-white p-2.5 rounded-full shadow-md cursor-pointer hover:bg-indigo-700 active:scale-90 transition-all duration-200 border-2 border-white">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z"/>
                    </svg>
                </label>
            </div>
            
            <input id="photo" name="photo" type="file" class="hidden" accept="image/*" />
            <p class="mt-3 text-[11px] text-slate-400 font-medium tracking-wide">Klik ikon kamera untuk mengganti foto profil</p>
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        {{-- INPUT: NAMA LENGKAP --}}
        <div class="space-y-1.5">
            <label for="name" class="text-xs font-bold text-slate-500 tracking-wider uppercase ml-1">Nama Lengkap</label>
            <div class="relative">
                <input id="name" name="name" type="text" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 shadow-sm transition-all duration-200 outline-none" 
                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            </div>
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        {{-- INPUT: EMAIL --}}
        <div class="space-y-1.5">
            <label for="email" class="text-xs font-bold text-slate-500 tracking-wider uppercase ml-1">Alamat Email</label>
            <div class="relative">
                <input id="email" name="email" type="email" 
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 py-3 px-4 shadow-sm transition-all duration-200 outline-none" 
                    value="{{ old('email', $user->email) }}" required autocomplete="username" />
            </div>
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3.5 bg-amber-50 rounded-xl border border-amber-100 flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <p class="text-xs text-amber-700 leading-relaxed">
                        {{ __('Email Anda belum diverifikasi.') }}
                        <button form="send-verification" class="font-bold underline hover:text-amber-800 transition ml-0.5">Kirim ulang tautan verifikasi.</button>
                    </p>
                </div>
            @endif
        </div>

        {{-- TOMBOL SUBMIT --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 rounded-xl shadow-md hover:shadow-indigo-100 active:scale-[0.98] transition-all duration-200">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" x-show="show" x-transition.out.opacity.duration.500ms x-init="setTimeout(() => show = false, 3000)" 
                    class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-slate-900/95 backdrop-blur text-white px-5 py-2.5 rounded-full text-xs font-semibold shadow-2xl z-50 flex items-center gap-2 border border-slate-800">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                    </svg>
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