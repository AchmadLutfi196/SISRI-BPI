<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Validasi Usulan Pembimbingan</h1>
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
                        Halaman ini menampilkan daftar usulan pembimbingan dari mahasiswa yang perlu Anda validasi.
                    </p>
                </div>
            </div>
        </div>

        <!-- Usulan List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($usulans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Topik</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang Minat</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sebagai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($usulans as $usulan)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $usulan->topik->mahasiswa->nama }}</div>
                                            <div class="text-sm text-gray-500">{{ $usulan->topik->mahasiswa->nim }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $usulan->topik->judul }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $usulan->topik->bidangMinat->nama ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $usulan->urutan == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                Pembimbing {{ $usulan->urutan }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $usulan->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($usulan->status == 'menunggu')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu
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
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('dosen.validasi-usulan.show', $usulan) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                Detail
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $usulans->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada usulan</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada usulan pembimbingan dari mahasiswa.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
