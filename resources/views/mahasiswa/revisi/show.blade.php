<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Submit Revisi Pasca Sidang</h1>
                <p class="text-gray-600 mt-1">
                    {{ $pelaksanaan->pendaftaranSidang->jenis === 'proposal' ? 'Seminar Proposal' : 'Sidang Skripsi' }} - 
                    {{ \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->format('d F Y') }}
                </p>
            </div>
            <a href="{{ route('mahasiswa.revisi.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Info Topik -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Skripsi</h3>
                <p class="text-gray-700">{{ $pelaksanaan->pendaftaranSidang->topik->judul }}</p>
            </div>
        </div>

        @if($allApproved)
            <!-- All Approved Alert -->
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">Revisi Selesai</h3>
                        <p class="text-sm text-green-700 mt-1">Semua revisi telah disetujui oleh penguji. Menunggu nilai akhir dari koordinator.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Revisi Sections -->
        <div class="space-y-6">
            @forelse($pengujiList as $index => $penguji)
                @php
                    $revisi = $revisiList[$penguji->dosen_id] ?? null;
                    $hasRevisi = $revisi !== null;
                    $statusClass = [
                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                        'disetujui' => 'bg-green-100 text-green-800',
                        'revisi_ulang' => 'bg-red-100 text-red-800',
                    ];
                @endphp
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Header Section -->
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                @php
                                    $roleLabel = match($penguji->role) {
                                        'penguji_1' => 'Penguji 1',
                                        'penguji_2' => 'Penguji 2',
                                        'penguji_3' => 'Penguji 3',
                                        default => ucwords(str_replace('_', ' ', $penguji->role))
                                    };
                                @endphp
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $roleLabel }}: {{ $penguji->dosen->user->name }}
                                </h3>
                                <span class="text-sm text-gray-500">{{ $penguji->dosen->nidn ?? '-' }}</span>
                            </div>
                            
                            @if($hasRevisi)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$revisi->status] }}">
                                    @if($revisi->status === 'menunggu')
                                        Menunggu Validasi
                                    @elseif($revisi->status === 'disetujui')
                                        ✓ Disetujui
                                    @else
                                        Perlu Diperbaiki
                                    @endif
                                </span>
                            @endif
                        </div>

                        <!-- Catatan Revisi dari Dosen -->
                        @if($penguji->catatan_revisi)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Revisi dari {{ $roleLabel }}:</label>
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-sm text-gray-700 whitespace-pre-line">
                                    {{ $penguji->catatan_revisi }}
                                </div>
                            </div>
                        @endif

                        <!-- Show existing revisi info or upload form -->
                        @if($hasRevisi)
                            <!-- Existing Revisi -->
                            <div class="border-t pt-4 mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">File yang Diupload:</label>
                                        <a href="{{ route('mahasiswa.revisi.download', $revisi) }}" target="_blank"
                                           class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-sm">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download File
                                        </a>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Submit:</label>
                                        <p class="text-sm text-gray-600">{{ $revisi->tanggal_submit->format('d F Y, H:i') }} WIB</p>
                                    </div>
                                </div>

                                @if($revisi->catatan)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Anda:</label>
                                        <div class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700">
                                            {{ $revisi->catatan }}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($revisi->status === 'revisi_ulang' && $revisi->catatan_validasi)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Perbaikan dari Penguji:</label>
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-gray-700">
                                            {{ $revisi->catatan_validasi }}
                                        </div>
                                    </div>
                                @endif
                                
                                @if($revisi->status === 'disetujui' && $revisi->catatan_validasi)
                                    <div class="mt-4">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Persetujuan dari Penguji:</label>
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-sm text-gray-700">
                                            {{ $revisi->catatan_validasi }}
                                        </div>
                                    </div>
                                @endif

                                @if($revisi->status === 'disetujui' && $revisi->tanggal_validasi)
                                    <div class="mt-4 bg-green-50 rounded-lg p-3">
                                        <p class="text-sm text-green-800">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Disetujui pada {{ $revisi->tanggal_validasi->format('d F Y, H:i') }} WIB
                                        </p>
                                    </div>
                                @endif
                                
                                <!-- Allow resubmit if revisi_ulang -->
                                @if($revisi->status === 'revisi_ulang')
                                    <div class="mt-4 pt-4 border-t">
                                        <p class="text-sm font-medium text-red-600 mb-3">Upload revisi yang sudah diperbaiki:</p>
                                        <form action="{{ route('mahasiswa.revisi.submit', [$pelaksanaan, $penguji->dosen_id]) }}" 
                                              method="POST" 
                                              enctype="multipart/form-data"
                                              x-data="{ uploading: false }"
                                              @submit="uploading = true">
                                            @csrf
                                            
                                            <div class="space-y-4">
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi (PDF, DOC, DOCX - Max 10MB)</label>
                                                    <input type="file" name="file_revisi" accept=".pdf,.doc,.docx" required
                                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                                    @error('file_revisi')
                                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                                    <textarea name="catatan_mahasiswa" rows="3" 
                                                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                              placeholder="Catatan mengenai revisi yang telah dilakukan...">{{ old('catatan_mahasiswa') }}</textarea>
                                                    @error('catatan_mahasiswa')
                                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                
                                                <button type="submit" 
                                                        :disabled="uploading"
                                                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 disabled:opacity-50">
                                                    <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': uploading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                    <span x-show="!uploading">Submit Ulang Revisi</span>
                                                    <span x-show="uploading" x-cloak>Uploading...</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Upload Form -->
                            @if($penguji->catatan_revisi)
                                <form action="{{ route('mahasiswa.revisi.submit', [$pelaksanaan, $penguji->dosen_id]) }}" 
                                      method="POST" 
                                      enctype="multipart/form-data"
                                      class="mt-4"
                                      x-data="{ uploading: false }"
                                      @submit="uploading = true">
                                    @csrf
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">File Revisi (PDF, DOC, DOCX - Max 10MB)</label>
                                            <input type="file" name="file_revisi" accept=".pdf,.doc,.docx" required
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                            @error('file_revisi')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                                            <textarea name="catatan_mahasiswa" rows="3" 
                                                      class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                      placeholder="Catatan mengenai revisi yang telah dilakukan...">{{ old('catatan_mahasiswa') }}</textarea>
                                            @error('catatan_mahasiswa')
                                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" 
                                                :disabled="uploading"
                                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 disabled:opacity-50">
                                            <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': uploading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                            </svg>
                                            <span x-show="!uploading">Submit Revisi</span>
                                            <span x-show="uploading" x-cloak>Uploading...</span>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="mt-4 bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-600">Penguji ini belum memberikan catatan revisi.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            @empty
                <!-- No Revisi Needed -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 mx-auto text-green-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak Ada Revisi</h3>
                        <p class="text-gray-500">Tidak ada penguji yang memberikan catatan revisi. Sidang Anda sudah selesai!</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
