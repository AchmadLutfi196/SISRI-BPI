<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Revisi</h1>
            <a href="{{ route('dosen.validasi-revisi.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Status Badge -->
        @php
            $statusClass = [
                'menunggu' => 'bg-yellow-100 text-yellow-800',
                'disetujui' => 'bg-green-100 text-green-800',
                'revisi_ulang' => 'bg-red-100 text-red-800',
            ];
        @endphp
        <div class="mb-6">
            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClass[$revisi->status] }}">
                @if($revisi->status === 'menunggu')
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Validasi
                @elseif($revisi->status === 'disetujui')
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Revisi Disetujui
                @else
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Perlu Revisi Ulang
                @endif
            </span>
        </div>

        <!-- Mahasiswa Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                <div class="flex items-start gap-4 mb-4">
                    <x-avatar 
                        :src="$revisi->pelaksanaanSidang->pendaftaranSidang->topik->mahasiswa->foto_url" 
                        :initials="$revisi->pelaksanaanSidang->pendaftaranSidang->topik->mahasiswa->initials" 
                        size="xl" 
                    />
                    <div class="flex-1">
                        <p class="text-lg font-semibold text-gray-900">{{ $revisi->pelaksanaanSidang->pendaftaranSidang->topik->mahasiswa->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $revisi->pelaksanaanSidang->pendaftaranSidang->topik->mahasiswa->nim }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Judul Skripsi</p>
                    <p class="font-medium text-gray-900">{{ $revisi->pelaksanaanSidang->pendaftaranSidang->topik->judul }}</p>
                </div>
                <div class="mt-3">
                    <p class="text-sm text-gray-500">Jenis Sidang</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        {{ in_array($revisi->pelaksanaanSidang->pendaftaranSidang->jenis, ['proposal', 'seminar_proposal']) ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                        {{ in_array($revisi->pelaksanaanSidang->pendaftaranSidang->jenis, ['proposal', 'seminar_proposal']) ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Catatan Revisi dari Dosen -->
        @php
            $penguji = \App\Models\PengujiSidang::where('pelaksanaan_sidang_id', $revisi->pelaksanaan_sidang_id)
                ->where('dosen_id', $revisi->dosen_id)
                ->first();
            
            $roleLabel = 'Anda';
            $roleColor = 'orange';
            if ($penguji) {
                $roleLabel = match($penguji->role) {
                    'pembimbing_1' => 'Anda (Pembimbing 1)',
                    'pembimbing_2' => 'Anda (Pembimbing 2)',
                    'penguji_1' => 'Anda (Penguji 1)',
                    'penguji_2' => 'Anda (Penguji 2)',
                    'penguji_3' => 'Anda (Penguji 3)',
                    default => 'Anda'
                };
                $roleColor = str_starts_with($penguji->role, 'pembimbing') ? 'purple' : 'blue';
            }
        @endphp
        @if($penguji && $penguji->catatan_revisi)
            <div class="bg-{{ $roleColor }}-50 border-l-4 border-{{ $roleColor }}-400 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-{{ $roleColor }}-800 mb-3">Catatan Revisi dari {{ $roleLabel }}</h3>
                    <div class="text-gray-700 whitespace-pre-line">{{ $penguji->catatan_revisi }}</div>
                </div>
            </div>
        @endif

        <!-- Revisi Detail -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Revisi</h3>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Submit</p>
                        <p class="font-medium text-gray-900">{{ $revisi->tanggal_submit->format('d F Y, H:i') }} WIB</p>
                    </div>

                    @if($revisi->catatan)
                        <div>
                            <p class="text-sm text-gray-500">Catatan dari Mahasiswa</p>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $revisi->catatan }}</p>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-500 mb-2">File Revisi</p>
                        <a href="{{ route('dosen.validasi-revisi.download', $revisi) }}" target="_blank"
                           class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download File Revisi
                        </a>
                    </div>

                    @if($revisi->tanggal_validasi)
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Validasi</p>
                            <p class="font-medium text-gray-900">{{ $revisi->tanggal_validasi->format('d F Y, H:i') }} WIB</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Forms -->
        @if($revisi->status === 'menunggu')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Approve Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-green-700 mb-4">Setujui Revisi</h3>
                        <form action="{{ route('dosen.validasi-revisi.approve', $revisi) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                <textarea name="catatan_validasi" rows="4" 
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                                          placeholder="Catatan mengenai persetujuan revisi...">{{ old('catatan_validasi') }}</textarea>
                                @error('catatan_validasi')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" 
                                    :disabled="submitting"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 disabled:opacity-50">
                                <svg class="w-5 h-5 mr-2" :class="{ 'animate-spin': submitting }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span x-show="!submitting">Setujui Revisi</span>
                                <span x-show="submitting" x-cloak>Memproses...</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Reject Form -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-red-700 mb-4">Minta Revisi Ulang</h3>
                        <form action="{{ route('dosen.validasi-revisi.reject', $revisi) }}" method="POST" x-data="{ submitting: false }" @submit="submitting = true">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Perbaikan (Wajib)</label>
                                <textarea name="catatan_revisi" rows="4" required
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500"
                                          placeholder="Jelaskan apa yang perlu diperbaiki...">{{ old('catatan_revisi') }}</textarea>
                                @error('catatan_revisi')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" 
                                    :disabled="submitting"
                                    class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 disabled:opacity-50">
                                <svg class="w-5 h-5 mr-2" :class="{ 'animate-spin': submitting }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span x-show="!submitting">Minta Revisi Ulang</span>
                                <span x-show="submitting" x-cloak>Memproses...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
