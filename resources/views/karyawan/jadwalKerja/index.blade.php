<x-app-layout>
    <div x-data="calendarApp()" x-init="init()" class="max-w-md mx-auto mb-24">
        
        <div class="bg-blue-900 text-white p-6 pb-20 rounded-b-[3rem] shadow-lg">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm opacity-80">Jadwal & Riwayat,</p>
                    <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-[10px] opacity-70">Radius Kerja: {{ Auth::user()->radius }}m</p>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-white overflow-hidden bg-gray-200">
                    <img src="{{ Auth::user()->photo ? asset('storage/'.Auth::user()->photo) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name) }}" class="w-full h-full object-cover">
                </div>
            </div>
        </div>

        <div class="px-4 -mt-16">
            <div class="bg-white rounded-3xl shadow-xl p-6 mb-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-black text-2xl text-gray-800" x-text="monthNames[month] + ' ' + year"></h3>
                    <div class="flex gap-4 text-gray-400">
                        <button @click="prevMonth()" class="p-2 hover:bg-gray-100 rounded-full transition active:scale-90">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click="nextMonth()" class="p-2 hover:bg-gray-100 rounded-full transition active:scale-90">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-7 gap-2 text-center mb-2">
                    <template x-for="day in ['S','M','T','W','T','F','S']">
                        <span class="text-xs font-bold text-gray-400" x-text="day"></span>
                    </template>
                </div>

                <div class="grid grid-cols-7 gap-2 text-center">
                    <template x-for="blankday in blankdays">
                        <div class="py-2"></div>
                    </template>

                    <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                        <div class="relative py-1">
                            <button 
                                @click="selectDate(date)"
                                class="text-sm font-semibold w-9 h-9 flex items-center justify-center mx-auto rounded-full transition-all relative"
                                :class="{
                                    'bg-blue-600 text-white shadow-lg scale-110': isSelected(date),
                                    'text-blue-600 font-black border border-blue-100': isToday(date) && !isSelected(date),
                                    'text-gray-700 hover:bg-gray-50': !isToday(date) && !isSelected(date)
                                }">
                                <span x-text="date"></span>
                                <template x-if="hasHistory(date)">
                                    <div class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 rounded-full bg-green-500"></div>
                                </template>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <div class="space-y-6">
                <h4 class="text-gray-400 font-bold text-[10px] uppercase tracking-[0.2em] border-b pb-2 flex justify-between">
                    <span x-text="'Detail: ' + selectedDateFormatted"></span>
                    <span class="text-blue-500 font-black" x-text="isWorkDaySelected() ? 'Jadwal Kerja' : 'Libur'"></span>
                </h4>

                <template x-if="getHistoryForSelectedDate()">
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-2xl shadow-sm animate-fadeIn">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-bold text-green-600 uppercase tracking-wider">Riwayat Absensi Terdeteksi</span>
                            <span class="text-[10px] bg-green-200 text-green-700 px-2 py-0.5 rounded-full font-bold" x-text="getHistoryForSelectedDate().status"></span>
                        </div>
                        <div class="flex gap-8 text-gray-700">
                            <div>
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Check In</p>
                                <p class="font-black text-lg" x-text="getHistoryForSelectedDate().check_in"></p>
                            </div>
                            <div class="border-l border-green-200 pl-8">
                                <p class="text-[10px] text-gray-500 uppercase font-bold">Check Out</p>
                                <p class="font-black text-lg" x-text="getHistoryForSelectedDate().check_out || '--:--'"></p>
                            </div>
                        </div>
                    </div>
                </template>

                <div class="flex gap-6 items-start">
                    <div class="text-gray-400 text-[11px] font-bold w-14 pt-1 text-right">
                        <span x-text="isWorkDaySelected() ? '{{ date('H:i', strtotime(Auth::user()->startTime)) }}' : '--:--'"></span>
                        <div class="text-[9px] opacity-50 mt-1 uppercase" x-text="selectedDayName"></div>
                    </div>

                    <div class="relative flex-1 p-5 rounded-2xl shadow-sm border-l-4 bg-white transition-all"
                        :class="isWorkDaySelected() ? 'border-blue-600' : 'border-gray-300 opacity-60 bg-gray-50'">
                        
                        <div class="flex justify-between items-start">
                            <div>
                                <h5 class="font-bold text-sm text-gray-800" 
                                    x-text="isWorkDaySelected() ? 'Shift Kerja ' + selectedDayName : 'Tidak Ada Jadwal / Off'"></h5>
                                <p class="text-[10px] text-gray-400 mt-1" 
                                    x-text="isWorkDaySelected() ? 'Jam Pulang: {{ date('H:i', strtotime(Auth::user()->quitTime)) }}' : 'Nikmati waktu istirahat Anda'"></p>
                            </div>
                            
                            <template x-if="isTodaySelected()">
                                <span class="bg-blue-100 text-blue-600 text-[9px] font-black px-2 py-0.5 rounded-full uppercase">Hari Ini</span>
                            </template>
                        </div>
                        
                        <div class="absolute -left-[32px] top-6 w-3 h-3 rounded-full border-2 border-white transition-colors"
                            :class="isWorkDaySelected() ? 'bg-blue-600 shadow-sm' : 'bg-gray-300'">
                        </div>
                    </div>
                </div>

                <template x-if="!isWorkDaySelected() && !getHistoryForSelectedDate()">
                    <div class="text-center p-6 border-2 border-dashed border-gray-100 rounded-3xl">
                        <p class="text-gray-400 text-xs italic">Keterangan: Hari Libur Mingguan</p>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <script>
        function calendarApp() {
            return {
                month: '',
                year: '',
                no_of_days: [],
                blankdays: [],
                // Default ke hari ini
                selectedDate: new Date().getDate(),
                selectedMonth: new Date().getMonth(),
                selectedYear: new Date().getFullYear(),
                
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                
                // Data dari Backend
                workSchedules: @json(Auth::user()->workSchedule ?? []),
                attendanceHistory: @json($history ?? []),

                init() {
                    let today = new Date();
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    this.getNoOfDays();
                },

                get selectedDateFormatted() {
                    return this.selectedDate + ' ' + this.monthNames[this.selectedMonth] + ' ' + this.selectedYear;
                },

                get selectedDayName() {
                    let d = new Date(this.selectedYear, this.selectedMonth, this.selectedDate);
                    return this.dayNames[d.getDay()];
                },

                isWorkDaySelected() {
                    return this.workSchedules.includes(this.selectedDayName);
                },

                isTodaySelected() {
                    const today = new Date();
                    return today.getDate() === this.selectedDate && 
                           today.getMonth() === this.selectedMonth && 
                           today.getFullYear() === this.selectedYear;
                },

                hasHistory(date) {
                    let dateStr = `${this.year}-${String(this.month + 1).padStart(2, '0')}-${String(date).padStart(2, '0')}`;
                    return this.attendanceHistory.some(h => h.date === dateStr);
                },

                getHistoryForSelectedDate() {
                    let dateStr = `${this.selectedYear}-${String(this.selectedMonth + 1).padStart(2, '0')}-${String(this.selectedDate).padStart(2, '0')}`;
                    return this.attendanceHistory.find(h => h.date === dateStr);
                },

                selectDate(date) {
                    this.selectedDate = date;
                    this.selectedMonth = this.month;
                    this.selectedYear = this.year;
                },

                isSelected(date) {
                    return this.selectedDate === date && this.selectedMonth === this.month && this.selectedYear === this.year;
                },

                isToday(date) {
                    const today = new Date();
                    return today.getDate() === date && today.getMonth() === this.month && today.getFullYear() === this.year;
                },

                getNoOfDays() {
                    let daysInMonth = new Date(this.year, this.month + 1, 0).getDate();
                    let dayOfWeek = new Date(this.year, this.month, 1).getDay();
                    let blankdaysArray = [];
                    for (var i = 1; i <= dayOfWeek; i++) { blankdaysArray.push(i); }
                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) { daysArray.push(i); }
                    this.blankdays = blankdaysArray;
                    this.no_of_days = daysArray;
                },

                prevMonth() {
                    if (this.month === 0) { this.month = 11; this.year--; } else { this.month--; }
                    this.getNoOfDays();
                },

                nextMonth() {
                    if (this.month === 11) { this.month = 0; this.year++; } else { this.month++; }
                    this.getNoOfDays();
                }
            }
        }
    </script>

    <style>
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>