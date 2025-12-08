<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Input Nilai Sidang</h1>
            <a href="{{ route('dosen.nilai.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Sidang Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Sidang</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Mahasiswa</p>
                        <p class="font-medium text-gray-900">{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->nama }}</p>
                        <p class="text-sm text-gray-500">{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jenis Sidang</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $pelaksanaan->pendaftaranSidang->jenis_sidang == 'proposal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            Sidang {{ ucfirst($pelaksanaan->pendaftaranSidang->jenis_sidang) }}
                        </span>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Judul</p>
                        <p class="font-medium text-gray-900">{{ $pelaksanaan->pendaftaranSidang->topik->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Sidang</p>
                        <p class="font-medium text-gray-900">{{ $pelaksanaan->tanggal_sidang->format('d F Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Waktu</p>
                        <p class="font-medium text-gray-900">{{ $pelaksanaan->tanggal_sidang ? $pelaksanaan->tanggal_sidang->format('H:i') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Peran Anda</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ str_contains($penguji->role, 'pembimbing') ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ str_replace('_', ' ', ucfirst($penguji->role)) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Existing Nilai -->
        @if($existingNilai)
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            Anda sudah memberikan nilai untuk sidang ini.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Nilai yang Sudah Diberikan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-500 mb-1">Nilai</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $existingNilai->nilai }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg text-center">
                            <p class="text-sm text-gray-500 mb-1">Grade</p>
                            @php
                                $nilai = $existingNilai->nilai;
                                $grade = $nilai >= 85 ? 'A' : ($nilai >= 80 ? 'A-' : ($nilai >= 75 ? 'B+' : ($nilai >= 70 ? 'B' : ($nilai >= 65 ? 'B-' : ($nilai >= 60 ? 'C+' : ($nilai >= 55 ? 'C' : ($nilai >= 50 ? 'D' : 'E')))))));
                            @endphp
                            <p class="text-3xl font-bold {{ $nilai >= 70 ? 'text-green-600' : ($nilai >= 55 ? 'text-yellow-600' : 'text-red-600') }}">{{ $grade }}</p>
                        </div>
                    </div>
                    @if($existingNilai->catatan)
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Catatan</p>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg mt-1">{{ $existingNilai->catatan }}</p>
                        </div>
                    @endif
                    @if($penguji->catatan_revisi)
                        <div class="mt-4 border-t pt-4">
                            <p class="text-sm text-gray-500 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-1 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                Catatan Revisi Pasca Sidang
                            </p>
                            <div class="text-gray-900 bg-orange-50 p-4 rounded-lg mt-1 border border-orange-200">
                                <p class="whitespace-pre-line">{{ $penguji->catatan_revisi }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Edit Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Nilai</h3>
                    <form method="POST" action="{{ route('dosen.nilai.update', $existingNilai) }}">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div>
                                <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai (0-100) <span class="text-red-500">*</span></label>
                                <input type="number" name="nilai" id="nilai" required min="0" max="100" step="0.01"
                                       value="{{ old('nilai', $existingNilai->nilai) }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('nilai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <textarea name="catatan" id="catatan" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Tambahkan catatan untuk nilai...">{{ old('catatan', $existingNilai->catatan) }}</textarea>
                            </div>

                            <div class="border-t pt-6">
                                <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Revisi Pasca Sidang (Opsional)
                                    <span class="text-xs text-gray-500 block mt-1">Tuliskan poin-poin yang perlu direvisi oleh mahasiswa setelah {{ $pelaksanaan->pendaftaranSidang->jenis_sidang == 'proposal' ? 'sempro' : 'sidang' }}.</span>
                                </label>
                                <textarea name="catatan_revisi" id="catatan_revisi" rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Contoh:&#10;1. Perbaiki metodologi penelitian pada Bab 3&#10;2. Tambahkan referensi terbaru (5 tahun terakhir)&#10;3. Perbaiki format penulisan sesuai template">{{ old('catatan_revisi', $penguji->catatan_revisi) }}</textarea>
                                <p class="mt-2 text-xs text-gray-500">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Catatan revisi akan dikirim ke mahasiswa untuk diperbaiki dan dikumpulkan kembali.
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Update Nilai
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <!-- New Nilai Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Input Nilai</h3>
                    <form method="POST" action="{{ route('dosen.nilai.store', $pelaksanaan) }}">
                        @csrf
                        <div class="space-y-6">
                            <!-- Hidden field - jenis nilai selalu ujian -->
                            <input type="hidden" name="jenis_nilai" value="ujian">

                            <div>
                                <label for="nilai" class="block text-sm font-medium text-gray-700">Nilai (0-100) <span class="text-red-500">*</span></label>
                                <input type="number" name="nilai" id="nilai" required min="0" max="100" step="0.01"
                                       value="{{ old('nilai') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                       placeholder="Masukkan nilai 0-100">
                                @error('nilai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Grade Preview -->
                                <div x-data="{ nilai: {{ old('nilai', 0) }} }" class="mt-2">
                                    <input type="hidden" x-model="nilai" x-init="$watch('nilai', val => $refs.nilaiInput.value = val)">
                                    <script>
                                        document.getElementById('nilai').addEventListener('input', function(e) {
                                            const nilai = parseFloat(e.target.value) || 0;
                                            const gradeEl = document.getElementById('gradePreview');
                                            let grade = '';
                                            let color = '';
                                            
                                            if (nilai >= 85) { grade = 'A'; color = 'text-green-600'; }
                                            else if (nilai >= 80) { grade = 'A-'; color = 'text-green-600'; }
                                            else if (nilai >= 75) { grade = 'B+'; color = 'text-green-600'; }
                                            else if (nilai >= 70) { grade = 'B'; color = 'text-green-600'; }
                                            else if (nilai >= 65) { grade = 'B-'; color = 'text-yellow-600'; }
                                            else if (nilai >= 60) { grade = 'C+'; color = 'text-yellow-600'; }
                                            else if (nilai >= 55) { grade = 'C'; color = 'text-yellow-600'; }
                                            else if (nilai >= 50) { grade = 'D'; color = 'text-red-600'; }
                                            else { grade = 'E'; color = 'text-red-600'; }
                                            
                                            gradeEl.textContent = 'Grade: ' + grade;
                                            gradeEl.className = 'text-sm font-semibold ' + color;
                                        });
                                    </script>
                                    <p id="gradePreview" class="text-sm font-semibold text-gray-500">Grade: -</p>
                                </div>
                            </div>

                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                <textarea name="catatan" id="catatan" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Tambahkan catatan untuk nilai...">{{ old('catatan') }}</textarea>
                            </div>

                            <div class="border-t pt-6">
                                <label for="catatan_revisi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Revisi Pasca Sidang (Opsional)
                                    <span class="text-xs text-gray-500 block mt-1">Tuliskan poin-poin yang perlu direvisi oleh mahasiswa setelah {{ $pelaksanaan->pendaftaranSidang->jenis_sidang == 'proposal' ? 'sempro' : 'sidang' }}.</span>
                                </label>
                                <textarea name="catatan_revisi" id="catatan_revisi" rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Contoh:&#10;1. Perbaiki metodologi penelitian pada Bab 3&#10;2. Tambahkan referensi terbaru (5 tahun terakhir)&#10;3. Perbaiki format penulisan sesuai template">{{ old('catatan_revisi', $penguji->catatan_revisi) }}</textarea>
                                <p class="mt-2 text-xs text-gray-500">
                                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Catatan revisi akan dikirim ke mahasiswa untuk diperbaiki dan dikumpulkan kembali.
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Simpan Nilai
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
