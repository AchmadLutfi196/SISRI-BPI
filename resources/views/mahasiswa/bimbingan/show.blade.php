<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('mahasiswa.bimbingan.index', ['jenis' => $bimbingan->jenis]) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Bimbingan
            </a>
        </div>

        <!-- Detail Card -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-semibold text-gray-800">Detail Bimbingan {{ ucfirst($bimbingan->jenis) }}</h1>
                            <p class="text-sm text-gray-500">{{ $bimbingan->created_at->format('d F Y, H:i') }} WIB</p>
                        </div>
                    </div>
                    
                    <!-- Status Badge -->
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium
                        {{ $bimbingan->status === 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $bimbingan->status === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                        {{ $bimbingan->status === 'direvisi' ? 'bg-orange-100 text-orange-800' : '' }}
                        {{ $bimbingan->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                        @switch($bimbingan->status)
                            @case('disetujui')
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                Disetujui
                                @break
                            @case('direvisi')
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                </svg>
                                Perlu Revisi
                                @break
                            @case('ditolak')
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                                Ditolak
                                @break
                            @default
                                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                                Menunggu Konfirmasi
                        @endswitch
                    </span>
                </div>
            </div>

            <!-- Body -->
            <div class="p-6">
                <!-- Dosen Info -->
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Dosen Pembimbing</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-sm font-bold text-blue-600">{{ substr($bimbingan->dosen->nama ?? 'X', 0, 1) }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $bimbingan->dosen->nama ?? '-' }}</p>
                            <p class="text-sm text-gray-500">{{ $bimbingan->dosen->nidn ?? $bimbingan->dosen->nip ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pokok Bimbingan -->
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Pokok Bimbingan</h3>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-800 whitespace-pre-line">{{ $bimbingan->pokok_bimbingan }}</p>
                    </div>
                </div>

                <!-- Pesan Mahasiswa -->
                @if($bimbingan->pesan_mahasiswa)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Pesan Anda</h3>
                    <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                        <p class="text-blue-800 whitespace-pre-line">{{ $bimbingan->pesan_mahasiswa }}</p>
                    </div>
                </div>
                @endif

                <!-- File Bimbingan -->
                @if($bimbingan->file_bimbingan)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Dokumen Yang Diupload</h3>
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                        <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ basename($bimbingan->file_bimbingan) }}</p>
                            <p class="text-xs text-gray-500">Dokumen bimbingan</p>
                        </div>
                        <a href="{{ Storage::url($bimbingan->file_bimbingan) }}" target="_blank"
                           class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- Catatan Dosen (Response) -->
                @if($bimbingan->pesan_dosen)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Catatan dari Dosen</h3>
                    <div class="p-4 rounded-lg
                        {{ $bimbingan->status === 'disetujui' ? 'bg-green-50 border border-green-200' : '' }}
                        {{ $bimbingan->status === 'direvisi' ? 'bg-orange-50 border border-orange-200' : '' }}
                        {{ $bimbingan->status === 'ditolak' ? 'bg-red-50 border border-red-200' : '' }}">
                        <p class="whitespace-pre-line
                            {{ $bimbingan->status === 'disetujui' ? 'text-green-800' : '' }}
                            {{ $bimbingan->status === 'direvisi' ? 'text-orange-800' : '' }}
                            {{ $bimbingan->status === 'ditolak' ? 'text-red-800' : '' }}">{{ $bimbingan->pesan_dosen }}</p>
                    </div>
                </div>
                @endif

                <!-- File Revisi (if uploaded) -->
                @if($bimbingan->file_revisi)
                <div class="mb-6 pb-6 border-b">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Dokumen Revisi Yang Diupload</h3>
                    <div class="flex items-center gap-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <svg class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800">{{ basename($bimbingan->file_revisi) }}</p>
                            <p class="text-xs text-gray-500">Dokumen revisi</p>
                        </div>
                        <a href="{{ Storage::url($bimbingan->file_revisi) }}" target="_blank"
                           class="px-4 py-2 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- Upload Revisi Form (only if status is 'direvisi') -->
                @if($bimbingan->status === 'direvisi')
                <div class="p-6 bg-orange-50 rounded-lg border border-orange-200">
                    <h3 class="text-lg font-semibold text-orange-800 mb-4">Upload Revisi</h3>
                    <p class="text-sm text-orange-700 mb-4">Dosen meminta Anda untuk melakukan revisi. Silakan upload dokumen yang sudah direvisi.</p>
                    
                    <form action="{{ route('mahasiswa.bimbingan.upload-revisi', $bimbingan) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Upload File -->
                        <div class="mb-4">
                            <label for="file_revisi" class="block text-sm font-medium text-orange-800 mb-1">
                                File Revisi <span class="text-red-500">*</span>
                            </label>
                            <div id="upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-orange-300 border-dashed rounded-lg bg-white hover:border-orange-400 transition-colors cursor-pointer">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-10 w-10 text-orange-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file_revisi" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500">
                                            <span>Pilih file</span>
                                            <input id="file_revisi" name="file_revisi" type="file" class="sr-only" accept=".pdf,.doc,.docx" required>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, DOC, DOCX hingga 10MB</p>
                                </div>
                            </div>
                            <!-- File Preview -->
                            <div id="file-preview" class="hidden mt-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p id="file-name" class="text-sm font-medium text-green-800 truncate"></p>
                                        <p id="file-size" class="text-xs text-green-600"></p>
                                    </div>
                                    <button type="button" id="remove-file" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            @error('file_revisi')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pesan Tambahan -->
                        <div class="mb-4">
                            <label for="pesan_mahasiswa" class="block text-sm font-medium text-orange-800 mb-1">
                                Catatan Revisi (Opsional)
                            </label>
                            <textarea name="pesan_mahasiswa" id="pesan_mahasiswa" rows="3"
                                      class="w-full px-4 py-2 border border-orange-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500"
                                      placeholder="Jelaskan perubahan yang sudah Anda lakukan...">{{ old('pesan_mahasiswa') }}</textarea>
                        </div>

                        <button type="submit" 
                                class="w-full px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Upload Revisi
                        </button>
                    </form>
                </div>
                @endif

                <!-- Timeline -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500 mb-4">Riwayat</h3>
                    <div class="space-y-4">
                        @forelse($bimbingan->histories->sortBy('created_at') as $history)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                @if($history->status === 'disetujui') bg-green-100
                                @elseif($history->status === 'direvisi') bg-orange-100
                                @elseif($history->status === 'ditolak') bg-red-100
                                @else bg-blue-100
                                @endif">
                                @if($history->status === 'disetujui')
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($history->status === 'direvisi')
                                    <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                @elseif($history->status === 'ditolak')
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-800">
                                    @if($history->aksi === 'diajukan')
                                        Mengajukan bimbingan
                                    @elseif($history->aksi === 'upload_revisi')
                                        Mengupload revisi
                                    @elseif($history->aksi === 'direspon')
                                        @if($history->status === 'disetujui')
                                            Menyetujui bimbingan
                                        @elseif($history->status === 'direvisi')
                                            Meminta revisi
                                        @else
                                            Merespon bimbingan
                                        @endif
                                    @else
                                        {{ $history->catatan ?? 'Update status' }}
                                    @endif
                                    <span class="text-gray-500 font-normal">
                                        — {{ $history->oleh === 'mahasiswa' ? 'Anda' : 'Dosen' }}
                                    </span>
                                </p>
                                <p class="text-xs text-gray-500">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                @if($history->file)
                                <a href="{{ Storage::url($history->file) }}" target="_blank" 
                                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-1">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ basename($history->file) }}
                                </a>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Bimbingan diajukan</p>
                                <p class="text-xs text-gray-500">{{ $bimbingan->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show selected file name for revisi with preview
        var revisiInput = document.getElementById('file_revisi');
        var uploadArea = document.getElementById('upload-area');
        var filePreview = document.getElementById('file-preview');
        var fileName = document.getElementById('file-name');
        var fileSize = document.getElementById('file-size');
        var removeFile = document.getElementById('remove-file');

        if (revisiInput) {
            revisiInput.addEventListener('change', function(e) {
                if (e.target.files[0]) {
                    var file = e.target.files[0];
                    fileName.textContent = file.name;
                    
                    // Format file size
                    var size = file.size;
                    if (size < 1024) {
                        fileSize.textContent = size + ' bytes';
                    } else if (size < 1024 * 1024) {
                        fileSize.textContent = (size / 1024).toFixed(2) + ' KB';
                    } else {
                        fileSize.textContent = (size / (1024 * 1024)).toFixed(2) + ' MB';
                    }
                    
                    uploadArea.classList.add('hidden');
                    filePreview.classList.remove('hidden');
                } else {
                    uploadArea.classList.remove('hidden');
                    filePreview.classList.add('hidden');
                }
            });

            // Remove file button
            if (removeFile) {
                removeFile.addEventListener('click', function() {
                    revisiInput.value = '';
                    uploadArea.classList.remove('hidden');
                    filePreview.classList.add('hidden');
                });
            }
        }
    </script>
</x-app-layout>
