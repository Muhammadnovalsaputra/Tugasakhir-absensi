@if(session('success'))
    <div class="fixed top-5 right-5 z-50 mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-lg rounded" id="notif-success">
        {{ session('success') }}
    </div>
    <script>
        setTimeout(() => { document.getElementById('notif-success').remove(); }, 3000);
    </script>
@endif

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight text-center">
            Sistem Absensi Karyawan
            <p class="text-sm text-gray-500 text-center">CV Sinar Yafiq Kamil Teknik</p>
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex gap-8 border-b mb-8">
                <a href="{{ route('dashboard') }}" class="pb-3 text-gray-500 hover:text-blue-600 flex items-center gap-2">
                    ⏱ Absensi
                </a>
                <a href="{{ route('karyawan.pengajuanCuti.index') }}" class="pb-3 border-b-2 border-blue-600 text-blue-600 font-medium flex items-center gap-2">
                    📅 Pengajuan Cuti
                    <span class="bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $leaveHistory->where('status', 'Pending')->count() }}</span>
                </a>
                <a href="{{route('karyawan.riwayat')}}" class="pb-3 text-gray-500 hover:text-blue-600">Riwayat</a>
            </div>

            <div class="bg-white rounded-xl shadow p-6 flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-semibold mb-1">Sisa Kuota Cuti Tahunan</h3>
                    <p class="text-4xl font-bold text-blue-600">12 Hari</p>
                    <p class="text-gray-500 text-sm mt-1">dari 12 hari per tahun</p>
                </div>
                <button id="btnBukaForm" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg shadow transition">
                    ➕ Ajukan Cuti
                </button>
            </div>

            <div id="formPengajuanCuti" class="hidden bg-white rounded-xl shadow p-6 mb-8 border-t-4 border-blue-600 transition-all duration-300">
                <h3 class="text-lg font-semibold mb-4 text-gray-800">Form Pengajuan Cuti</h3>
                
                <form action="{{ route('karyawan.pengajuanCuti.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            
                        @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                         @endif
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti</label>
                            <select name="leave_type" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 focus:ring-blue-500">
                                <option value="CutiTahunan">Cuti Tahunan</option>
                                <option value="CutiSakit">Cuti Sakit</option>
                                <option value="IzinMendesak">Izin Mendesak</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Cuti</label>
                            <textarea name="reason" rows="3" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5" placeholder="Jelaskan alasan pengajuan cuti..."></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col md:flex-row gap-3">
                        <button type="button" id="btnBatal" class="w-full md:w-1/2 px-4 py-3 bg-gray-50 text-gray-700 rounded-lg hover:bg-gray-100 font-medium transition">
                            Batal
                        </button>
                        <button type="submit" class="w-full md:w-1/2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium shadow-sm transition">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Riwayat Pengajuan Cuti</h3>
                
                <div class="space-y-4">
                    @forelse($leaveHistory as $leave)
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-semibold text-gray-800">
                                    {{ $leave->leave_type }}
                                    <span class="ml-2 {{ $leave->status == 'Pending' ? 'bg-orange-500' : ($leave->status == 'Approved' ? 'bg-green-500' : 'bg-red-500') }} text-white text-xs px-3 py-1 rounded-full">
                                        {{ $leave->status }}
                                    </span>
                                </h4>
                                <p class="text-gray-500 text-sm mt-1">
                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                </p>
                                <p class="text-gray-600 mt-2 text-sm">
                                   {{ $leave->reason }}
                                </p>
                            </div>

                            <div class="flex space-x-2">
                    @if($leave->status == 'Pending')
                        <a href="{{ route('karyawan.pengajuanCuti.edit', $leave->id) }}" 
                           class="text-blue-600 hover:bg-blue-50 p-2 rounded-lg transition" 
                           title="Edit Pengajuan">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </a>

                        <form action="{{route('karyawan.pengajuanCuti.destroy', $leave->id)}}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-lg transition" title="Hapus Pengajuan">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </form>
                    @endif
                    
                    </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500">
                        Belum ada riwayat pengajuan cuti.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnBuka = document.getElementById('btnBukaForm');
        const btnBatal = document.getElementById('btnBatal');
        const formCuti = document.getElementById('formPengajuanCuti');

        btnBuka.addEventListener('click', function(e) {
            e.preventDefault();
            formCuti.classList.toggle('hidden');
            if (!formCuti.classList.contains('hidden')) {
                formCuti.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        btnBatal.addEventListener('click', function() {
            formCuti.classList.add('hidden');
        });
    });
</script>