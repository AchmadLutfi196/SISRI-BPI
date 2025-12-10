<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Validasi Revisi Pasca {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi' }}</h1>
            <p class="text-gray-600 mt-1">Daftar revisi yang perlu divalidasi</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-yellow-50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Menunggu Validasi</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $menunggu }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Disetujui</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $disetujui }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Revisi Ulang</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $revisiUlang }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($revisiList->isEmpty())
            <!-- Empty State -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum Ada Revisi</h3>
                    <p class="text-gray-500">Belum ada mahasiswa yang mengsubmit revisi untuk divalidasi.</p>
                </div>
            </div>
        @else
            <!-- Revisi List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Submit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($revisiList as $revisi)
                                @php
                                    $mahasiswa = $revisi->pelaksanaanSidang->pendaftaranSidang->topik->mahasiswa;
                                    $topik = $revisi->pelaksanaanSidang->pendaftaranSidang->topik;
                                    $statusClass = [
                                        'menunggu' => 'bg-yellow-100 text-yellow-800',
                                        'disetujui' => 'bg-green-100 text-green-800',
                                        'revisi_ulang' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <x-avatar 
                                                :src="$mahasiswa->foto_url" 
                                                :initials="$mahasiswa->initials" 
                                                size="sm"
                                                class="mr-3"
                                            />
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $mahasiswa->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $mahasiswa->nim }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $topik->judul }}">
                                            {{ $topik->judul }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $revisi->tanggal_submit->format('d M Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass[$revisi->status] }}">
                                            @if($revisi->status === 'menunggu')
                                                Menunggu
                                            @elseif($revisi->status === 'disetujui')
                                                Disetujui
                                            @else
                                                Revisi Ulang
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('dosen.validasi-revisi.show', $revisi) }}" 
                                               class="text-{{ $jenis === 'sempro' ? 'blue' : 'purple' }}-600 hover:text-{{ $jenis === 'sempro' ? 'blue' : 'purple' }}-900">
                                                Lihat Detail
                                            </a>
                                            
                                            @if($revisi->status === 'disetujui')
                                                @php
                                                    $pelaksanaan = $revisi->pelaksanaanSidang;
                                                    $jenisSidang = $pelaksanaan->pendaftaranSidang->jenis;
                                                    $routeName = in_array($jenisSidang, ['proposal', 'seminar_proposal']) 
                                                        ? 'dosen.nilai-sempro.create' 
                                                        : 'dosen.nilai-sidang.create';
                                                @endphp
                                                <span class="text-gray-300">|</span>
                                                <a href="{{ route($routeName, $pelaksanaan) }}" 
                                                   class="text-green-600 hover:text-green-900 font-medium">
                                                    Update Nilai
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t">
                    {{ $revisiList->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
