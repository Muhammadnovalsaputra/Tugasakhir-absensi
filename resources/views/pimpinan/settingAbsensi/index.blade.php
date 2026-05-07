<x-app-layout>
    <div class="p-8 bg-gray-50 min-h-screen">

        {{-- Alert Sukses --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl font-medium shadow-sm flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Alert Error Validasi --}}
        @if($errors->any())
        <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-medium shadow-sm">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                <span class="font-bold">Terjadi Kesalahan:</span>
            </div>
            <ul class="list-disc list-inside ml-7 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Header --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-6 flex items-center gap-6">
            <div class="bg-blue-600 p-4 rounded-2xl text-white shadow-lg shadow-blue-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-3xl font-bold text-blue-600">Setting Absensi Global</h2>
                <p class="text-gray-500 font-medium">Konfigurasi ini berlaku untuk seluruh karyawan</p>
            </div>
        </div>

        <form id="form-setting-absensi" action="{{ route('pimpinan.settingAbsensi.update') }}" method="POST">
            @csrf

            {{-- Section: Titik Koordinat --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 bg-blue-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Titik Koordinat Kantor</h3>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                            <input type="text" name="latitude" id="lat"
                                   value="{{ old('latitude', $setting->latitude) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                            <input type="text" name="longitude" id="lng"
                                   value="{{ old('longitude', $setting->longitude) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Batas Radius Absensi (Meter)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.487V6a2 2 0 011.106-1.789l5.447-2.724a2 2 0 011.894 0l5.447 2.724A2 2 0 0118 6v9.487a2 2 0 01-1.106 1.789L11.447 20a2 2 0 01-1.894 0z"/>
                                    </svg>
                                </div>
                                <input type="number" name="radius"
                                       value="{{ old('radius', $setting->radius ?? 100) }}" min="10"
                                       class="w-full pl-11 pr-16 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition"
                                       placeholder="Contoh: 100">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 font-bold">
                                    Meter
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Karyawan hanya bisa absen jika jaraknya kurang dari radius di atas.</p>
                        </div>
                    </div>

                    <button type="button" onclick="getLocation()"
                            class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm mb-6">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        Gunakan Lokasi Saat Ini
                    </button>

                    <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl">
                        <div class="flex items-center gap-2 text-blue-700 mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="font-bold">Koordinat: <span id="display-coord">{{ $setting->latitude ?? '0' }}, {{ $setting->longitude ?? '0' }}</span></span>
                        </div>
                        <a id="gmaps-link"
                           href="https://www.google.com/maps?q={{ $setting->latitude ?? 0 }},{{ $setting->longitude ?? 0 }}"
                           target="_blank"
                           class="text-blue-600 text-sm font-medium hover:underline">
                            Lihat di Google Maps →
                        </a>
                    </div>
                </div>
            </div>

            {{-- Section: Jam Kerja --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
                <div class="p-6 bg-purple-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-purple-600 p-2 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Jam Kerja</h3>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Masuk</label>
                            <input type="time" name="start_time"
                                   value="{{ old('start_time', isset($setting->start_time) ? \Carbon\Carbon::parse($setting->start_time)->format('H:i') : '08:00') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Pulang</label>
                            <input type="time" name="quit_time"
                                   value="{{ old('quit_time', isset($setting->quit_time) ? \Carbon\Carbon::parse($setting->quit_time)->format('H:i') : '17:00') }}"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Section: Jadwal Kerja --}}
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="p-6 bg-indigo-50/50 border-b border-gray-100 flex items-center gap-3">
                    <div class="bg-indigo-600 p-2 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 text-lg">Jadwal Kerja</h3>
                </div>
                <div class="p-8">
                    @php
                        $hariDefault     = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
                        $jadwalTersimpan = old('work_schedule', $setting->work_schedule ?? []);
                    @endphp
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($hariDefault as $h)
                        <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition
                                      {{ in_array($h, $jadwalTersimpan) ? 'border-indigo-300 bg-indigo-50' : 'border-gray-200' }}">
                            <input type="checkbox" name="work_schedule[]" value="{{ $h }}"
                                   {{ in_array($h, $jadwalTersimpan) ? 'checked' : '' }}
                                   class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="ml-3 font-medium text-gray-700">{{ $h }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Tombol Simpan --}}
            <div class="flex gap-4">
                <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition text-lg">
                    Simpan Konfigurasi Global
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // Konfirmasi submit
    document.getElementById('form-setting-absensi').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Simpan Konfigurasi Global?',
            text: "Setting ini akan berlaku untuk SEMUA karyawan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                form.submit();
            }
        });
    });

    // Alert sukses dari session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            customClass: { popup: 'rounded-3xl' }
        });
    @endif

    // Ambil lokasi saat ini
    function getLocation() {
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'error', title: 'Tidak Didukung', text: 'Browser Anda tidak mendukung geolocation.' });
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                document.getElementById('display-coord').innerText = lat + ', ' + lng;
                document.getElementById('gmaps-link').href = `https://www.google.com/maps?q=${lat},${lng}`;
                Swal.fire({
                    icon: 'success',
                    title: 'Lokasi Berhasil Diambil',
                    timer: 1500,
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-3xl' }
                });
            },
            function() {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pastikan izin lokasi aktif di browser Anda.' });
            }
        );
    }

    // Highlight checkbox saat diklik
    document.querySelectorAll('input[name="work_schedule[]"]').forEach(cb => {
        cb.addEventListener('change', function() {
            const label = this.closest('label');
            if (this.checked) {
                label.classList.add('border-indigo-300', 'bg-indigo-50');
                label.classList.remove('border-gray-200');
            } else {
                label.classList.remove('border-indigo-300', 'bg-indigo-50');
                label.classList.add('border-gray-200');
            }
        });
    });
    </script>
</x-app-layout>