<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Bimbingan</h1>
            <a href="{{ route('dosen.bimbingan.index', ['jenis' => $bimbingan->jenis]) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Status Badge -->
        <div class="mb-6">
            @if($bimbingan->status == 'menunggu')
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Respon
                </span>
            @elseif($bimbingan->status == 'direvisi')
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Perlu Revisi
                </span>
            @else
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Disetujui
                </span>
            @endif
        </div>

        <!-- Mahasiswa Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                <div class="flex items-start gap-4 mb-4">
                    <x-avatar 
                        :src="$bimbingan->topik->mahasiswa->foto_url" 
                        :initials="$bimbingan->topik->mahasiswa->initials" 
                        size="xl" 
                    />
                    <div class="flex-1">
                        <p class="text-lg font-semibold text-gray-900">{{ $bimbingan->topik->mahasiswa->nama }}</p>
                        <p class="text-sm text-gray-500">{{ $bimbingan->topik->mahasiswa->nim }}</p>
                        <p class="text-sm text-gray-500">{{ $bimbingan->topik->mahasiswa->prodi->nama ?? '' }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Judul Skripsi</p>
                        <p class="font-medium text-gray-900">{{ $bimbingan->topik->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Jenis Bimbingan</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $bimbingan->jenis == 'proposal' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            Bimbingan {{ ucfirst($bimbingan->jenis) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bimbingan Detail -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Bimbingan #{{ $bimbingan->bimbingan_ke }}</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                        <p class="font-medium text-gray-900">{{ $bimbingan->created_at->format('d F Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pokok Bimbingan</p>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $bimbingan->pokok_bimbingan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Pesan dari Mahasiswa</p>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $bimbingan->pesan_mahasiswa ?: '-' }}</p>
                    </div>

                    @if($bimbingan->file_bimbingan)
                        <div>
                            <p class="text-sm text-gray-500 mb-2">File Bimbingan</p>
                            <a href="{{ Storage::url($bimbingan->file_bimbingan) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download File
                            </a>
                        </div>
                    @endif

                    @if($bimbingan->file_revisi)
                        <div>
                            <p class="text-sm text-gray-500 mb-2">File Revisi Mahasiswa</p>
                            <a href="{{ Storage::url($bimbingan->file_revisi) }}" target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download File Revisi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Previous Response (if exists) -->
        @if($bimbingan->tanggal_respon)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Respon Anda</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Respon</p>
                            <p class="font-medium text-gray-900">{{ $bimbingan->tanggal_respon->format('d F Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pesan untuk Mahasiswa</p>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $bimbingan->pesan_dosen ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Riwayat Bimbingan -->
        @if($bimbingan->histories->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Bimbingan</h3>
                    <div class="space-y-4">
                        @foreach($bimbingan->histories->sortBy('created_at') as $history)
                        <div class="flex gap-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                                @if($history->status === 'disetujui') bg-green-100
                                @elseif($history->status === 'direvisi') bg-orange-100
                                @elseif($history->status === 'ditolak') bg-red-100
                                @else bg-blue-100
                                @endif">
                                @if($history->status === 'disetujui')
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($history->status === 'direvisi')
                                    <svg class="w-5 h-5 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                    </svg>
                                @elseif($history->status === 'ditolak')
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 pb-4 border-b border-gray-100">
                                <div class="flex items-center justify-between">
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
                                    </p>
                                    <span class="text-xs px-2 py-1 rounded-full 
                                        {{ $history->oleh === 'mahasiswa' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $history->oleh === 'mahasiswa' ? 'Mahasiswa' : 'Dosen' }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">{{ $history->created_at->format('d M Y, H:i') }}</p>
                                @if($history->file)
                                <a href="{{ Storage::url($history->file) }}" target="_blank" 
                                   class="inline-flex items-center text-xs text-blue-600 hover:text-blue-800 mt-2">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ basename($history->file) }}
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Response Form -->
        @if($bimbingan->status == 'menunggu')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Berikan Respon</h3>

                    <form method="POST" action="{{ route('dosen.bimbingan.respond', $bimbingan) }}">
                        @csrf
                        <div class="space-y-6">
                            <!-- Status Selection -->
                            <div x-data="{ status: '' }">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Status Bimbingan <span class="text-red-500">*</span></label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <label class="relative">
                                        <input type="radio" name="status" value="disetujui" x-model="status" class="sr-only peer" required>
                                        <div class="p-4 border-2 rounded-lg cursor-pointer transition-all peer-checked:border-green-500 peer-checked:bg-green-50 hover:border-green-300">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div>
                                                    <p class="font-semibold text-gray-900">Setujui</p>
                                                    <p class="text-sm text-gray-500">Bimbingan sudah baik, lanjutkan</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="status" value="direvisi" x-model="status" class="sr-only peer" required>
                                        <div class="p-4 border-2 rounded-lg cursor-pointer transition-all peer-checked:border-orange-500 peer-checked:bg-orange-50 hover:border-orange-300">
                                            <div class="flex items-center">
                                                <svg class="w-8 h-8 text-orange-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                <div>
                                                    <p class="font-semibold text-gray-900">Perlu Revisi</p>
                                                    <p class="text-sm text-gray-500">Ada yang perlu diperbaiki</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="pesan_dosen" class="block text-sm font-medium text-gray-700">Pesan/Catatan untuk Mahasiswa <span class="text-red-500">*</span></label>
                                <textarea name="pesan_dosen" id="pesan_dosen" rows="5" required
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                          placeholder="Berikan masukan, catatan, atau arahan untuk mahasiswa...">{{ old('pesan_dosen') }}</textarea>
                                @error('pesan_dosen')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <div class="flex justify-end">
                                <button type="submit"
                                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Kirim Respon
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
