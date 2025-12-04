<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Daftar {{ $jenis === 'seminar_proposal' ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                </h1>
                <p class="text-gray-600">Pilih jadwal yang tersedia untuk mendaftar</p>
            </div>
            <a href="{{ route('mahasiswa.sidang.index') }}" 
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

        <!-- No Jadwal Available -->
        @if($jadwals->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak Ada Jadwal Tersedia</h3>
            <p class="text-gray-500 mb-6">Saat ini tidak ada jadwal {{ $jenis === 'seminar_proposal' ? 'seminar proposal' : 'sidang skripsi' }} yang terbuka untuk pendaftaran.</p>
            <a href="{{ route('mahasiswa.sidang.index') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
        @else
        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6">
                <form action="{{ route('mahasiswa.sidang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="jenis" value="{{ $jenis }}">

                    <!-- Pilih Jadwal -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Pilih Periode Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        
                        <div class="space-y-4">
                            @foreach($jadwals as $jadwal)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="jadwal_sidang_id" value="{{ $jadwal->id }}" 
                                       class="peer sr-only" {{ old('jadwal_sidang_id') == $jadwal->id ? 'checked' : '' }} required>
                                <div class="p-4 border-2 rounded-lg transition-all
                                            peer-checked:border-blue-500 peer-checked:bg-blue-50
                                            hover:border-gray-300 border-gray-200">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-medium text-gray-800">{{ $jadwal->nama }}</h4>
                                            <p class="text-sm text-gray-500 mt-1">{{ $jadwal->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                                            
                                            <div class="flex flex-wrap gap-4 mt-3 text-sm">
                                                <span class="flex items-center gap-1 text-gray-600">
                                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Buka: {{ \Carbon\Carbon::parse($jadwal->tanggal_buka)->format('d M Y') }}
                                                </span>
                                                <span class="flex items-center gap-1 text-gray-600">
                                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Tutup: {{ \Carbon\Carbon::parse($jadwal->tanggal_tutup)->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="hidden peer-checked:block">
                                            <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Countdown -->
                                    @php
                                        $daysLeft = now()->diffInDays(\Carbon\Carbon::parse($jadwal->tanggal_tutup), false);
                                    @endphp
                                    <div class="mt-3 pt-3 border-t">
                                        @if($daysLeft > 7)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $daysLeft }} hari lagi
                                            </span>
                                        @elseif($daysLeft > 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Segera ditutup - {{ $daysLeft }} hari lagi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Hari terakhir
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('jadwal_sidang_id')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Upload Dokumen -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Upload Dokumen {{ $jenis === 'seminar_proposal' ? 'Proposal' : 'Skripsi' }} <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="file_dokumen" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload file</span>
                                        <input id="file_dokumen" name="file_dokumen" type="file" class="sr-only" accept=".pdf" required>
                                    </label>
                                    <p class="pl-1">atau drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PDF maksimal 10MB</p>
                                <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                            </div>
                        </div>
                        @error('file_dokumen')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Persyaratan -->
                    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <h4 class="font-medium text-yellow-800 mb-2">Persyaratan Pendaftaran</h4>
                        <ul class="text-sm text-yellow-700 space-y-1">
                            @if($jenis === 'seminar_proposal')
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Topik skripsi sudah disetujui oleh kedua pembimbing
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Minimal 4x bimbingan proposal dengan masing-masing pembimbing
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Draft proposal sudah selesai (Bab 1-3)
                                </li>
                            @else
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Sudah lulus seminar proposal
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Minimal 8x bimbingan skripsi dengan masing-masing pembimbing
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Draft skripsi lengkap (Bab 1-5)
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    Persetujuan dari kedua pembimbing untuk sidang
                                </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('mahasiswa.sidang.index') }}" 
                           class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endif
    </div>

    <script>
        document.getElementById('file_dokumen').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            const fileNameEl = document.getElementById('file-name');
            if (fileName) {
                fileNameEl.textContent = fileName;
                fileNameEl.classList.remove('hidden');
            } else {
                fileNameEl.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
