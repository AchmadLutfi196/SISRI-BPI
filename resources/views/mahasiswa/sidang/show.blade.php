<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('mahasiswa.sidang.index') }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Daftar Sidang
            </a>
        </div>

        @php
            $approvedCount = 0;
            if ($pendaftaran->status_pembimbing_1 === 'disetujui') $approvedCount++;
            if ($pendaftaran->status_pembimbing_2 === 'disetujui') $approvedCount++;
            if ($pendaftaran->status_koordinator === 'disetujui') $approvedCount++;
            
            $isRejected = $pendaftaran->status_pembimbing_1 === 'ditolak' || 
                          $pendaftaran->status_pembimbing_2 === 'ditolak' || 
                          $pendaftaran->status_koordinator === 'ditolak';
            
            $overallStatus = $isRejected ? 'ditolak' : ($approvedCount === 3 ? 'disetujui' : 'menunggu');
            $statusText = $isRejected ? 'Ditolak' : ($approvedCount === 3 ? 'Disetujui' : 'Menunggu Konfirmasi (' . $approvedCount . '/3)');
        @endphp

        <!-- Header Card -->
        <div class="bg-gradient-to-r {{ $pendaftaran->jenis === 'seminar_proposal' ? 'from-green-600 to-green-800' : 'from-blue-600 to-blue-800' }} rounded-lg shadow-sm p-6 mb-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm opacity-75">Detail Pendaftaran</p>
                    <h1 class="text-2xl font-bold mt-1">
                        {{ $pendaftaran->jenis === 'seminar_proposal' ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                    </h1>
                    <p class="mt-2 opacity-90">{{ $pendaftaran->jadwalSidang->nama ?? 'Periode tidak diketahui' }}</p>
                </div>
                
                <!-- Status Badge -->
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur">
                    @if($overallStatus === 'disetujui')
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Disetujui
                    @elseif($overallStatus === 'ditolak')
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Ditolak
                    @else
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $statusText }}
                    @endif
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status Approval -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-800">Status Persetujuan</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Pembimbing 1 -->
                            <div class="p-4 rounded-lg border-2 {{ $pendaftaran->status_pembimbing_1 === 'disetujui' ? 'border-green-200 bg-green-50' : ($pendaftaran->status_pembimbing_1 === 'ditolak' ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50') }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Pembimbing 1</span>
                                    @if($pendaftaran->status_pembimbing_1 === 'disetujui')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Disetujui
                                        </span>
                                    @elseif($pendaftaran->status_pembimbing_1 === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                            Menunggu
                                        </span>
                                    @endif
                                </div>
                                @if($pendaftaran->catatan_pembimbing_1)
                                    <p class="text-xs text-gray-600 mt-2">{{ $pendaftaran->catatan_pembimbing_1 }}</p>
                                @endif
                            </div>

                            <!-- Pembimbing 2 -->
                            <div class="p-4 rounded-lg border-2 {{ $pendaftaran->status_pembimbing_2 === 'disetujui' ? 'border-green-200 bg-green-50' : ($pendaftaran->status_pembimbing_2 === 'ditolak' ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50') }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Pembimbing 2</span>
                                    @if($pendaftaran->status_pembimbing_2 === 'disetujui')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Disetujui
                                        </span>
                                    @elseif($pendaftaran->status_pembimbing_2 === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                            Menunggu
                                        </span>
                                    @endif
                                </div>
                                @if($pendaftaran->catatan_pembimbing_2)
                                    <p class="text-xs text-gray-600 mt-2">{{ $pendaftaran->catatan_pembimbing_2 }}</p>
                                @endif
                            </div>

                            <!-- Koordinator -->
                            <div class="p-4 rounded-lg border-2 {{ $pendaftaran->status_koordinator === 'disetujui' ? 'border-green-200 bg-green-50' : ($pendaftaran->status_koordinator === 'ditolak' ? 'border-red-200 bg-red-50' : 'border-gray-200 bg-gray-50') }}">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium text-gray-700">Koordinator</span>
                                    @if($pendaftaran->status_koordinator === 'disetujui')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                            Disetujui
                                        </span>
                                    @elseif($pendaftaran->status_koordinator === 'ditolak')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                            Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>
                                            Menunggu
                                        </span>
                                    @endif
                                </div>
                                @if($pendaftaran->catatan_koordinator)
                                    <p class="text-xs text-gray-600 mt-2">{{ $pendaftaran->catatan_koordinator }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Topik Info -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-800">Informasi Topik</h3>
                    </div>
                    <div class="p-6">
                        <h4 class="font-medium text-gray-800 mb-2">{{ $pendaftaran->topik->judul }}</h4>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                                </svg>
                                {{ $pendaftaran->topik->bidangMinat->nama ?? '-' }}
                            </span>
                        </div>
                        
                        <!-- Pembimbing -->
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-3">Dosen Pembimbing</p>
                            <div class="flex flex-wrap gap-4">
                                @foreach($pendaftaran->topik->usulanPembimbing->sortBy('urutan') as $usulan)
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <span class="text-sm font-bold text-blue-600">{{ $usulan->urutan }}</span>
                                    </div>
                                    <span class="text-sm text-gray-700">{{ $usulan->dosen->nama ?? '-' }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Jadwal Pelaksanaan (if exists) -->
                @if($pendaftaran->pelaksanaanSidang)
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-gray-50">
                        <h3 class="font-semibold text-gray-800">Jadwal Pelaksanaan</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tanggal & Waktu</p>
                                <p class="font-medium text-gray-800">
                                    @if($pendaftaran->pelaksanaanSidang->tanggal)
                                        {{ \Carbon\Carbon::parse($pendaftaran->pelaksanaanSidang->tanggal)->format('d F Y') }}
                                    @else
                                        Belum ditentukan
                                    @endif
                                </p>
                                @if($pendaftaran->pelaksanaanSidang->waktu_mulai)
                                <p class="text-sm text-gray-600">
                                    {{ $pendaftaran->pelaksanaanSidang->waktu_mulai }} - {{ $pendaftaran->pelaksanaanSidang->waktu_selesai ?? '...' }} WIB
                                </p>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Ruangan</p>
                                <p class="font-medium text-gray-800">
                                    {{ $pendaftaran->pelaksanaanSidang->ruangan ?? 'Belum ditentukan' }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Penguji -->
                        @if($pendaftaran->pelaksanaanSidang->pengujiSidang && $pendaftaran->pelaksanaanSidang->pengujiSidang->isNotEmpty())
                        <div class="mt-6 pt-4 border-t">
                            <p class="text-sm text-gray-500 mb-3">Dosen Penguji</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($pendaftaran->pelaksanaanSidang->pengujiSidang->sortBy('urutan') as $penguji)
                                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                        <span class="text-sm font-bold text-purple-600">{{ $penguji->urutan }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $penguji->dosen->nama ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">Penguji {{ $penguji->urutan }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Nilai (if exists) -->
                @if($pendaftaran->pelaksanaanSidang && $pendaftaran->pelaksanaanSidang->nilai->isNotEmpty())
                @php
                    $nilaiCollection = $pendaftaran->pelaksanaanSidang->nilai;
                    $totalNilai = $nilaiCollection->avg('nilai');
                    
                    // Hitung nilai huruf berdasarkan rata-rata
                    $nilaiHuruf = match(true) {
                        $totalNilai >= 85 => 'A',
                        $totalNilai >= 80 => 'A-',
                        $totalNilai >= 75 => 'B+',
                        $totalNilai >= 70 => 'B',
                        $totalNilai >= 65 => 'B-',
                        $totalNilai >= 60 => 'C+',
                        $totalNilai >= 55 => 'C',
                        $totalNilai >= 50 => 'D',
                        default => 'E',
                    };
                @endphp
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b bg-green-50">
                        <h3 class="font-semibold text-green-800">Hasil Sidang</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center justify-center mb-6">
                            <div class="text-center">
                                <div class="w-24 h-24 mx-auto rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center mb-3">
                                    <span class="text-3xl font-bold text-white">{{ $nilaiHuruf }}</span>
                                </div>
                                <p class="text-3xl font-bold text-gray-800">{{ number_format($totalNilai, 2) }}</p>
                                <p class="text-sm text-gray-500">Nilai Rata-rata</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t">
                            @foreach($nilaiCollection as $n)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm text-gray-500">{{ $n->dosen->nama ?? 'Dosen' }}</p>
                                        <p class="text-xs text-gray-400">{{ ucfirst(str_replace('_', ' ', $n->jenis_nilai)) }}</p>
                                    </div>
                                    <p class="text-xl font-bold text-gray-800">{{ number_format($n->nilai, 2) }}</p>
                                </div>
                                @if($n->catatan)
                                <p class="text-xs text-gray-600 mt-2">{{ $n->catatan }}</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Info Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Informasi Pendaftaran</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Daftar</p>
                            <p class="font-medium text-gray-800">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Jenis Sidang</p>
                            <p class="font-medium text-gray-800">
                                {{ $pendaftaran->jenis === 'seminar_proposal' ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                            </p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Periode</p>
                            <p class="font-medium text-gray-800">{{ $pendaftaran->jadwalSidang->nama ?? '-' }}</p>
                        </div>
                        
                        @if($pendaftaran->jadwalSidang)
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Buka</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pendaftaran->jadwalSidang->tanggal_buka)->format('d M Y') }}</p>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Tutup</p>
                            <p class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($pendaftaran->jadwalSidang->tanggal_tutup)->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Riwayat</h3>
                    
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Pendaftaran diajukan</p>
                                <p class="text-xs text-gray-500">{{ $pendaftaran->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($overallStatus !== 'menunggu')
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center
                                {{ $overallStatus === 'disetujui' ? 'bg-green-100' : 'bg-red-100' }}">
                                @if($overallStatus === 'disetujui')
                                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">
                                    {{ $overallStatus === 'disetujui' ? 'Pendaftaran disetujui' : 'Pendaftaran ditolak' }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $pendaftaran->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($pendaftaran->pelaksanaanSidang && $pendaftaran->pelaksanaanSidang->tanggal)
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Jadwal sidang ditentukan</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($pendaftaran->pelaksanaanSidang->tanggal)->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($pendaftaran->pelaksanaanSidang && $pendaftaran->pelaksanaanSidang->nilai->isNotEmpty())
                        <div class="flex gap-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">Nilai diumumkan</p>
                                <p class="text-xs text-gray-500">{{ $pendaftaran->pelaksanaanSidang->nilai->first()->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Waiting Status -->
                @if($overallStatus === 'menunggu')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-yellow-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-medium text-yellow-800">Menunggu Konfirmasi ({{ $approvedCount }}/3)</p>
                            <p class="text-xs text-yellow-700 mt-1">
                                Pendaftaran Anda sedang menunggu persetujuan dari pembimbing dan koordinator prodi.
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
