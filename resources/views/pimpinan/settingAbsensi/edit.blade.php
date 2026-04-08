<x-app-layout>
            <div class="p-8 bg-gray-50 min-h-screen">
                {{-- Pesan Error Validasi --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-2xl font-medium shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                            <span class="font-bold">Terjadi Kesalahan:</span>
                        </div>
                        <ul class="list-disc list-inside ml-7 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-6 flex items-center gap-6">
                    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-blue-50 shadow-md">
                        @if($user->photo)
                            <img src="{{ asset('storage/'.$user->photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-2xl">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-blue-600">Setting Absensi</h2>
                        <p class="text-gray-500 font-medium">{{ $user->name }} • {{ $user->role }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 bg-blue-50/50 border-b border-gray-100 flex items-center gap-3">
                        <div class="bg-blue-600 p-2 rounded-xl text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </div>
                        <h3 class="font-bold text-gray-800 text-lg">Titik Koordinat Absensi</h3>
                    </div>

                    <form id="form-setting-absensi" action="{{ route('pimpinan.settingAbsensi.update', $user->id) }}" method="POST" class="p-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                                <input type="text" name="latitude" id="lat" value="{{ old('latitude', $user->latitude) }}" required
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                                <input type="text" name="longitude" id="lng" value="{{ old('longitude', $user->longitude) }}" required
                                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                            </div>
                            <div class="mt-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batas Radius Absensi (Meter)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A2 2 0 013 15.487V6a2 2 0 011.106-1.789l5.447-2.724a2 2 0 011.894 0l5.447 2.724A2 2 0 0118 6v9.487a2 2 0 01-1.106 1.789L11.447 20a2 2 0 01-1.894 0z" /></svg>
                                    </div>
                                    <input type="number" name="radius" value="{{ old('radius', $user->radius ?? 100) }}" 
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
                                class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm mb-8">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                            Gunakan Lokasi Saat Ini
                        </button>

                        <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl mb-8">
                            <div class="flex items-center gap-2 text-blue-700 mb-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                                <span class="font-bold">Koordinat: <span id="display-coord">{{ $user->latitude ?? '0' }}, {{ $user->longitude ?? '0' }}</span></span>
                            </div>
                            <a id="gmaps-link" href="https://www.google.com/maps?q={{ $user->latitude }},{{ $user->longitude }}" target="_blank" class="text-blue-600 text-sm font-medium hover:underline">
                                Lihat di Google Maps →
                            </a>
                        </div>

                        <div class="mt-8 space-y-8">
                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                                <div class="p-6 bg-purple-50/50 border-b border-gray-100 flex items-center gap-3">
                                    <div class="bg-purple-600 p-2 rounded-xl text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-lg">Jam Kerja</h3>
                                </div>

                                <div class="p-8">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Masuk</label>
                                            <input type="time" name="startTime" value="{{ old('startTime', $user->startTime ?? '08:00') }}"
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Jam Pulang</label>
                                            <input type="time" name="quitTime" value="{{ old('quitTime', $user->quitTime ?? '17:00') }}" 
                                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 outline-none transition">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-12">
                                <div class="p-6 bg-indigo-50/50 border-b border-gray-100 flex items-center gap-3">
                                    <div class="bg-indigo-600 p-2 rounded-xl text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                    <h3 class="font-bold text-gray-800 text-lg">Jadwal Kerja</h3>
                                </div>

                                <div class="p-8">
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        @php 
                                            $hariDefault = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                            $jadwalTersimpan = old('jadwal', $user->workSchedule ?? []); 
                                        @endphp

                                        @foreach($hariDefault as $h)
                                        <label class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                            <input type="checkbox" name="jadwal[]" value="{{ $h }}" 
                                                {{ in_array($h, $jadwalTersimpan) ? 'checked' : '' }}
                                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                            <span class="ml-3 font-medium text-gray-700">{{ $h }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 transition">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('pimpinan.settingAbsensi.index') }}" class="px-8 py-4 bg-gray-100 text-gray-500 rounded-2xl font-bold hover:bg-gray-200 transition text-center">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // 1. Alert Konfirmasi saat Klik Simpan
    const form = document.getElementById('form-setting-absensi');
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Hentikan kirim form otomatis

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Pastikan data jam kerja dan lokasi sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // Warna biru-600 (Tailwind)
            cancelButtonColor: '#9ca3af', // Warna gray-400
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-3xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Tampilkan loading sebentar sebelum submit
                Swal.fire({
                    title: 'Memproses...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                this.submit(); // Kirim form ke server
            }
        });
    });

    // 2. Alert Sukses setelah Redirect dari Controller
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            customClass: {
                popup: 'rounded-3xl'
            }
        });
    @endif

    // 3. Fungsi Ambil Lokasi (Tetap seperti sebelumnya)
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, (error) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengambil Lokasi',
                    text: 'Pastikan izin lokasi aktif di browser Anda.'
                });
            });
        }
    }

    function showPosition(position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        
        document.getElementById("lat").value = lat;
        document.getElementById("lng").value = lng;
        document.getElementById("display-coord").innerText = lat + ", " + lng;
        document.getElementById("gmaps-link").href = `https://www.google.com/maps?q=${lat},${lng}`;
        
        Swal.fire({
            icon: 'success',
            title: 'Lokasi Berhasil Diambil',
            timer: 1500,
            showConfirmButton: false,
            customClass: { popup: 'rounded-3xl' }
        });
    }
</script>
</x-app-layout>