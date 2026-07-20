<x-app-layout>
    <div class="p-4 sm:p-8 bg-slate-50/50 min-h-screen">
        
        {{-- Grid Statistik Status Pengajuan Cuti --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            {{-- Card 1: Menunggu Approval --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div class="space-y-1">
                        <p class="text-slate-400 text-xs font-bold tracking-wider uppercase">Menunggu Approval</p>
                        <div class="flex items-baseline gap-2 mt-2">
                            <span class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $stats['pending'] ?? 0 }}</span>
                            @if(($stats['pending'] ?? 0) > 0)
                                <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md animate-pulse">Perlu Review</span>
                            @endif
                        </div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold pt-1">Dokumen Pengajuan</p>
                    </div>
                    <div class="p-3 bg-amber-50/80 text-amber-600 rounded-2xl border border-amber-100/50 group-hover:bg-amber-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 2: Disetujui --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div class="space-y-1">
                        <p class="text-slate-400 text-xs font-bold tracking-wider uppercase">Disetujui</p>
                        <span class="text-4xl font-extrabold tracking-tight text-slate-900 block mt-2">{{ $stats['approved'] ?? 0 }}</span>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold pt-1">Pengajuan Selesai</p>
                    </div>
                    <div class="p-3 bg-emerald-50/80 text-emerald-600 rounded-2xl border border-emerald-100/50 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Card 3: Ditolak --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                <div class="flex justify-between items-start relative z-10">
                    <div class="space-y-1">
                        <p class="text-slate-400 text-xs font-bold tracking-wider uppercase">Ditolak</p>
                        <span class="text-4xl font-extrabold tracking-tight text-slate-900 block mt-2">{{ $stats['rejected'] ?? 0 }}</span>
                        <p class="text-[10px] text-slate-400 uppercase tracking-widest font-semibold pt-1">Arsip Ditolak</p>
                    </div>
                    <div class="p-3 bg-rose-50/80 text-rose-600 rounded-2xl border border-rose-100/50 group-hover:bg-rose-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Konten Utama --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
            {{-- Header Konten --}}
            <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white">
                <div>
                    <h3 class="font-bold text-slate-800 tracking-wide text-lg">Daftar Pengajuan Cuti</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Kelola data perizinan, cuti tahunan, dan dispensasi karyawan</p>
                </div>

                {{-- Search Bar --}}
                <form action="{{ route('pimpinan.pengajuanCuti.index') }}" method="GET" class="relative w-full sm:w-72">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search ?? '' }}"
                        placeholder="Cari nama, jenis cuti, status..."
                        class="w-full pl-10 pr-8 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-500 transition-all placeholder:text-slate-400"
                    >
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.35 4.35a7.5 7.5 0 0012.3 12.3z" />
                    </svg>
                    @if(!empty($search))
                        <a href="{{ route('pimpinan.pengajuanCuti.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600" title="Hapus pencarian">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    @endif
                </form>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/70 text-slate-500 text-xs uppercase font-bold tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4">Karyawan</th>
                            <th class="px-6 py-4">Kategori Cuti</th>
                            <th class="px-6 py-4">Rentang Tanggal</th>
                            <th class="px-6 py-4">Alasan & Keperluan</th>
                            <th class="px-6 py-4">Status Progress</th>
                            <th class="px-6 py-4 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($allLeaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition duration-200 group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm shadow-inner shrink-0 group-hover:scale-105 transition-transform overflow-hidden">
                                        @if(!empty($leave->user->photo))
                                            <img src="{{ asset('storage/' . $leave->user->photo) }}" alt="Foto {{ $leave->user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($leave->user->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800 text-sm tracking-wide">{{ $leave->user->name }}</div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">Masuk: {{ $leave->created_at->format('d M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200/50">
                                    {{ $leave->leave_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-700 space-y-1">
                                    <div class="flex items-center gap-1.5 font-medium">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-400 pl-3 font-semibold uppercase">sampai dengan</div>
                                    <div class="flex items-center gap-1.5 font-medium text-indigo-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-xs text-slate-600 max-w-xs break-words leading-relaxed bg-slate-50/50 p-2.5 rounded-xl border border-slate-100">
                                    {{ $leave->reason }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($leave->status == 'Pending')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Menunggu
                                    </span>
                                @elseif($leave->status == 'Approved')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Disetujui
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center items-center gap-2">
                                    @if($leave->status == 'Pending')
                                        {{-- Form Setujui --}}
                                        <form id="form-approve-{{ $leave->id }}" action="{{ route('pimpinan.pengajuanCuti.update', $leave->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Approved">
                                            <button type="button" onclick="triggerConfirmModal('{{ $leave->id }}', 'approve', '{{ $leave->user->name }}')" title="Setujui Pengajuan" class="p-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-600 hover:text-white hover:border-emerald-600 shadow-sm transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            </button>
                                        </form>

                                        {{-- Form Tolak --}}
                                        <form id="form-reject-{{ $leave->id }}" action="{{ route('pimpinan.pengajuanCuti.update', $leave->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="Rejected">
                                            <button type="button" onclick="triggerConfirmModal('{{ $leave->id }}', 'reject', '{{ $leave->user->name }}')" title="Tolak Pengajuan" class="p-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-600 hover:text-white hover:border-rose-600 shadow-sm transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-slate-400 font-medium italic">Selesai diperiksa</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center max-w-sm mx-auto">
                                    <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 mb-3 shadow-inner">
                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-slate-700 font-bold tracking-wide">Tidak Ada Pengajuan</h4>
                                    <p class="text-xs text-slate-400 mt-1 leading-relaxed">
                                        @if(!empty($search))
                                            Tidak ditemukan hasil untuk pencarian "<span class="font-semibold text-slate-600">{{ $search }}</span>".
                                        @else
                                            Saat ini kotak masuk bersih. Belum ada berkas pengajuan cuti baru dari karyawan.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse

                        @if ($allLeaves->hasPages())
                        <tr>
                            <td colspan="6" class="px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                                    <p class="text-xs font-semibold text-slate-500 tracking-wide">
                                        Menampilkan <span class="text-slate-800 font-bold">{{ $allLeaves->firstItem() }}</span> sampai <span class="text-slate-800 font-bold">{{ $allLeaves->lastItem() }}</span> dari <span class="text-indigo-600 font-extrabold">{{ $allLeaves->total() }}</span> total pengajuan
                                    </p>
                                    <div class="w-full sm:w-auto">
                                        {{ $allLeaves->links() }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Confirmation Modal --}}
    <div id="confirmation-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 overflow-x-hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeConfirmModal()"></div>

        <div class="relative bg-white w-full max-w-md rounded-3xl p-6 shadow-xl border border-slate-100 animate-in fade-in zoom-in-95 duration-200">
            <div id="modal-icon-container" class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl mb-4 border shadow-sm">
                <svg id="modal-icon-approve" class="h-6 w-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <svg id="modal-icon-reject" class="h-6 w-6 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <div class="text-center mb-6">
                <h3 id="modal-title" class="text-lg font-bold text-slate-900 tracking-wide">Konfirmasi Tindakan</h3>
                <p id="modal-description" class="text-sm text-slate-500 mt-2 leading-relaxed">Apakah Anda yakin ingin memproses pengajuan ini?</p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeConfirmModal()" class="flex-1 px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-colors duration-200">
                    Batal
                </button>
                <button id="modal-submit-btn" type="button" class="flex-1 px-4 py-3 text-white font-bold text-sm rounded-xl shadow-sm transition-colors duration-200">
                    Ya, Konfirmasi
                </button>
            </div>
        </div>
    </div>

    {{-- JavaScript Actions --}}
    <script>
        let targetFormId = null;

        function triggerConfirmModal(id, action, employeeName) {
            const modal = document.getElementById('confirmation-modal');
            const title = document.getElementById('modal-title');
            const desc = document.getElementById('modal-description');
            const iconContainer = document.getElementById('modal-icon-container');
            const iconApprove = document.getElementById('modal-icon-approve');
            const iconReject = document.getElementById('modal-icon-reject');
            const submitBtn = document.getElementById('modal-submit-btn');

            iconApprove.classList.add('hidden');
            iconReject.classList.add('hidden');
            
            if (action === 'approve') {
                targetFormId = `form-approve-${id}`;
                title.innerText = 'Setujui Pengajuan Cuti';
                desc.innerHTML = `Apakah Anda yakin ingin <strong>menyetujui</strong> pengajuan cuti dari <strong>${employeeName}</strong>?`;
                
                iconContainer.className = "mx-auto flex h-14 w-14 items-center justify-center rounded-2xl mb-4 border border-emerald-100 bg-emerald-50 text-emerald-600 shadow-sm";
                iconApprove.classList.remove('hidden');
                submitBtn.className = "flex-1 px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm rounded-xl shadow-sm transition-colors duration-200";
            } else if (action === 'reject') {
                targetFormId = `form-reject-${id}`;
                title.innerText = 'Tolak Pengajuan Cuti';
                desc.innerHTML = `Apakah Anda yakin ingin <strong>menolak</strong> pengajuan cuti dari <strong>${employeeName}</strong>?`;
                
                iconContainer.className = "mx-auto flex h-14 w-14 items-center justify-center rounded-2xl mb-4 border border-rose-100 bg-rose-50 text-rose-600 shadow-sm";
                iconReject.classList.remove('hidden');
                submitBtn.className = "flex-1 px-4 py-3 bg-rose-600 hover:bg-rose-700 text-white font-bold text-sm rounded-xl shadow-sm transition-colors duration-200";
            }

            submitBtn.onclick = function() {
                if (targetFormId) {
                    document.getElementById(targetFormId).submit();
                }
            };

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; 
        }

        function closeConfirmModal() {
            const modal = document.getElementById('confirmation-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = ''; 
            targetFormId = null;
        }
    </script>
</x-app-layout>