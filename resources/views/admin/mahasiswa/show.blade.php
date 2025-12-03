<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Flash Message -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Mahasiswa</h1>
                <p class="text-sm text-gray-500 mt-1">{{ $mahasiswa->user->name ?? $mahasiswa->nama }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.mahasiswa.edit', $mahasiswa) }}"
                    class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('admin.mahasiswa.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-3xl">{{ substr($mahasiswa->user->name ?? $mahasiswa->nama ?? 'M', 0, 1) }}</span>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">{{ $mahasiswa->user->name ?? $mahasiswa->nama }}</h3>
                        <p class="text-sm text-gray-500">{{ $mahasiswa->nim }}</p>
                        <div class="mt-2">
                            @if($mahasiswa->user->is_active ?? false)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Reset Password Button -->
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <form action="{{ route('admin.mahasiswa.reset-password', $mahasiswa) }}" method="POST" 
                            id="reset-password-mahasiswa">
                            @csrf
                            <button type="button" onclick="confirmAction('reset-password-mahasiswa', 'Reset Password', 'Yakin ingin mereset password mahasiswa ini ke default (password123)?', 'Reset', 'warning')" class="w-full inline-flex justify-center items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Reset Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Detail Info -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Mahasiswa</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIM</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->nim }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->user->name ?? $mahasiswa->nama }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">No. HP</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->no_hp ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Program Studi</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->prodi->nama ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Angkatan</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->angkatan }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Daftar</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $mahasiswa->created_at->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Topik Skripsi -->
                @if($mahasiswa->topikSkripsi->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Topik Skripsi</h3>
                            @foreach($mahasiswa->topikSkripsi as $topik)
                                <div class="bg-gray-50 p-4 rounded-lg {{ !$loop->last ? 'mb-3' : '' }}">
                                    <h4 class="font-medium text-gray-900">{{ $topik->judul }}</h4>
                                    <p class="text-sm text-gray-500 mt-1">Bidang Minat: {{ $topik->bidangMinat->nama ?? '-' }}</p>
                                    <div class="mt-2">
                                        @php
                                            $statusClass = match($topik->status) {
                                                'diterima' => 'bg-green-100 text-green-800',
                                                'ditolak' => 'bg-red-100 text-red-800',
                                                default => 'bg-yellow-100 text-yellow-800',
                                            };
                                        @endphp
                                        <span class="px-2 py-1 text-xs rounded-full {{ $statusClass }}">
                                            {{ ucfirst($topik->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
