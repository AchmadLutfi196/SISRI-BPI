<x-app-layout>
    <div class="max-w-5xl mx-auto">
        <!-- Header dengan Tombol Kembali -->
        <div class="mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Detail Pendaftaran {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $pendaftaran->topik->mahasiswa->user->name ?? '-' }} - {{ $pendaftaran->topik->mahasiswa->nim ?? '-' }}
                    </p>
                </div>
                <a href="{{ route('koordinator.pendaftaran.index', ['jenis' => $jenis]) }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Info Mahasiswa & Topik -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Data Mahasiswa -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Data Mahasiswa</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nama</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $pendaftaran->topik->mahasiswa->user->name ?? '-' }}</dd>
                            </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIM</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pendaftaran->topik->mahasiswa->nim ?? '-' }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Judul {{ $jenis === 'sempro' ? 'Proposal' : 'Skripsi' }}</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pendaftaran->topik->judul ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Periode Pendaftaran -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Periode Pendaftaran</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Periode</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $pendaftaran->jadwalSidang->nama ?? '-' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jenis</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $pendaftaran->jenis === 'seminar_proposal' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ $pendaftaran->jenis === 'seminar_proposal' ? 'Seminar Proposal' : 'Sidang Skripsi' }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Buka</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pendaftaran->jadwalSidang->tanggal_buka)->format('d F Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Tutup</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($pendaftaran->jadwalSidang->tanggal_tutup)->format('d F Y') }}</dd>
                                </div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Estimasi Pelaksanaan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @php
                                            $tanggalBuka = \Carbon\Carbon::parse($pendaftaran->jadwalSidang->tanggal_buka);
                                            $tanggalTutup = \Carbon\Carbon::parse($pendaftaran->jadwalSidang->tanggal_tutup);
                                            $today = \Carbon\Carbon::today();
                                            
                                            if ($today->lt($tanggalBuka)) {
                                                $startPelaksanaan = $tanggalBuka->copy();
                                            } else {
                                                $startPelaksanaan = \Carbon\Carbon::tomorrow();
                                            }
                                            $endPelaksanaan = $tanggalTutup->copy()->addDays(14);
                                        @endphp
                                        <span class="text-green-600 font-medium">{{ $startPelaksanaan->format('d M Y') }}</span> s/d 
                                        <span class="text-green-600 font-medium">{{ $endPelaksanaan->format('d M Y') }}</span>
                                        <p class="text-xs text-gray-500 mt-1">Jadwal otomatis akan mencari slot dalam rentang ini</p>
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Dokumen yang Diupload -->
                    @if($pendaftaran->file_dokumen)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dokumen {{ $pendaftaran->jenis === 'seminar_proposal' ? 'Proposal' : 'Skripsi' }}</h3>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-red-100 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $pendaftaran->file_dokumen_original_name ?? 'Dokumen.pdf' }}</p>
                                        <p class="text-sm text-gray-500">PDF - Dokumen {{ $pendaftaran->jenis === 'seminar_proposal' ? 'Proposal' : 'Skripsi' }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('koordinator.pendaftaran.download-dokumen', $pendaftaran) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Pembimbing -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Dosen Pembimbing</h3>
                            <div class="space-y-3">
                                @foreach($pendaftaran->topik->usulanPembimbing as $pembimbing)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-medium mr-3">
                                                {{ $pembimbing->urutan }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $pembimbing->dosen->user->name ?? '-' }}</p>
                                                <p class="text-xs text-gray-500">{{ $pembimbing->dosen->nidn ?? $pembimbing->dosen->nip ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Disetujui
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Status Persetujuan -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Persetujuan</h3>
                            <div class="grid grid-cols-3 gap-4">
                                <div class="text-center p-4 rounded-lg {{ $pendaftaran->status_pembimbing_1 === 'disetujui' ? 'bg-green-50' : ($pendaftaran->status_pembimbing_1 === 'ditolak' ? 'bg-red-50' : 'bg-yellow-50') }}">
                                    <p class="text-xs text-gray-500">Pembimbing 1</p>
                                    <p class="text-sm font-medium {{ $pendaftaran->status_pembimbing_1 === 'disetujui' ? 'text-green-700' : ($pendaftaran->status_pembimbing_1 === 'ditolak' ? 'text-red-700' : 'text-yellow-700') }}">
                                        {{ ucfirst($pendaftaran->status_pembimbing_1) }}
                                    </p>
                                </div>
                                <div class="text-center p-4 rounded-lg {{ $pendaftaran->status_pembimbing_2 === 'disetujui' ? 'bg-green-50' : ($pendaftaran->status_pembimbing_2 === 'ditolak' ? 'bg-red-50' : 'bg-yellow-50') }}">
                                    <p class="text-xs text-gray-500">Pembimbing 2</p>
                                    <p class="text-sm font-medium {{ $pendaftaran->status_pembimbing_2 === 'disetujui' ? 'text-green-700' : ($pendaftaran->status_pembimbing_2 === 'ditolak' ? 'text-red-700' : 'text-yellow-700') }}">
                                        {{ ucfirst($pendaftaran->status_pembimbing_2) }}
                                    </p>
                                </div>
                                <div class="text-center p-4 rounded-lg {{ $pendaftaran->status_koordinator === 'disetujui' ? 'bg-green-50' : ($pendaftaran->status_koordinator === 'ditolak' ? 'bg-red-50' : 'bg-yellow-50') }}">
                                    <p class="text-xs text-gray-500">Koordinator</p>
                                    <p class="text-sm font-medium {{ $pendaftaran->status_koordinator === 'disetujui' ? 'text-green-700' : ($pendaftaran->status_koordinator === 'ditolak' ? 'text-red-700' : 'text-yellow-700') }}">
                                        {{ ucfirst($pendaftaran->status_koordinator) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jadwal Pelaksanaan (jika sudah ada) -->
                    @if($pendaftaran->pelaksanaanSidang)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Pelaksanaan</h3>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal & Waktu</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($pendaftaran->pelaksanaanSidang->tanggal_sidang)->format('l, d F Y - H:i') }} WIB
                                        </dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tempat</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $pendaftaran->pelaksanaanSidang->tempat }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                                        <dd class="mt-1">
                                            @if($pendaftaran->pelaksanaanSidang->status === 'selesai')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Dijadwalkan
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>

                                <!-- Tim Pembimbing & Penguji -->
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    @php
                                        $pembimbingList = $pendaftaran->pelaksanaanSidang->pengujiSidang->filter(function($p) {
                                            return str_starts_with($p->role, 'pembimbing_');
                                        })->sortBy('role');
                                        
                                        $pengujiList = $pendaftaran->pelaksanaanSidang->pengujiSidang->filter(function($p) {
                                            return str_starts_with($p->role, 'penguji_');
                                        })->sortBy('role');
                                    @endphp

                                    <!-- Tim Pembimbing -->
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Tim Pembimbing</h4>
                                    <div class="grid grid-cols-2 gap-2 mb-4">
                                        @foreach($pembimbingList as $pembimbing)
                                            <div class="flex items-center p-2 bg-blue-50 rounded">
                                                <span class="text-xs text-blue-600 font-medium mr-2">Pembimbing {{ substr($pembimbing->role, -1) }}:</span>
                                                <span class="text-sm text-gray-900">{{ $pembimbing->dosen->user->name ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Tim Penguji -->
                                    <h4 class="text-sm font-medium text-gray-900 mb-2">Tim Penguji</h4>
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($pengujiList as $penguji)
                                            <div class="flex items-center p-2 bg-purple-50 rounded">
                                                <span class="text-xs text-purple-600 font-medium mr-2">Penguji {{ substr($penguji->role, -1) }}:</span>
                                                <span class="text-sm text-gray-900">{{ $penguji->dosen->user->name ?? '-' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nilai (jika sudah dinilai) -->
                        @php
                            $nilaiCollection = $pendaftaran->pelaksanaanSidang->nilai ?? collect();
                            $nilaiUjian = $nilaiCollection->where('jenis_nilai', 'ujian');
                            $hasNilai = $nilaiUjian->isNotEmpty();
                            $nilaiRata = $hasNilai ? $nilaiUjian->avg('nilai') : null;
                            $isLulus = $nilaiRata !== null && $nilaiRata >= 55;
                            
                            // Get nilai huruf
                            $nilaiHuruf = null;
                            if ($nilaiRata !== null) {
                                $nilaiHuruf = match(true) {
                                    $nilaiRata >= 85 => 'A',
                                    $nilaiRata >= 80 => 'A-',
                                    $nilaiRata >= 75 => 'B+',
                                    $nilaiRata >= 70 => 'B',
                                    $nilaiRata >= 65 => 'B-',
                                    $nilaiRata >= 60 => 'C+',
                                    $nilaiRata >= 55 => 'C',
                                    $nilaiRata >= 50 => 'D',
                                    default => 'E',
                                };
                            }
                        @endphp

                        @if($hasNilai)
                            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="p-6">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-lg font-medium text-gray-900">Hasil Penilaian</h3>
                                        @if($isLulus)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                LULUS
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                TIDAK LULUS
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Ringkasan Nilai -->
                                    <div class="flex items-center justify-center mb-6 p-4 rounded-lg {{ $isLulus ? 'bg-green-50' : 'bg-red-50' }}">
                                        <div class="text-center">
                                            <div class="w-20 h-20 mx-auto rounded-full flex items-center justify-center mb-2 {{ $isLulus ? 'bg-green-500' : 'bg-red-500' }}">
                                                <span class="text-2xl font-bold text-white">{{ $nilaiHuruf }}</span>
                                            </div>
                                            <p class="text-2xl font-bold {{ $isLulus ? 'text-green-700' : 'text-red-700' }}">{{ number_format($nilaiRata, 2) }}</p>
                                            <p class="text-sm text-gray-500">Nilai Rata-rata</p>
                                        </div>
                                    </div>

                                    <!-- Detail Nilai per Penguji -->
                                    <div class="border-t border-gray-200 pt-4">
                                        <h4 class="text-sm font-medium text-gray-900 mb-3">Detail Nilai per Penguji</h4>
                                        <div class="space-y-3">
                                            @php
                                                // Filter hanya penguji (bukan pembimbing)
                                                $pengujiOnly = $pendaftaran->pelaksanaanSidang->pengujiSidang->filter(function($p) {
                                                    return str_starts_with($p->role, 'penguji_');
                                                })->sortBy('role');
                                            @endphp
                                            @foreach($pengujiOnly as $penguji)
                                                @php
                                                    $nilaiPenguji = $nilaiCollection->where('dosen_id', $penguji->dosen_id)->where('jenis_nilai', 'ujian')->first();
                                                @endphp
                                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                    <div class="flex items-center">
                                                        <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3 bg-purple-100 text-purple-700">
                                                            <span class="text-xs font-medium">
                                                                U{{ substr($penguji->role, -1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $penguji->dosen->user->name ?? '-' }}</p>
                                                            <p class="text-xs text-gray-500">{{ ucwords(str_replace('_', ' ', $penguji->role)) }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        @if($nilaiPenguji)
                                                            <span class="text-lg font-bold {{ $nilaiPenguji->nilai >= 55 ? 'text-green-600' : 'text-red-600' }}">
                                                                {{ number_format($nilaiPenguji->nilai, 1) }}
                                                            </span>
                                                            @if($nilaiPenguji->catatan)
                                                                <p class="text-xs text-gray-500 max-w-xs truncate">{{ $nilaiPenguji->catatan }}</p>
                                                            @endif
                                                        @else
                                                            <span class="text-sm text-gray-400 italic">Belum dinilai</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Statistik Nilai -->
                                    <div class="mt-4 pt-4 border-t border-gray-200">
                                        <div class="grid grid-cols-3 gap-4 text-center">
                                            <div>
                                                <p class="text-xs text-gray-500">Nilai Tertinggi</p>
                                                <p class="text-lg font-bold text-green-600">{{ number_format($nilaiUjian->max('nilai'), 1) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Nilai Terendah</p>
                                                <p class="text-lg font-bold text-red-600">{{ number_format($nilaiUjian->min('nilai'), 1) }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500">Jumlah Penilai</p>
                                                <p class="text-lg font-bold text-gray-700">{{ $nilaiUjian->count() }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Form Aksi (sidebar) -->
                <div class="space-y-6">
                    @if($pendaftaran->status_koordinator === 'menunggu')
                        <!-- Opsi Jadwal Otomatis -->
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 overflow-hidden sm:rounded-lg">
                            <div class="p-4">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-green-800">Jadwalkan Otomatis</h4>
                                        <p class="text-xs text-green-600 mt-1">Sistem akan otomatis menentukan tanggal, waktu, ruangan, dan 3 penguji.</p>
                                        <form action="{{ route('koordinator.pendaftaran.auto-approve', $pendaftaran) }}" method="POST" class="mt-3" id="auto-approve-form">
                                            @csrf
                                            <button type="button"
                                                class="inline-flex items-center px-3 py-1.5 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 transition"
                                                onclick="confirmAction('auto-approve-form', 'Jadwalkan Otomatis', 'Sistem akan otomatis menjadwalkan sidang untuk mahasiswa ini. Lanjutkan?', 'Ya, Jadwalkan')">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                </svg>
                                                Setujui & Jadwalkan Otomatis
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Approve Manual -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Setujui & Jadwalkan</h3>
                                <form action="{{ route('koordinator.pendaftaran.approve', $pendaftaran) }}" method="POST" id="approve-pendaftaran-form">
                                    @csrf
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="tanggal_sidang" class="block text-sm font-medium text-gray-700">Tanggal & Waktu</label>
                                            <input type="datetime-local" name="tanggal_sidang" id="tanggal_sidang" 
                                                value="{{ old('tanggal_sidang') }}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                            @error('tanggal_sidang')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="tempat" class="block text-sm font-medium text-gray-700">Tempat / Ruangan</label>
                                            <select name="tempat" id="tempat"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                                <option value="">-- Pilih Ruangan --</option>
                                                @foreach($ruangans as $ruangan)
                                                    <option value="{{ $ruangan->nama }}" {{ old('tempat') == $ruangan->nama ? 'selected' : '' }}>
                                                        {{ $ruangan->nama }}{{ $ruangan->lokasi ? " - {$ruangan->lokasi}" : '' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tempat')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="penguji_1_id" class="block text-sm font-medium text-gray-700">Penguji 1</label>
                                            <select name="penguji_1_id" id="penguji_1_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                                <option value="">-- Pilih Penguji 1 --</option>
                                                @foreach($dosens as $dosen)
                                                    <option value="{{ $dosen->id }}" {{ old('penguji_1_id') == $dosen->id ? 'selected' : '' }}>
                                                        {{ $dosen->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('penguji_1_id')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="penguji_2_id" class="block text-sm font-medium text-gray-700">Penguji 2</label>
                                            <select name="penguji_2_id" id="penguji_2_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                                <option value="">-- Pilih Penguji 2 --</option>
                                                @foreach($dosens as $dosen)
                                                    <option value="{{ $dosen->id }}" {{ old('penguji_2_id') == $dosen->id ? 'selected' : '' }}>
                                                        {{ $dosen->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('penguji_2_id')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="penguji_3_id" class="block text-sm font-medium text-gray-700">Penguji 3</label>
                                            <select name="penguji_3_id" id="penguji_3_id"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                                                <option value="">-- Pilih Penguji 3 --</option>
                                                @foreach($dosens as $dosen)
                                                    <option value="{{ $dosen->id }}" {{ old('penguji_3_id') == $dosen->id ? 'selected' : '' }}>
                                                        {{ $dosen->user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('penguji_3_id')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (Opsional)</label>
                                            <textarea name="catatan" id="catatan" rows="2"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">{{ old('catatan') }}</textarea>
                                        </div>

                                        <button type="button" onclick="confirmAction('approve-pendaftaran-form', 'Setujui & Jadwalkan', 'Yakin ingin menyetujui dan menjadwalkan sidang ini?', 'Setujui', 'success')"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Setujui & Jadwalkan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Form Reject -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Pendaftaran</h3>
                                <form action="{{ route('koordinator.pendaftaran.reject', $pendaftaran) }}" method="POST" id="reject-pendaftaran-form">
                                    @csrf
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label for="reject_catatan" class="block text-sm font-medium text-gray-700">Alasan Penolakan</label>
                                            <textarea name="catatan" id="reject_catatan" rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm"
                                                placeholder="Berikan alasan penolakan..." required></textarea>
                                            @error('catatan')
                                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button type="button" onclick="confirmAction('reject-pendaftaran-form', 'Tolak Pendaftaran', 'Yakin ingin menolak pendaftaran ini?', 'Tolak', 'warning')"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Tolak Pendaftaran
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Status sudah diproses -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 text-center">
                                @if($pendaftaran->status_koordinator === 'disetujui')
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mb-4">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">Pendaftaran Disetujui</h3>
                                    <p class="mt-1 text-sm text-gray-500">Sidang telah dijadwalkan.</p>
                                @else
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mb-4">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">Pendaftaran Ditolak</h3>
                                    @if($pendaftaran->catatan_koordinator)
                                        <p class="mt-2 text-sm text-gray-500">{{ $pendaftaran->catatan_koordinator }}</p>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
</x-app-layout>
