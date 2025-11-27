<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard Dosen</h1>

        <!-- Info Dosen -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-2xl font-bold text-gray-900">{{ $dosen->nama }}</h3>
                            <p class="text-gray-500">NIP: {{ $dosen->nip ?? '-' }} | NIDN: {{ $dosen->nidn ?? '-' }}</p>
                            <p class="text-gray-500">{{ $dosen->prodi->nama ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Usulan Menunggu</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['usulan_menunggu'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Usulan Diterima</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['usulan_diterima'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Bimbingan Menunggu</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['bimbingan_menunggu'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Bimbingan</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_bimbingan'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Sidang Menunggu</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['sidang_menunggu'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Usulan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Usulan Pembimbingan Terbaru</h3>
                            <a href="{{ route('dosen.validasi-usulan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                        </div>
                        @if($recentUsulan->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentUsulan as $usulan)
                                    <div class="border rounded-lg p-4">
                                        <p class="font-medium text-gray-900">{{ $usulan->topik->judul }}</p>
                                        <p class="text-sm text-gray-500">{{ $usulan->topik->mahasiswa->nama }} ({{ $usulan->topik->mahasiswa->nim }})</p>
                                        <p class="text-xs text-gray-400 mt-1">Pembimbing {{ $usulan->urutan }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Tidak ada usulan menunggu.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Bimbingan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Bimbingan Menunggu Respon</h3>
                            <a href="{{ route('dosen.bimbingan.index') }}" class="text-sm text-blue-600 hover:text-blue-800">Lihat Semua</a>
                        </div>
                        @if($recentBimbingan->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentBimbingan as $bimbingan)
                                    <div class="border rounded-lg p-4">
                                        <p class="font-medium text-gray-900">{{ $bimbingan->pokok_bimbingan }}</p>
                                        <p class="text-sm text-gray-500">{{ $bimbingan->topik->mahasiswa->nama }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ ucfirst($bimbingan->jenis) }} | {{ $bimbingan->created_at->diffForHumans() }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Tidak ada bimbingan menunggu.</p>
                        @endif
                    </div>
                </div>
            </div>
    </div>
</x-app-layout>
