<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Bimbingan Skripsi</h1>
                <p class="text-gray-600">Kelola bimbingan proposal dan skripsi Anda</p>
            </div>
            @if($hasPendingBimbingan)
            <div class="mt-4 md:mt-0">
                <span class="inline-flex items-center px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed" title="Tunggu bimbingan sebelumnya disetujui">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Ajukan Bimbingan
                </span>
                <p class="text-xs text-orange-600 mt-1">Menunggu persetujuan bimbingan sebelumnya</p>
            </div>
            @else
            <a href="{{ route('mahasiswa.bimbingan.create', ['jenis' => $jenis]) }}" 
               class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajukan Bimbingan
            </a>
            @endif
        </div>

        <!-- Topik Info Card -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg shadow-sm p-6 mb-6 text-white">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-white/20 rounded-lg">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-blue-100 text-sm">Topik Skripsi</p>
                    <h2 class="text-lg font-semibold mt-1">{{ $topik->judul }}</h2>
                    <div class="mt-3 flex flex-wrap gap-4 text-sm">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                            </svg>
                            {{ $topik->bidangMinat->nama ?? '-' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b">
                <nav class="flex -mb-px">
                    <a href="{{ route('mahasiswa.bimbingan.index', ['jenis' => 'proposal']) }}" 
                       class="px-6 py-4 border-b-2 text-sm font-medium transition-colors
                              {{ $jenis === 'proposal' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Bimbingan Proposal
                    </a>
                    <a href="{{ route('mahasiswa.bimbingan.index', ['jenis' => 'skripsi']) }}" 
                       class="px-6 py-4 border-b-2 text-sm font-medium transition-colors
                              {{ $jenis === 'skripsi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Bimbingan Skripsi
                    </a>
                </nav>
            </div>
        </div>

        <!-- Bimbingan List -->
        @if($bimbingans->isEmpty())
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div class="w-20 h-20 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Belum Ada Bimbingan</h3>
            <p class="text-gray-500 mb-6">Anda belum mengajukan bimbingan {{ $jenis }} apapun.</p>
            <a href="{{ route('mahasiswa.bimbingan.create', ['jenis' => $jenis]) }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Ajukan Bimbingan Pertama
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($bimbingans as $index => $bimbingan)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start gap-4">
                            <!-- Number Badge -->
                            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ $bimbingans->total() - (($bimbingans->currentPage() - 1) * $bimbingans->perPage()) - $index }}
                            </div>
                            
                            <div>
                                <!-- Date & Dosen -->
                                <div class="flex flex-wrap items-center gap-2 text-sm text-gray-500 mb-2">
                                    <span>{{ $bimbingan->created_at->format('d M Y, H:i') }}</span>
                                    <span>•</span>
                                    <span class="font-medium text-gray-700">{{ $bimbingan->dosen->nama ?? '-' }}</span>
                                </div>
                                
                                <!-- Pokok Bimbingan -->
                                <h3 class="text-gray-800 font-medium mb-2">{{ Str::limit($bimbingan->pokok_bimbingan, 150) }}</h3>
                                
                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $bimbingan->status === 'disetujui' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $bimbingan->status === 'ditolak' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $bimbingan->status === 'direvisi' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $bimbingan->status === 'menunggu' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                    @switch($bimbingan->status)
                                        @case('disetujui')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            @break
                                        @case('direvisi')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                            </svg>
                                            @break
                                        @case('ditolak')
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            @break
                                        @default
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                    @endswitch
                                    {{ ucfirst($bimbingan->status) }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Action Button -->
                        <a href="{{ route('mahasiswa.bimbingan.show', $bimbingan) }}" 
                           class="px-4 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Detail
                        </a>
                    </div>
                    
                    <!-- Feedback from Dosen -->
                    @if($bimbingan->catatan_dosen && in_array($bimbingan->status, ['disetujui', 'direvisi', 'ditolak']))
                    <div class="mt-4 p-4 rounded-lg
                        {{ $bimbingan->status === 'disetujui' ? 'bg-green-50 border border-green-200' : '' }}
                        {{ $bimbingan->status === 'direvisi' ? 'bg-orange-50 border border-orange-200' : '' }}
                        {{ $bimbingan->status === 'ditolak' ? 'bg-red-50 border border-red-200' : '' }}">
                        <p class="text-sm font-medium mb-1
                            {{ $bimbingan->status === 'disetujui' ? 'text-green-800' : '' }}
                            {{ $bimbingan->status === 'direvisi' ? 'text-orange-800' : '' }}
                            {{ $bimbingan->status === 'ditolak' ? 'text-red-800' : '' }}">
                            Catatan Dosen:
                        </p>
                        <p class="text-sm {{ $bimbingan->status === 'disetujui' ? 'text-green-700' : '' }}
                            {{ $bimbingan->status === 'direvisi' ? 'text-orange-700' : '' }}
                            {{ $bimbingan->status === 'ditolak' ? 'text-red-700' : '' }}">
                            "{{ Str::limit($bimbingan->catatan_dosen, 200) }}"
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $bimbingans->appends(['jenis' => $jenis])->links() }}
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $bimbingans->total() }}</p>
                        <p class="text-sm text-gray-500">Total Bimbingan</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $bimbingans->where('status', 'menunggu')->count() }}</p>
                        <p class="text-sm text-gray-500">Menunggu</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $bimbingans->where('status', 'direvisi')->count() }}</p>
                        <p class="text-sm text-gray-500">Perlu Revisi</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-800">{{ $bimbingans->where('status', 'disetujui')->count() }}</p>
                        <p class="text-sm text-gray-500">Disetujui</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
