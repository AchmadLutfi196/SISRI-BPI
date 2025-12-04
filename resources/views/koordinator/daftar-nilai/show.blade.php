<x-app-layout>
    @php
        $jenis = $pelaksanaan->pendaftaranSidang->jadwalSidang->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';
        $jenisLabel = $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi';
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Nilai {{ $jenisLabel }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->user->name ?? '-' }} - 
                    {{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->nim ?? '-' }}
                </p>
            </div>
            <a href="{{ route('koordinator.daftar-nilai.index', ['jenis' => $jenis]) }}"
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
            <!-- Info Mahasiswa & Sidang -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi {{ $jenisLabel }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Nama Mahasiswa</p>
                            <p class="font-medium">{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->user->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NIM</p>
                            <p class="font-medium">{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->nim ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Jenis</p>
                            @if($jenis === 'sempro')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    Seminar Proposal
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Sidang Skripsi
                                </span>
                            @endif
                        </div>
                        <div class="md:col-span-3">
                            <p class="text-sm text-gray-500">Judul {{ $jenis === 'sempro' ? 'Proposal' : 'Skripsi' }}</p>
                            <p class="font-medium">{{ $pelaksanaan->pendaftaranSidang->topik->judul ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal {{ $jenisLabel }}</p>
                            <p class="font-medium">{{ \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->format('d F Y, H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tempat</p>
                            <p class="font-medium">{{ $pelaksanaan->tempat }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($pelaksanaan->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tim Penguji -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tim Penguji</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($pelaksanaan->pengujiSidang as $penguji)
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wider">
                                    {{ str_replace('_', ' ', ucwords($penguji->role, '_')) }}
                                </p>
                                <p class="font-medium text-gray-900">{{ $penguji->dosen->user->name ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Daftar Nilai -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Nilai Ujian</h3>

                    @if($pelaksanaan->nilai->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penilai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nilai</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pelaksanaan->nilai as $nilai)
                                        @php
                                            $penguji = $pelaksanaan->pengujiSidang->where('dosen_id', $nilai->dosen_id)->first();
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $nilai->dosen->user->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $penguji ? str_replace('_', ' ', ucwords($penguji->role, '_')) : '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="text-lg font-bold {{ $nilai->nilai >= 55 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $nilai->nilai }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                {{ $nilai->catatan ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada nilai</h3>
                            <p class="mt-1 text-sm text-gray-500">Penguji belum memberikan nilai.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ringkasan Nilai -->
            @php
                $nilaiRata = $pelaksanaan->nilai->count() > 0 ? $pelaksanaan->nilai->avg('nilai') : null;
                $isLulus = $nilaiRata !== null && $nilaiRata >= 55;
            @endphp

            @if($nilaiRata !== null)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Nilai</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="text-center p-4 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Rata-rata Nilai</p>
                                <p class="text-3xl font-bold {{ $nilaiRata >= 55 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($nilaiRata, 1) }}
                                </p>
                                <p class="text-xs text-gray-400 mt-1">Dari {{ $pelaksanaan->nilai->count() }} penguji</p>
                            </div>

                            <div class="text-center p-4 rounded-lg {{ $isLulus ? 'bg-green-50' : 'bg-red-50' }}">
                                <p class="text-sm text-gray-500 mb-1">Status Kelulusan</p>
                                @if($isLulus)
                                    <p class="text-2xl font-bold text-green-600">LULUS</p>
                                    <p class="text-sm text-gray-500 mt-1">Nilai ≥ 55</p>
                                @else
                                    <p class="text-2xl font-bold text-red-600">TIDAK LULUS</p>
                                    <p class="text-sm text-gray-500 mt-1">Nilai < 55</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
