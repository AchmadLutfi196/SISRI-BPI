<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Title -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Persetujuan Pendaftaran Sidang</h1>
        </div>

        <!-- Jenis Filter Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="{{ route('dosen.persetujuan-sidang.index', ['jenis' => 'proposal']) }}"
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenis == 'proposal' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Seminar Proposal
                    </a>
                    <a href="{{ route('dosen.persetujuan-sidang.index', ['jenis' => 'skripsi']) }}"
                       class="py-4 px-1 border-b-2 font-medium text-sm {{ $jenis == 'skripsi' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Sidang Skripsi
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
                        Halaman ini menampilkan daftar pendaftaran sidang {{ $jenis == 'proposal' ? 'seminar proposal' : 'skripsi' }} dari mahasiswa bimbingan Anda yang memerlukan persetujuan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Pendaftaran List -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($pendaftarans->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sebagai</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Anda</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pendaftarans as $pendaftaran)
                                    @php
                                        $statusField = 'status_pembimbing_' . $pendaftaran->urutan_pembimbing;
                                        $myStatus = $pendaftaran->$statusField ?? 'menunggu';
                                        
                                        $approvedCount = 0;
                                        if ($pendaftaran->status_pembimbing_1 === 'disetujui') $approvedCount++;
                                        if ($pendaftaran->status_pembimbing_2 === 'disetujui') $approvedCount++;
                                        if ($pendaftaran->status_koordinator === 'disetujui') $approvedCount++;
                                    @endphp
                                    <tr class="hover:bg-gray-50 {{ $myStatus == 'menunggu' ? 'bg-yellow-50' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $pendaftaran->topik->mahasiswa->nama }}</div>
                                            <div class="text-sm text-gray-500">{{ $pendaftaran->topik->mahasiswa->nim }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs truncate">{{ $pendaftaran->topik->judul }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $pendaftaran->urutan_pembimbing == 1 ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                Pembimbing {{ $pendaftaran->urutan_pembimbing }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($myStatus == 'menunggu')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Menunggu
                                                </span>
                                            @elseif($myStatus == 'disetujui')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Disetujui
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-sm font-medium {{ $approvedCount == 3 ? 'text-green-600' : 'text-gray-600' }}">
                                                    {{ $approvedCount }}/3
                                                </span>
                                                <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ ($approvedCount / 3) * 100 }}%"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $pendaftaran->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('dosen.persetujuan-sidang.show', $pendaftaran) }}" 
                                               class="text-blue-600 hover:text-blue-900">
                                                {{ $myStatus == 'menunggu' ? 'Validasi' : 'Detail' }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $pendaftarans->appends(['jenis' => $jenis])->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada pendaftaran</h3>
                        <p class="mt-1 text-sm text-gray-500">Belum ada pendaftaran sidang dari mahasiswa bimbingan Anda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
