{{--
    Partial: resources/views/karyawan/absensi/stateButton.blade.php
    Variable yang dibutuhkan: $state, $attendance, $setting
--}}


<form id="attendanceForm" action="{{ route('attendance.checkin') }}" method="POST">
    @csrf
    <input type="hidden" name="image_in" id="image_in">
    <input type="hidden" name="latitude_in"  id="lat">
    <input type="hidden" name="longitude_in" id="lon">
</form>

<form id="checkoutForm" action="{{ route('attendance.checkout') }}" method="POST">
    @csrf
    <input type="hidden" name="latitude_out"  id="lat_out">
    <input type="hidden" name="longitude_out" id="lon_out">
</form>


@switch($state)

    
    @case('can_checkin')
        <div id="camera-container" class="hidden mb-4">
            <div id="my_camera" class="rounded-2xl overflow-hidden mx-auto" style="width:320px;height:240px;"></div>
            <canvas id="snapshotCanvas" width="320" height="240" class="hidden"></canvas>
            <p id="locationStatus" class="text-xs text-gray-400 mt-2">Mendeteksi lokasi...</p>
        </div>

        <button type="button"
                id="btn-open-camera"
                onclick="openCamera()"
                class="w-full py-3 rounded-2xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-sm shadow-md transition">
             Absen Masuk
        </button>

        <button type="button"
                id="btn-kirim"
                onclick="take_snapshot()"
                class="w-full py-3 mt-2 rounded-2xl bg-green-500 hover:bg-green-600 active:scale-95 text-white font-bold text-sm shadow-md transition hidden">
            Kirim Absen Masuk
        </button>
        @break

    @case('already_checkin')
        <div class="bg-green-50 border border-green-200 rounded-2xl p-3 mb-3 text-sm text-green-700">
            Absen masuk tercatat pukul
            <strong>{{ $attendance?->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}</strong>
        </div>

        <button type="button"
                onclick="handleCheckout()"
                class="w-full py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white font-bold text-sm shadow-md transition">
            Absen Pulang
        </button>
        @break
        
        @case('can_checkout_after_correction')
            <div class="bg-green-50 border border-green-200 rounded-2xl p-3 mb-3 text-sm text-green-700">
                <strong>Koreksi absensi Anda disetujui!</strong><br>
                <span class="text-xs">Jam masuk tercatat:
                    <strong>{{ $attendance?->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}</strong>
                </span>
                @if($correction?->reviewer_note)
                    <br>
                    <span class="text-sm">Catatan: <strong>{{ $correction->reviewer_note }}</strong></span>
                @endif
            </div>

    <button type="button"
            onclick="handleCheckout()"
            class="w-full py-3 rounded-2xl bg-orange-500 hover:bg-orange-600 active:scale-95 text-white font-bold text-sm shadow-md transition">
        🏠 Absen Pulang
    </button>
    @break

    @case('show_correction')
        <div class="bg-yellow-50 border border-yellow-300 rounded-2xl p-3 mb-3 text-sm text-yellow-800">
            ⚠️ <strong>Waktu absen masuk telah berakhir.</strong><br>
            <span class="text-xs">Jika Anda sudah berada di kantor, ajukan koreksi.</span>
        </div>
        <a href="{{ route('karyawan.koreksiAbsen.create') }}"
           class="block w-full py-3 text-center rounded-2xl bg-yellow-500 hover:bg-yellow-600 active:scale-95 text-white font-bold text-sm shadow-md transition">
            📝 Pengajuan Koreksi Lupa Absen
        </a>
        @break
    @case('correction_pending')
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-3 text-sm text-blue-700">
            ⏳ <strong>Pengajuan koreksi sedang ditinjau.</strong><br>
            <span class="text-xs">Harap tunggu persetujuan dari Admin/HRD.</span>
        </div>
        @break


    @case('correction_rejected')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-3 text-sm text-red-700">
            ❌ <strong>Pengajuan koreksi ditolak.</strong><br>
            Status hari ini:
            <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-bold text-xs">
                Setengah Hari
            </span><br>
            <span class="text-xs text-gray-500 mt-1 block">
                Absen pulang tidak dapat dilakukan. Hubungi Admin/HRD.
            </span>
        </div>
        @break
    @case('done')
        <div class="bg-green-50 border border-green-200 rounded-2xl p-3 text-sm text-green-700">
             <strong>Absensi hari ini sudah selesai.</strong>
            <div class="mt-2 flex justify-around text-xs text-gray-600">
                <span>Masuk: <strong>{{ $attendance?->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '-' }}</strong></span>
                <span>Pulang: <strong>{{ $attendance?->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '-' }}</strong></span>
            </div>
        </div>
        @break
    @case('on_leave')
    <div class="bg-purple-50 border border-purple-200 rounded-2xl p-3 text-sm text-purple-700">
        🌴 <strong>Anda sedang {{ $attendance->status }} hari ini.</strong>
        <div class="mt-2 text-xs text-purple-500">
            Absen masuk/pulang dinonaktifkan selama masa cuti/izin.
        </div>
    </div>
    @break

@endswitch


<script>
    // Buka kamera dan minta lokasi sekaligus
    function openCamera() {
        document.getElementById('camera-container').classList.remove('hidden');
        document.getElementById('btn-open-camera').classList.add('hidden');
        document.getElementById('btn-kirim').classList.remove('hidden');

        Webcam.set({
            width: 320, height: 240,
            image_format: 'jpeg', jpeg_quality: 90,
            constraints: { facingMode: 'environment' } // kamera belakang di HP
        });
        Webcam.attach('#my_camera');

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById('lat').value = position.coords.latitude;
                document.getElementById('lon').value = position.coords.longitude;

                const status = document.getElementById('locationStatus');
                if (status) {
                    status.innerText = '📍 Lokasi Terdeteksi';
                    status.className = 'text-xs text-green-600 font-bold mt-2';
                }
            }, function () {
                Swal.fire({ icon: 'warning', title: 'Lokasi Dibutuhkan', text: 'Harap aktifkan GPS.' });
            }, { enableHighAccuracy: true });
        }
    }

    // Ambil foto lalu submit form check-in
    function take_snapshot() {
        const lat = document.getElementById('lat').value;
        if (!lat) {
            Swal.fire({ icon: 'info', title: 'Mencari Lokasi', text: 'Tunggu sebentar sampai lokasi terdeteksi.' });
            return;
        }

        const btnKirim = document.getElementById('btn-kirim');
        btnKirim.innerText = 'Mengirim...';
        btnKirim.disabled  = true;

        Webcam.snap(function (data_uri) {
            const canvas = document.getElementById('snapshotCanvas');
            const ctx    = canvas.getContext('2d');
            const img    = new Image();

            img.onload = function () {
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                ctx.font      = 'bold 12px Arial';
                ctx.fillStyle = 'yellow';
                ctx.fillText(new Date().toLocaleString('id-ID'), 10, canvas.height - 25);
                ctx.fillText('Lat: ' + lat, 10, canvas.height - 10);

                document.getElementById('image_in').value = canvas.toDataURL('image/jpeg');
                document.getElementById('attendanceForm').submit();
            };
            img.src = data_uri;
        });
    }

    // Ambil lokasi lalu konfirmasi check-out — dipakai oleh SEMUA tombol pulang
    function handleCheckout() {
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'error', title: 'GPS tidak didukung', text: 'Browser Anda tidak mendukung geolokasi.' });
            return;
        }

        Swal.fire({ title: 'Mendeteksi Lokasi...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

        navigator.geolocation.getCurrentPosition(function (position) {
            document.getElementById('lat_out').value = position.coords.latitude;
            document.getElementById('lon_out').value = position.coords.longitude;

            Swal.close();
            Swal.fire({
                title: 'Absen Pulang Sekarang?',
                text: 'Pastikan Anda sudah menyelesaikan pekerjaan hari ini.',
                icon: 'question',
                showCancelButton:  true,
                confirmButtonText: 'Ya, Pulang',
                cancelButtonText:  'Batal',
                customClass: { popup: 'rounded-3xl' }
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('checkoutForm').submit();
                }
            });
        }, function () {
            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Lokasi tidak ditemukan. Cek pengaturan GPS Anda.' });
        }, { enableHighAccuracy: true });
    }
</script>