<x-app-layout>
    <div class="bg-blue-900 text-white p-6 pb-20 rounded-b-[3rem] shadow-lg">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <div>
                <p class="text-sm opacity-80">Pengajuan</p>
                <h2 class="text-xl font-bold">Cuti & Izin</h2>
                <p class="text-[10px] opacity-70">CV Sinar Yafiq Kamil Teknik</p>
            </div>
            <div class="w-12 h-12 rounded-full border-2 border-white overflow-hidden bg-gray-200 shadow-md">
                <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=0D8ABC&color=fff' }}" 
                    class="w-full h-full object-cover" alt="Profile Photo">
            </div>
        </div>
    </div>

    <div class="py-6 px-4 -mt-16 mb-24 max-w-md mx-auto">

        {{-- ✅ Alert Sukses --}}
        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-200 text-green-700 rounded-2xl text-sm font-bold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- ✅ Alert Error --}}
        @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-2xl text-sm font-bold flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- ✅ Kartu Kuota (diganti dari hardcode 12 ke dinamis) --}}
        <div class="bg-white rounded-3xl shadow-xl p-6 mb-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Kuota Cuti Tahun Ini</p>
                    <div class="flex items-end gap-1 mt-1">
                        <p class="text-3xl font-black text-blue-600">{{ $remainingQuota }}</p>
                        <p class="text-sm text-gray-400 mb-1">/ {{ $totalQuota }} hari tersisa</p>
                    </div>
                </div>
                <button id="btnBukaForm"
                        {{ $remainingQuota <= 0 ? 'disabled' : '' }}
                        class="bg-blue-600 text-white p-3 rounded-2xl shadow-lg shadow-blue-100 active:scale-95 transition
                               {{ $remainingQuota <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>

            {{-- Progress Bar --}}
            @php $pct = $totalQuota > 0 ? round(($usedQuota / $totalQuota) * 100) : 0; @endphp
            <div class="w-full bg-gray-100 rounded-full h-2.5 mb-1">
                <div class="h-2.5 rounded-full transition-all duration-500
                            {{ $pct >= 80 ? 'bg-red-500' : ($pct >= 50 ? 'bg-orange-400' : 'bg-blue-500') }}"
                     style="width: {{ $pct }}%"></div>
            </div>
            <p class="text-[10px] text-gray-400">{{ $usedQuota }} hari terpakai dari {{ $totalQuota }} hari</p>

            @if($remainingQuota <= 0)
            <div class="mt-3 p-2 bg-red-50 rounded-xl text-red-600 text-xs font-bold text-center">
                Kuota cuti Anda telah habis
            </div>
            @endif
        </div>

        {{-- Form Pengajuan (tidak berubah) --}}
        <div id="formPengajuanCuti" class="hidden bg-white rounded-3xl shadow-xl p-6 mb-6 border-t-4 border-blue-600">
            <h3 class="font-bold text-gray-800 mb-4">Form Pengajuan</h3>
            <form action="{{ route('karyawan.pengajuanCuti.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Jenis Cuti</label>
                        <select name="leave_type" required class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500">
                            <option value="CutiTahunan">Cuti Tahunan</option>
                            <option value="CutiSakit">Cuti Sakit</option>
                            <option value="IzinMendesak">Izin Mendesak</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Mulai</label>
                            <input type="date" name="start_date" required 
                                   class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Selesai</label>
                            <input type="date" name="end_date" required 
                                   class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-gray-400 ml-1 uppercase">Alasan</label>
                        <textarea name="reason" rows="3" required 
                                  class="w-full mt-1 bg-gray-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Keterangan singkat..."></textarea>
                    </div>
                    <div class="flex gap-2 pt-2">
                        <button type="button" id="btnBatal" 
                                class="flex-1 bg-gray-100 text-gray-500 font-bold py-3 rounded-2xl text-sm">Batal</button>
                        <button type="submit" 
                                class="flex-[2] bg-blue-600 text-white font-bold py-3 rounded-2xl text-sm shadow-lg shadow-blue-100">Kirim</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Riwayat Pengajuan --}}
        <div class="flex justify-between items-center mb-4 px-2">
            <h4 class="font-bold text-gray-800 text-sm">Riwayat Pengajuan</h4>
            <span class="bg-orange-100 text-orange-600 text-[10px] font-bold px-2 py-0.5 rounded-full">
                {{ $leaveHistory->where('status', 'Pending')->count() }} Menunggu
            </span>
        </div>

        <div class="space-y-3">
            @forelse($leaveHistory as $leave)
            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-50 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="p-3 rounded-xl
                        {{ $leave->status == 'Approved' ? 'bg-green-100 text-green-600' : 
                           ($leave->status == 'Rejected' ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600') }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($leave->status == 'Approved')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            @elseif($leave->status == 'Rejected')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            @else
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            @endif
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800">{{ $leave->leave_type }}</p>
                        <p class="text-[10px] text-gray-400 font-medium">
                            {{ \Carbon\Carbon::parse($leave->start_date)->translatedFormat('d M') }} 
                            - 
                            {{ \Carbon\Carbon::parse($leave->end_date)->translatedFormat('d M Y') }}
                        </p>
                        {{-- ✅ Tampilkan durasi hari --}}
                        <p class="text-[10px] text-blue-400 font-semibold">
                            {{ $leave->duration_days }} hari
                        </p>
                    </div>
                </div>

                <div class="text-right flex items-center gap-2">
                    @if($leave->status == 'Pending')
                    <form action="{{ route('karyawan.pengajuanCuti.destroy', $leave->id) }}" method="POST" 
                          onsubmit="return confirm('Batalkan pengajuan ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-red-300 hover:text-red-500 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                    @endif
                    <span class="text-[9px] font-black uppercase
                        {{ $leave->status == 'Approved' ? 'text-green-500' : 
                           ($leave->status == 'Rejected' ? 'text-red-500' : 'text-orange-500') }}">
                        {{ $leave->status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-10 bg-gray-50 rounded-3xl border border-dashed border-gray-200">
                <p class="text-gray-400 text-xs italic">Belum ada riwayat pengajuan.</p>
            </div>
            @endforelse
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnBuka  = document.getElementById('btnBukaForm');
            const btnBatal = document.getElementById('btnBatal');
            const formCuti = document.getElementById('formPengajuanCuti');

            // Jangan buka form jika kuota habis
            btnBuka.addEventListener('click', () => {
                if (!btnBuka.disabled) {
                    formCuti.classList.toggle('hidden');
                }
            });
            btnBatal.addEventListener('click', () => {
                formCuti.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>