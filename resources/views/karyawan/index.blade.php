<x-app-layout>
    <div class="bg-blue-900 text-white p-6 pb-20 rounded-b-[3rem] shadow-lg">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Selamat Pagi,</p>
                <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                <p class="text-xs opacity-70">CV Sinar Yafiq Kamil Teknik</p>
            </div>
            <div class="w-12 h-12 rounded-full border-2 border-white overflow-hidden shadow-md bg-gray-200">
                <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}"
                     alt="Profile" class="w-full h-full object-cover">
            </div>
        </div>
    </div>

    <div class="py-6 px-4 -mt-16 mb-24 max-w-md mx-auto">

        {{-- Card Utama Absensi --}}
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

            <div class="mt-4">
                @include('karyawan.absensi.stateButton')
            </div>
        </div>

        {{-- Menu Navigasi Icon --}}
        <div class="grid grid-cols-3 gap-4 mb-8 max-w-xs mx-auto justify-items-center">
            <a href="{{ route('karyawan.jadwal') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center shadow-sm group-active:scale-90 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-600">Jadwal Kerja</span>
            </a>

            <a href="{{ route('karyawan.pengajuanCuti.index') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center shadow-sm group-active:scale-90 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-600">Ajukan Cuti</span>
            </a>

            <a href="{{ route('karyawan.riwayatKerja.index') }}" class="flex flex-col items-center gap-2 group">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center shadow-sm group-active:scale-90 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-bold text-gray-600">Riwayat Absensi</span>
            </a>
        </div>

        {{-- Aktivitas Terakhir --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
            <h4 class="font-bold text-gray-800 text-sm mb-4">Aktivitas Terakhir Saya</h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between border-l-4 {{ $attendance && $attendance->check_in ? 'border-blue-500' : 'border-gray-200' }} pl-3">
                    <div>
                        <p class="text-xs font-bold text-gray-800">Check In</p>
                        <p class="text-[10px] text-gray-400">
                            {{ $attendance && $attendance->check_in ? \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') : 'Belum ada record hari ini' }}
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">
                        {{ $attendance && $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('H:i') : '--:--' }}
                    </span>
                </div>

                <div class="flex items-center justify-between border-l-4 {{ $attendance && $attendance->check_out ? 'border-red-400' : 'border-gray-200' }} pl-3">
                    <div>
                        <p class="text-xs font-bold text-gray-800">Check Out</p>
                        <p class="text-[10px] text-gray-400">
                            {{ $attendance && $attendance->check_out ? \Carbon\Carbon::parse($attendance->date)->translatedFormat('d M Y') : 'Belum ada record hari ini' }}
                        </p>
                    </div>
                    <span class="text-sm font-semibold text-gray-700">
                        {{ $attendance && $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('H:i') : '--:--' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Library eksternal --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

<script>
    // Flash message
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-3xl' } });
    @endif
    @if(session('error'))
        Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", customClass: { popup: 'rounded-3xl' } });
    @endif

    // Jam live
    function updateLiveTime() {
        const el = document.getElementById('liveTime');
        if (el) el.innerText = new Date().toLocaleTimeString('id-ID', { hour12: false });
    }
    setInterval(updateLiveTime, 1000);
    updateLiveTime();
</script>