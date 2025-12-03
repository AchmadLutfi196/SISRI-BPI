<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Data Dosen</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola data dosen di sistem</p>
            </div>
            <a href="{{ route('admin.dosen.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Dosen
            </a>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <form action="{{ route('admin.dosen.index') }}" method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, NIP, atau NIDN..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <select name="prodi" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Semua Prodi</option>
                        @foreach(\App\Models\Unit::prodi()->get() as $prodi)
                            <option value="{{ $prodi->id }}" {{ request('prodi') == $prodi->id ? 'selected' : '' }}>
                                {{ $prodi->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm">
                    Filter
                </button>
                @if(request()->hasAny(['search', 'prodi', 'status']))
                    <a href="{{ route('admin.dosen.index') }}" class="px-4 py-2 text-gray-500 hover:text-gray-700 text-sm">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($dosen->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dosen</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIP/NIDN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prodi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($dosen as $index => $dsn)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $dosen->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                    <span class="text-purple-600 font-medium text-sm">{{ substr($dsn->user->name ?? $dsn->nama ?? 'D', 0, 1) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $dsn->user->name ?? $dsn->nama }}</div>
                                                    <div class="text-sm text-gray-500">{{ $dsn->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $dsn->nidn ?? '-' }}</div>
                                            <div class="text-sm text-gray-500">{{ $dsn->nip ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $dsn->prodi->nama ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($dsn->user->is_active ?? false)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.dosen.show', $dsn) }}" class="text-blue-600 hover:text-blue-900" title="Detail">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </a>
                                                <a href="{{ route('admin.dosen.edit', $dsn) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                                <button type="button" onclick="openResetPasswordModal({{ $dsn->user->id }}, '{{ $dsn->user->name ?? $dsn->nama }}')" 
                                                    class="text-purple-600 hover:text-purple-900" title="Reset Password">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                                    </svg>
                                                </button>
                                                <form action="{{ route('admin.dosen.destroy', $dsn) }}" method="POST" class="inline" id="delete-dsn-{{ $dsn->id }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="confirmDelete('delete-dsn-{{ $dsn->id }}', '{{ $dsn->nama }}')" class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                    <div class="mt-6">
                        {{ $dosen->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m6 5.197v1"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada dosen yang terdaftar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div id="resetPasswordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeResetPasswordModal()">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Reset Password</h3>
                    <p class="text-sm text-gray-500 mb-4">Reset password untuk <strong id="resetUserName"></strong></p>
                    <form id="resetPasswordForm" action="" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                            <input type="password" name="password" required minlength="8"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" required minlength="8"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <input type="checkbox" id="useDefaultPassword" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleDefaultPassword()">
                            <label for="useDefaultPassword" class="text-sm text-gray-700">Gunakan password default (password123)</label>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" onclick="closeResetPasswordModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openResetPasswordModal(userId, userName) {
            document.getElementById('resetPasswordModal').classList.remove('hidden');
            document.getElementById('resetUserName').textContent = userName;
            document.getElementById('resetPasswordForm').action = '/admin/dosen/' + userId + '/reset-password';
        }
        
        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('hidden');
        }
        
        function toggleDefaultPassword() {
            const checkbox = document.getElementById('useDefaultPassword');
            const passwordInputs = document.querySelectorAll('#resetPasswordForm input[type="password"]');
            if (checkbox.checked) {
                passwordInputs.forEach(input => {
                    input.value = 'password123';
                    input.readOnly = true;
                    input.classList.add('bg-gray-100');
                });
            } else {
                passwordInputs.forEach(input => {
                    input.value = '';
                    input.readOnly = false;
                    input.classList.remove('bg-gray-100');
                });
            }
        }
    </script>
</x-app-layout>
