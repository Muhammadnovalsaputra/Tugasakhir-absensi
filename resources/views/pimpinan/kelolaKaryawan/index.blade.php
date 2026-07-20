<x-app-layout>
    <div class="bg-slate-50/50 min-h-screen pb-12 font-sans text-slate-800">
        {{-- HEADER SECTION --}}
        <div class="bg-white border-b border-slate-100 py-6 mb-8 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-2xl text-slate-800 tracking-tight">Kelola Karyawan</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Manajemen hak akses, informasi data diri, dan peran tim Sinar YT</p>
                    </div>
                </div>
                
                <button onclick="openModal()" class="inline-flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-bold gap-2 transition-all duration-200 shadow-md hover:shadow-indigo-100 active:scale-[0.98]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Karyawan
                </button>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- NOTIFIKASI / ALERTS --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl font-medium text-sm flex items-start gap-3">
                    <span class="text-lg">⚠️</span>
                    <div>
                        <p class="font-bold mb-1">Periksa kembali inputan Anda:</p>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-600/90 text-xs">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if(session('success'))
                <div id="success-message" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-800 rounded-2xl font-semibold text-sm flex items-center gap-3 shadow-sm transition-all">
                    <div class="w-6 h-6 rounded-full bg-emerald-500/10 text-emerald-600 flex items-center justify-center text-xs">✓</div>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div id="error-message" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-800 rounded-2xl font-semibold text-sm flex items-center gap-3 shadow-sm transition-all">
                    <div class="w-6 h-6 rounded-full bg-rose-500/10 text-rose-600 flex items-center justify-center text-xs">✕</div>
                    {{ session('error') }}
                </div>
            @endif

           <div class="mb-6 w-full"> {{-- Mengubah max-w-md menjadi w-full --}}
                <div class="relative shadow-sm rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" id="searchInput" placeholder="Cari nama atau email karyawan..." 
                        class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl font-medium text-sm text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none">
                </div>
            </div>

            {{-- DATATABLE CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-100">
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Karyawan</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Posisi (Role)</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bergabung</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTableBody" class="divide-y divide-slate-100 font-medium text-sm text-slate-700">
                            @forelse($employees as $k)
                            <tr class="hover:bg-slate-50/40 transition duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-10 h-10 rounded-full border border-slate-100 overflow-hidden bg-slate-50 flex-shrink-0">
                                            @if($k->photo)
                                                <img src="{{ asset('storage/'.$k->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="https://ui-avatars.com/api/?background=EEF2FF&color=4F46E5&bold=true&name={{ urlencode($k->name) }}" class="w-full h-full object-cover">
                                            @endif
                                        </div>
                                        <span class="font-bold text-slate-800 tracking-tight">{{ $k->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-500 font-normal">{{ $k->email }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        // Badge styling berdasarkan jenis role agar informatif
                                        $colorClasses = match($k->role) {
                                            'Pimpinan' => 'bg-purple-50 text-purple-600 border-purple-100',
                                            'Admin' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                            'Finance' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'KetuaTeknisi', 'Teknisi' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'Sekretaris' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            default => 'bg-slate-50 text-slate-600 border-slate-100'
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-md border uppercase tracking-wider {{ $colorClasses }}">
                                        {{ $k->role === 'KetuaTeknisi' ? 'Ketua Teknisi' : $k->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-400 text-xs font-normal">{{ $k->created_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2.5">
                                        <button onclick='openEditModal(@json($k))' class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-200" title="Edit Data">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>

                                        <form action="{{ route('pimpinan.kelolaKaryawan.destroy', $k->id) }}" method="POST" class="form-delete inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete(this)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition duration-200" title="Hapus Karyawan">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center max-w-sm mx-auto">
                                        <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 mb-3 shadow-inner">
                                            <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                            </svg>
                                        </div>
                                        <h4 class="text-slate-700 font-bold tracking-wide">Belum Ada Karyawan</h4>
                                        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Klik "Tambah Karyawan" untuk mulai menambahkan data tim.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                </div>

                {{-- PAGINATION --}}
                @if ($employees->hasPages())
                <div id="paginationContainer" class="px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-xs font-semibold text-slate-500 tracking-wide">
                            Menampilkan <span class="text-slate-800 font-bold">{{ $employees->firstItem() }}</span> sampai <span class="text-slate-800 font-bold">{{ $employees->lastItem() }}</span> dari <span class="text-indigo-600 font-extrabold">{{ $employees->total() }}</span> total karyawan
                        </p>
                        <div class="w-full sm:w-auto">
                            {{ $employees->links() }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

{{-- MODAL 1: TAMBAH KARYAWAN --}}
<div id="modalKaryawan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" onclick="closeModal()"></div>
        
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:max-w-lg sm:w-full">
            <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Tambah Karyawan Baru</h3>
                <button onclick="closeModal()" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('pimpinan.kelolaKaryawan.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none" placeholder="Nama Lengkap">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Email (Akun Login)</label>
                        <input type="email" name="email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none" placeholder="nama@sinaryt.com">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Password</label>
                            <input type="password" name="password" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none" placeholder="Min. 8 Karakter">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Posisi (Role)</label>
                            <select name="role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none appearance-none cursor-pointer">
                                <option value="Finance">Finance</option>
                                <option value="KetuaTeknisi">Ketua Teknisi</option>
                                <option value="Teknisi">Teknisi</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Admin">Admin</option>
                                <option value="Pimpinan">Pimpinan</option>
                            </select>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 pt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 ml-0.5">Foto Profil</label>
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full border-2 border-slate-100 bg-slate-50 flex items-center justify-center text-slate-300 overflow-hidden flex-shrink-0" id="create_photo_preview_container">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" id="create_photo_input" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="mt-1 text-[11px] text-slate-400">*Maksimal file dokumen 2MB (Format JPG/PNG)</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="w-full sm:w-1/3 px-4 py-3 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition order-2 sm:order-1">Batal</button>
                    <button type="submit" class="w-full sm:w-2/3 px-4 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md hover:shadow-indigo-100 transition order-1 sm:order-2">Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 2: EDIT KARYAWAN --}}
<div id="modalEditKaryawan" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-slate-900/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
        
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:max-w-lg sm:w-full">
            <div class="px-6 py-5 bg-white border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-base font-bold text-slate-800 tracking-tight">Ubah Data Karyawan</h3>
                <button onclick="closeEditModal()" class="p-1.5 rounded-lg text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="editFormKaryawan" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Alamat Email</label>
                        <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-medium text-sm text-slate-800 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-amber-600 uppercase tracking-wide mb-1.5 ml-0.5">Kata Sandi Baru</label>
                            <input type="password" name="password" id="edit_password" class="w-full px-4 py-2.5 bg-slate-50 border border-amber-200 rounded-xl font-medium text-sm text-slate-800 focus:bg-white focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 transition outline-none" placeholder="Isi jika ingin diganti">
                            <p class="mt-1 text-[10px] text-slate-400 leading-normal">*Biarkan kosong jika tidak diganti</p>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5 ml-0.5">Posisi (Role)</label>
                            <select name="role" id="edit_role" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl font-bold text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 transition outline-none cursor-pointer">
                                <option value="Finance">Finance</option>
                                <option value="KetuaTeknisi">Ketua Teknisi</option>
                                <option value="Teknisi">Teknisi</option>
                                <option value="Sekretaris">Sekretaris</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Admin">Admin</option>
                                <option value="Pimpinan">Pimpinan</option>
                            </select>
                        </div>
                    </div>
                    <div class="border-t border-slate-100 pt-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2 ml-0.5">Foto Profil Anggota</label>
                        <div class="flex items-center gap-4">
                            <div id="current_photo_container" class="flex-shrink-0">
                                <img id="current_photo" src="" alt="Foto Karyawan" class="w-14 h-14 rounded-full object-cover border-2 border-slate-200 shadow-sm">
                            </div>
                            <div class="flex-1">
                                <input type="file" name="photo" id="edit_photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="mt-1 text-[11px] text-slate-400">*Gunakan file PNG/JPG ukuran maksimum 2MB</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="w-full sm:w-1/3 px-4 py-3 text-sm font-bold text-slate-500 bg-slate-100 rounded-xl hover:bg-slate-200 transition order-2 sm:order-1">Batal</button>
                    <button type="submit" class="w-full sm:w-2/3 px-4 py-3 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-md hover:shadow-indigo-100 transition order-1 sm:order-2">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    function confirmDelete(button) {
        const form = button.closest('.form-delete');
        
        Swal.fire({
            title: 'Hapus Akun Karyawan?', 
            text: 'Seluruh riwayat tugas, login absensi, dan data profil karyawan terkait akan dihapus permanen dari basis data perusahaan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48', 
            cancelButtonColor: '#f1f5f9',  
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batalkan',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl p-5',
                confirmButton: 'px-5 py-2.5 rounded-xl font-bold text-white shadow-lg shadow-rose-100 text-sm',
                cancelButton: 'px-5 py-2.5 rounded-xl font-bold text-slate-500 text-sm'
            },
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    setTimeout(function() {
        ['success-message', 'error-message'].forEach(id => {
            const element = document.getElementById(id);
            if (element) {
                element.style.transition = "all 0.5s cubic-bezier(0.4, 0, 0.2, 1)";
                element.style.opacity = "0";
                element.style.transform = "translateY(-10px)";
                setTimeout(() => element.remove(), 500);
            }
        });
    }, 4000);

    function openModal() {
        document.getElementById('modalKaryawan').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal() {
        document.getElementById('modalKaryawan').classList.add('hidden');
        document.body.style.overflow = 'auto';    
    }

    
    function openEditModal(user) {
        const form = document.getElementById('editFormKaryawan');
        let updateUrl = "{{ route('pimpinan.kelolaKaryawan.update', ':id') }}";
        form.action = updateUrl.replace(':id', user.id);

        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_password').value = ''; 
        
        const currentPhoto = document.getElementById('current_photo');
        if (user.photo) {
            currentPhoto.src = `/storage/${user.photo}`;
        } else {
            currentPhoto.src = `https://ui-avatars.com/api/?background=EEF2FF&color=4F46E5&bold=true&name=${encodeURIComponent(user.name)}`;
        }

        document.getElementById('modalEditKaryawan').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('modalEditKaryawan').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editFormKaryawan').action = '';
        document.getElementById('edit_photo').value = '';
        document.getElementById('edit_password').value = '';
    }

    
    function setupImagePreview(inputId, containerId, isAvatar = false) {
        document.getElementById(inputId)?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const container = document.getElementById(containerId);
                    if (isAvatar) {
                        container.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    } else {
                        const previewImg = container.querySelector('img');
                        if (previewImg) previewImg.src = e.target.result;
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    }

    setupImagePreview('create_photo_input', 'create_photo_preview_container', true);
    setupImagePreview('edit_photo', 'current_photo_container', false);


    document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const tableBody = document.getElementById('employeeTableBody');
    const paginationContainer = document.getElementById('paginationContainer');
    
    let debounceTimer;

    // Event listener saat user mengetik
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        
        // Menunggu user selesai mengetik selama 300ms
        debounceTimer = setTimeout(() => {
            fetchEmployees(this.value);
        }, 300);
    });

    // Fungsi untuk mengambil data via Fetch API
    function fetchEmployees(keyword = '', url = null) {
        // Jika url tidak ditentukan, gunakan current URL + query parameter
        let fetchUrl = url ? url : `${window.location.pathname}?search=${encodeURIComponent(keyword)}`;

        fetch(fetchUrl, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            renderTable(data.data);
            renderPagination(data);
        })
        .catch(error => console.error('Error fetching data:', error));
    }

    // Fungsi render ulang baris tabel
    function renderTable(employees) {
        tableBody.innerHTML = '';

        if (employees.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center max-w-sm mx-auto">
                            <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-400 mb-3 shadow-inner">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                            <h4 class="text-slate-700 font-bold tracking-wide">Karyawan Tidak Ditemukan</h4>
                            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Tidak ada hasil yang cocok dengan kata kunci pencarian Anda.</p>
                        </div>
                    </td>
                </tr>`;
            return;
        }

        employees.forEach(k => {
            // Logika warna badge Role
            let colorClasses = '';
            switch(k.role) {
                case 'Pimpinan': colorClasses = 'bg-purple-50 text-purple-600 border-purple-100'; break;
                case 'Admin': colorClasses = 'bg-indigo-50 text-indigo-600 border-indigo-100'; break;
                case 'Finance': colorClasses = 'bg-emerald-50 text-emerald-600 border-emerald-100'; break;
                case 'KetuaTeknisi':
                case 'Teknisi': colorClasses = 'bg-blue-50 text-blue-600 border-blue-100'; break;
                case 'Sekretaris': colorClasses = 'bg-amber-50 text-amber-600 border-amber-100'; break;
                default: colorClasses = 'bg-slate-50 text-slate-600 border-slate-100';
            }

            let roleName = k.role === 'KetuaTeknisi' ? 'Ketua Teknisi' : k.role;
            let photoUrl = k.photo ? `/storage/${k.photo}` : `https://ui-avatars.com/api/?background=EEF2FF&color=4F46E5&bold=true&name=${encodeURIComponent(k.name)}`;
            
            // Format Tanggal (Sederhana)
            let date = new Date(k.created_at);
            let formattedDate = date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });

            // Stringify user object untuk fungsi edit modal safely
            let userJson = JSON.stringify(k).replace(/'/g, "&apos;");

            let tr = document.createElement('tr');
            tr.className = "hover:bg-slate-50/40 transition duration-150";
            tr.innerHTML = `
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-full border border-slate-100 overflow-hidden bg-slate-50 flex-shrink-0">
                            <img src="${photoUrl}" class="w-full h-full object-cover">
                        </div>
                        <span class="font-bold text-slate-800 tracking-tight">${k.name}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-slate-500 font-normal">${k.email}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 text-[11px] font-bold rounded-md border uppercase tracking-wider ${colorClasses}">
                        ${roleName}
                    </span>
                </td>
                <td class="px-6 py-4 text-slate-400 text-xs font-normal">${formattedDate}</td>
                <td class="px-6 py-4 text-center">
                    <div class="flex items-center justify-center gap-2.5">
                        <button onclick='openEditModal(${userJson})' class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition duration-200" title="Edit Data">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </button>
                        <form action="/pimpinan/kelolaKaryawan/${k.id}" method="POST" class="form-delete inline-block">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]')?.content || ''}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" onclick="confirmDelete(this)" class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition duration-200" title="Hapus Karyawan">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            `;
            tableBody.appendChild(tr);
        });
    }

    // Memperbarui pagination text & link secara asinkronus
    function renderPagination(data) {
        if (!data.links || data.total <= data.per_page) {
            paginationContainer.innerHTML = '';
            return;
        }

        let linksHtml = '';
        // Membangun ulang struktur link pagination bawaan Tailwind Laravel (sederhana)
        data.links.forEach(link => {
            let activeClass = link.active 
                ? 'bg-indigo-600 text-white border-indigo-600' 
                : 'bg-white text-slate-600 hover:bg-slate-50 border-slate-200';
            let disabledAttr = link.url ? '' : 'disabled';
            
            linksHtml += `
                <button ${disabledAttr} data-url="${link.url}" class="pagination-link px-3 py-1.5 border text-xs font-bold rounded-lg transition ${activeClass} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}">
                    ${link.label}
                </button>
            `;
        });

        paginationContainer.innerHTML = `
            <div class="px-6 py-4 bg-slate-50/80 border-t border-slate-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-xs font-semibold text-slate-500 tracking-wide">
                        Menampilkan <span class="text-slate-800 font-bold">${data.from || 0}</span> sampai <span class="text-slate-800 font-bold">${data.to || 0}</span> dari <span class="text-indigo-600 font-extrabold">${data.total}</span> total karyawan
                    </p>
                    <div class="w-full sm:w-auto flex items-center justify-center gap-1">
                        ${linksHtml}
                    </div>
                </div>
            </div>
        `;

        // Daftarkan kembali event click untuk link pagination baru
        document.querySelectorAll('.pagination-link').forEach(button => {
            button.addEventListener('click', function () {
                let url = this.getAttribute('data-url');
                if (url) fetchEmployees('', url);
            });
        });
    }
});

</script>