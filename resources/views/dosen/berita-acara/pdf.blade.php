<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        
        .container {
            padding: 20px 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
        }
        
        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 12pt;
            font-weight: normal;
        }
        
        .title {
            text-align: center;
            margin: 30px 0;
        }
        
        .title h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .title p {
            font-size: 12pt;
        }
        
        .content {
            margin: 20px 0;
        }
        
        .info-table {
            width: 100%;
            margin: 20px 0;
        }
        
        .info-table td {
            padding: 5px 0;
            vertical-align: top;
        }
        
        .info-table td:first-child {
            width: 150px;
        }
        
        .info-table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }
        
        .section-title {
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        
        .table-ttd {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        .table-ttd th,
        .table-ttd td {
            border: 1px solid #000;
            padding: 8px 12px;
            text-align: left;
        }
        
        .table-ttd th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        .table-ttd td.center {
            text-align: center;
        }
        
        .ttd-status {
            font-size: 10pt;
        }
        
        .ttd-signed {
            color: green;
        }
        
        .ttd-not-signed {
            color: red;
        }
        
        .footer {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        
        .signature-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            width: 200px;
            margin: 60px auto 5px auto;
        }
        
        .note {
            margin-top: 30px;
            font-size: 10pt;
            font-style: italic;
        }
        
        .page-number {
            position: fixed;
            bottom: 20px;
            right: 40px;
            font-size: 10pt;
        }
    </style>
</head>
<body>
    @php
        $mahasiswa = $pelaksanaan->pendaftaranSidang->topik->mahasiswa ?? null;
        $prodi = $mahasiswa?->prodi;
        $jurusan = $prodi?->parent;
        $fakultas = $jurusan?->parent;
    @endphp
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>UNIVERSITAS TRUNOJOYO MADURA</h1>
            <h2>{{ strtoupper($fakultas->nama ?? 'FAKULTAS') }}</h2>
            <h2>{{ strtoupper($prodi->nama ?? '-') }}</h2>
        </div>
        
        <!-- Title -->
        <div class="title">
            <h3>BERITA ACARA {{ $jenis === 'sempro' ? 'SEMINAR PROPOSAL' : 'SIDANG SKRIPSI' }}</h3>
            <p>Nomor: BA/{{ $pelaksanaan->id }}/{{ date('Y') }}</p>
        </div>
        
        <!-- Content -->
        <div class="content">
            <p>Pada hari ini <strong>{{ \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->locale('id')->isoFormat('dddd') }}</strong>, 
            tanggal <strong>{{ \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->locale('id')->isoFormat('D MMMM Y') }}</strong>, 
            pukul <strong>{{ \Carbon\Carbon::parse($pelaksanaan->tanggal_sidang)->format('H:i') }} WIB</strong>,
            bertempat di <strong>{{ $pelaksanaan->tempat }}</strong>, 
            telah dilaksanakan {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi' }} dengan data sebagai berikut:</p>
            
            <!-- Data Mahasiswa -->
            <table class="info-table">
                <tr>
                    <td>Nama Mahasiswa</td>
                    <td>:</td>
                    <td><strong>{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->user->name ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>:</td>
                    <td><strong>{{ $pelaksanaan->pendaftaranSidang->topik->mahasiswa->nim ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Judul {{ $jenis === 'sempro' ? 'Proposal' : 'Skripsi' }}</td>
                    <td>:</td>
                    <td><strong>{{ $pelaksanaan->pendaftaranSidang->topik->judul ?? '-' }}</strong></td>
                </tr>
            </table>
            
            <!-- Tim Pembimbing -->
            <p class="section-title">Tim Pembimbing:</p>
            <table class="table-ttd">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Dosen</th>
                        <th style="width: 100px;">Jabatan</th>
                        <th style="width: 60px;">Nilai</th>
                        <th style="width: 80px;">Status TTD</th>
                        <th style="width: 100px;">Tanggal TTD</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNilaiPembimbing = 0; $countNilaiPembimbing = 0; @endphp
                    @foreach($pembimbingList as $index => $pembimbing)
                    @php
                        $nilaiPembimbing = $pelaksanaan->nilai->where('dosen_id', $pembimbing->dosen_id)->first();
                        if ($nilaiPembimbing) {
                            $totalNilaiPembimbing += $nilaiPembimbing->nilai;
                            $countNilaiPembimbing++;
                        }
                    @endphp
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $pembimbing->dosen->user->name ?? '-' }}</td>
                        <td class="center">{{ ucwords(str_replace('_', ' ', $pembimbing->role)) }}</td>
                        <td class="center"><strong>{{ $nilaiPembimbing ? $nilaiPembimbing->nilai : '-' }}</strong></td>
                        <td class="center">
                            @if($pembimbing->ttd_berita_acara)
                                <span class="ttd-status ttd-signed">✓ Sudah TTD</span>
                            @else
                                <span class="ttd-status ttd-not-signed">✗ Belum TTD</span>
                            @endif
                        </td>
                        <td class="center">
                            {{ $pembimbing->tanggal_ttd ? $pembimbing->tanggal_ttd->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Tim Penguji -->
            <p class="section-title">Tim Penguji:</p>
            <table class="table-ttd">
                <thead>
                    <tr>
                        <th style="width: 40px;">No</th>
                        <th>Nama Dosen</th>
                        <th style="width: 100px;">Jabatan</th>
                        <th style="width: 60px;">Nilai</th>
                        <th style="width: 80px;">Status TTD</th>
                        <th style="width: 100px;">Tanggal TTD</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalNilaiPenguji = 0; $countNilaiPenguji = 0; @endphp
                    @foreach($pengujiList as $index => $pnguji)
                    @php
                        $nilaiPenguji = $pelaksanaan->nilai->where('dosen_id', $pnguji->dosen_id)->first();
                        if ($nilaiPenguji) {
                            $totalNilaiPenguji += $nilaiPenguji->nilai;
                            $countNilaiPenguji++;
                        }
                    @endphp
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td>{{ $pnguji->dosen->user->name ?? '-' }}</td>
                        <td class="center">{{ ucwords(str_replace('_', ' ', $pnguji->role)) }}</td>
                        <td class="center"><strong>{{ $nilaiPenguji ? $nilaiPenguji->nilai : '-' }}</strong></td>
                        <td class="center">
                            @if($pnguji->ttd_berita_acara)
                                <span class="ttd-status ttd-signed">✓ Sudah TTD</span>
                            @else
                                <span class="ttd-status ttd-not-signed">✗ Belum TTD</span>
                            @endif
                        </td>
                        <td class="center">
                            {{ $pnguji->tanggal_ttd ? $pnguji->tanggal_ttd->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Ringkasan Nilai -->
            @php
                $totalNilai = $totalNilaiPembimbing + $totalNilaiPenguji;
                $countNilai = $countNilaiPembimbing + $countNilaiPenguji;
                $rataRata = $countNilai > 0 ? round($totalNilai / $countNilai, 2) : 0;
                
                // Tentukan grade
                if ($rataRata >= 85) {
                    $grade = 'A';
                } elseif ($rataRata >= 70) {
                    $grade = 'B';
                } elseif ($rataRata >= 55) {
                    $grade = 'C';
                } elseif ($rataRata >= 40) {
                    $grade = 'D';
                } else {
                    $grade = 'E';
                }
                
                // Tentukan kelulusan
                $lulus = in_array($grade, ['A', 'B', 'C']);
            @endphp
            
            <div style="margin-top: 25px; border: 2px solid #000; padding: 15px;">
                <p class="section-title" style="margin-top: 0; text-align: center; font-size: 13pt;">HASIL PENILAIAN {{ $jenis === 'sempro' ? 'SEMINAR PROPOSAL' : 'SIDANG SKRIPSI' }}</p>
                <table style="width: 100%; margin-top: 10px;">
                    <tr>
                        <td style="width: 200px;">Jumlah Penilai</td>
                        <td style="width: 10px;">:</td>
                        <td><strong>{{ $countNilai }} Dosen</strong> ({{ $countNilaiPembimbing }} Pembimbing, {{ $countNilaiPenguji }} Penguji)</td>
                    </tr>
                    <tr>
                        <td>Rata-rata Nilai</td>
                        <td>:</td>
                        <td><strong style="font-size: 14pt;">{{ $rataRata }}</strong></td>
                    </tr>
                    <tr>
                        <td>Grade</td>
                        <td>:</td>
                        <td><strong style="font-size: 14pt;">{{ $grade }}</strong></td>
                    </tr>
                    <tr>
                        <td>Status Kelulusan</td>
                        <td>:</td>
                        <td>
                            @if($countNilai == 0)
                                <strong style="color: gray;">BELUM ADA NILAI</strong>
                            @elseif($lulus)
                                <strong style="color: green; font-size: 14pt;">✓ LULUS</strong>
                            @else
                                <strong style="color: red; font-size: 14pt;">✗ TIDAK LULUS</strong>
                                <br><span style="font-size: 10pt; color: red;">(Harus mengulang {{ $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi' }})</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            
            <!-- Status Keputusan -->
            <p style="margin-top: 20px;">
                Demikian berita acara ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p style="text-align: right; margin-bottom: 20px;">
                Dibuat di : -<br>
                Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
            </p>
            
            <div class="note">
                <p>Catatan:</p>
                <ul style="margin-left: 20px;">
                    <li>Berita acara ini sah apabila telah ditandatangani oleh semua pembimbing dan penguji.</li>
                    <li>Dokumen ini dicetak secara elektronik dari Sistem Informasi Skripsi (SISRI).</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
