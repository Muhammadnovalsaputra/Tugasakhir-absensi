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
        <div class="divide-y divide-gray-100">
            @forelse($attendances as $item)
            <div class="px-6 py-5">
                <!-- Baris Tanggal -->
                <div class="text-gray-400 text-sm mb-3">
                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('l, d F Y') }}
                </div>

                <!-- Baris Masuk -->
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

                <!-- Baris Pulang -->
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