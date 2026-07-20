<x-app-layout>
    <div x-data="scheduleApp()" x-init="init()" class="max-w-md mx-auto mb-24 bg-gray-50 min-h-screen">

        {{-- Header --}}
        <div class="bg-gradient-to-br from-blue-500 to-blue-700 text-white px-6 pt-10 pb-24 relative">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-400 flex items-center justify-center text-white font-bold text-sm overflow-hidden">
                        <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=60a5fa&color=fff' }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="text-xs opacity-75">Selamat datang</p>
                        <p class="font-bold text-base leading-tight">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                
            </div>

            <div class="mt-5">
                <p class="text-xs font-semibold uppercase tracking-widest opacity-70">JADWAL KERJA</p>
                <p class="text-2xl font-bold mt-1" x-text="headerDateLabel"></p>
            </div>
        </div>

        {{-- Weekly Strip --}}
        <div class="mx-4 -mt-16 bg-white rounded-2xl shadow-lg px-4 py-5 relative z-10">
            {{-- Week Nav --}}
            <div class="flex justify-between items-center mb-4">
                <button @click="prevWeek()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-100 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <p class="text-xs font-semibold text-gray-500" x-text="weekRangeLabel"></p>
                <button @click="nextWeek()" class="w-7 h-7 flex items-center justify-center rounded-full hover:bg-gray-100 transition">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Day Columns --}}
            <div class="flex justify-between">
                <template x-for="(day, idx) in weekDays" :key="idx">
                    <div class="flex flex-col items-center gap-1.5 cursor-pointer" @click="selectDay(day)">
                        <span class="text-[10px] font-bold uppercase"
                              :class="isSelectedDay(day) ? 'text-blue-600' : 'text-gray-400'"
                              x-text="day.shortName"></span>
                        <button class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all"
                                :class="{
                                    'bg-blue-600 text-white shadow-md scale-110': isSelectedDay(day),
                                    'text-gray-700 hover:bg-gray-100': !isSelectedDay(day)
                                }"
                                x-text="day.date"></button>
                        <div class="w-1.5 h-1.5 rounded-full transition-colors"
                             :class="{
                                'bg-orange-400': dayStatus(day) === 'Cuti' || dayStatus(day) === 'Izin',
                                'bg-purple-500': hasHistory(day) && dayStatus(day) !== 'Cuti' && dayStatus(day) !== 'Izin',
                                'bg-transparent': !hasHistory(day)
                             }"></div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Content --}}
        <div class="mx-4 mt-5 space-y-4">

            {{-- Tag Row --}}
            <div class="flex gap-2">
                <span class="flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                    </svg>
                    <span x-text="isTodaySelected() ? 'Hari Ini' : selectedDayLabel"></span>
                </span>

                {{-- Sedang Cuti / Izin --}}
                <template x-if="isOnLeaveSelected()">
                    <span class="flex items-center gap-1.5 bg-purple-50 border border-purple-200 text-purple-600 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                        🌴 <span x-text="getHistoryForSelectedDate().status"></span>
                    </span>
                </template>

                {{-- Shift Pagi (hanya kalau bukan sedang cuti) --}}
                <template x-if="!isOnLeaveSelected() && isWorkDaySelected()">
                    <span class="flex items-center gap-1.5 bg-white border border-gray-200 text-gray-600 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                        <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="7" width="18" height="14" rx="2" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M8 7V5a2 2 0 014 0v2M16 7V5a2 2 0 014 0v2"/>
                        </svg>
                        Shift Pagi
                    </span>
                </template>

                {{-- Hari Libur (hanya kalau bukan sedang cuti) --}}
                <template x-if="!isOnLeaveSelected() && !isWorkDaySelected()">
                    <span class="flex items-center gap-1.5 bg-orange-50 border border-orange-200 text-orange-600 text-xs font-semibold px-3 py-1.5 rounded-full shadow-sm">
                        Hari Libur
                    </span>
                </template>
            </div>

            {{-- Check in / Check out --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="bg-green-50 border border-green-100 rounded-2xl p-4">
                    <div class="flex items-center gap-1.5 mb-2">
                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <p class="text-[10px] font-bold text-green-600 uppercase tracking-wide">JAM MASUK</p>
                    </div>
                    <p class="text-3xl font-black text-green-500 leading-none tracking-tight"
                       x-text="(isWorkDaySelected() && !isOnLeaveSelected()) ? '{{ $setting ? date('H:i', strtotime($setting->start_time)) : '--:--' }}' : '--:--'"></p>
                    <p class="text-[10px] text-green-400 mt-1 font-semibold">WIB</p>
                </div>
                <div class="bg-red-50 border border-red-100 rounded-2xl p-4">
                    <div class="flex items-center gap-1.5 mb-2">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                        </svg>
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wide">JAM PULANG</p>
                    </div>
                    <p class="text-3xl font-black text-red-400 leading-none tracking-tight"
                       x-text="(isWorkDaySelected() && !isOnLeaveSelected()) ? '{{ $setting ? date('H:i', strtotime($setting->quit_time)) : '--:--' }}' : '--:--'"></p>
                    <p class="text-[10px] text-red-300 mt-1 font-semibold">WIB</p>
                </div>
            </div>

            {{-- Progress Jam Kerja --}}
            <template x-if="isWorkDaySelected() && isTodaySelected() && !isOnLeaveSelected()">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <p class="text-sm font-bold text-gray-800">Progress Jam Kerja</p>
                        </div>
                        <span class="text-sm font-bold text-blue-600" x-text="workProgress + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2.5 rounded-full transition-all duration-500"
                             :style="'width: ' + workProgress + '%'"></div>
                    </div>
                    <div class="flex items-center gap-1.5 mt-2.5">
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-width="2"/>
                            <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                        </svg>
                        <p class="text-xs text-gray-500">Sisa waktu kerja</p>
                        <span class="ml-auto text-xs font-bold text-gray-700" x-text="remainingTimeLabel"></span>
                    </div>
                </div>
            </template>

            {{-- Info Cuti / Izin untuk tanggal terpilih --}}
            <template x-if="isOnLeaveSelected()">
                <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4 animate-fadeIn text-center">
                    <div class="text-2xl mb-1">🌴</div>
                    <p class="text-sm font-bold text-purple-700" x-text="getHistoryForSelectedDate().status"></p>
                    <p class="text-xs text-purple-400 mt-1">Anda sedang cuti/izin pada tanggal ini</p>
                </div>
            </template>

            {{-- Attendance History for selected date (bukan cuti/izin) --}}
            <template x-if="getHistoryForSelectedDate() && !isOnLeaveSelected()">
                <div class="bg-green-50 border border-green-200 rounded-2xl p-4 animate-fadeIn">
                    <div class="flex justify-between items-center mb-3">
                        <p class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Riwayat Absensi</p>
                        <span class="text-[10px] bg-green-200 text-green-700 px-2 py-0.5 rounded-full font-bold"
                              x-text="getHistoryForSelectedDate().status"></span>
                    </div>
                    <div class="flex gap-8">
                        <div>
                            <p class="text-[10px] text-gray-500 uppercase font-bold">Check In</p>
                            <p class="font-black text-lg text-gray-800" x-text="getHistoryForSelectedDate().check_in"></p>
                        </div>
                        <div class="border-l border-green-200 pl-8">
                            <p class="text-[10px] text-gray-500 uppercase font-bold">Check Out</p>
                            <p class="font-black text-lg text-gray-800" x-text="getHistoryForSelectedDate().check_out || '--:--'"></p>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Lokasi Kerja --}}
            @if($setting)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6a4 4 0 11-8 0 4 4 0 018 0zM12 12v9"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wide">LOKASI KERJA</p>
                    <p class="text-sm font-bold text-gray-800">{{ $setting?->latitude }}, {{ $setting?->longitude }}</p>
                </div>
            </div>
            @endif

            {{-- Total Jam Kerja --}}
            @if($setting)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex items-center gap-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke-width="2"/>
                        <path stroke-linecap="round" stroke-width="2" d="M12 6v6l4 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wide">TOTAL JAM KERJA</p>
                    @php
                        $start = \Carbon\Carbon::parse($setting->start_time);
                        $end   = \Carbon\Carbon::parse($setting->quit_time);
                        $totalMinutes = $start->diffInMinutes($end);
                        $diffH = (int) floor($totalMinutes / 60);
                        $diffM = $totalMinutes % 60;
                    @endphp
                    <p class="text-base font-black text-gray-800">{{ $diffH }} jam {{ $diffM }} menit</p>
                </div>
            </div>
            @endif

            {{-- Off day message (tidak muncul kalau ada history/cuti di tanggal ini) --}}
            <template x-if="!isWorkDaySelected() && !getHistoryForSelectedDate()">
                <div class="text-center p-8 border-2 border-dashed border-gray-200 rounded-3xl bg-white">
                    <div class="text-3xl mb-2">🌿</div>
                    <p class="text-gray-400 text-sm font-semibold">Hari Libur Mingguan</p>
                    <p class="text-gray-300 text-xs mt-1">Nikmati waktu istirahat Anda</p>
                </div>
            </template>

        </div>
    </div>

    <script>
        function scheduleApp() {
            return {
                selectedDate: new Date().getDate(),
                selectedMonth: new Date().getMonth(),
                selectedYear: new Date().getFullYear(),

                
                weekOffset: 0,

                
                weekDays: [],

                monthNamesID: ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'],
                dayNamesID:   ['Min','Sen','Sel','Rab','Kam','Jum','Sab'],
                dayNamesFullID: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],

                
                workSchedules: @json($workSchedule),
                attendanceHistory: @json($history ?? []),

                
                startTimeStr: '{{ $setting ? date('H:i', strtotime($setting->start_time)) : '08:00' }}',
                quitTimeStr:  '{{ $setting ? date('H:i', strtotime($setting->quit_time)) : '17:00' }}',

                init() {
                    this.buildWeek();
                },

                buildWeek() {
                    const today = new Date();
                    const dayOfWeek = today.getDay(); 
                    const diffToMon = (dayOfWeek === 0) ? -6 : 1 - dayOfWeek;
                    const monday = new Date(today);
                    monday.setDate(today.getDate() + diffToMon + (this.weekOffset * 7));

                    this.weekDays = [];
                    for (let i = 0; i < 7; i++) {
                        const d = new Date(monday);
                        d.setDate(monday.getDate() + i);
                        this.weekDays.push({
                            date:      d.getDate(),
                            month:     d.getMonth(),
                            year:      d.getFullYear(),
                            shortName: this.dayNamesID[d.getDay()].toUpperCase(),
                            fullName:  this.dayNamesFullID[d.getDay()],
                        });
                    }
                },

                get weekRangeLabel() {
                    if (this.weekDays.length === 0) return '';
                    const first = this.weekDays[0];
                    const last  = this.weekDays[6];
                    return `${first.date} – ${last.date} ${this.monthNamesID[last.month]} ${last.year}`;
                },

                get headerDateLabel() {
                    const d = new Date(this.selectedYear, this.selectedMonth, this.selectedDate);
                    const dayShort = this.dayNamesFullID[d.getDay()].substring(0, 3);
                    return `${dayShort}, ${this.selectedDate} ${this.monthNamesID[this.selectedMonth]} ${this.selectedYear}`;
                },

                get selectedDayLabel() {
                    const d = new Date(this.selectedYear, this.selectedMonth, this.selectedDate);
                    return this.dayNamesFullID[d.getDay()];
                },

                selectDay(day) {
                    this.selectedDate  = day.date;
                    this.selectedMonth = day.month;
                    this.selectedYear  = day.year;
                },

                isSelectedDay(day) {
                    return day.date === this.selectedDate &&
                           day.month === this.selectedMonth &&
                           day.year  === this.selectedYear;
                },

                isTodaySelected() {
                    const t = new Date();
                    return t.getDate() === this.selectedDate &&
                           t.getMonth() === this.selectedMonth &&
                           t.getFullYear() === this.selectedYear;
                },

                isWorkDaySelected() {
                    const d = new Date(this.selectedYear, this.selectedMonth, this.selectedDate);
                    return this.workSchedules.includes(this.dayNamesFullID[d.getDay()]);
                },

                hasHistory(day) {
                    const dateStr = `${day.year}-${String(day.month + 1).padStart(2,'0')}-${String(day.date).padStart(2,'0')}`;
                    return this.attendanceHistory.some(h => h.date === dateStr);
                },
                dayStatus(day) {
                    const dateStr = `${day.year}-${String(day.month + 1).padStart(2,'0')}-${String(day.date).padStart(2,'0')}`;
                    const h = this.attendanceHistory.find(x => x.date === dateStr);
                    return h ? h.status : null;
                },

                getHistoryForSelectedDate() {
                    const dateStr = `${this.selectedYear}-${String(this.selectedMonth + 1).padStart(2,'0')}-${String(this.selectedDate).padStart(2,'0')}`;
                    return this.attendanceHistory.find(h => h.date === dateStr);
                },
                isOnLeaveSelected() {
                    const h = this.getHistoryForSelectedDate();
                    return !!h && (h.status === 'Cuti' || h.status === 'Izin');
                },

                get workProgress() {
                    const now   = new Date();
                    const start = this._parseTime(this.startTimeStr);
                    const end   = this._parseTime(this.quitTimeStr);
                    const total = end - start;
                    if (total <= 0) return 0;
                    const elapsed = now - start;
                    if (elapsed <= 0) return 0;
                    if (elapsed >= total) return 100;
                    return Math.round((elapsed / total) * 100);
                },

                get remainingTimeLabel() {
                    const now = new Date();
                    const end = this._parseTime(this.quitTimeStr);
                    const diff = end - now;
                    if (diff <= 0) return '0j 0m';
                    const h = Math.floor(diff / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    return `${h}j ${m}m`;
                },

                _parseTime(str) {
                    const [h, m] = str.split(':').map(Number);
                    const d = new Date();
                    d.setHours(h, m, 0, 0);
                    return d;
                },

                prevWeek() { this.weekOffset--; this.buildWeek(); },
                nextWeek() { this.weekOffset++; this.buildWeek(); },
            }
        }
    </script>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>