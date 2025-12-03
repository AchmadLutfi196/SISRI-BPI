<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('admin.koordinator.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 mb-4">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Detail Koordinator Prodi</h1>
        </div>

        <!-- Info Koordinator -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="h-16 w-16 rounded-full bg-purple-100 flex items-center justify-center">
                                <span class="text-purple-600 font-bold text-xl">
                                    {{ substr($koordinator->dosen->user->name ?? '-', 0, 2) }}
                                </span>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-900">
                                {{ $koordinator->dosen->user->name ?? '-' }}
                            </h3>
                            <p class="text-gray-500">{{ $koordinator->dosen->nip ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        @if($koordinator->is_active)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                Tidak Aktif
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Info Dosen -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Informasi Dosen</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-500">NIP</dt>
                                <dd class="text-gray-900">{{ $koordinator->dosen->nip ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">NIDN</dt>
                                <dd class="text-gray-900">{{ $koordinator->dosen->nidn ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Email</dt>
                                <dd class="text-gray-900">{{ $koordinator->dosen->user->email ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Info Prodi -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Program Studi</h4>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm text-gray-500">Nama Prodi</dt>
                                <dd class="text-gray-900">{{ $koordinator->prodi->nama ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Kode Prodi</dt>
                                <dd class="text-gray-900">{{ $koordinator->prodi->kode ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Tahun Mulai</dt>
                                <dd class="text-gray-900">{{ $koordinator->tahun_mulai ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Tahun Selesai</dt>
                                <dd class="text-gray-900">{{ $koordinator->tahun_selesai ?? 'Masih Aktif' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-500">Tanggal Penugasan</dt>
                                <dd class="text-gray-900">{{ $koordinator->created_at->format('d M Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-4">
            <form action="{{ route('admin.koordinator.toggle-status', $koordinator) }}" method="POST">
                @csrf
                @method('PATCH')
                <button type="submit" 
                    class="px-4 py-2 border {{ $koordinator->is_active ? 'border-yellow-500 text-yellow-600 hover:bg-yellow-50' : 'border-green-500 text-green-600 hover:bg-green-50' }} rounded-md transition">
                    {{ $koordinator->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
            @if(!$koordinator->is_active)
                <form action="{{ route('admin.koordinator.destroy', $koordinator) }}" method="POST" 
                    id="delete-koordinator-show">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('delete-koordinator-show', 'Hapus Koordinator', 'Yakin ingin menghapus koordinator ini?')"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                        Hapus
                    </button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
