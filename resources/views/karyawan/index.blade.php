<x-app-layout>
    <div class="bg-blue-900 text-white p-6 pb-20 rounded-b-[3rem] shadow-lg">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Selamat Pagi,</p>
                <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-xs opacity-70">CV Sinar Yafiq Kamil Teknik</p>
            </div>
            <div class="w-12 h-12 rounded-full border-2 border-white overflow-hidden shadow-md bg-gray-200">
                @if(Auth::user()->Photo)
                    {{-- Menampilkan foto dari kolom Photo jika ada --}}
                    <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}" 
                        alt="Profile" 
                        class="w-full h-full object-cover">
                @else
                    {{-- Fallback ke UI Avatars jika user belum upload foto --}}
                    <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}"
                        alt="Profile" 
                        class="w-full h-full object-cover">
                @endif
            </div>
        </div>
    </div>

    <div class="py-6 px-4 -mt-16 mb-24 max-w-md mx-auto">
        
        <div class="bg-white rounded-3xl shadow-xl p-6 mb-6 text-center border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Jadwal Kerja</h3>
                <span class="text-xs text-blue-600 font-semibold px-3 py-1 bg-blue-50 rounded-full">
                    {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                </span>
            </div>

            <div class="text-3xl font-black text-gray-800 mb-1" id="liveTime">00:00:00</div>
            <p class="text-gray-400 text-sm mb-6">
                {{ $setting ? \Carbon\Carbon::parse($setting->start_time)->format('H:i') : '09:00' }} 
                - 
                {{ $setting ? \Carbon\Carbon::parse($setting->quit_time)->format('H:i') : '18:00' }}
            </p>

            <div x-data="{ openKamera: false }" class="space-y-4">
                @if(!$attendance)
                    <div x-show="!openKamera">
                        <button @click="openKamera = true; $nextTick(() => initWebcam())" 
                            class="w-full bg-indigo-600 text-white font-bold py-4 rounded-2xl shadow-lg shadow-indigo-100 active:scale-95 transition-all">
                            Absen Masuk
                        </button>
                    </div>

                    <div x-show="openKamera" class="flex flex-col items-center gap-4 bg-gray-50 p-4 rounded-2xl border-2 border-dashed border-blue-200">
                        <div id="my_camera" class="rounded-xl overflow-hidden shadow-md border-2 border-white"></div>
                        <canvas id="snapshotCanvas" width="320" height="240" class="hidden"></canvas>
                        
                        <form action="{{ route('attendance.checkin') }}" method="POST" id="attendanceForm" class="w-full">
                            @csrf
                            <input type="hidden" name="latitude_in" id="lat">
                            <input type="hidden" name="longitude_in" id="lon">
                            <input type="hidden" name="image_in" id="image_in"> 
                            
                            <div class="flex gap-2">
                                <button type="button" @click="openKamera = false" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold">Batal</button>
                                <button type="button" onclick="take_snapshot()" class="flex-[2] bg-green-600 text-white py-3 rounded-xl font-bold shadow-md">Kirim Absen</button>
                            </div>
                        </form>
                        <p id="locationStatus" class="text-[10px] text-red-500 italic">Mendeteksi lokasi GPS...</p>
                    </div>

                @elseif($attendance && !$attendance->check_out)
                    <form action="{{ route('attendance.checkout') }}" method="POST" id="checkoutForm">
                        @csrf
                        <input type="hidden" name="latitude_out" id="lat_out">
                        <input type="hidden" name="longitude_out" id="lon_out">
                        <button type="button" onclick="handleCheckout()" 
                            class="w-full bg-red-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-red-100 active:scale-95 transition-all">
                            Absen Pulang
                        </button>
                    </form>
                @else
                    <div class="bg-green-50 text-green-700 p-4 rounded-2xl text-sm font-bold border border-green-100 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"></path></svg>
                        <span>Absensi hari ini selesai</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-8 max-w-xs mx-auto justify-items-center">
            <a href="{{ route('karyawan.jadwal') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm group-active:scale-90 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-600">Jadwal Kerja</span>
            </a>

            <a href="{{ route('karyawan.pengajuanCuti.index') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center shadow-sm group-active:scale-90 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-600">Ajukan Cuti</span>
            </a>

            <a href="{{ route('karyawan.riwayatKerja.index') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center shadow-sm group-active:scale-90 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-600">Riwayat Absensi</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
    <h4 class="font-bold text-gray-800 text-sm mb-4">Aktivitas Terakhir</h4>
    <div class="space-y-4">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
    <h4 class="font-bold text-gray-800 text-sm mb-4">Aktivitas Terakhir Saya</h4>
    <div class="space-y-4">
        
        <!-- Baris Check In -->
        <div class="flex items-center justify-between border-l-4 {{ $attendance ? 'border-blue-500' : 'border-gray-200' }} pl-3">
            <div>
                <p class="text-xs font-bold text-gray-800">Check In</p>
                <p class="text-[10px] text-gray-400">
                    {{ $attendance ? \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') : 'Belum ada record hari ini' }}
                </p>
            </div>
            <span class="text-sm font-semibold text-gray-700">
                {{ $attendance ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}
            </span>
        </div>

        <!-- Baris Check Out -->
        <div class="flex items-center justify-between border-l-4 {{ ($attendance && $attendance->check_out) ? 'border-red-400' : 'border-gray-200' }} pl-3">
            <div>
                <p class="text-xs font-bold text-gray-800">Check Out</p>
                <p class="text-[10px] text-gray-400">
                    {{ ($attendance && $attendance->check_out) ? \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') : 'Belum ada record hari ini' }}
                </p>
            </div>
            <span class="text-sm font-semibold text-gray-700">
                {{ ($attendance && $attendance->check_out) ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}
            </span>
        </div>

    </div>
</div>
    </div>

    
</x-app-layout>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-3xl' } });
    @endif

    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", customClass: { popup: 'rounded-3xl' } });
    @endif

    function updateLiveTime() {
        const now = new Date();
        const el = document.getElementById('liveTime');
        if (el) el.innerText = now.toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateLiveTime, 1000);
    updateLiveTime();

    
    function initWebcam() {
        Webcam.set({ width: 320, height: 240, image_format: 'jpeg', jpeg_quality: 90, constraints: { facingMode: 'user' } });
        Webcam.attach('#my_camera');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lon').value = position.coords.longitude;
                let status = document.getElementById('locationStatus');
                if(status) {
                    status.innerText = "📍 Lokasi Terdeteksi";
                    status.className = "text-xs text-green-600 font-bold mt-2";
                }
            }, function(error) {
                Swal.fire({ icon: 'warning', title: 'Lokasi Dibutuhkan', text: 'Harap aktifkan GPS agar bisa mengirim absen.' });
            }, { enableHighAccuracy: true });
        }
    }

    
    function take_snapshot() {
        const btnKirim = event.target;
        const lat = document.getElementById('lat').value;

        if (!lat) {
            Swal.fire({ icon: 'info', title: 'Mencari Lokasi', text: 'Tunggu sebentar sampai lokasi Anda terdeteksi.' });
            return;
        }

        btnKirim.innerText = "Mengirim...";
        btnKirim.disabled = true;

        Webcam.snap(function(data_uri) {
            const canvas = document.getElementById('snapshotCanvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();

            img.onload = function() {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                ctx.font = "bold 12px Arial";
                ctx.fillStyle = "yellow";
                ctx.fillText(new Date().toLocaleString('id-ID'), 10, canvas.height - 25);
                ctx.fillText(`Lat: ${lat}`, 10, canvas.height - 10);

                document.getElementById('image_in').value = canvas.toDataURL('image/jpeg');
                document.getElementById('attendanceForm').submit();
            };
            img.src = data_uri;
        });
    }

    
    function handleCheckout() {
        if (navigator.geolocation) {
            Swal.fire({ title: 'Proses Lokasi...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('lat_out').value = position.coords.latitude;
                document.getElementById('lon_out').value = position.coords.longitude;
                
                Swal.fire({
                    title: 'Absen Pulang Sekarang?',
                    text: "Pastikan Anda sudah menyelesaikan pekerjaan hari ini.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Pulang',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('checkoutForm').submit();
                    }
                });
            }, function() {
                Swal.fire({ icon: 'error', title: 'Gagal', text: 'Lokasi tidak ditemukan. Cek pengaturan GPS Anda.' });
            }, { enableHighAccuracy: true });
        }
    }
</script>