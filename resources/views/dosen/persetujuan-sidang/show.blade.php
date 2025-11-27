<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Pendaftaran Sidang</h1>
            @php
                $jenisUrl = $pendaftaran->jenis === 'seminar_proposal' ? 'proposal' : 'skripsi';
            @endphp
            <a href="{{ route('dosen.persetujuan-sidang.index', ['jenis' => $jenisUrl]) }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Status Badge -->
        @php
            $statusField = 'status_pembimbing_' . $usulanPembimbing->urutan;
            $myStatus = $pendaftaran->$statusField;
        @endphp
        <div class="mb-6">
            @if($myStatus == 'menunggu')
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Menunggu Keputusan Anda
                </span>
            @elseif($myStatus == 'disetujui')
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Anda Sudah Menyetujui
                </span>
            @else
                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Anda Sudah Menolak
                </span>
            @endif
        </div>

        <!-- Mahasiswa Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-medium text-gray-900">{{ $pendaftaran->topik->mahasiswa->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">NIM</p>
                        <p class="font-medium text-gray-900">{{ $pendaftaran->topik->mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $pendaftaran->topik->mahasiswa->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Telepon</p>
                        <p class="font-medium text-gray-900">{{ $pendaftaran->topik->mahasiswa->telepon ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Topik Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Topik Skripsi</h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Judul</p>
                        <p class="font-medium text-gray-900">{{ $pendaftaran->topik->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bidang Minat</p>
                        <p class="font-medium text-gray-900">{{ $pendaftaran->topik->bidangMinat->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Deskripsi</p>
                        <p class="text-gray-900">{{ $pendaftaran->topik->deskripsi ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembimbing Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daftar Pembimbing</h3>
                <div class="space-y-4">
                    @foreach($pendaftaran->topik->usulanPembimbings->where('status', 'diterima')->sortBy('urutan') as $usulan)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $usulan->urutan == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }} mr-3">
                                    Pembimbing {{ $usulan->urutan }}
                                </span>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $usulan->dosen->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ $usulan->dosen->nidn ?? $usulan->dosen->nip ?? '-' }}</p>
                                </div>
                            </div>
                            @if($usulan->dosen_id == auth()->user()->dosen->id)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Anda
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Approval Status -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Persetujuan</h3>
                <div class="space-y-4">
                    <!-- Pembimbing 1 -->
                    <div class="flex items-center justify-between p-4 rounded-lg 
                        {{ $pendaftaran->status_pembimbing_1 == 'disetujui' ? 'bg-green-50' : ($pendaftaran->status_pembimbing_1 == 'ditolak' ? 'bg-red-50' : 'bg-yellow-50') }}">
                        <div class="flex items-center">
                            @if($pendaftaran->status_pembimbing_1 == 'disetujui')
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($pendaftaran->status_pembimbing_1 == 'ditolak')
                                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            <span class="font-medium">Pembimbing 1</span>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pendaftaran->status_pembimbing_1 == 'disetujui' ? 'bg-green-100 text-green-800' : ($pendaftaran->status_pembimbing_1 == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($pendaftaran->status_pembimbing_1) }}
                        </span>
                    </div>

                    <!-- Pembimbing 2 -->
                    <div class="flex items-center justify-between p-4 rounded-lg 
                        {{ $pendaftaran->status_pembimbing_2 == 'disetujui' ? 'bg-green-50' : ($pendaftaran->status_pembimbing_2 == 'ditolak' ? 'bg-red-50' : 'bg-yellow-50') }}">
                        <div class="flex items-center">
                            @if($pendaftaran->status_pembimbing_2 == 'disetujui')
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($pendaftaran->status_pembimbing_2 == 'ditolak')
                                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            <span class="font-medium">Pembimbing 2</span>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pendaftaran->status_pembimbing_2 == 'disetujui' ? 'bg-green-100 text-green-800' : ($pendaftaran->status_pembimbing_2 == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($pendaftaran->status_pembimbing_2) }}
                        </span>
                    </div>

                    <!-- Koordinator -->
                    <div class="flex items-center justify-between p-4 rounded-lg 
                        {{ $pendaftaran->status_koordinator == 'disetujui' ? 'bg-green-50' : ($pendaftaran->status_koordinator == 'ditolak' ? 'bg-red-50' : 'bg-yellow-50') }}">
                        <div class="flex items-center">
                            @if($pendaftaran->status_koordinator == 'disetujui')
                                <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @elseif($pendaftaran->status_koordinator == 'ditolak')
                                <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-yellow-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                            <span class="font-medium">Koordinator Prodi</span>
                        </div>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pendaftaran->status_koordinator == 'disetujui' ? 'bg-green-100 text-green-800' : ($pendaftaran->status_koordinator == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($pendaftaran->status_koordinator) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Form -->
        @if($myStatus == 'menunggu')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Berikan Keputusan</h3>

                    <div x-data="{ action: '' }">
                        <!-- Action Buttons -->
                        <div class="flex space-x-4 mb-6">
                            <button type="button" @click="action = 'approve'"
                                    class="flex-1 px-4 py-3 rounded-lg border-2 transition-all"
                                    :class="action === 'approve' ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300 hover:border-green-300'">
                                <div class="flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Setujui
                                </div>
                            </button>
                            <button type="button" @click="action = 'reject'"
                                    class="flex-1 px-4 py-3 rounded-lg border-2 transition-all"
                                    :class="action === 'reject' ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-300 hover:border-red-300'">
                                <div class="flex items-center justify-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Tolak
                                </div>
                            </button>
                        </div>

                        <!-- Approve Form -->
                        <form x-show="action === 'approve'" x-cloak method="POST" action="{{ route('dosen.persetujuan-sidang.approve', $pendaftaran) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="catatan_approve" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                    <textarea name="catatan" id="catatan_approve" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Tambahkan catatan untuk mahasiswa..."></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui Pendaftaran
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Reject Form -->
                        <form x-show="action === 'reject'" x-cloak method="POST" action="{{ route('dosen.persetujuan-sidang.reject', $pendaftaran) }}">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="catatan_reject" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                                    <textarea name="catatan" id="catatan_reject" rows="3" required
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Jelaskan alasan penolakan..."></textarea>
                                    @error('catatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                            class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak Pendaftaran
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Already Decided Info -->
            @php
                $catatanField = 'catatan_pembimbing_' . $usulanPembimbing->urutan;
            @endphp
            @if($pendaftaran->$catatanField)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Catatan Anda</h3>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $pendaftaran->$catatanField }}</p>
                    </div>
                </div>
            @endif
        @endif
    </div>
</x-app-layout>
