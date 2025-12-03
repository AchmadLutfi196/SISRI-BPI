<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $jadwal->nama }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $jadwal->jenis === 'seminar_proposal' ? 'Seminar Proposal' : 'Sidang Skripsi' }} - 
                    {{ $jadwal->periode?->tahun_akademik }} {{ ucfirst($jadwal->periode?->semester ?? '') }}
                </p>
            </div>
            @php
                $jenisParam = $jadwal->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';
            @endphp
            <a href="{{ route('koordinator.penjadwalan.index', ['jenis' => $jenisParam]) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
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

            <!-- Info Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Periode Pendaftaran</p>
                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_buka)->format('d M Y') }} - 
                                {{ \Carbon\Carbon::parse($jadwal->tanggal_tutup)->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            @if($jadwal->is_active)
                                @if(now()->between($jadwal->tanggal_buka, $jadwal->tanggal_tutup))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Pendaftaran Dibuka
                                    </span>
                                @elseif(now()->lt($jadwal->tanggal_buka))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Belum Dibuka
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Pendaftaran Ditutup
                                    </span>
                                @endif
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Tidak Aktif
                                </span>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Pendaftar</p>
                            <p class="font-medium">{{ $pendaftarans->total() }} mahasiswa</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('koordinator.penjadwalan.edit', $jadwal) }}"
                                class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pendaftaran -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Pendaftaran</h3>

                    @if($pendaftarans->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mahasiswa</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Topik</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembimbing</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelaksanaan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendaftarans as $pendaftaran)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $pendaftaran->topik->mahasiswa->user->name ?? '-' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $pendaftaran->topik->mahasiswa->nim ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                                    {{ $pendaftaran->topik->judul ?? '-' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @php
                                                    $pembimbing = $pendaftaran->topik->usulanPembimbing->where('status', 'disetujui')->sortBy('urutan');
                                                @endphp
                                                @foreach($pembimbing as $p)
                                                    <div>{{ $p->dosen->user->name ?? '-' }}</div>
                                                @endforeach
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="space-y-1">
                                                    {{-- Status Pembimbing 1 --}}
                                                    @if($pendaftaran->status_pembimbing_1 === 'disetujui')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            Pembimbing 1: ✓
                                                        </span>
                                                    @elseif($pendaftaran->status_pembimbing_1 === 'ditolak')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                            Pembimbing 1: ✗
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Pembimbing 1: Menunggu
                                                        </span>
                                                    @endif

                                                    {{-- Status Pembimbing 2 --}}
                                                    @if($pendaftaran->status_pembimbing_2 === 'disetujui')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            Pembimbing 2: ✓
                                                        </span>
                                                    @elseif($pendaftaran->status_pembimbing_2 === 'ditolak')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                            Pembimbing 2: ✗
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Pembimbing 2: Menunggu
                                                        </span>
                                                    @endif

                                                    {{-- Status Koordinator --}}
                                                    @if($pendaftaran->status_koordinator === 'disetujui')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                            Koordinator: ✓
                                                        </span>
                                                    @elseif($pendaftaran->status_koordinator === 'ditolak')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                            Koordinator: ✗
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                                            Koordinator: Menunggu
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($pendaftaran->pelaksanaanSidang)
                                                    <div class="text-sm">
                                                        <div class="text-gray-900">
                                                            {{ \Carbon\Carbon::parse($pendaftaran->pelaksanaanSidang->tanggal_sidang)->format('d M Y') }}
                                                        </div>
                                                        <div class="text-gray-500">{{ $pendaftaran->pelaksanaanSidang->tempat }}</div>
                                                        @if($pendaftaran->pelaksanaanSidang->status === 'selesai')
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                                Selesai
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                                Dijadwalkan
                                                            </span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500">Belum dijadwalkan</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex flex-col space-y-1">
                                                    {{-- Approve/Reject untuk Koordinator --}}
                                                    @if($pendaftaran->status_koordinator === 'menunggu')
                                                        <button type="button" onclick="showApproveModal({{ $pendaftaran->id }})"
                                                            class="text-green-600 hover:text-green-900 text-left">Setujui</button>
                                                        <button type="button" onclick="showRejectModal({{ $pendaftaran->id }})"
                                                            class="text-red-600 hover:text-red-900 text-left">Tolak</button>
                                                    @endif

                                                    {{-- Jadwalkan Pelaksanaan --}}
                                                    @if($pendaftaran->isFullyApproved() && !$pendaftaran->pelaksanaanSidang)
                                                        <a href="{{ route('koordinator.penjadwalan.create-pelaksanaan', $pendaftaran) }}"
                                                            class="text-blue-600 hover:text-blue-900">Jadwalkan Sidang</a>
                                                    @endif

                                                    {{-- Selesaikan Sidang --}}
                                                    @if($pendaftaran->pelaksanaanSidang && $pendaftaran->pelaksanaanSidang->status === 'dijadwalkan')
                                                        <form action="{{ route('koordinator.penjadwalan.complete-pelaksanaan', $pendaftaran->pelaksanaanSidang) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-purple-600 hover:text-purple-900 text-left"
                                                                onclick="return confirm('Tandai sidang ini selesai?')">
                                                                Selesaikan
                                                            </button>
                                                        </form>
                                                    @endif

                                                    {{-- Lihat Nilai --}}
                                                    @if($pendaftaran->pelaksanaanSidang && $pendaftaran->pelaksanaanSidang->status === 'selesai')
                                                        <a href="{{ route('koordinator.daftar-nilai.show', $pendaftaran->pelaksanaanSidang) }}"
                                                            class="text-indigo-600 hover:text-indigo-900">Lihat Nilai</a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $pendaftarans->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pendaftaran</h3>
                            <p class="mt-1 text-sm text-gray-500">Mahasiswa belum ada yang mendaftar pada jadwal ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui Pendaftaran</h3>
                <form id="approveForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="approve_catatan" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                        <textarea name="catatan_koordinator" id="approve_catatan" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeApproveModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Setujui</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pendaftaran</h3>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reject_catatan" class="block text-sm font-medium text-gray-700">Alasan Penolakan <span class="text-red-500">*</span></label>
                        <textarea name="catatan_koordinator" id="reject_catatan" rows="3" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeRejectModal()"
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showApproveModal(pendaftaranId) {
            document.getElementById('approveForm').action = '/koordinator/penjadwalan/pendaftaran/' + pendaftaranId + '/approve';
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approve_catatan').value = '';
        }

        function showRejectModal(pendaftaranId) {
            document.getElementById('rejectForm').action = '/koordinator/penjadwalan/pendaftaran/' + pendaftaranId + '/reject';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('reject_catatan').value = '';
        }
    </script>
</x-app-layout>
