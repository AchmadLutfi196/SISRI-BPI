<x-app-layout>
    <div class="max-w-7xl mx-auto">
        @php
            // Ambil pendaftaran TERBARU untuk setiap jenis (agar tidak ambil yang lama/ditolak)
            $seminarProposal = $pendaftarans->where('jenis', 'seminar_proposal')->sortByDesc('id')->first();
            $sidangSkripsi = $pendaftarans->where('jenis', 'sidang_skripsi')->sortByDesc('id')->first();
            
            // Pendaftaran aktif (tidak ditolak) untuk cek bisa daftar atau tidak
            $seminarProposalActive = $seminarProposal && !$seminarProposal->isRejected() ? $seminarProposal : null;
            $sidangSkripsiActive = $sidangSkripsi && !$sidangSkripsi->isRejected() ? $sidangSkripsi : null;
            
            // Cek rejected (dari pendaftaran terbaru)
            $seminarRejected = $seminarProposal && $seminarProposal->isRejected();
            $sidangRejected = $sidangSkripsi && $sidangSkripsi->isRejected();
            
            // Cek apakah sudah lulus sempro (punya nilai)
            $lulusSempro = $seminarProposalActive && $seminarProposalActive->pelaksanaanSidang && $seminarProposalActive->pelaksanaanSidang->nilai;
            
            // Status untuk progress
            $seminarStatus = 'pending';
            if ($seminarProposalActive) {
                if ($lulusSempro) {
                    $seminarStatus = 'completed';
                } else {
                    $seminarStatus = 'active';
                }
            }
            
            $sidangStatus = 'pending';
            if ($sidangSkripsiActive) {
                if ($sidangSkripsiActive->pelaksanaanSidang && $sidangSkripsiActive->pelaksanaanSidang->nilai) {
                    $sidangStatus = 'completed';
                } else {
                    $sidangStatus = 'active';
                }
            }
        @endphp

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Pendaftaran Sidang</h1>
                <p class="text-gray-600">Kelola pendaftaran seminar dan sidang skripsi Anda</p>
            </div>
            <div class="mt-4 md:mt-0 flex gap-2">
                @if(!$seminarProposalActive)
                <a href="{{ route('mahasiswa.sidang.create', ['jenis' => 'seminar_proposal']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ $seminarRejected ? 'Daftar Ulang Seminar' : 'Daftar Seminar' }}
                </a>
                @endif
                
                @if($lulusSempro && !$sidangSkripsiActive)
                <a href="{{ route('mahasiswa.sidang.create', ['jenis' => 'sidang_skripsi']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ $sidangRejected ? 'Daftar Ulang Sidang' : 'Daftar Sidang' }}
                </a>
                @elseif(!$lulusSempro && !$sidangSkripsiActive)
                <button disabled
                   class="inline-flex items-center px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" title="Lulus seminar proposal terlebih dahulu">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Daftar Sidang
                </button>
                @endif
            </div>
        </div>

        <!-- Topik Info Card -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-800 rounded-lg shadow-sm p-6 mb-6 text-white">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-purple-100 text-sm">Topik Skripsi</p>
                    <h2 class="text-lg font-semibold mt-1">{{ $topik->judul }}</h2>
                    <div class="mt-3 flex flex-wrap gap-4 text-sm">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"></path>
                            </svg>
                            {{ $topik->bidangMinat->nama ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidang Progress -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Progress Sidang</h3>
            <div class="flex items-center justify-between">
                
                <!-- Step 1: Seminar Proposal -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $seminarStatus === 'completed' ? 'bg-green-500' : ($seminarStatus === 'active' ? 'bg-blue-500' : 'bg-gray-200') }}">
                        @if($seminarStatus === 'completed')
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <span class="text-xl font-bold {{ $seminarStatus === 'active' ? 'text-white' : 'text-gray-500' }}">1</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium mt-2 {{ $seminarStatus === 'completed' ? 'text-green-600' : ($seminarStatus === 'active' ? 'text-blue-600' : 'text-gray-500') }}">
                        Seminar Proposal
                    </p>
                    <p class="text-xs text-gray-500">
                        @if($seminarStatus === 'completed')
                            Selesai
                        @elseif($seminarStatus === 'active')
                            Terdaftar
                        @else
                            Belum Daftar
                        @endif
                    </p>
                </div>

                <!-- Line -->
                <div class="flex-1 h-1 mx-2 {{ $seminarStatus === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}"></div>

                <!-- Step 2: Sidang Skripsi -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $sidangStatus === 'completed' ? 'bg-green-500' : ($sidangStatus === 'active' ? 'bg-blue-500' : 'bg-gray-200') }}">
                        @if($sidangStatus === 'completed')
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        @else
                            <span class="text-xl font-bold {{ $sidangStatus === 'active' ? 'text-white' : 'text-gray-500' }}">2</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium mt-2 {{ $sidangStatus === 'completed' ? 'text-green-600' : ($sidangStatus === 'active' ? 'text-blue-600' : 'text-gray-500') }}">
                        Sidang Skripsi
                    </p>
                    <p class="text-xs text-gray-500">
                        @if($sidangStatus === 'completed')
                            Selesai
                        @elseif($sidangStatus === 'active')
                            Terdaftar
                        @else
                            Belum Daftar
                        @endif
                    </p>
                </div>

                <!-- Line -->
                <div class="flex-1 h-1 mx-2 {{ $sidangStatus === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}"></div>

                <!-- Step 3: Lulus -->
                <div class="flex flex-col items-center flex-1">
                    <div class="w-14 h-14 rounded-full flex items-center justify-center {{ $sidangStatus === 'completed' ? 'bg-green-500' : 'bg-gray-200' }}">
                        @if($sidangStatus === 'completed')
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0z"></path>
                            </svg>
                        @else
                            <span class="text-xl font-bold text-gray-500">🎓</span>
                        @endif
                    </div>
                    <p class="text-sm font-medium mt-2 {{ $sidangStatus === 'completed' ? 'text-green-600' : 'text-gray-500' }}">
                        Lulus
                    </p>
                    <p class="text-xs text-gray-500">
                        {{ $sidangStatus === 'completed' ? 'Selamat!' : 'Menunggu' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Pendaftaran List -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Seminar Proposal -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-green-50">
                    <h3 class="text-lg font-semibold text-green-800">Seminar Proposal</h3>
                </div>
                <div class="p-6">
                    @if($seminarProposal)
                        @php
                            $approvedCount = 0;
                            if ($seminarProposal->status_pembimbing_1 === 'disetujui') $approvedCount++;
                            if ($seminarProposal->status_pembimbing_2 === 'disetujui') $approvedCount++;
                            if ($seminarProposal->status_koordinator === 'disetujui') $approvedCount++;
                            
                            $isRejected = $seminarProposal->isRejected();
                            
                            $seminarStatusText = $isRejected ? 'Ditolak' : ($approvedCount === 3 ? 'Disetujui' : 'Menunggu (' . $approvedCount . '/3)');
                            $seminarStatusClass = $isRejected ? 'bg-red-100 text-red-800' : ($approvedCount === 3 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800');
                        @endphp
                        <div class="space-y-4">
                            <!-- Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Status</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $seminarStatusClass }}">
                                    {{ $seminarStatusText }}
                                </span>
                            </div>
                            
                            @if($isRejected)
                            <!-- Alasan Ditolak -->
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-sm font-medium text-red-800 mb-2">Alasan Penolakan:</p>
                                @if($seminarProposal->status_pembimbing_1 === 'ditolak' && $seminarProposal->catatan_pembimbing_1)
                                <p class="text-sm text-red-700">• Pembimbing 1: {{ $seminarProposal->catatan_pembimbing_1 }}</p>
                                @endif
                                @if($seminarProposal->status_pembimbing_2 === 'ditolak' && $seminarProposal->catatan_pembimbing_2)
                                <p class="text-sm text-red-700">• Pembimbing 2: {{ $seminarProposal->catatan_pembimbing_2 }}</p>
                                @endif
                                @if($seminarProposal->status_koordinator === 'ditolak' && $seminarProposal->catatan_koordinator)
                                <p class="text-sm text-red-700">• Koordinator: {{ $seminarProposal->catatan_koordinator }}</p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Jadwal -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Periode</span>
                                <span class="text-sm font-medium text-gray-800">{{ $seminarProposal->jadwalSidang->nama ?? '-' }}</span>
                            </div>
                            
                            <!-- Tanggal Daftar -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Tanggal Daftar</span>
                                <span class="text-sm font-medium text-gray-800">{{ $seminarProposal->created_at->format('d M Y') }}</span>
                            </div>
                            
                            @if($seminarProposal->pelaksanaanSidang)
                            <!-- Tanggal Sidang -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Tanggal Sidang</span>
                                <span class="text-sm font-medium text-gray-800">{{ $seminarProposal->pelaksanaanSidang->tanggal ? \Carbon\Carbon::parse($seminarProposal->pelaksanaanSidang->tanggal)->format('d M Y') : '-' }}</span>
                            </div>
                            @endif
                            
                            @if($lulusSempro)
                            <!-- Nilai -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Nilai</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ✓ Lulus
                                </span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-6 pt-4 border-t flex gap-2">
                            <a href="{{ route('mahasiswa.sidang.show', $seminarProposal) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors">
                                Lihat Detail
                            </a>
                            @if($isRejected)
                            <a href="{{ route('mahasiswa.sidang.create', ['jenis' => 'seminar_proposal']) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Daftar Ulang
                            </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 mb-4">Anda belum mendaftar seminar proposal</p>
                            <a href="{{ route('mahasiswa.sidang.create', ['jenis' => 'seminar_proposal']) }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Daftar Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidang Skripsi -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b bg-blue-50">
                    <h3 class="text-lg font-semibold text-blue-800">Sidang Skripsi</h3>
                </div>
                <div class="p-6">
                    @if($sidangSkripsi)
                        @php
                            $approvedCount = 0;
                            if ($sidangSkripsi->status_pembimbing_1 === 'disetujui') $approvedCount++;
                            if ($sidangSkripsi->status_pembimbing_2 === 'disetujui') $approvedCount++;
                            if ($sidangSkripsi->status_koordinator === 'disetujui') $approvedCount++;
                            
                            $isRejected = $sidangSkripsi->isRejected();
                            
                            $sidangStatusText = $isRejected ? 'Ditolak' : ($approvedCount === 3 ? 'Disetujui' : 'Menunggu (' . $approvedCount . '/3)');
                            $sidangStatusClass = $isRejected ? 'bg-red-100 text-red-800' : ($approvedCount === 3 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800');
                        @endphp
                        <div class="space-y-4">
                            <!-- Status -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Status</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $sidangStatusClass }}">
                                    {{ $sidangStatusText }}
                                </span>
                            </div>
                            
                            @if($isRejected)
                            <!-- Alasan Ditolak -->
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                <p class="text-sm font-medium text-red-800 mb-2">Alasan Penolakan:</p>
                                @if($sidangSkripsi->status_pembimbing_1 === 'ditolak' && $sidangSkripsi->catatan_pembimbing_1)
                                <p class="text-sm text-red-700">• Pembimbing 1: {{ $sidangSkripsi->catatan_pembimbing_1 }}</p>
                                @endif
                                @if($sidangSkripsi->status_pembimbing_2 === 'ditolak' && $sidangSkripsi->catatan_pembimbing_2)
                                <p class="text-sm text-red-700">• Pembimbing 2: {{ $sidangSkripsi->catatan_pembimbing_2 }}</p>
                                @endif
                                @if($sidangSkripsi->status_koordinator === 'ditolak' && $sidangSkripsi->catatan_koordinator)
                                <p class="text-sm text-red-700">• Koordinator: {{ $sidangSkripsi->catatan_koordinator }}</p>
                                @endif
                            </div>
                            @endif
                            
                            <!-- Jadwal -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Periode</span>
                                <span class="text-sm font-medium text-gray-800">{{ $sidangSkripsi->jadwalSidang->nama ?? '-' }}</span>
                            </div>
                            
                            <!-- Tanggal Daftar -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Tanggal Daftar</span>
                                <span class="text-sm font-medium text-gray-800">{{ $sidangSkripsi->created_at->format('d M Y') }}</span>
                            </div>
                            
                            @if($sidangSkripsi->pelaksanaanSidang)
                            <!-- Tanggal Sidang -->
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Tanggal Sidang</span>
                                <span class="text-sm font-medium text-gray-800">{{ $sidangSkripsi->pelaksanaanSidang->tanggal ? \Carbon\Carbon::parse($sidangSkripsi->pelaksanaanSidang->tanggal)->format('d M Y') : '-' }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <div class="mt-6 pt-4 border-t flex gap-2">
                            <a href="{{ route('mahasiswa.sidang.show', $sidangSkripsi) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors">
                                Lihat Detail
                            </a>
                            @if($isRejected)
                            <a href="{{ route('mahasiswa.sidang.create', ['jenis' => 'sidang_skripsi']) }}" 
                               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Daftar Ulang
                            </a>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            @if($lulusSempro)
                                <!-- Bisa daftar sidang -->
                                <div class="w-16 h-16 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-medium mb-2">Anda sudah lulus seminar proposal!</p>
                                <p class="text-gray-500 mb-4">Silakan daftar sidang skripsi sekarang</p>
                                <a href="{{ route('mahasiswa.sidang.create', ['jenis' => 'sidang_skripsi']) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Daftar Sekarang
                                </a>
                            @else
                                <!-- Belum lulus sempro, tidak bisa daftar -->
                                <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-700 font-medium mb-2">Belum bisa mendaftar sidang skripsi</p>
                                <p class="text-gray-500 mb-4">Selesaikan seminar proposal terlebih dahulu</p>
                                <button disabled
                                   class="inline-flex items-center px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Terkunci
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
