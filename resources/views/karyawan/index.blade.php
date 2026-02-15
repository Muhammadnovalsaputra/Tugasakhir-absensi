<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            Sistem Absensi Karyawan
            <p class="text-sm text-gray-500 text-center">CV Sinar Yafiq Kamil Teknik</p>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tabs -->
            <div class="flex gap-6 border-b mb-6 justify-center">
                <a href="#" class="pb-2 border-b-2 border-blue-600 text-blue-600 font-medium">
                    Absensi
                </a>
                <a href="{{route('karyawan.pengajuanCuti.index')}}" class="pb-2 text-gray-500 hover:text-blue-600">
                    Pengajuan Cuti
                </a>
                <a href="{{route('karyawan.riwayat')}}" class="pb-2 text-gray-500 hover:text-blue-600">
                    Riwayat
                </a>
            </div>
            <div class="bg-white rounded-xl shadow p-6 text-center">

                <h3 class="text-lg font-semibold mb-2">Absensi Hari Ini</h3>

                <div id="liveTime" class="text-4xl font-bold text-blue-600 mb-1">
                00:00:00
                </div>

                <p class="text-gray-500 mb-6">
                    Sabtu, 27 Desember 2025
                </p>

                <!-- Check In / Out -->
                <div x-data="{ openKamera: false }" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
        <div class="bg-blue-50 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Check-In</p>
            <p class="text-xl font-semibold text-blue-600">
                {{ $attendance->check_in ?? '--:--' }}
            </p>
        </div>
        <div class="bg-blue-50 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-500">Check-Out</p>
            <p class="text-xl font-semibold text-blue-600">
                {{ $attendance->check_out ?? '--:--' }}
            </p>
        </div>
    </div>

    @if(!$attendance)
        <div x-show="!openKamera">
            <button @click="openKamera = true; initWebcam()" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg hover:bg-blue-700 transition">
                Ambil Absen Masuk (Selfie)
            </button>
        </div>

        <div x-show="openKamera" class="flex flex-col items-center gap-4 bg-gray-50 p-4 rounded-xl border-2 border-dashed border-blue-200">
            <div id="my_camera" class="rounded-lg overflow-hidden shadow-md"></div>
            <canvas id="snapshotCanvas" width="320" height="240" style="display:none;"></canvas>
            
            <form action="{{ route('attendance.checkin') }}" method="POST" id="attendanceForm" class="w-full text-center">
            @csrf
            <input type="hidden" name="latitude_in" id="lat">
            <input type="hidden" name="longitude_in" id="lon">
            <input type="hidden" name="image_in" id="image_in"> <div class="flex gap-2 justify-center">
                <button type="button" @click="openKamera = false" class="bg-gray-400 text-white px-4 py-2 rounded-lg text-sm">Batal</button>
                <button type="button" onclick="take_snapshot()" class="bg-green-600 text-white px-6 py-2 rounded-lg text-sm font-bold shadow-md">Kirim Absen</button>
            </div>
        </form>
            <p id="locationStatus" class="text-xs text-red-500 italic font-medium">Mendeteksi lokasi...</p>
        </div>
    @else
        <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm font-medium">
            Selesai! Anda sudah melakukan absensi.
        </div>
    @endif
</div>

                
                <div class="bg-green-50 text-green-700 p-3 rounded-lg text-sm">
                    Anda sudah menyelesaikan absensi hari ini
                </div>

            </div>
            
            <div class="bg-white rounded-xl shadow p-6 mt-6 text-center">
                <h4 class="font-semibold mb-3">Informasi Jam Kerja</h4>

                <ul class="text-gray-600 text-sm space-y-1">
                    <li>• Jam Masuk : 08:00</li>
                    <li>• Jam Pulang : 17:00</li>
                    <li>• Toleransi Keterlambatan : 30 Menit</li>
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>

@if (session('success'))
<div x-data="{ show: true }" 
     x-init="setTimeout(() => show = false, 5000)" 
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed bottom-5 right-5 bg-white shadow-lg rounded-lg px-4 py-3 flex items-center gap-2">
    
    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M16.707 5.293a1 1 0 010 1.414L8.414 15 4.293 10.879a1 1 0 011.414-1.414L8.414 12.586l7.293-7.293a1 1 0 011.414 0z"
            clip-rule="evenodd" />
    </svg>
    <span class="text-sm font-medium">Login berhasil!</span>
</div>
@endif


<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
    function updateLiveTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const el = document.getElementById('liveTime');
        if (el) el.innerText = `${hours}:${minutes}:${seconds}`;
    }
    setInterval(updateLiveTime, 1000);
    updateLiveTime();

    function initWebcam() {
        console.log("Memulai kamera...");
        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90,
            constraints: { facingMode: 'user' }
        });
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
                alert("Harap izinkan lokasi agar tombol Kirim Absen berfungsi.");
            }, { enableHighAccuracy: true });
        }
    }

    
function take_snapshot() {
    const lat = document.getElementById('lat').value;
    const lon = document.getElementById('lon').value;

    if (!lat) {
        alert("Tunggu sampai lokasi terdeteksi (tanda 📍 muncul)");
        return;
    }

    Webcam.snap(function(data_uri) {
        const canvas = document.getElementById('snapshotCanvas');
        const ctx = canvas.getContext('2d');
        const img = new Image();

        img.onload = function() {
            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

            // Watermark text
            ctx.font = "bold 14px Arial";
            ctx.fillStyle = "yellow";
            ctx.shadowBlur = 4;
            ctx.shadowColor = "black";

            const now = new Date();
            const timeString = now.toLocaleString('id-ID');
            const coordString = `Lat: ${lat}, Lon: ${lon}`;

            ctx.fillText(timeString, 10, canvas.height - 30);
            ctx.fillText(coordString, 10, canvas.height - 10);

            // AMBIL HASIL DAN MASUKKAN KE INPUT YANG BENAR
            const finalImage = canvas.toDataURL('image/jpeg');
            document.getElementById('image_in').value = finalImage; // Pastikan ID ini 'image_in'
            
            console.log("Data siap dikirim ke database...");
            document.getElementById('attendanceForm').submit();
        };
        
        img.src = data_uri;
    });
}
</script>
