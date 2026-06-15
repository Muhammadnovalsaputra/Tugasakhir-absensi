<x-app-layout>
    {{-- Header Dashboard --}}
    <div class="bg-blue-900 text-white p-6 pb-20 rounded-b-[3rem] shadow-lg">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Formulir Pengajuan</p>
                <h2 class="text-xl font-bold">Koreksi Lupa Absen</h2>
                <p class="text-xs opacity-70">CV Sinar Yafiq Kamil Teknik</p>
            </div>
            <div class="w-10 h-10 rounded-full border border-white/30 flex items-center justify-center bg-blue-800">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
        </div>
    </div>

    {{-- Konten Utama Form --}}
    <div class="py-6 px-4 -mt-16 mb-24 max-w-md mx-auto">
        <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100">
            
            {{-- Info Alert --}}
            <div class="bg-blue-50 text-blue-800 p-4 rounded-2xl text-xs border border-blue-200 text-left mb-5">
                <span class="font-bold block text-sm mb-1 text-blue-900">ℹ️ Informasi Pengajuan</span>
                Isi formulir ini jika Anda sudah berada di kantor namun lupa melakukan absen masuk. Pengajuan akan ditinjau langsung oleh Pimpinan / Admin.
            </div>

            {{-- Validasi Error Global --}}
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-2xl text-xs border border-red-100 mb-5">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('karyawan.koreksiAbsen.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Input Jam Masuk Sebenarnya --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Jam Masuk Sebenarnya <span class="text-red-500">*</span>
                    </label>
                    <input type="time"
                           name="claimed_check_in"
                           class="w-full px-4 py-3 rounded-xl border {{ $errors->has('claimed_check_in') ? 'border-red-400 focus:ring-red-200' : 'border-gray-200 focus:ring-blue-200' }} focus:border-blue-500 focus:ring text-sm transition"
                           value="{{ old('claimed_check_in') }}"
                           required>
                    <p class="text-[11px] text-gray-400 mt-1.5">Masukkan jam perkiraan saat Anda benar-benar tiba di kantor.</p>
                    @error('claimed_check_in')
                        <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Input Foto Bukti Selfie --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Foto Selfie di Area Kantor <span class="text-red-500">*</span>
                    </label>
                    <input type="file"
                           name="proof_photo"
                           id="proof_photo_input"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl p-1.5 transition"
                           accept="image/jpeg,image/png,image/jpg"
                           required>
                    <p class="text-[11px] text-gray-400 mt-1.5">Format: JPG/PNG (Maksimal 3MB). Pastikan wajah dan latar kantor terlihat jelas.</p>
                    @error('proof_photo')
                        <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                    @enderror

                    {{-- Container Preview Foto --}}
                    <div id="preview-container" class="hidden mt-3 p-2 bg-gray-50 rounded-2xl border border-gray-100 flex justify-center">
                        <img id="preview-photo" src="#" alt="Preview" class="rounded-xl object-cover max-h-48 shadow-sm">
                    </div>
                </div>

                
                <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alasan / Keterangan Koreksi <span class="text-red-500">*</span></label>
                        
                    
                        <select name="reason_category" 
                                id="reason_category"
                                class="w-full px-4 py-3 rounded-xl border {{ $errors->has('reason') ? 'border-red-400 focus:ring-red-200' : 'border-gray-200 focus:ring-blue-200' }} focus:border-blue-500 focus:ring text-sm transition"
                                required>
                            <option value="" disabled {{ old('reason_category') ? '' : 'selected' }}>-- Pilih Alasan Terkendala Absen --</option>
                            <option value="AplikasiError" {{ old('reason_category') == 'AplikasiError' ? 'selected' : '' }}>Aplikasi / Sistem Terkendala</option>
                            <option value="MacetLaluLintas" {{ old('reason_category') == 'MacetLaluLintas' ? 'selected' : '' }}>Macet Lalu Lintas</option>
                            <option value="IzinMendesak" {{ old('reason_category') == 'IzinMendesak' ? 'selected' : '' }}>Izin Mendesak</option>
                            <option value="PerangkatHpBermasalah" {{ old('reason_category') == 'PerangkatHpBermasalah' ? 'selected' : '' }}>Perangkat (HP) Mengalami Masalah</option>
                            <option value="Lainnya" {{ old('reason_category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>

                        {{-- Textarea Tambahan (Hanya muncul otomatis jika memilih opsi 'Lainnya') --}}
                        <div id="custom-reason-container" class="{{ old('reason_category') == 'Lainnya' ? '' : 'hidden' }} mt-3">
                            <label class="block text-xs font-bold text-gray-500 mb-1.5 uppercase">Jelaskan Alasan Anda <span class="text-red-500">*</span></label>
                            <textarea name="reason"
                                    id="custom_reason"
                                    class="w-full px-4 py-3 rounded-xl border {{ $errors->has('reason') ? 'border-red-400 focus:ring-red-200' : 'border-gray-200 focus:ring-blue-200' }} focus:border-blue-500 focus:ring text-sm transition"
                                    rows="3"
                                    maxlength="500"
                                    placeholder="Tuliskan detail kendala kehadiran Anda secara rinci..."
                                    {{ old('reason_category') == 'Lainnya' ? 'required' : '' }}>{{ old('reason') }}</textarea>
                        </div>

                        @error('reason')
                            <p class="text-xs text-red-500 mt-1 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>
                {{-- Aksi Tombol --}}
                <div class="flex gap-3 pt-2">
                    <a href="{{ route('dashboard') }}" 
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 py-3.5 rounded-2xl font-bold text-sm transition-all active:scale-95">
                        Batal
                    </a>
                    <button type="submit" 
                            class="flex-[2] bg-amber-500 hover:bg-amber-600 text-white py-3.5 rounded-2xl font-bold text-sm shadow-md shadow-amber-100 transition-all active:scale-95">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    // Penanganan preview gambar unggahan
    document.getElementById('proof_photo_input').addEventListener('change', function (e) {
        const container = document.getElementById('preview-container');
        const preview = document.getElementById('preview-photo');
        const file = e.target.files[0];

        if (file) {
            preview.src = URL.createObjectURL(file);
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    });

    document.getElementById('reason_category').addEventListener('change', function () {
        const container = document.getElementById('custom-reason-container');
        const textarea = document.getElementById('custom_reason');

        if (this.value === 'Lainnya') {
            container.classList.remove('hidden');
            textarea.setAttribute('required', 'required');
            textarea.focus();
        } else {
            container.classList.add('hidden');
            textarea.removeAttribute('required');
            textarea.value = ''; 
        }
    });
</script>