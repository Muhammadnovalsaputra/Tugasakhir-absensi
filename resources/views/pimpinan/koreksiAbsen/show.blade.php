<x-app-layout>
    {{-- Header Dashboard Pimpinan --}}
    <div class="relative bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white pt-10 pb-28 px-4 overflow-hidden">
        {{-- Elemen Dekorasi Latar Belakang --}}
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-400 via-transparent to-transparent"></div>
        
        <div class="max-w-5xl mx-auto flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 relative z-10">
            <div>
                <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-indigo-300 mb-1">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Manajemen Absensi
                </div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-white via-slate-200 to-slate-400">
                    Detail Pengajuan Koreksi
                </h2>
                <p class="text-sm text-slate-400 mt-1">Evaluasi validitas data kehadiran karyawan secara berkala.</p>
            </div>
            
            <a href="{{ route('pimpinan.koreksiAbsen.index') }}" 
               class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/15 backdrop-blur-md text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 border border-white/10 shadow-sm hover:shadow active:scale-98 group">
                <svg class="w-4 h-4 text-slate-300 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- Konten Utama dengan State Management Alpine.js untuk Modal --}}
    <div class="py-6 px-4 -mt-16 mb-24 max-w-5xl mx-auto" x-data="{ openModal: false, modalAction: '', modalTitle: '', modalConfig: {} }">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-6 md:p-8 border border-slate-100 backdrop-blur-sm">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                
                {{-- Sisi Kiri: Informasi Detail Pengajuan --}}
                <div class="lg:col-span-7 space-y-6">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Informasi Laporan</h3>
                        <div>
                            @if($correction->status === 'Pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-full border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Pending
                                </span>
                            @elseif($correction->status === 'Approved')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-full border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-700 text-xs font-semibold rounded-full border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Nama Karyawan</span>
                            <span class="font-semibold text-slate-800 bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">
                                {{ $correction->user->name }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Tanggal Absen</span>
                            <span class="font-semibold text-slate-700">
                                {{ $correction->date->translatedFormat('l, d F Y') }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Jam Masuk Diklaim</span>
                            <span class="font-bold text-indigo-600 bg-indigo-50/70 px-3 py-1 rounded-xl border border-indigo-100/50 token tracking-wide">
                                {{ \Carbon\Carbon::parse($correction->claimed_check_in)->format('H:i') }} WIB
                            </span>
                        </div>

                        <div class="flex justify-between items-center py-1">
                            <span class="text-slate-500 font-medium">Waktu Pengajuan</span>
                            <span class="text-slate-600 font-medium">
                                {{ $correction->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>
                        
                        <div class="flex flex-col gap-2 pt-2">
                            <span class="text-slate-500 font-medium">Alasan / Keterangan Karyawan</span>
                            <div class="text-slate-700 bg-slate-50/80 p-4 rounded-2xl border border-slate-100 text-sm leading-relaxed relative">
                                <span class="absolute top-3 left-3 text-2xl text-slate-200 font-serif leading-none">“</span>
                                <p class="pl-4 italic">
                                    {{ $correction->reason ?? 'Tidak ada keterangan tambahan dari karyawan.' }}
                                </p>
                            </div>
                        </div>

                        {{-- Riwayat Reviewer jika sudah diproses --}}
                        @if($correction->reviewer)
                            <div class="mt-6 bg-gradient-to-r from-slate-50 to-slate-100 p-4 rounded-2xl border border-slate-200/60 space-y-3 shadow-inner">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                    </svg>
                                    Jejak Tinjauan Sistem
                                </p>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">{{ $correction->reviewer->name }}</span>
                                    <span class="text-slate-500 bg-white px-2 py-0.5 rounded-md shadow-sm text-[11px]">{{ $correction->reviewed_at->format('d M Y, H:i') }}</span>
                                </div>
                                @if($correction->reviewer_note)
                                    <div class="text-xs text-slate-600 border-t border-slate-200/80 pt-2.5 mt-2 bg-white/60 p-2.5 rounded-xl">
                                        <span class="font-semibold text-slate-500 block mb-0.5">Catatan Pimpinan:</span>
                                        "{{ $correction->reviewer_note }}"
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Sisi Kanan: Berkas Foto Bukti --}}
                <div class="lg:col-span-5 flex flex-col">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Foto Bukti Lapangan</h3>
                    <div class="relative group rounded-2xl border border-slate-200 p-2.5 bg-slate-50 flex justify-center items-center overflow-hidden shadow-inner flex-grow min-h-[280px]">
                        <a href="{{ Storage::url($correction->proof_photo) }}" target="_blank" class="block w-full h-full relative overflow-hidden rounded-xl">
                            <img src="{{ Storage::url($correction->proof_photo) }}" 
                                 alt="Foto Bukti Selfie" 
                                 class="rounded-xl object-cover w-full max-h-80 lg:max-h-none lg:h-full shadow transition-all duration-500 group-hover:scale-105 group-hover:brightness-95">
                            
                            {{-- Overlay Efek Hover --}}
                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                                <span class="bg-white/90 text-slate-900 px-4 py-2 rounded-xl text-xs font-bold shadow-lg flex items-center gap-1.5 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.604 10.604z"></path>
                                    </svg>
                                    Perbesar Gambar
                                </span>
                            </div>
                        </a>
                    </div>
                    <p class="text-[11px] text-slate-400 text-center mt-3 flex items-center justify-center gap-1">
                        <span>💡</span> Klik pada gambar untuk meninjau resolusi penuh.
                    </p>
                </div>

            </div>
            
            {{-- Bagian Form Keputusan Pimpinan --}}
            @if($correction->isPending())
                <div class="mt-10 pt-8 border-t border-slate-100">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-slate-800">Form Persetujuan Pimpinan</h3>
                    </div>
                    
                    {{-- ID Form ditambahkan untuk mempermudah trigger submit dari luar form --}}
                    <form id="reviewForm" action="{{ route('pimpinan.koreksiAbsen.review', $correction) }}" method="POST" class="space-y-5">
                        @csrf
                        
                        {{-- Input Hidden untuk melempar data action ke controller --}}
                        <input type="hidden" name="action" :value="modalAction">

                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Catatan Keputusan (Opsional)</label>
                            <textarea name="note" 
                                      rows="3" 
                                      maxlength="500"
                                      class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-slate-100 focus:border-slate-400 text-sm transition-all duration-200 placeholder:text-slate-400 bg-slate-50/50 focus:bg-white resize-none"
                                      placeholder="Berikan alasan spesifik penyetujuan atau penolakan sebagai catatan transparansi..."></textarea>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            {{-- Button Reject memicu State Modal Reject --}}
                            <button type="button" 
                                    @click="
                                        openModal = true; 
                                        modalAction = 'reject'; 
                                        modalTitle = 'Tolak Pengajuan Koreksi?';
                                        modalConfig = { 
                                            desc: 'Apakah Anda yakin ingin menolak pengajuan ini? Karyawan akan menerima notifikasi penolakan.',
                                            btnClass: 'bg-rose-600 hover:bg-rose-700 focus:ring-rose-100 text-white',
                                            btnText: 'Ya, Tolak Pengajuan'
                                        }
                                    "
                                    class="w-full sm:flex-1 bg-white hover:bg-rose-50 text-rose-600 border border-slate-200 hover:border-rose-200 py-3.5 rounded-2xl font-bold text-sm transition-all duration-200 active:scale-98 flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Tolak Pengajuan
                            </button>
                            
                            {{-- Button Approve memicu State Modal Approve --}}
                            <button type="button" 
                                    @click="
                                        openModal = true; 
                                        modalAction = 'approve'; 
                                        modalTitle = 'Setujui Pengajuan Koreksi?';
                                        modalConfig = { 
                                            desc: 'Apakah Anda yakin ingin menyetujui pengajuan ini? Data absensi karyawan akan langsung diperbarui di sistem.',
                                            btnClass: 'bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-100 text-white',
                                            btnText: 'Ya, Setujui & Rekam'
                                        }
                                    "
                                    class="w-full sm:flex-[2] bg-indigo-600 hover:bg-indigo-700 text-white py-3.5 rounded-2xl font-bold text-sm shadow-md shadow-indigo-200 hover:shadow-lg transition-all duration-200 active:scale-98 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"></path>
                                </svg>
                                Setujui & Rekam Kehadiran
                            </button>
                        </div>
                    </form>
                </div>
            @endif

        </div>


        <div class="fixed inset-0 z-50 overflow-y-auto" 
             x-show="openModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" @click="openModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full sm:p-6 border border-slate-100"
                     x-show="openModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    
                    <div>
                        
                        <div class="flex items-center justify-center w-12 h-12 mx-auto rounded-2xl"
                             :class="modalAction === 'approve' ? 'bg-indigo-50 text-indigo-600' : 'bg-rose-50 text-rose-600'">
                            
                            
                            <svg x-show="modalAction === 'approve'" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            
                            <svg x-show="modalAction === 'reject'" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>

                        <div class="mt-4 text-center sm:mt-5">
                            <h3 class="text-base font-bold leading-6 text-slate-900" x-text="modalTitle"></h3>
                            <div class="mt-2">
                                <p class="text-xs text-slate-500 leading-relaxed" x-text="modalConfig.desc"></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <button type="button" 
                                @click="openModal = false" 
                                class="inline-flex justify-center w-full px-4 py-3 text-xs font-semibold text-slate-500 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors focus:outline-none">
                            Batalkan
                        </button>
                        <button type="button"
                                @click="document.getElementById('reviewForm').submit()"
                                :class="modalConfig.btnClass"
                                class="inline-flex justify-center w-full px-4 py-3 text-xs font-semibold rounded-xl focus:outline-none focus:ring-4 transition-all">
                            <span x-text="modalConfig.btnText"></span>
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</x-app-layout>