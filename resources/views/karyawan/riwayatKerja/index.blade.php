<x-app-layout>
    <div class="bg-white min-h-screen">
        <div class="flex items-center gap-4 px-6 pt-6 pb-2">
            <a href="javascript:history.back()" class="p-2 -ml-2 rounded-full active:bg-gray-100 transition">
                <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="text-xl font-bold text-gray-800">Riwayat Absensi</h2>
        </div>

        {{-- Filter Date Range --}}
        <form method="GET" action="{{ request()->url() }}" class="px-6 py-3 space-y-3">
            <div class="flex gap-3">
                <div class="flex-1">
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-wide">Dari</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 mt-1">
                </div>
                <div class="flex-1">
                    <label class="text-[10px] text-gray-400 uppercase font-bold tracking-wide">Sampai</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-100 mt-1">
                </div>
            </div>
            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2.5 rounded-xl transition">
                Tampilkan
            </button>
        </form>

        {{-- Label Periode --}}
        <div class="px-6 pb-2">
            <p class="text-xs text-gray-400">
                Periode:
                <span class="font-semibold text-gray-600">
                    {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }}
                </span>
                —
                <span class="font-semibold text-gray-600">
                    {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
                </span>
            </p>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($attendances as $item)
            <div class="px-6 py-5">
                {{-- Tanggal --}}
                <div class="text-gray-400 text-sm mb-3">
                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}
                </div>

                {{-- Masuk --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="text-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <span class="text-gray-600 font-medium">Masuk</span>
                    </div>
                    <div class="text-gray-500 font-mono">
                        {{ $item->check_in ?? '--:--:--' }}
                    </div>
                </div>

                {{-- Pulang --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="text-orange-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                        </div>
                        <span class="text-gray-600 font-medium">Pulang</span>
                    </div>
                    <div class="text-gray-500 font-mono">
                        {{ $item->check_out ?? '--:--:--' }}
                    </div>
                </div>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-400">
                Tidak ada data absensi untuk periode ini.
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>