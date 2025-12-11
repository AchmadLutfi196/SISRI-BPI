<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Topik Skripsi</h1>
            <a href="{{ route('mahasiswa.topik.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Rejection Alert -->
        @if($topik->status === 'ditolak')
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex">
                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-red-800 font-semibold">Topik Ditolak</h3>
                    <p class="text-red-700 text-sm mt-1">Silakan perbaiki topik skripsi Anda sesuai dengan catatan pembimbing dan ajukan kembali.</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Pembimbing Feedback -->
        @if($topik->usulanPembimbing->isNotEmpty())
        <div class="mb-6 bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Catatan dari Pembimbing</h2>
            <div class="space-y-4">
                @foreach($topik->usulanPembimbing as $usulan)
                    @if($usulan->status === 'ditolak' && $usulan->catatan)
                    <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-red-400">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="font-medium text-gray-800">{{ $usulan->dosen->nama }}</p>
                                <p class="text-sm text-gray-500">{{ $usulan->urutan == 1 ? 'Pembimbing 1' : 'Pembimbing 2' }}</p>
                            </div>
                            <span class="text-xs text-gray-500">{{ $usulan->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div class="mt-2 p-3 bg-white rounded border">
                            <p class="text-sm text-gray-700">"{{ $usulan->catatan }}"</p>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('mahasiswa.topik.update', $topik) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Section: Informasi Topik -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">
                            Informasi Topik
                        </h2>

                        <!-- Bidang Minat -->
                        <div class="mb-4">
                            <label for="bidang_minat_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Bidang Minat <span class="text-red-500">*</span>
                            </label>
                            <select name="bidang_minat_id" id="bidang_minat_id" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('bidang_minat_id') ? 'border-red-500' : 'border-gray-300' }}">
                                <option value="">-- Pilih Bidang Minat --</option>
                                @foreach($bidangMinats as $bidang)
                                    <option value="{{ $bidang->id }}" {{ old('bidang_minat_id', $topik->bidang_minat_id) == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('bidang_minat_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                                Judul Skripsi <span class="text-red-500">*</span>
                            </label>
                            <textarea name="judul" id="judul" rows="3" required
                                      class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('judul') ? 'border-red-500' : 'border-gray-300' }}"
                                      placeholder="Masukkan judul skripsi Anda">{{ old('judul', $topik->judul) }}</textarea>
                            <p class="text-gray-500 text-xs mt-1">Maksimal 500 karakter</p>
                            @error('judul')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current File -->
                        @if($topik->file_proposal)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">File Proposal Saat Ini</label>
                            <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg">
                                <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800">{{ basename($topik->file_proposal) }}</p>
                                    <p class="text-xs text-gray-500">File proposal yang sudah diupload</p>
                                </div>
                                <a href="{{ Storage::url($topik->file_proposal) }}" target="_blank"
                                   class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Lihat File
                                </a>
                            </div>
                        </div>
                        @endif

                        <!-- New File Proposal -->
                        <div class="mb-4">
                            <label for="file_proposal" class="block text-sm font-medium text-gray-700 mb-1">
                                Upload File Baru (Opsional)
                            </label>
                            <div id="upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors cursor-pointer">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="file_proposal" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload file baru</span>
                                            <input id="file_proposal" name="file_proposal" type="file" class="sr-only" accept=".pdf">
                                        </label>
                                        <p class="pl-1">untuk mengganti file lama</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF hingga 5MB</p>
                                </div>
                            </div>
                            <!-- File Preview -->
                            <div id="file-preview" class="hidden mt-3 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 20 20">
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
                            @error('file_proposal')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Section: Pembimbing -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">
                            Dosen Pembimbing
                        </h2>

                        @if($hasPembimbingDitolak)
                        <div class="mb-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-yellow-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-yellow-800 font-medium">Ada pembimbing yang menolak usulan Anda!</p>
                                    <p class="text-sm text-yellow-700 mt-1">Silakan pilih dosen pembimbing pengganti untuk pembimbing yang menolak.</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($topik->usulanPembimbing->sortBy('urutan') as $usulan)
                            <div class="p-4 bg-gray-50 rounded-lg border {{ $usulan->status === 'ditolak' ? 'border-red-300 bg-red-50' : '' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-sm font-medium text-gray-700">Pembimbing {{ $usulan->urutan }}</span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $usulan->status === 'diterima' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $usulan->status === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $usulan->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                        {{ ucfirst($usulan->status) }}
                                    </span>
                                </div>

                                @if($usulan->status === 'ditolak')
                                    <!-- Form Ganti Pembimbing -->
                                    <div class="mb-3">
                                        <div class="p-3 bg-white rounded border border-red-200 mb-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1">
                                                    <p class="font-medium text-gray-800 line-through">{{ $usulan->dosen->nama }}</p>
                                                    <p class="text-xs text-red-600">Menolak usulan</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Pilih Pembimbing Pengganti <span class="text-red-500">*</span>
                                    </label>
                                    <select name="pembimbing_{{ $usulan->urutan }}_id" required
                                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300">
                                        <option value="">-- Pilih Dosen Pembimbing {{ $usulan->urutan }} --</option>
                                        @foreach($dosens as $dosen)
                                            @if($dosen->id !== $usulan->dosen_id)
                                            <option value="{{ $dosen->id }}" {{ old('pembimbing_' . $usulan->urutan . '_id') == $dosen->id ? 'selected' : '' }}>
                                                {{ $dosen->nama }} ({{ $dosen->nip ?? $dosen->nidn ?? '-' }})
                                            </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('pembimbing_' . $usulan->urutan . '_id')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                @elseif($usulan->status === 'diterima')
                                    <!-- Pembimbing yang Sudah Menerima -->
                                    <div class="p-3 bg-white rounded border border-green-200">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $usulan->dosen->nama }}</p>
                                                <p class="text-xs text-green-600">Sudah menyetujui</p>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-2 italic">Pembimbing ini akan tetap dipertahankan</p>
                                @else
                                    <!-- Status Menunggu -->
                                    <div class="p-3 bg-white rounded border">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-lg font-bold text-blue-600">{{ substr($usulan->dosen->nama, 0, 1) }}</span>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-800">{{ $usulan->dosen->nama }}</p>
                                                <p class="text-xs text-gray-500">{{ $usulan->dosen->nip ?? $usulan->dosen->nidn ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            @endforeach
                        </div>

                        @if(!$hasPembimbingDitolak)
                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-blue-400 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-800">
                                        <strong>Info:</strong> Pembimbing yang sudah menyetujui akan tetap dipertahankan.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('mahasiswa.topik.index') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // File upload preview
        var fileInput = document.getElementById('file_proposal');
        var uploadArea = document.getElementById('upload-area');
        var filePreview = document.getElementById('file-preview');
        var fileName = document.getElementById('file-name');
        var fileSize = document.getElementById('file-size');
        var removeFile = document.getElementById('remove-file');

        fileInput.addEventListener('change', function(e) {
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
        removeFile.addEventListener('click', function() {
            fileInput.value = '';
            uploadArea.classList.remove('hidden');
            filePreview.classList.add('hidden');
        });
    </script>
</x-app-layout>
