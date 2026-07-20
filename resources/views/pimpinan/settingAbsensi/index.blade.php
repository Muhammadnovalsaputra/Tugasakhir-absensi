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

       
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="p-6 bg-blue-50/50 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="bg-blue-600 p-2 rounded-xl text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">Titik Lokasi Absensi</h3>
                        <p class="text-xs text-gray-400">Karyawan bisa absen dari salah satu titik ini</p>
                    </div>
                </div>
                <button type="button" onclick="openLocationModal()"
                        class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-sm text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Lokasi
                </button>
            </div>

            <div class="p-8">
                @forelse($locations as $location)
                <div class="flex items-center justify-between p-5 mb-3 rounded-2xl border {{ $location->is_active ? 'border-gray-200 bg-white' : 'border-gray-100 bg-gray-50 opacity-60' }}">
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-100 p-3 rounded-xl">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">
                                {{ $location->name }}
                                @if(!$location->is_active)
                                    <span class="ml-2 text-xs font-medium text-gray-400 bg-gray-200 px-2 py-0.5 rounded-full">Nonaktif</span>
                                @endif
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ $location->latitude }}, {{ $location->longitude }} &middot; Radius {{ $location->radius }}m
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button"
                                onclick="editLocation({{ $location->id }}, '{{ $location->name }}', {{ $location->latitude }}, {{ $location->longitude }}, {{ $location->radius }}, {{ $location->is_active ? 'true' : 'false' }})"
                                class="p-2.5 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button type="button" onclick="confirmDeleteLocation({{ $location->id }}, '{{ $location->name }}')"
                                class="p-2.5 bg-red-50 text-red-500 rounded-xl hover:bg-red-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400">
                    <p class="font-medium">Belum ada lokasi absensi.</p>
                    <p class="text-sm">Klik "Tambah Lokasi" untuk mulai konfigurasi.</p>
                </div>
                @endforelse
            </div>
        </div>

       
        <div id="location-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg overflow-hidden">
                <form id="location-form" method="POST">
                    @csrf
                    <div id="location-method-field"></div>

                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 id="location-modal-title" class="font-bold text-gray-800 text-lg">Tambah Lokasi</h3>
                        <button type="button" onclick="closeLocationModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lokasi</label>
                            <input type="text" name="name" id="loc-name" required placeholder="Contoh: Kantor Pusat"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                                <input type="text" name="latitude" id="loc-lat" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                                <input type="text" name="longitude" id="loc-lng" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Radius (Meter)</label>
                            <input type="number" name="radius" id="loc-radius" min="10" required value="100"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                        </div>

                        <button type="button" onclick="getLocationForModal()"
                                class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 rounded-xl font-bold text-gray-700 hover:bg-gray-50 transition shadow-sm text-sm">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Gunakan Lokasi Saat Ini
                        </button>

                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer">
                            <input type="checkbox" name="is_active" id="loc-active" value="1" checked
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="font-medium text-gray-700 text-sm">Lokasi aktif (bisa dipakai untuk absen)</span>
                        </label>
                    </div>

                    <div class="p-6 border-t border-gray-100 flex gap-3">
                        <button type="button" onclick="closeLocationModal()"
                                class="flex-1 py-3 rounded-xl font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-3 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 transition">
                            Simpan Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

       
        <form id="delete-location-form" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

      
        <form id="form-setting-absensi" action="{{ route('pimpinan.settingAbsensi.update') }}" method="POST">
            @csrf

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

    // Highlight checkbox jadwal kerja saat diklik
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

  
    const routes = {
        store:   "{{ route('pimpinan.settingAbsensi.lokasi.store') }}",
        update:  "{{ route('pimpinan.settingAbsensi.lokasi.update', ':id') }}",
        destroy: "{{ route('pimpinan.settingAbsensi.lokasi.destroy', ':id') }}",
    };

    function openLocationModal() {
        document.getElementById('location-modal-title').innerText = 'Tambah Lokasi';
        document.getElementById('location-form').action = routes.store;
        document.getElementById('location-method-field').innerHTML = '';
        document.getElementById('loc-name').value = '';
        document.getElementById('loc-lat').value = '';
        document.getElementById('loc-lng').value = '';
        document.getElementById('loc-radius').value = 100;
        document.getElementById('loc-active').checked = true;
        document.getElementById('location-modal').classList.remove('hidden');
        document.getElementById('location-modal').classList.add('flex');
    }

    function editLocation(id, name, lat, lng, radius, isActive) {
        document.getElementById('location-modal-title').innerText = 'Edit Lokasi';
        document.getElementById('location-form').action = routes.update.replace(':id', id);
        document.getElementById('location-method-field').innerHTML = '@method("PUT")';
        document.getElementById('loc-name').value = name;
        document.getElementById('loc-lat').value = lat;
        document.getElementById('loc-lng').value = lng;
        document.getElementById('loc-radius').value = radius;
        document.getElementById('loc-active').checked = isActive;
        document.getElementById('location-modal').classList.remove('hidden');
        document.getElementById('location-modal').classList.add('flex');
    }

    function closeLocationModal() {
        document.getElementById('location-modal').classList.add('hidden');
        document.getElementById('location-modal').classList.remove('flex');
    }

    function getLocationForModal() {
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'error', title: 'Tidak Didukung', text: 'Browser Anda tidak mendukung geolocation.' });
            return;
        }
        navigator.geolocation.getCurrentPosition(
            function(position) {
                document.getElementById('loc-lat').value = position.coords.latitude;
                document.getElementById('loc-lng').value = position.coords.longitude;
                Swal.fire({ icon: 'success', title: 'Lokasi Berhasil Diambil', timer: 1200, showConfirmButton: false });
            },
            function() {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Pastikan izin lokasi aktif di browser Anda.' });
            }
        );
    }

    function confirmDeleteLocation(id, name) {
        Swal.fire({
            title: `Hapus "${name}"?`,
            text: 'Lokasi ini tidak akan bisa lagi dipakai untuk absen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-location-form');
                form.action = routes.destroy.replace(':id', id);
                form.submit();
            }
        });
    }
    </script>
</x-app-layout>