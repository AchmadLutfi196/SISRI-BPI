<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Jadwalkan Pelaksanaan Sidang
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Info Mahasiswa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Mahasiswa</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-medium">{{ $pendaftaran->topik->mahasiswa->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NIM</p>
                            <p class="font-medium">{{ $pendaftaran->topik->mahasiswa->nim ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Judul Skripsi</p>
                            <p class="font-medium">{{ $pendaftaran->topik->judul ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Pembimbing</p>
                            @php
                                $pembimbing = $pendaftaran->topik->usulanPembimbing()->approved()->orderBy('urutan')->get();
                            @endphp
                            @foreach($pembimbing as $p)
                                <p class="font-medium">
                                    Pembimbing {{ $p->urutan }}: {{ $p->dosen->user->name ?? '-' }}
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Jadwal -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Form Penjadwalan Manual</h3>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Mode Manual
                        </span>
                    </div>

                    <!-- Info validasi -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-yellow-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <div class="text-sm text-yellow-700">
                                <p class="font-medium">Sistem akan otomatis memvalidasi:</p>
                                <ul class="list-disc list-inside mt-1">
                                    <li>Ruangan tidak bentrok dengan jadwal sidang lain</li>
                                    <li>Dosen pembimbing dan penguji tidak bentrok dengan jadwal sidang lain</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('koordinator.penjadwalan.store-pelaksanaan', $pendaftaran) }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="tanggal_sidang" class="block text-sm font-medium text-gray-700">Tanggal dan Waktu Sidang</label>
                                <input type="datetime-local" name="tanggal_sidang" id="tanggal_sidang" value="{{ old('tanggal_sidang') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                @error('tanggal_sidang')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tempat" class="block text-sm font-medium text-gray-700">Tempat / Ruangan</label>
                                <select name="tempat" id="tempat"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($ruangans as $ruangan)
                                        <option value="{{ $ruangan->nama }}" {{ old('tempat') == $ruangan->nama ? 'selected' : '' }}>
                                            {{ $ruangan->nama }} {{ $ruangan->lokasi ? "- {$ruangan->lokasi}" : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tempat')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <h4 class="text-md font-medium text-gray-900 mb-2">Tim Penguji</h4>
                            <p class="text-sm text-gray-500 mb-4">Pembimbing akan otomatis ditambahkan sebagai penguji. Silakan pilih penguji tambahan.</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="penguji_1_id" class="block text-sm font-medium text-gray-700">Penguji 1</label>
                                    <select name="penguji_1_id" id="penguji_1_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">-- Pilih Penguji 1 --</option>
                                        @foreach($dosens as $dosen)
                                            @php
                                                $isPembimbing = $pembimbing->contains('dosen_id', $dosen->id);
                                            @endphp
                                            @if(!$isPembimbing)
                                                <option value="{{ $dosen->id }}" {{ old('penguji_1_id') == $dosen->id ? 'selected' : '' }}>
                                                    {{ $dosen->user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('penguji_1_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="penguji_2_id" class="block text-sm font-medium text-gray-700">Penguji 2</label>
                                    <select name="penguji_2_id" id="penguji_2_id"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">-- Pilih Penguji 2 --</option>
                                        @foreach($dosens as $dosen)
                                            @php
                                                $isPembimbing = $pembimbing->contains('dosen_id', $dosen->id);
                                            @endphp
                                            @if(!$isPembimbing)
                                                <option value="{{ $dosen->id }}" {{ old('penguji_2_id') == $dosen->id ? 'selected' : '' }}>
                                                    {{ $dosen->user->name }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('penguji_2_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Preview Tim -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Tim Sidang yang Akan Terbentuk:</h4>
                            <ul class="text-sm text-gray-600 space-y-1">
                                @foreach($pembimbing as $p)
                                    <li class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="font-medium">Pembimbing {{ $p->urutan }}:</span>&nbsp;{{ $p->dosen->user->name ?? '-' }}
                                    </li>
                                @endforeach
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="font-medium">Penguji 1:</span>&nbsp;<span id="preview_penguji_1">-</span>
                                </li>
                                <li class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    <span class="font-medium">Penguji 2:</span>&nbsp;<span id="preview_penguji_2">-</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('koordinator.penjadwalan.show', $pendaftaran->jadwal_sidang_id) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Jadwalkan Sidang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('penguji_1_id').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            document.getElementById('preview_penguji_1').textContent = selected.value ? selected.text : '-';
        });

        document.getElementById('penguji_2_id').addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            document.getElementById('preview_penguji_2').textContent = selected.value ? selected.text : '-';
        });
    </script>
</x-app-layout>
