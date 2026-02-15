<x-app-layout>
@if (session('success'))
    <div id="alert-success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex justify-between items-center" role="alert">
        <span class="block sm:inline text-sm font-medium">{{ session('success') }}</span>
        <button onclick="document.getElementById('alert-success').remove()" class="text-green-700 font-bold">
            &times;
        </button>
    </div>
@endif
    <div class="flex h-screen bg-gray-100">
        @include('inc.sidebar')

        <main class="flex-1 overflow-y-auto">
            @include('inc.header')

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Hadir Hari Ini</p>
                                <p class="text-4xl font-bold text-green-500 mt-2">{{ $stats['hadir'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider font-semibold">Karyawan</p>
                            </div>
                            <div class="text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Terlambat</p>
                                <p class="text-4xl font-bold text-red-500 mt-2">{{ $stats['terlambat'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider font-semibold">Karyawan</p>
                            </div>
                            <div class="text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Cuti/Izin</p>
                                <p class="text-4xl font-bold text-blue-500 mt-2">{{ $stats['cuti_izin'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider font-semibold">Karyawan</p>
                            </div>
                            <div class="text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Pengajuan Menunggu</p>
                                <p class="text-4xl font-bold text-orange-500 mt-2">{{ $stats['pending'] }}</p>
                                <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider font-semibold">Cuti</p>
                            </div>
                            <div class="text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50">
                        <h3 class="font-bold text-gray-800 text-lg">Kehadiran Real-Time Hari Ini</h3>
                    </div>
                    
                    <div class="p-6 space-y-3">
                        @foreach($todayAttendance as $item)
                        <div class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition duration-200">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center font-bold text-gray-400">
                                    {{ substr($item['name'], 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $item['name'] }}</h4>
                                    <p class="text-xs text-gray-500">
                                        Check-in: <span class="text-gray-700 font-medium">{{ $item['check_in'] }}</span> | 
                                        Check-out: <span class="text-gray-700 font-medium">{{ $item['check_out'] }}</span>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold shadow-sm {{ $item['status'] == 'Hadir' ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
                                    {{ $item['status'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>