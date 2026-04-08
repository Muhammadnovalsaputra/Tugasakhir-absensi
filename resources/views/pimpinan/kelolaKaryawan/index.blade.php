<x-app-layout>
        <div class="p-8">
    <div class="p-8 bg-gray-50 min-h-screen">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Kelola Karyawan</h2>
            @if(session('success'))
                <div id="success-message" class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif
            <button onclick="openModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 transition duration-200 shadow-lg shadow-blue-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" /></svg>
                Tambah Karyawan
            </button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Posisi (Role)</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($employes as $k)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                @if($k->photo)
                                    <img src="{{ asset('storage/'.$k->photo) }}" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">
                                        {{ substr($k->name, 0, 1) }}
                                    </div>
                                @endif
                                <span class="font-bold text-gray-800">{{ $k->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $k->email }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-50 text-blue-600 text-xs font-bold rounded-full border border-blue-100 uppercase">
                                {{ $k->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $k->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-3">
                                <button onclick="openEditModal({{ json_encode($k) }})" class="text-gray-400 hover:text-blue-600 transition duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <form action="{{ route('kelolaKaryawan.destroy', $k->id) }}" method="POST" class="form-delete">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="text-gray-400 hover:text-red-600 transition duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
</x-app-layout>

<div id="modalKaryawan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" onclick="closeModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-8 py-6 bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Tambah Karyawan Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form action="{{ route('kelolaKaryawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" placeholder="Contoh: Ahmad Rizki">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email (Untuk Login)</label>
                            <input type="email" name="email" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" placeholder="nama@sinaryt.com">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                                <input type="password" name="password" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition" placeholder="Min. 8 Karakter">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi (Role)</label>
                                <select name="role" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition">
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

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Foto Profil</label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                            <p class="mt-1 text-xs text-gray-400 font-medium">*Maksimal 2MB (JPG/PNG)</p>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeModal()" class="flex-1 px-4 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Simpan Karyawan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modalEditKaryawan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-50" onclick="closeEditModal()"></div>

        <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="px-8 py-6 bg-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Edit Data Karyawan</h3>
                    <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <form id="editFormKaryawan" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" id="edit_name" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1 text-orange-600">Password Baru</label>
                                <input type="password" name="password" class="w-full px-4 py-2.5 bg-gray-50 border border-orange-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500" placeholder="Isi jika ganti">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Posisi (Role)</label>
                                <select name="role" id="edit_role" required class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl outline-none">
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

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ganti Foto Profil</label>
                            <input type="file" name="photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 cursor-pointer">
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3">
                        <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-3 text-sm font-bold text-gray-500 bg-gray-100 rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" class="flex-1 px-4 py-3 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmDelete(button) {
        const form = button.closest('.form-delete');
        
        Swal.fire({
            title: 'Apakah Kamu Yakin?', 
            text: 'Tindakan ini tidak dapat dibatalkan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ed0202', 
            cancelButtonColor: '#f3f4f6',  
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'px-6 py-2 rounded-xl font-bold shadow-lg shadow-blue-100',
                cancelButton: 'px-6 py-2 rounded-xl font-bold text-gray-500'
            },
            buttonsStyling: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // 2. Auto-hide pesan sukses (3 detik)
    @if(session('success'))
    setTimeout(function() {
        const message = document.getElementById('success-message');
        if (message) {
            message.style.transition = "opacity 0.5s ease";
            message.style.opacity = "0";
            setTimeout(() => message.remove(), 500);
        }
    }, 3000); 
    @endif

    // 3. Fungsi Modal Tambah
    function openModal() {
        document.getElementById('modalKaryawan').classList.remove('hidden');
        document.body.style.overflow = 'hidden'; 
    }

    function closeModal() {
        document.getElementById('modalKaryawan').classList.add('hidden');
        document.body.style.overflow = 'auto';   
    }

    // 4. Fungsi Modal Edit
    function openEditModal(user) {
        const form = document.getElementById('editFormKaryawan');
        form.action = `/pimpinan/kelolaKaryawan/${user.id}`;
        
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;

        document.getElementById('modalEditKaryawan').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('modalEditKaryawan').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>