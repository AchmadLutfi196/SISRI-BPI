<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Pendaftaran {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Info Box -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Daftar pendaftaran yang sudah disetujui oleh Pembimbing 1 dan Pembimbing 2.</p>
                        <p class="mt-1">Sebagai koordinator, Anda dapat menyetujui dan menjadwalkan pelaksanaan sidang atau menolak pendaftaran.</p>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($pendaftarans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jadwal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendaftarans as $pendaftaran)
                                        <tr class="{{ $pendaftaran->status_koordinator === 'menunggu' ? 'bg-yellow-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pendaftaran->topik->mahasiswa->user->name ?? '-' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $pendaftaran->topik->mahasiswa->nim ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $pendaftaran->topik->judul }}">
                                                    {{ Str::limit($pendaftaran->topik->judul, 50) }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $pendaftaran->jadwalSidang->nama ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @foreach($pendaftaran->topik->usulanPembimbing as $p)
                                                    <div class="flex items-center">
                                                        <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-medium rounded-full bg-gray-200 text-gray-700 mr-1">
                                                            {{ $p->urutan }}
                                                        </span>
                                                        {{ $p->dosen->user->name ?? '-' }}
                                                    </div>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($pendaftaran->status_koordinator === 'disetujui')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Disetujui
                                                    </span>
                                                @elseif($pendaftaran->status_koordinator === 'ditolak')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Ditolak
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                        Menunggu
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($pendaftaran->pelaksanaanSidang)
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($pendaftaran->pelaksanaanSidang->tanggal_sidang)->format('d M Y H:i') }}
                                                    </div>
                                                    <div class="text-xs">{{ $pendaftaran->pelaksanaanSidang->tempat }}</div>
                                                    @if($pendaftaran->pelaksanaanSidang->status === 'selesai')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            Selesai
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            Dijadwalkan
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-gray-400">Belum dijadwalkan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex flex-col space-y-1">
                                                    <a href="{{ route('koordinator.pendaftaran.show', $pendaftaran) }}" 
                                                        class="text-blue-600 hover:text-blue-900">Detail</a>
                                                    
                                                    @if($pendaftaran->pelaksanaanSidang && $pendaftaran->pelaksanaanSidang->status === 'dijadwalkan')
                                                        <a href="{{ route('koordinator.pendaftaran.edit-pelaksanaan', $pendaftaran) }}"
                                                            class="text-yellow-600 hover:text-yellow-900">Edit Jadwal</a>
                                                        <form action="{{ route('koordinator.pendaftaran.complete-pelaksanaan', $pendaftaran->pelaksanaanSidang) }}" 
                                                            method="POST" class="inline"
                                                            id="complete-sidang-{{ $pendaftaran->id }}">
                                                            @csrf
                                                            <button type="button" onclick="confirmAction('complete-sidang-{{ $pendaftaran->id }}', 'Selesaikan Sidang', 'Tandai sidang ini selesai?', 'Selesaikan', 'success')" class="text-purple-600 hover:text-purple-900">
                                                                Selesaikan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $pendaftarans->appends(['jenis' => $jenis])->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pendaftaran</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Belum ada pendaftaran {{ $jenis === 'sempro' ? 'seminar proposal' : 'sidang skripsi' }} yang perlu diproses.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
