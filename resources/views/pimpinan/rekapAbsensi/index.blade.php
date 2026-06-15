<x-app-layout>
    <div class="flex min-h-screen bg-[#f8fafc]">
        <div class="flex-1 p-8">

            {{-- FILTER FORM --}}
            <form method="GET" action="{{ route('pimpinan.rekapAbsensi.index') }}"
                  class="flex flex-col md:flex-row md:items-center gap-3 mb-6">

                <div class="relative w-full max-w-xs">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search employee name..."
                           class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div class="flex items-center gap-2">
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                    <span class="text-gray-400 text-sm">to</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                </div>
                <select name="status" class="px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm text-gray-600 shadow-sm focus:ring-blue-500">
                    <option value="">All Status</option>
                    <option value="Hadir"          {{ request('status') == 'Hadir'          ? 'selected' : '' }}>Hadir</option>
                    <option value="Terlambat"      {{ request('status') == 'Terlambat'      ? 'selected' : '' }}>Terlambat</option>
                    <option value="Alpa"           {{ request('status') == 'Alpa'           ? 'selected' : '' }}>Alpa</option>
                    <option value="Hadir (Koreksi)"{{ request('status') == 'Hadir (Koreksi)'? 'selected' : '' }}>Hadir (Koreksi)</option>
                    <option value="Setengah Hari"  {{ request('status') == 'Setengah Hari'  ? 'selected' : '' }}>Setengah Hari</option>
                    <option value="Izin"           {{ request('status') == 'Izin'           ? 'selected' : '' }}>Izin</option>
                    <option value="Sakit"          {{ request('status') == 'Sakit'          ? 'selected' : '' }}>Sakit</option>
                    <option value="Cuti"           {{ request('status') == 'Cuti'           ? 'selected' : '' }}>Cuti</option>
                </select>

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium shadow-sm transition">
                    Filter
                </button>
                <a href="{{ route('pimpinan.rekapAbsensi.index') }}"
                   class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg text-sm font-medium transition">
                    Reset
                </a>

                <a href="{{ route('pimpinan.rekapAbsensi.export', request()->all()) }}"
                   class="ml-auto flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm shadow-sm transition font-medium">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M14.72 13L16.22 15.53H14.41L13.39 13.72L12.38 15.53H10.57L12.07 13L10.64 10.47H12.45L13.39 12.16L14.33 10.47H16.14L14.72 13Z"/>
                        <path d="M14 2H5C3.89543 2 3 2.89543 3 4V20C3 21.1046 3.89543 22 5 22H19C20.1046 22 21 21.1046 21 20V9L14 2ZM19 20H5V4H13V10H19V20Z"/>
                    </svg>
                    Export Excel
                </a>

                 <a href="{{ route('pimpinan.rekapAbsensi.exportPdf', request()->all()) }}"
                     class="flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm shadow-sm transition font-medium">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12.819 14.427c.064.267.077.679-.021.948-.128.351-.381.528-.754.528h-.516v-2.202h.55c.246 0 .501.08.683.237.312.271.322.601.058.489zm1.33-4.981c-.167-.032-.363-.042-.55-.019v1.641l.55-.039c.333-.023.651-.218.651-.791 0-.426-.116-.752-.651-.792zm7.851-4.446v14c0 1.104-.896 2-2 2H4c-1.104 0-2-.896-2-2V4c0-1.104.896-2 2-2h10l6 6zm-6-4v4h4l-4-4zm-3 7.25c-.604-.036-1.185.068-1.586.25v5h1v-1.75h.617c.741 0 1.351-.239 1.678-.671.244-.324.354-.77.354-1.329 0-.547-.116-.967-.366-1.268-.309-.371-.777-.578-1.697-.232zm-3.5-.25h-1.5v5h1v-1.5h.5c.828 0 1.5-.672 1.5-1.5s-.672-1.5-1.5-1.5zm0 2h-.5v-1h.5c.275 0 .5.225.5.5s-.225.5-.5.5zm6.5-1c0 .275-.225.5-.5.5h-.5v-1h.5c.275 0 .5.225.5.5z"/>
                    </svg>
                    Export PDF
                </a>
            </form>

            {{-- TABLE --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-semibold">Nama Karyawan</th>
                            <th class="px-6 py-4 font-semibold">Tanggal Absensi</th>
                            <th class="px-6 py-4 font-semibold text-center">Absen Masuk dan Keluar</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Photo</th>
                            <th class="px-6 py-4 font-semibold">Lokasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($attendances as $row)
                        <tr class="hover:bg-blue-50/20 transition-colors">

                            {{-- Nama Karyawan --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full border border-slate-100 overflow-hidden bg-slate-50 flex-shrink-0">
                                        @if($row->user->photo)
                                            <img src="{{ asset('storage/' . $row->user->photo) }}" class="w-full h-full object-cover">
                                        @else
                                            <img src="https://ui-avatars.com/api/?background=EEF2FF&color=4F46E5&bold=true&name={{ urlencode($row->user->name) }}" class="w-full h-full object-cover">
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800">{{ $row->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $row->user->role ?? 'No ID' }}</p>
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

                            {{-- Jam Masuk & Keluar --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-3 text-xs font-medium">
                                    <span class="{{ $row->status == 'Terlambat' ? 'text-orange-500' : 'text-blue-600' }} font-bold">
                                        {{ $row->check_in ? \Carbon\Carbon::parse($row->check_in)->format('H:i') : '--:--' }}
                                    </span>
                                    <div class="h-px w-8 bg-gray-200"></div>
                                    <span class="text-gray-400">
                                        {{ $row->check_out ? \Carbon\Carbon::parse($row->check_out)->format('H:i') : '--:--' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $badgeClass = match($row->status) {
                                        'Hadir'           => 'bg-green-50 text-green-600 border-green-100',
                                        'Terlambat'       => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'Alpa'            => 'bg-red-50 text-red-600 border-red-100',
                                        'Hadir (Koreksi)' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'Setengah Hari'   => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                                        'Izin', 'Cuti'    => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'Sakit'           => 'bg-pink-50 text-pink-600 border-pink-100',
                                        default           => 'bg-gray-50 text-gray-600 border-gray-100',
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase shadow-sm border {{ $badgeClass }}">
                                    {{ $row->status }}
                                </span>
                            </td>

                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @if($row->image_in)
                                        <button onclick="window.open('{{ \Illuminate\Support\Facades\Storage::url($row->image_in) }}')"
                                                class="flex items-center gap-2 px-3 py-1.5 border border-gray-200 rounded-lg text-[11px] text-blue-500 hover:bg-blue-50 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            View Selfie
                                        </button>
                                    @else
                                        <span class="text-[11px] text-gray-300">— Tidak ada —</span>
                                    @endif
                                </div>
                            </td>

                            {{-- Lokasi --}}
                            <td class="px-6 py-4">
                                <div class="flex items-start gap-2 text-[11px] text-gray-500 max-w-[200px]">
                                    <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    @if($row->latitude_in)
                                        <span class="truncate">Lat: {{ $row->latitude_in }}, Lon: {{ $row->longitude_in }}</span>
                                    @else
                                        <span class="text-gray-300">— Tidak ada —</span>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-gray-500 font-medium">Tidak ada data absensi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($attendances->hasPages())
                <div class="flex items-center justify-between px-6 py-4 bg-gray-50 border-t border-gray-100">
                    <p class="text-sm text-gray-500">
                        Menampilkan
                        <span class="font-semibold text-gray-700">{{ $attendances->firstItem() }}</span>–<span class="font-semibold text-gray-700">{{ $attendances->lastItem() }}</span>
                        dari <span class="font-semibold text-gray-700">{{ $attendances->total() }}</span> data
                    </p>
                    {{ $attendances->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>