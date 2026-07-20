<x-app-layout>
<div class="min-vh-100 bg-gray-50 py-6 px-4 sm:px-6 lg:px-8 text-gray-900">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-5 border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Koreksi Absensi</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola pengajuan koreksi absensi karyawan</p>
            </div>
            
            <div class="flex items-center space-x-4 mt-4 md:mt-0">
                <div class="relative text-gray-400 hover:text-gray-500 cursor-pointer">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    @if($pendingCount > 0)
                        <span class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stats Cards Summary --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            {{-- Card 1 --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-5 flex justify-between items-center border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Total Pengajuan</p>
                    <p id="statTotal" class="mt-1 text-3xl font-bold text-gray-900">{{ $corrections->total() }}</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>

            {{-- Card 2 --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-5 flex justify-between items-center border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Menunggu Persetujuan</p>
                    <p class="mt-1 text-3xl font-bold text-amber-600">{{ $pendingCount }}</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-lg text-amber-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            {{-- Card 3 --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-xl p-5 flex justify-between items-center border border-gray-100">
                <div>
                    <p class="text-sm font-medium text-gray-500 truncate">Disetujui Hari Ini</p>
                    <p class="mt-1 text-3xl font-bold text-emerald-600">
                        {{ $corrections->where('status', 'Approved')->where('updated_at', '>=', now()->startOfDay())->count() }}
                    </p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-lg text-emerald-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
            {{-- Search & Filter Section Full-Width --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-6 w-full">
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                    </div>
                    <input type="text" id="searchCorrection" name="search" class="focus:ring-indigo-500 focus:border-indigo-500 focus:shadow-md focus:shadow-indigo-100/50 block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-xl text-sm bg-white text-gray-900 transition-all outline-none"
                           placeholder="Cari nama karyawan..." value="{{ request('search') }}">
                </div>
                <div class="w-full sm:w-64">
                    <select id="statusFilter" name="status" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full py-2.5 px-3 border border-gray-300 bg-white rounded-xl text-sm text-gray-700 outline-none">
                        <option value="">Semua Status</option>
                        <option value="Pending"  @selected(request('status') === 'Pending')>Pending</option>
                        <option value="Approved" @selected(request('status') === 'Approved')>Disetujui</option>
                        <option value="Rejected" @selected(request('status') === 'Rejected')>Ditolak</option>
                    </select>
                </div>
            </div>

            {{-- Professional Table --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 text-left text-sm text-gray-500">
                    <thead>
                        <tr class="text-xs font-semibold text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="pb-3 font-medium">Karyawan</th>
                            <th class="pb-3 font-medium">Tanggal</th>
                            <th class="pb-3 font-medium">Jam Diklaim</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium">Diajukan</th>
                            <th class="pb-3 font-medium text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="correctionTableBody" class="divide-y divide-gray-50 text-gray-700">
                        @forelse($corrections as $correction)
                        <tr>
                            <td class="py-4 whitespace-nowrap">
                                <div class="flex items-center space-x-3">
                                    <img src="{{ asset('storage/'.$correction->proof_photo) }}" class="w-10 h-10 rounded-full object-cover">
                                    <div>
                                        <span class="font-semibold text-gray-900 block">{{ $correction->user->name }}</span>
                                        <span class="text-xs text-gray-400 block">{{$correction->user->role}}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 whitespace-nowrap text-gray-600 font-medium">{{ $correction->date->format('d M Y') }}</td>
                            <td class="py-4 whitespace-nowrap text-gray-600 font-medium">
                                <div class="flex items-center space-x-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    <span>{{ \Carbon\Carbon::parse($correction->claimed_check_in)->format('H:i') }}</span>
                                </div>
                            </td>
                            <td class="py-4 whitespace-nowrap">
                                @if($correction->status === 'Pending')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Pending
                                    </span>
                                @elseif($correction->status === 'Approved')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                                        <span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 whitespace-nowrap text-gray-400 text-xs">{{ $correction->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-4 whitespace-nowrap text-right">
                                <a href="{{ route('pimpinan.koreksiAbsen.show', $correction) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-xs rounded-lg transition-colors duration-150">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-400 py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                                <span class="mt-2 block font-medium">Belum ada data pengajuan koreksi absensi.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="paginationContainer" class="mt-4">
                {{ $corrections->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchCorrection');
    const statusFilter = document.getElementById('statusFilter');
    const tableBody = document.getElementById('correctionTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    const statTotal = document.getElementById('statTotal');
    
    let debounceTimer;

    // Event input pencarian
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            fetchCorrections();
        }, 300);
    });

    // Event dropdown filter status
    statusFilter.addEventListener('change', function () {
        fetchCorrections();
    });

    function fetchCorrections(url = null) {
        let search = encodeURIComponent(searchInput.value);
        let status = encodeURIComponent(statusFilter.value);
        
        // Buat URL endpoint asinkronus
        let fetchUrl = url ? url : `${window.location.pathname}?search=${search}&status=${status}`;

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (statTotal) statTotal.textContent = data.total;
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    function renderTable(corrections) {
        tableBody.innerHTML = '';

        if (corrections.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-gray-400 py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <span class="mt-2 block font-medium">Data pengajuan tidak ditemukan.</span>
                    </td>
                </tr>`;
            return;
        }

        corrections.forEach(c => {
            // Status Badge Logic
            let statusBadge = '';
            if (c.status === 'Pending') {
                statusBadge = `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700"><span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-amber-500"></span> Pending</span>`;
            } else if (c.status === 'Approved') {
                statusBadge = `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700"><span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-emerald-500"></span> Disetujui</span>`;
            } else {
                statusBadge = `<span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700"><span class="w-1.5 h-1.5 mr-1.5 rounded-full bg-red-500"></span> Ditolak</span>`;
            }

            // Date Formatting
            let dateObj = new Date(c.date);
            let formattedDate = dateObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
            
            let createdObj = new Date(c.created_at);
            let formattedCreated = createdObj.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) + `, ${String(createdObj.getHours()).padStart(2, '0')}:${String(createdObj.getMinutes()).padStart(2, '0')}`;

            let claimedTime = c.claimed_check_in ? c.claimed_check_in.substring(0, 5) : '--:--';

            let tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-3">
                        <img src="/storage/${c.proof_photo}" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <span class="font-semibold text-gray-900 block">${c.user?.name || 'N/A'}</span>
                            <span class="text-xs text-gray-400 block">${c.user?.role || ''}</span>
                        </div>
                    </div>
                </td>
                <td class="py-4 whitespace-nowrap text-gray-600 font-medium">${formattedDate}</td>
                <td class="py-4 whitespace-nowrap text-gray-600 font-medium">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>${claimedTime}</span>
                    </div>
                </td>
                <td class="py-4 whitespace-nowrap">${statusBadge}</td>
                <td class="py-4 whitespace-nowrap text-gray-400 text-xs">${formattedCreated}</td>
                <td class="py-4 whitespace-nowrap text-right">
                    <a href="/pimpinan/koreksiAbsen/${c.id}" class="inline-flex items-center px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 font-semibold text-xs rounded-lg transition-colors duration-150">
                        Detail
                    </a>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    function renderPagination(data) {
        if (!data.links || data.total <= data.per_page) {
            paginationContainer.innerHTML = '';
            return;
        }

        let linksHtml = '';
        data.links.forEach(link => {
            let activeClass = link.active 
                ? 'bg-indigo-600 text-white border-indigo-600' 
                : 'bg-white text-gray-600 hover:bg-gray-50 border-gray-300';
            let disabledAttr = link.url ? '' : 'disabled';
            
            linksHtml += `
                <button ${disabledAttr} data-url="${link.url}" class="pagination-link px-3 py-1.5 border text-xs font-semibold rounded-lg transition ${activeClass} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}">
                    ${link.label}
                </button>
            `;
        });

        paginationContainer.innerHTML = `
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-semibold text-gray-500 tracking-wide">
                    Menampilkan <span class="text-gray-800 font-bold">${data.from || 0}</span> sampai <span class="text-gray-800 font-bold">${data.to || 0}</span> dari <span class="text-indigo-600 font-extrabold">${data.total}</span> total pengajuan
                </p>
                <div class="w-full sm:w-auto flex items-center justify-center gap-1">
                    ${linksHtml}
                </div>
            </div>
        `;

        document.querySelectorAll('.pagination-link').forEach(button => {
            button.addEventListener('click', function () {
                let url = this.getAttribute('data-url');
                if (url) fetchCorrections(url);
            });
        });
    }
});
</script>
</x-app-layout>