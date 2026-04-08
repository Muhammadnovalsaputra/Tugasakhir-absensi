<x-app-layout>
    <div class="flex min-h-screen bg-[#f8fafc]">
        <div class="flex-1 p-8">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-6">
                <div class="flex items-center gap-4 flex-1">
                    <div class="relative w-full max-w-xs">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Search employee name..." 
                            class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="date" id="startDate" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                        <span class="text-gray-400 text-sm">to</span>
                        <input type="date" id="endDate" class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                    </div>

                    <select id="statusFilter" class="px-4 pr-10 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="HADIR">Hadir</option>
                        <option value="TERLAMBAT">Terlambat</option>
                    </select>
                    <a href="{{ route('pimpinan.rekapAbsensi.export', request()->all()) }}" 
                    class="ml-auto flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm shadow-sm transition font-medium">
                    <svg class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14.72 13L16.22 15.53H14.41L13.39 13.72L12.38 15.53H10.57L12.07 13L10.64 10.47H12.45L13.39 12.16L14.33 10.47H16.14L14.72 13Z"/>
                        <path d="M14 2H5C3.89543 2 3 2.89543 3 4V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V9L14 2ZM19 20H5V4H13V10H19V20Z"/>
                    </svg>
                    Export Excel
                     </a>
                </div>
</div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Nama Karyawan</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Absensi</th>
                            <th class="px-6 py-4 font-semibold text-center">Absen masuk dan Keluar</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Photo</th>
                            <th class="px-6 py-4 font-semibold">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($attendances as $row)
                        <tr class="hover:bg-blue-50/20 transition-colors attendance-row" 
                        data-name="{{ strtolower($row->user->name) }}" 
                        data-status="{{ strtoupper($row->status) }}"
                        data-date="{{ \Carbon\Carbon::parse($row->date)->format('Y-m-d') }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                        {{ substr($row->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $row->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $row->user->employee_id ?? 'No ID' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                            <div class="inline-flex flex-col items-center">
                                <span class="text-sm font-medium text-gray-700">
                                    {{ \Carbon\Carbon::parse($row->date)->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-[10px] text-gray-400 uppercase">
                                    {{ \Carbon\Carbon::parse($row->date)->translatedFormat('l') }}
                                </span>
                            </div>
                        </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3 text-xs font-medium">
                                    <span class="{{ $row->status == 'Terlambat' ? 'text-orange-500' : 'text-blue-600' }} font-bold">
                                        {{ \Carbon\Carbon::parse($row->check_in)->format('H:i') }}
                                    </span>
                                    <div class="h-px w-8 bg-gray-200"></div>
                                    <span class="text-gray-400">
                                        {{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i') : '--:--' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm
                                    {{ $row->status == 'Hadir' ? 'bg-green-50 text-green-600 border border-green-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                    {{ $row->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <button onclick="window.open('{{ asset('storage/absensi/' . $row->image_in) }}')" 
                                            class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-lg text-[11px] text-blue-500 hover:bg-blue-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        View Selfie
                                    </button>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-2 text-[11px] text-gray-500 max-w-[200px]">
                                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span class="truncate">Lat: {{ $row->latitude_in }}, Lon: {{ $row->longitude_in }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Definisi Elemen Input
    const searchInput = document.getElementById('searchInput');
    const startDateInput = document.getElementById('startDate');
    const endDateInput = document.getElementById('endDate');
    const statusFilter = document.getElementById('statusFilter');
    
    
    // Ambil semua baris data
    const rows = document.querySelectorAll('.attendance-row');

    function filterTable() {
        const searchValue = searchInput.value.toLowerCase();
        const start = startDateInput.value;
        const end = endDateInput.value;
        const status = statusFilter.value;

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const rowStatus = row.getAttribute('data-status');
            const rowDate = row.getAttribute('data-date');

            // Logika Filter Nama
            const matchSearch = name.includes(searchValue);
            
            // Logika Filter Status
            const matchStatus = status === "" || rowStatus === status;

            // Logika Filter Tanggal
            let matchDate = true;
            if (start && end) {
                matchDate = rowDate >= start && rowDate <= end;
            } else if (start) {
                matchDate = rowDate >= start;
            } else if (end) {
                matchDate = rowDate <= end;
            }

            // Tampilkan atau Sembunyikan Baris
            if (matchSearch && matchStatus && matchDate) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Jalankan filter setiap ada perubahan input
    searchInput.addEventListener('input', filterTable);
    startDateInput.addEventListener('change', filterTable);
    endDateInput.addEventListener('change', filterTable);
    statusFilter.addEventListener('change', filterTable);

    
});
</script>