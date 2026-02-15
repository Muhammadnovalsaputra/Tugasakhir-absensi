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
                <a href="{{ route('karyawan.pengajuanCuti.index') }}" class="pb-3 text-gray-500 hover:text-blue-600 flex items-center gap-2">
                    📅 Pengajuan Cuti
                </a>
                <a href="{{route('karyawan.riwayat')}}" class="pb-3 border-b-2 border-blue-600 text-blue-600 font-medium flex items-center gap-2">
                    Riwayat
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                <h3 class="font-bold text-gray-700 mb-4">Filter Periode</h3>
                <form action="{{ route('karyawan.riwayat') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Tanggal Akhir</label>
                        <div class="flex gap-2">
                            <input type="date" name="end_date" value="{{ $endDate }}" 
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg p-2.5 focus:ring-blue-500">
                            <button type="submit" class="bg-blue-600 text-white px-4 rounded-lg hover:bg-blue-700">Cari</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-2">Hadir</p>
                    <p class="text-3xl font-bold text-green-600">{{ $stats['hadir'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-2">Terlambat</p>
                    <p class="text-3xl font-bold text-red-600">{{ $stats['terlambat'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-2">Cuti/Izin</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['cuti'] }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 text-center">
                    <p class="text-gray-500 text-sm mb-2">Alpa</p>
                    <p class="text-3xl font-bold text-gray-700">{{ $stats['alpa'] }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="mb-6">
                    <h3 class="font-bold text-gray-800">Detail Kehadiran</h3>
                    <p class="text-sm text-gray-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>

                <div class="space-y-4">
                    @forelse($attendances as $item)
                    <div class="border rounded-xl p-4 flex justify-between items-center hover:bg-gray-50 transition">
                        <div>
                            <p class="font-semibold text-gray-700">{{ $item['day'] }}, {{ \Carbon\Carbon::parse($item['date'])->format('d F Y') }}</p>
                            <div class="flex gap-4 mt-1">
                                <span class="text-sm text-gray-500">Masuk: <span class="text-gray-800">{{ $item['check_in'] }}</span></span>
                                <span class="text-sm text-gray-500">Pulang: <span class="text-gray-800">{{ $item['check_out'] ?? '--:--' }}</span></span>
                            </div>
                        </div>
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                {{ $item['status'] }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10 text-gray-500 italic">
                        Tidak ada data kehadiran pada periode ini.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>