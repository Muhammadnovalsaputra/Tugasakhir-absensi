<x-app-layout>
    <div class="p-8">
        <div class="p-8 bg-gray-50 min-h-screen">
            <div class="flex flex-col items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 text-center">Setting Absensi Karyawan</h2>
                    <p class="text-gray-500 mt-1 text-center">Cari karyawan untuk mengatur konfigurasi absensi</p>
                </div>
            </div>

            <div class="relative mb-8">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="Cari nama, posisi, atau email..." 
                    class="block w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 transition bg-white shadow-sm">
            </div>

            <div class="space-y-4" id="employeeList">
                @forelse($employes as $k)
                <div class="employee-card bg-white p-5 rounded-2xl border border-gray-100 shadow-sm flex items-center justify-between hover:shadow-md transition duration-200">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-full overflow-hidden bg-blue-50 border-2 border-white shadow-sm flex items-center justify-center text-blue-600 font-bold text-xl">
                            @if($k->photo)
                                <img src="{{ asset('storage/'.$k->photo) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($k->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-gray-900 searchable-content">{{ $k->name }}</h4>
                            <p class="text-gray-500 text-sm searchable-content">{{ $k->role }} • {{ $k->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('pimpinan.settingAbsensi.edit', $k->id) }}" class="bg-green-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-green-600 transition text-sm">
                            Atur Absensi
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-gray-500">Belum ada data karyawan.</p>
                </div>
                @endforelse

                <div id="noResults" class="hidden text-center py-10">
                    <p class="text-gray-500 font-medium">Karyawan tidak ditemukan.</p>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>