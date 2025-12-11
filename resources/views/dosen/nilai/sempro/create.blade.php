<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beri Nilai Seminar Proposal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Info Mahasiswa -->
                    <div class="mb-8 bg-blue-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-blue-900 mb-4">Informasi Seminar Proposal</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600">Nama Mahasiswa</p>
                                <p class="font-medium text-gray-900">{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->user->name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">NIM</p>
                                <p class="font-medium text-gray-900">{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->nim ?? '-' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600">Judul Skripsi</p>
                                <p class="font-medium text-gray-900">{{ $pelaksanaan->pendaftaranSidang->topik->judul ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tanggal Sempro</p>
                                <p class="font-medium text-gray-900">
                                    {{ $pelaksanaan->tanggal_sidang ? 
                                       \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->format('d M Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Tempat</p>
                                <p class="font-medium text-gray-900">{{ $pelaksanaan->tempat ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Peran Anda</p>
                                <p class="font-medium text-gray-900">
                                    @if($penugasan->role === 'pembimbing_1')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Pembimbing 1
                                        </span>
                                    @elseif($penugasan->role === 'pembimbing_2')
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Pembimbing 2
                                        </span>
                                    @else
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            Penguji
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Form Nilai -->
                    <form action="{{ $nilaiExisting ? route('dosen.nilai-sempro.update', $nilaiExisting->id) : route('dosen.nilai-sempro.store', $pelaksanaan->id) }}" method="POST">
                        @csrf
                        @if($nilaiExisting)
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            <div>
                                <label for="nilai" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nilai (0-100) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       name="nilai" 
                                       id="nilai" 
                                       min="0" 
                                       max="100" 
                                       value="{{ old('nilai', $nilaiExisting->nilai ?? '') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('nilai') border-red-500 @enderror"
                                       required>
                                @error('nilai')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                
                                <!-- Preview Grade -->
                                <div id="gradePreview" class="mt-2 hidden">
                                    <span class="text-sm text-gray-600">Nilai Huruf: </span>
                                    <span id="gradeText" class="font-semibold"></span>
                                    <span id="kelulusanText" class="ml-2"></span>
                                </div>
                            </div>

                            <div>
                                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                                    Catatan Revisian (jika ada)
                                </label>
                                <textarea name="catatan" 
                                          id="catatan" 
                                          rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('catatan') border-red-500 @enderror"
                                          placeholder="Tambahkan catatan atau komentar untuk mahasiswa...">{{ old('catatan', $nilaiExisting->catatan ?? '') }}</textarea>
                                @error('catatan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Info Skala Nilai -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Skala Nilai:</h4>
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-sm">
                                    <div class="text-center p-2 bg-green-100 rounded">
                                        <span class="font-bold text-green-800">A</span>
                                        <span class="text-green-600 block text-xs">85-100</span>
                                    </div>
                                    <div class="text-center p-2 bg-blue-100 rounded">
                                        <span class="font-bold text-blue-800">B</span>
                                        <span class="text-blue-600 block text-xs">70-84</span>
                                    </div>
                                    <div class="text-center p-2 bg-yellow-100 rounded">
                                        <span class="font-bold text-yellow-800">C</span>
                                        <span class="text-yellow-600 block text-xs">55-69</span>
                                    </div>
                                    <div class="text-center p-2 bg-orange-100 rounded">
                                        <span class="font-bold text-orange-800">D</span>
                                        <span class="text-orange-600 block text-xs">40-54</span>
                                    </div>
                                    <div class="text-center p-2 bg-red-100 rounded">
                                        <span class="font-bold text-red-800">E</span>
                                        <span class="text-red-600 block text-xs">0-39</span>
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-600">
                                    <span class="text-green-600 font-medium">Lulus:</span> Nilai ≥ 55 (Minimal C) |
                                    <span class="text-red-600 font-medium">Tidak Lulus:</span> Nilai < 55 (D atau E) - Harus mengulang seminar proposal
                                </p>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('dosen.nilai-sempro.index') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Kembali
                                </a>
                                <button type="submit" 
                                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $nilaiExisting ? 'Update Nilai' : 'Simpan Nilai' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('nilai').addEventListener('input', function() {
            const nilai = parseInt(this.value);
            const preview = document.getElementById('gradePreview');
            const gradeText = document.getElementById('gradeText');
            const kelulusanText = document.getElementById('kelulusanText');
            
            if (isNaN(nilai) || nilai < 0 || nilai > 100) {
                preview.classList.add('hidden');
                return;
            }
            
            preview.classList.remove('hidden');
            
            let grade, gradeClass, lulus;
            if (nilai >= 85) {
                grade = 'A';
                gradeClass = 'text-green-600';
                lulus = true;
            } else if (nilai >= 70) {
                grade = 'B';
                gradeClass = 'text-blue-600';
                lulus = true;
            } else if (nilai >= 55) {
                grade = 'C';
                gradeClass = 'text-yellow-600';
                lulus = true;
            } else if (nilai >= 40) {
                grade = 'D';
                gradeClass = 'text-orange-600';
                lulus = false;
            } else {
                grade = 'E';
                gradeClass = 'text-red-600';
                lulus = false;
            }
            
            gradeText.textContent = grade;
            gradeText.className = 'font-semibold ' + gradeClass;
            
            if (lulus) {
                kelulusanText.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">LULUS</span>';
            } else {
                kelulusanText.innerHTML = '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">TIDAK LULUS</span>';
            }
        });

        // Trigger on page load if value exists
        const nilaiInput = document.getElementById('nilai');
        if (nilaiInput.value) {
            nilaiInput.dispatchEvent(new Event('input'));
        }
    </script>
    @endpush
</x-app-layout>
