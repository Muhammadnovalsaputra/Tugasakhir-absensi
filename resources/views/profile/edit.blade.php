<x-app-layout>
    {{-- Header Modul dengan Background Lembut --}}
    <div class="bg-white border-b border-slate-100 py-6 mb-8 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
        </div>
    </div>

    {{-- Layout Utama --}}
    <div class="pb-16 bg-slate-50/50 min-h-[calc(100vh-120px)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Grid Wrapper: Menggunakan 2 Kolom di Desktop --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- Kolom Kiri (Lebih Lebar): Form Utama --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- 1. Informasi Profil --}}
                    <div class="transition-all duration-200">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    {{-- 2. Update Password --}}
                    <div class="transition-all duration-200">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Kolom Kanan (Lebih Ramping): Zona Bahaya / Edukasi Karyawan --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- 3. Hapus Akun (Danger Zone) --}}
                    <div class="sticky top-24">
                        @include('profile.partials.delete-user-form')
                        
                        {{-- Widget Tambahan Edukasi Keamanan --}}
                        <div class="mt-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm flex gap-3">
                            <span class="text-xl">💡</span>
                            <div>
                                <h4 class="text-xs font-bold text-slate-700">Tips Keamanan:</h4>
                                <p class="text-[11px] text-slate-400 leading-relaxed mt-1">
                                    Jangan pernah memberitahukan kredensial login atau token absensi Anda kepada siapapun, termasuk administrator sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>