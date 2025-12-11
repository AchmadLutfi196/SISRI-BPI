<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header with Back Button -->
        <div class="mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('dosen.bimbingan.index', ['jenis' => $jenis]) }}" 
                   class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-2xl font-bold text-gray-800">Detail Bimbingan Mahasiswa</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $jenis === 'proposal' ? 'Bimbingan Proposal' : 'Bimbingan Skripsi' }}</p>
                </div>
            </div>
        </div>

        <!-- Mahasiswa Info Card -->
        <div class="bg-white rounded-lg shadow-sm mb-6 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                <div class="flex items-center gap-4">
                    <x-avatar 
                        :src="$mahasiswa->foto_url" 
                        :initials="$mahasiswa->initials" 
                        size="xl"
                        class="ring-4 ring-white" 
                    />
                    <div class="flex-1">
                        <h2 class="text-xl font-bold text-white">{{ $mahasiswa->nama }}</h2>
                        <p class="text-blue-100">{{ $mahasiswa->nim }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-blue-100">Total Bimbingan</p>
                        <p class="text-3xl font-bold text-white">{{ $bimbingans->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-b">
                <p class="text-sm text-gray-500 mb-1">Topik Skripsi:</p>
                <p class="text-gray-900 font-medium">{{ $topik->judul }}</p>
            </div>
            <div class="px-6 py-4 bg-gray-50">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">Menunggu</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $bimbingans->where('status', 'menunggu')->count() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">Perlu Revisi</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $bimbingans->where('status', 'direvisi')->count() }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500 mb-1">Disetujui</p>
                        <p class="text-2xl font-bold text-green-600">{{ $bimbingans->where('status', 'disetujui')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Bimbingan -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-800">Riwayat Bimbingan</h3>
            </div>
            <div class="p-6">
                @if($bimbingans->count() > 0)
                    <div class="space-y-6">
                        @foreach($bimbingans as $index => $bimbingan)
                            <div class="relative pl-8 pb-6 {{ !$loop->last ? 'border-l-2 border-gray-200' : '' }}">
                                <!-- Timeline Dot -->
                                <div class="absolute left-0 -ml-2 flex items-center justify-center">
                                    <div class="w-4 h-4 rounded-full border-2 {{ $bimbingan->status === 'menunggu' ? 'bg-yellow-400 border-yellow-500' : ($bimbingan->status === 'direvisi' ? 'bg-orange-400 border-orange-500' : 'bg-green-400 border-green-500') }}"></div>
                                </div>

                                <!-- Bimbingan Card -->
                                <div class="bg-white border rounded-lg overflow-hidden {{ $bimbingan->status === 'menunggu' ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200' }}">
                                    <!-- Header -->
                                    <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Bimbingan ke-{{ $bimbingan->bimbingan_ke }}
                                            </span>
                                            @if($bimbingan->status === 'menunggu')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu Respon
                                                </span>
                                            @elseif($bimbingan->status === 'direvisi')
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                                    Perlu Revisi
                                                </span>
                                            @else
                                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-500">{{ $bimbingan->created_at->format('d M Y, H:i') }}</span>
                                    </div>

                                    <!-- Body -->
                                    <div class="p-4">
                                        <div class="mb-3">
                                            <p class="text-xs text-gray-500 mb-1">Pokok Bimbingan:</p>
                                            <p class="text-sm font-medium text-gray-900">{{ $bimbingan->pokok_bimbingan }}</p>
                                        </div>

                                        @if($bimbingan->catatan)
                                            <div class="mb-3">
                                                <p class="text-xs text-gray-500 mb-1">Catatan Mahasiswa:</p>
                                                <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded border">{{ $bimbingan->catatan }}</p>
                                            </div>
                                        @endif

                                        @if($bimbingan->pesan_dosen)
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                                <p class="text-xs text-blue-600 font-medium mb-1">Respon Dosen:</p>
                                                <p class="text-sm text-gray-800">{{ $bimbingan->pesan_dosen }}</p>
                                                @if($bimbingan->tanggal_respon)
                                                    <p class="text-xs text-gray-500 mt-2">{{ $bimbingan->tanggal_respon->format('d M Y, H:i') }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Footer/Actions -->
                                    <div class="px-4 py-3 bg-gray-50 border-t">
                                        <div class="flex items-center justify-between">
                                            <div class="text-xs text-gray-500">
                                                <span class="inline-flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $bimbingan->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <a href="{{ route('dosen.bimbingan.show', $bimbingan) }}" 
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium {{ $bimbingan->status === 'menunggu' ? 'bg-blue-600 text-white hover:bg-blue-700' : 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50' }} rounded-lg transition-colors">
                                                @if($bimbingan->status === 'menunggu')
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                    Respon Sekarang
                                                @else
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Lihat Detail
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada bimbingan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mahasiswa ini belum mengajukan bimbingan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
