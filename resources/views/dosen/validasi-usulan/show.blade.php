<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detail Usulan Pembimbingan</h1>
            <a href="{{ route('dosen.validasi-usulan.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Mahasiswa Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Mahasiswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Nama</p>
                        <p class="font-medium text-gray-900">{{ $usulan->topik->mahasiswa->nama }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">NIM</p>
                        <p class="font-medium text-gray-900">{{ $usulan->topik->mahasiswa->nim }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $usulan->topik->mahasiswa->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Telepon</p>
                        <p class="font-medium text-gray-900">{{ $usulan->topik->mahasiswa->telepon ?? '-' }}</p>
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
                        <p class="font-medium text-gray-900">{{ $usulan->topik->judul }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Bidang Minat</p>
                        <p class="font-medium text-gray-900">{{ $usulan->topik->bidangMinat->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Deskripsi</p>
                        <p class="text-gray-900">{{ $usulan->topik->deskripsi ?? '-' }}</p>
                    </div>
                    @if($usulan->topik->file_proposal)
                        <div>
                            <p class="text-sm text-gray-500 mb-2">File Proposal</p>
                            <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">Proposal Skripsi</p>
                                        <p class="text-sm text-gray-600">{{ basename($usulan->topik->file_proposal) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('dosen.validasi-usulan.download-proposal', $usulan) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Usulan Info -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Detail Usulan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Sebagai</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $usulan->urutan == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                            Pembimbing {{ $usulan->urutan }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        @if($usulan->status == 'menunggu')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Menunggu Validasi
                            </span>
                        @elseif($usulan->status == 'diterima')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Diterima
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Ditolak
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Tanggal Pengajuan</p>
                        <p class="font-medium text-gray-900">{{ $usulan->created_at->format('d F Y H:i') }}</p>
                    </div>
                    @if($usulan->tanggal_respon)
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Respon</p>
                            <p class="font-medium text-gray-900">{{ $usulan->tanggal_respon->format('d F Y H:i') }}</p>
                        </div>
                    @endif
                    @if($usulan->jangka_waktu)
                        <div>
                            <p class="text-sm text-gray-500">Jangka Waktu</p>
                            <p class="font-medium text-gray-900">{{ $usulan->jangka_waktu->format('d F Y') }}</p>
                        </div>
                    @endif
                    @if($usulan->catatan)
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Catatan</p>
                            <p class="text-gray-900">{{ $usulan->catatan }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Kuota Info -->
        @if($usulan->status == 'menunggu')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kuota Pembimbing {{ $usulan->urutan }}</h3>
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <p class="text-sm text-gray-500">Sisa Kuota</p>
                            <p class="text-2xl font-bold {{ $kuotaInfo['sisa'] > 0 ? ($usulan->urutan == 1 ? 'text-blue-600' : 'text-purple-600') : 'text-red-600' }}">
                                {{ $kuotaInfo['sisa'] }} <span class="text-sm font-normal text-gray-500">/ {{ $kuotaInfo['kuota'] }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Terpakai</p>
                            <p class="text-lg font-semibold text-gray-700">{{ $kuotaInfo['terpakai'] }} mahasiswa</p>
                        </div>
                    </div>
                    <!-- Progress bar -->
                    <div class="w-full bg-gray-200 rounded-full h-3">
                        @php
                            $percent = $kuotaInfo['kuota'] > 0 ? ($kuotaInfo['terpakai'] / $kuotaInfo['kuota']) * 100 : 0;
                            $barColor = $percent >= 90 ? 'bg-red-500' : ($percent >= 70 ? 'bg-yellow-500' : ($usulan->urutan == 1 ? 'bg-blue-500' : 'bg-purple-500'));
                        @endphp
                        <div class="{{ $barColor }} h-3 rounded-full transition-all duration-300" style="width: {{ min($percent, 100) }}%"></div>
                    </div>

                    @if(!$kuotaInfo['available'])
                        <div class="mt-4 bg-red-50 border-l-4 border-red-500 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-red-700 font-medium">
                                        Kuota pembimbing {{ $usulan->urutan }} Anda sudah penuh!
                                    </p>
                                    <p class="text-sm text-red-600 mt-1">
                                        Anda tidak dapat menyetujui usulan ini sampai ada slot kuota yang tersedia.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Action Form -->
        @if($usulan->status == 'menunggu')
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Validasi Usulan</h3>

                    <div x-data="{ action: '' }">
                        <!-- Action Buttons -->
                        <div class="flex space-x-4 mb-6">
                            @if($kuotaInfo['available'])
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
                            @else
                                <button type="button" disabled
                                        class="flex-1 px-4 py-3 rounded-lg border-2 border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed"
                                        title="Kuota pembimbing {{ $usulan->urutan }} sudah penuh">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui (Kuota Penuh)
                                    </div>
                                </button>
                            @endif
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
                        @if($kuotaInfo['available'])
                        <form x-show="action === 'approve'" x-cloak method="POST" action="{{ route('dosen.validasi-usulan.approve', $usulan) }}" id="approve-usulan-form">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label for="jangka_waktu" class="block text-sm font-medium text-gray-700">Jangka Waktu Bimbingan (Opsional)</label>
                                    <input type="date" name="jangka_waktu" id="jangka_waktu"
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Tentukan batas waktu bimbingan (jika diperlukan)</p>
                                </div>
                                <div>
                                    <label for="catatan_approve" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                    <textarea name="catatan" id="catatan_approve" rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                              placeholder="Tambahkan catatan untuk mahasiswa..."></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" onclick="confirmAction('approve-usulan-form', 'Setujui Usulan', 'Yakin ingin menyetujui usulan pembimbingan ini?', 'Setujui', 'success')"
                                            class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Setujui Usulan
                                    </button>
                                </div>
                            </div>
                        </form>
                        @endif

                        <!-- Reject Form -->
                        <form x-show="action === 'reject'" x-cloak method="POST" action="{{ route('dosen.validasi-usulan.reject', $usulan) }}" id="reject-usulan-form">
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
                                    <button type="button" onclick="confirmAction('reject-usulan-form', 'Tolak Usulan', 'Yakin ingin menolak usulan pembimbingan ini?', 'Tolak', 'warning')"
                                            class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-md font-semibold text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Tolak Usulan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
