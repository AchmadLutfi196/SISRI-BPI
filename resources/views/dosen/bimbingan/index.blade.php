<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Bimbingan Mahasiswa</h1>
        </div>

        <!-- Kuota Info Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <!-- Kuota Pembimbing 1 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kuota Pembimbing 1</p>
                        <div class="mt-1 flex items-baseline">
                            <p class="text-2xl font-bold text-blue-600">{{ $kuotaInfo['sisa_1'] }}</p>
                            <p class="ml-2 text-sm text-gray-500">/ {{ $kuotaInfo['kuota_1'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Terpakai: {{ $kuotaInfo['terpakai_1'] }} mahasiswa</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                <!-- Progress bar -->
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $percent1 = $kuotaInfo['kuota_1'] > 0 ? ($kuotaInfo['terpakai_1'] / $kuotaInfo['kuota_1']) * 100 : 0;
                            $barColor1 = $percent1 >= 90 ? 'bg-red-500' : ($percent1 >= 70 ? 'bg-yellow-500' : 'bg-blue-500');
                        @endphp
                        <div class="{{ $barColor1 }} h-2 rounded-full transition-all duration-300" style="width: {{ min($percent1, 100) }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Kuota Pembimbing 2 -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Kuota Pembimbing 2</p>
                        <div class="mt-1 flex items-baseline">
                            <p class="text-2xl font-bold text-purple-600">{{ $kuotaInfo['sisa_2'] }}</p>
                            <p class="ml-2 text-sm text-gray-500">/ {{ $kuotaInfo['kuota_2'] }}</p>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Terpakai: {{ $kuotaInfo['terpakai_2'] }} mahasiswa</p>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full">
                        <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <!-- Progress bar -->
                <div class="mt-3">
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        @php
                            $percent2 = $kuotaInfo['kuota_2'] > 0 ? ($kuotaInfo['terpakai_2'] / $kuotaInfo['kuota_2']) * 100 : 0;
                            $barColor2 = $percent2 >= 90 ? 'bg-red-500' : ($percent2 >= 70 ? 'bg-yellow-500' : 'bg-purple-500');
                        @endphp
                        <div class="{{ $barColor2 }} h-2 rounded-full transition-all duration-300" style="width: {{ min($percent2, 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jenis Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('dosen.bimbingan.index', ['jenis' => 'proposal']) }}"
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenis == 'proposal' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Bimbingan Proposal
                    </a>
                    <a href="{{ route('dosen.bimbingan.index', ['jenis' => 'skripsi']) }}"
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenis == 'skripsi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Bimbingan Skripsi
                    </a>
                </nav>
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700">
                        Halaman ini menampilkan daftar bimbingan {{ $jenis }} dari mahasiswa yang Anda bimbing.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bimbingan List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($mahasiswaList->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($mahasiswaList as $item)
                            <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow duration-200">
                                <!-- Header Card -->
                                <div class="p-5 border-b border-gray-100">
                                    <div class="flex items-center gap-4">
                                        <x-avatar 
                                            :src="$item['mahasiswa']->foto_url" 
                                            :initials="$item['mahasiswa']->initials" 
                                            size="lg" 
                                        />
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-base font-semibold text-gray-900 truncate">
                                                {{ $item['mahasiswa']->nama }}
                                            </h3>
                                            <p class="text-sm text-gray-500">{{ $item['mahasiswa']->nim }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Body Card -->
                                <div class="p-5 space-y-3">
                                    <!-- Topik -->
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Topik Skripsi:</p>
                                        <p class="text-sm text-gray-900 line-clamp-2">{{ $item['topik']->judul }}</p>
                                    </div>

                                    <!-- Statistics -->
                                    <div class="grid grid-cols-3 gap-2 pt-3 border-t">
                                        <div class="text-center">
                                            <p class="text-xl font-bold text-gray-900">{{ $item['total_bimbingan'] }}</p>
                                            <p class="text-xs text-gray-500">Total</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xl font-bold text-yellow-600">{{ $item['menunggu'] }}</p>
                                            <p class="text-xs text-gray-500">Menunggu</p>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xl font-bold text-green-600">{{ $item['disetujui'] }}</p>
                                            <p class="text-xs text-gray-500">Disetujui</p>
                                        </div>
                                    </div>

                                    <!-- Last Activity -->
                                    <div class="pt-3 border-t">
                                        <div class="flex items-center justify-between text-xs">
                                            <span class="text-gray-500">Terakhir:</span>
                                            <span class="text-gray-700 font-medium">
                                                {{ $item['last_bimbingan']->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer Card -->
                                <div class="px-5 pb-5">
                                    <a href="{{ route('dosen.bimbingan.mahasiswa', ['mahasiswa' => $item['mahasiswa']->id, 'jenis' => $jenis]) }}" 
                                       class="block w-full text-center px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                        <div class="flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Lihat Detail Bimbingan
                                        </div>
                                    </a>
                                    
                                    @if($item['menunggu'] > 0)
                                        <p class="text-xs text-center text-yellow-600 mt-2 font-medium">
                                            {{ $item['menunggu'] }} bimbingan perlu direspon
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada mahasiswa</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada mahasiswa yang mengajukan bimbingan {{ $jenis }}.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
