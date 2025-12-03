<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Ajukan Bimbingan {{ ucfirst($jenis) }}</h1>
                <p class="text-gray-600">Isi form berikut untuk mengajukan bimbingan</p>
            </div>
            <a href="{{ route('mahasiswa.bimbingan.index', ['jenis' => $jenis]) }}" 
               class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Topik Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="text-sm font-medium text-blue-800">Topik Skripsi Anda</p>
                    <p class="text-sm text-blue-700 mt-1">{{ $topik->judul }}</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('mahasiswa.bimbingan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis" value="{{ $jenis }}">

                    <!-- Pilih Pembimbing -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Dosen Pembimbing <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($pembimbings as $pembimbing)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="dosen_id" value="{{ $pembimbing->dosen_id }}" 
                                       class="peer sr-only" {{ old('dosen_id') == $pembimbing->dosen_id ? 'checked' : '' }} required>
                                <div class="p-4 border-2 rounded-lg transition-all
                                            peer-checked:border-blue-500 peer-checked:bg-blue-50
                                            hover:border-gray-300 border-gray-200">
                                    <div class="flex items-center gap-3">
                                        <x-avatar 
                                            :src="$pembimbing->dosen->foto_url" 
                                            :initials="$pembimbing->dosen->initials" 
                                            size="lg" 
                                        />
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $pembimbing->dosen->nama }}</p>
                                            <p class="text-sm text-gray-500">Pembimbing {{ $pembimbing->urutan }}</p>
                                        </div>
                                    </div>
                                    <div class="absolute top-4 right-4 hidden peer-checked:block">
                                        <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </label>
                            @empty
                            <div class="col-span-2 p-4 bg-yellow-50 border border-yellow-200 rounded-lg text-center">
                                <p class="text-yellow-800">Tidak ada pembimbing yang tersedia.</p>
                            </div>
                            @endforelse
                        </div>
                        @error('dosen_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pokok Bimbingan -->
                    <div class="mb-6">
                        <label for="pokok_bimbingan" class="block text-sm font-medium text-gray-700 mb-1">
                            Pokok Bimbingan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="pokok_bimbingan" id="pokok_bimbingan" rows="4" required
                                  class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 {{ $errors->has('pokok_bimbingan') ? 'border-red-500' : 'border-gray-300' }}"
                                  placeholder="Jelaskan hal yang ingin Anda konsultasikan atau progres yang sudah dikerjakan...">{{ old('pokok_bimbingan') }}</textarea>
                        <p class="text-gray-500 text-xs mt-1">Jelaskan secara detail topik/bab yang ingin dikonsultasikan</p>
                        @error('pokok_bimbingan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload File -->
                    <div class="mb-6">
                        <label for="file_bimbingan" class="block text-sm font-medium text-gray-700 mb-1">
                            Upload Dokumen (Opsional)
                        </label>
                        
                        <!-- Upload Area -->
                        <div id="upload-area" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors cursor-pointer">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file_bimbingan" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>Upload dokumen</span>
                                        <input id="file_bimbingan" name="file_bimbingan" type="file" class="sr-only" accept=".pdf,.doc,.docx">
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF, DOC, DOCX hingga 10MB</p>
                            </div>
                        </div>

                        <!-- File Selected Preview -->
                        <div id="file-preview" class="hidden mt-1 p-4 border-2 border-green-500 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p id="file-name" class="text-sm font-medium text-gray-800"></p>
                                        <p id="file-size" class="text-xs text-gray-500"></p>
                                    </div>
                                </div>
                                <button type="button" id="remove-file" class="p-1 text-gray-400 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs text-green-600 font-medium">File siap diupload</span>
                            </div>
                        </div>

                        @error('file_bimbingan')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pesan Tambahan -->
                    <div class="mb-6">
                        <label for="pesan_mahasiswa" class="block text-sm font-medium text-gray-700 mb-1">
                            Pesan Tambahan (Opsional)
                        </label>
                        <textarea name="pesan_mahasiswa" id="pesan_mahasiswa" rows="3"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Pesan tambahan untuk dosen pembimbing...">{{ old('pesan_mahasiswa') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('mahasiswa.bimbingan.index', ['jenis' => $jenis]) }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Ajukan Bimbingan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const fileInput = document.getElementById('file_bimbingan');
        const uploadArea = document.getElementById('upload-area');
        const filePreview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        const fileSize = document.getElementById('file-size');
        const removeBtn = document.getElementById('remove-file');

        // Format file size
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Handle file selection
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                uploadArea.classList.add('hidden');
                filePreview.classList.remove('hidden');
            }
        });

        // Handle remove file
        removeBtn.addEventListener('click', function() {
            fileInput.value = '';
            fileName.textContent = '';
            fileSize.textContent = '';
            filePreview.classList.add('hidden');
            uploadArea.classList.remove('hidden');
        });

        // Make upload area clickable
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop functionality
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('border-blue-500', 'bg-blue-50');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                if (allowedTypes.includes(file.type)) {
                    fileInput.files = files;
                    fileName.textContent = file.name;
                    fileSize.textContent = formatFileSize(file.size);
                    uploadArea.classList.add('hidden');
                    filePreview.classList.remove('hidden');
                } else {
                    alert('Format file tidak didukung. Gunakan PDF, DOC, atau DOCX.');
                }
            }
        });
    </script>
</x-app-layout>
