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
                </a>
                <a href="#" class="pb-3 text-gray-500 hover:text-blue-600">Riwayat</a>
            </div>

            <div id="formEditCuti" class="bg-white rounded-xl shadow p-6 mb-8 border-t-4 border-orange-500 transition-all duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Mode Edit Pengajuan Cuti</h3>
                    <span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">Mengubah data pengajuan</span>
                </div>
                
                <form action="{{ route('karyawan.pengajuanCuti.update', $leave->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Cuti</label>
                            <select name="leave_type" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 focus:ring-blue-500">
                                <option value="CutiTahunan" {{ $leave->leave_type == 'CutiTahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                                <option value="CutiSakit"   {{ $leave->leave_type == 'CutiSakit'   ? 'selected' : '' }}>Cuti Sakit</option>
                                <option value="IzinMendesak"{{ $leave->leave_type == 'IzinMendesak'? 'selected' : '' }}>Izin Mendesak</option>
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ $leave->start_date }}" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="end_date" value="{{ $leave->end_date }}" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alasan Cuti</label>
                            <textarea name="reason" rows="3" required class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5" placeholder="Jelaskan alasan pengajuan cuti...">{{ $leave->reason }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col md:flex-row gap-3">
                        <a href="{{ route('karyawan.pengajuanCuti.index') }}" class="w-full md:w-1/2 px-4 py-3 bg-gray-50 text-center text-gray-700 rounded-lg hover:bg-gray-100 font-medium transition">
                            Batal
                        </a>
                        <button type="submit" class="w-full md:w-1/2 px-4 py-3 bg-orange-500 text-center text-white rounded-lg hover:bg-orange-600 font-medium shadow-sm transition">
                        Simpan Perubahan
                    </button>
                    </div>
                </form>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Anda hanya dapat mengedit pengajuan yang berstatus <strong>Pending</strong>.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

<script>
    window.onload = function() {
        document.getElementById('formEditCuti').scrollIntoView({ behavior: 'smooth', block: 'center' });
    };
</script>