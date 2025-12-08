<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Revisi Pasca Sidang</h1>
            <p class="text-gray-600 mt-1">Daftar sidang yang memerlukan revisi</p>
        </div>

        @if($pelaksanaanList->isEmpty())
            <!-- Empty State -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Sidang</h3>
                    <p class="text-gray-500">Anda belum memiliki sidang yang selesai atau memerlukan revisi.</p>
                </div>
            </div>
        @else
            <!-- List of Pelaksanaan -->
            <div class="space-y-4">
                @foreach($pelaksanaanList as $pelaksanaan)
                    @php
                        $topik = $pelaksanaan->pendaftaranSidang->topik;
                        $jenis = $pelaksanaan->pendaftaranSidang->jenis;
                        
                        // Count penguji yang kasih catatan revisi
                        $jumlahPengujiRevisi = $pelaksanaan->pengujiSidang ? $pelaksanaan->pengujiSidang->count() : 0;
                        
                        // Count revisi status (yang sudah disubmit)
                        $totalRevisi = $pelaksanaan->revisiSidang->count();
                        $menunggu = $pelaksanaan->revisiSidang->where('status', 'menunggu')->count();
                        $disetujui = $pelaksanaan->revisiSidang->where('status', 'disetujui')->count();
                        $revisiUlang = $pelaksanaan->revisiSidang->where('status', 'revisi_ulang')->count();
                    @endphp
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Type Badge -->
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        {{ in_array($jenis, ['proposal', 'seminar_proposal']) ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ in_array($jenis, ['proposal', 'seminar_proposal']) ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                                    </span>
                                    
                                    <!-- Title -->
                                    <h3 class="text-lg font-semibold text-gray-900 mt-3">{{ $topik->judul }}</h3>
                                    
                                    <!-- Date -->
                                    <p class="text-sm text-gray-500 mt-2">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->format('d F Y, H:i') }} WIB
                                    </p>
                                    
                                    <!-- Status Revisi -->
                                    <div class="mt-4 flex items-center gap-4">
                                        @if($jumlahPengujiRevisi > 0)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                                {{ $jumlahPengujiRevisi }} Penguji Beri Revisi
                                            </span>
                                        @endif
                                        
                                        @if($totalRevisi > 0)
                                            @if($menunggu > 0)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    {{ $menunggu }} Menunggu Validasi
                                                </span>
                                            @endif
                                            @if($disetujui > 0)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $disetujui }} Disetujui
                                                </span>
                                            @endif
                                            @if($revisiUlang > 0)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    {{ $revisiUlang }} Perlu Diperbaiki
                                                </span>
                                            @endif
                                        @elseif($jumlahPengujiRevisi == 0)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Belum Submit Revisi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Action Button -->
                                <a href="{{ route('mahasiswa.revisi.show', $pelaksanaan) }}" 
                                   class="ml-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    Lihat Detail
                                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-app-layout>
