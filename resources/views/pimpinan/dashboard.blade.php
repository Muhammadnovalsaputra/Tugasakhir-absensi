<x-app-layout>
        @if (session('success'))
            <div id="alert-success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative flex justify-between items-center" role="alert">
                <span class="block sm:inline text-sm font-medium">{{ session('success') }}</span>
                <button onclick="document.getElementById('alert-success').remove()" class="text-green-700 font-bold">
                    &times;
                </button>
            </div>
        @endif

            <div class="p-8">
              <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-emerald-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div class="space-y-1">
                            <p class="text-slate-500 text-xs font-semibold tracking-wide uppercase">Hadir Hari Ini</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $stats['hadir'] }}</span>
                                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md">Aktif</span>
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold pt-2">Karyawan</p>
                        </div>
                        
                        <div class="p-3 bg-emerald-50/80 text-emerald-600 rounded-2xl border border-emerald-100/50 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5H4.875C3.839 19.5 3 18.661 3 17.625M15 19.5V12m0 7.5h3.125c1.036 0 1.875-.839 1.875-1.875V15M15 12V6m0 6h3.125c1.036 0 1.875.84 1.875 1.875V15M15 6V3.75c0-1.036-.84-1.875-1.875-1.875h-2.25A1.875 1.875 0 009 3.75V6m6 0H9m0 0V12m0 0h3.125C13.16 12 14 12.84 14 13.875V15M9 12H5.875A1.875 1.875 0 004 13.875V15m10 0.5H4" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-rose-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div class="space-y-1">
                            <p class="text-slate-500 text-xs font-semibold tracking-wide uppercase">Terlambat</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $stats['terlambat'] }}</span>
                                @if($stats['terlambat'] > 0)
                                    <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md animate-pulse">Butuh Evaluasi</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold pt-2">Karyawan</p>
                        </div>
                        
                        <div class="p-3 {{ $stats['terlambat'] > 0 ? 'bg-rose-50/80 text-rose-600 border border-rose-100/50 group-hover:bg-rose-500' : 'bg-slate-50 text-slate-400 border border-slate-100 group-hover:bg-slate-500' }} group-hover:text-white rounded-2xl transition-colors duration-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-indigo-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div class="space-y-1">
                            <p class="text-slate-500 text-xs font-semibold tracking-wide uppercase">Cuti / Izin</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $stats['cuti_izin'] }}</span>
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold pt-2">Karyawan</p>
                        </div>
                        
                        <div class="p-3 bg-indigo-50/80 text-indigo-600 rounded-2xl border border-indigo-100/50 group-hover:bg-indigo-500 group-hover:text-white transition-colors duration-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                    <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-50 rounded-full opacity-40 group-hover:scale-110 transition-transform duration-300"></div>
                    
                    <div class="flex justify-between items-start relative z-10">
                        <div class="space-y-1">
                            <p class="text-slate-500 text-xs font-semibold tracking-wide uppercase">Menunggu Review</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-4xl font-extrabold tracking-tight text-slate-900">{{ $stats['pending'] }}</span>
                                @if($stats['pending'] > 0)
                                    <span class="text-xs font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md">Perlu Tindakan</span>
                                @endif
                            </div>
                            <p class="text-[10px] text-slate-400 uppercase tracking-widest font-bold pt-2">Berkas Cuti</p>
                        </div>
                        
                        <div class="p-3 {{ $stats['pending'] > 0 ? 'bg-amber-50/80 text-amber-600 border border-amber-100/50 group-hover:bg-amber-500' : 'bg-slate-50 text-slate-400 border border-slate-100 group-hover:bg-slate-500' }} group-hover:text-white rounded-2xl transition-colors duration-300 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>
                </div>

            </div>

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50">
                        <h3 class="font-bold text-gray-800 text-lg">Kehadiran Real-Time Hari Ini</h3>
                    </div>
                    
                <div class="p-6 space-y-4">
                        @foreach($todayAttendance as $item)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl shadow-sm hover:shadow-md hover:border-slate-200 transition-all duration-200 gap-4 group">
                            
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm tracking-wider shadow-inner shrink-0 transition-transform duration-200 group-hover:scale-105 overflow-hidden
                                    {{ $item['status'] == 'Hadir' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                                    
                                    @if(!empty($item['photo'])) 
                                        <img src="{{ asset('storage/' . $item['photo']) }}" 
                                            alt="Foto {{ $item['name'] }}" 
                                            class="w-full h-full object-cover">
                                    @else
                                        {{ strtoupper(substr($item['name'], 0, 2)) }}
                                    @endif
                                </div>
                                
                                <div class="space-y-1">
                                    <h4 class="font-semibold text-slate-800 tracking-wide text-sm sm:text-base group-hover:text-indigo-600 transition-colors">
                                        {{ $item['name'] }}
                                    </h4>
                                    
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-slate-500">
                                        <span class="flex items-center gap-1 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Masuk: <strong class="text-slate-700 font-semibold ml-0.5">{{ $item['check_in'] }}</strong>
                                        </span>
                                        <span class="flex items-center gap-1 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Keluar: <strong class="text-slate-700 font-semibold ml-0.5">{{ $item['check_out'] }}</strong>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between sm:justify-end border-t border-slate-50 pt-3 sm:pt-0 sm:border-none shrink-0">
                                @if($item['status'] == 'Hadir')
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $item['status'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200/60 shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        {{ $item['status'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        @endforeach

                        <div class="mt-6 pt-2">
                            {{ $todayAttendance->links() }}
                        </div>
                    </div>
                </div>
            </div>
</x-app-layout>