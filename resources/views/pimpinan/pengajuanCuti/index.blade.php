<x-app-layout>
    <div class="flex h-screen bg-gray-100">
        @include('inc.sidebar')

        <main class="flex-1 overflow-y-auto">
            @include('inc.header')

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative">
                        <p class="text-gray-500 text-sm font-medium">Menunggu Approval</p>
                        <p class="text-4xl font-bold text-orange-500 mt-2">{{ $stats['pending'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Pengajuan</p>
                        <div class="absolute top-6 right-6 text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative">
                        <p class="text-gray-500 text-sm font-medium">Disetujui</p>
                        <p class="text-4xl font-bold text-green-500 mt-2">{{ $stats['approved'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Pengajuan</p>
                        <div class="absolute top-6 right-6 text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 relative">
                        <p class="text-gray-500 text-sm font-medium">Ditolak</p>
                        <p class="text-4xl font-bold text-red-500 mt-2">{{ $stats['rejected'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Pengajuan</p>
                        <div class="absolute top-6 right-6 text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Daftar Pengajuan Cuti</h3>
                        <div class="flex bg-gray-100 p-1 rounded-lg text-xs font-semibold">
                            <button class="px-4 py-2 bg-white shadow-sm rounded-md text-blue-600">Semua</button>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-gray-500 text-xs uppercase font-medium">
                                <tr>
                                    <th class="px-6 py-4">Karyawan</th>
                                    <th class="px-6 py-4">Jenis Cuti</th>
                                    <th class="px-6 py-4">Tanggal</th>
                                    <th class="px-6 py-4">Alasan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($allLeaves as $leave)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $leave->user->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-medium">Diajukan: {{ $leave->created_at->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $leave->leave_type }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-500 leading-relaxed">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} <br> 
                                        <span class="text-[10px] text-gray-400">s/d</span> <br>
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $leave->reason }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider
                                            {{ $leave->status == 'Pending' ? 'bg-orange-100 text-orange-600' : ($leave->status == 'Approved' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600') }}">
                                            {{ $leave->status == 'Pending' ? 'Menunggu' : ($leave->status == 'Approved' ? 'Disetujui' : 'Ditolak') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($leave->status == 'Pending')
                                      <div class="flex justify-center gap-2">
                                            <form action="{{ route('pimpinan.pengajuanCuti.update', $leave->id) }}" method="POST" onsubmit="return confirm('Setujui pengajuan ini?')">
                                                @csrf
                                                @method('PATCH') <input type="hidden" name="status" value="Approved">
                                                <button type="submit" class="p-2 bg-green-50 text-green-600 rounded-lg hover:bg-green-600 hover:text-white transition duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                </button>
                                            </form>

                                            <form action="{{ route('pimpinan.pengajuanCuti.update', $leave->id) }}" method="POST" onsubmit="return confirm('Tolak pengajuan ini?')">
                                                @csrf
                                                @method('PATCH') <input type="hidden" name="status" value="Rejected">
                                                <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                        @else
                                        <div class="text-center">
                                            <span class="text-xs text-gray-400 italic font-light">Sudah diproses</span>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <p class="text-gray-500 font-medium">Belum ada pengajuan cuti masuk.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</x-app-layout>