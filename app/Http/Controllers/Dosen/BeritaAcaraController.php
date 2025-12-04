<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PelaksanaanSidang;
use App\Models\PengujiSidang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class BeritaAcaraController extends Controller
{
    /**
     * Menampilkan daftar berita acara yang perlu ditandatangani
     */
    public function index(Request $request)
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        $jenis = $request->get('jenis', 'sempro');
        $jenisDb = $jenis === 'sempro' ? 'seminar_proposal' : 'sidang_skripsi';

        // Ambil pelaksanaan sidang yang melibatkan dosen ini (status dijadwalkan/selesai)
        $pelaksanaans = PelaksanaanSidang::whereHas('pengujiSidang', function ($q) use ($dosen) {
                $q->where('dosen_id', $dosen->id);
            })
            ->whereHas('pendaftaranSidang', function ($q) use ($jenisDb) {
                $q->where('jenis', $jenisDb);
            })
            ->whereIn('status', ['dijadwalkan', 'selesai'])
            ->with([
                'pendaftaranSidang.topik.mahasiswa.user',
                'pengujiSidang.dosen.user',
            ])
            ->orderBy('tanggal_sidang', 'desc')
            ->paginate(10);

        return view('dosen.berita-acara.index', compact('pelaksanaans', 'jenis', 'dosen'));
    }

    /**
     * Menampilkan detail berita acara
     */
    public function show(PelaksanaanSidang $pelaksanaan)
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Data dosen tidak ditemukan.');
        }

        // Cek apakah dosen terlibat dalam sidang ini
        $penguji = $pelaksanaan->pengujiSidang()->where('dosen_id', $dosen->id)->first();
        
        if (!$penguji) {
            abort(403, 'Anda tidak terlibat dalam sidang ini.');
        }

        $pelaksanaan->load([
            'pendaftaranSidang.topik.mahasiswa.user',
            'pendaftaranSidang.topik.usulanPembimbing.dosen.user',
            'pendaftaranSidang.jadwalSidang',
            'pengujiSidang.dosen.user',
        ]);

        $jenis = $pelaksanaan->pendaftaranSidang->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        // Pisahkan pembimbing dan penguji
        $pembimbingList = $pelaksanaan->pengujiSidang->filter(function($p) {
            return str_starts_with($p->role, 'pembimbing_');
        })->sortBy('role');
        
        $pengujiList = $pelaksanaan->pengujiSidang->filter(function($p) {
            return str_starts_with($p->role, 'penguji_');
        })->sortBy('role');

        // Cek apakah semua sudah TTD
        $totalPenguji = $pelaksanaan->pengujiSidang->count();
        $totalTtd = $pelaksanaan->pengujiSidang->where('ttd_berita_acara', true)->count();
        $semuaSudahTtd = $totalTtd === $totalPenguji;

        return view('dosen.berita-acara.show', compact(
            'pelaksanaan', 
            'penguji', 
            'jenis', 
            'pembimbingList', 
            'pengujiList',
            'totalPenguji',
            'totalTtd',
            'semuaSudahTtd'
        ));
    }

    /**
     * Tanda tangan berita acara
     */
    public function tandaTangan(PelaksanaanSidang $pelaksanaan)
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Cek apakah dosen terlibat dalam sidang ini
        $penguji = $pelaksanaan->pengujiSidang()->where('dosen_id', $dosen->id)->first();
        
        if (!$penguji) {
            abort(403, 'Anda tidak terlibat dalam sidang ini.');
        }

        // Cek apakah sudah TTD
        if ($penguji->ttd_berita_acara) {
            return back()->with('error', 'Anda sudah menandatangani berita acara ini.');
        }

        // Update TTD
        $penguji->update([
            'ttd_berita_acara' => true,
            'tanggal_ttd' => Carbon::now(),
        ]);

        // Cek apakah semua sudah TTD
        $totalPenguji = $pelaksanaan->pengujiSidang->count();
        $totalTtd = $pelaksanaan->pengujiSidang()->where('ttd_berita_acara', true)->count();
        
        if ($totalTtd === $totalPenguji) {
            // Semua sudah TTD, update status pelaksanaan jika belum selesai
            // Opsional: kirim notifikasi ke mahasiswa
        }

        $jenis = $pelaksanaan->pendaftaranSidang->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return redirect()->route('dosen.berita-acara.index', ['jenis' => $jenis])
            ->with('success', 'Berita acara berhasil ditandatangani.');
    }

    /**
     * Download PDF berita acara
     */
    public function downloadPdf(PelaksanaanSidang $pelaksanaan)
    {
        $dosen = auth()->user()->dosen;
        
        if (!$dosen) {
            return back()->with('error', 'Data dosen tidak ditemukan.');
        }

        // Cek apakah dosen terlibat dalam sidang ini
        $penguji = $pelaksanaan->pengujiSidang()->where('dosen_id', $dosen->id)->first();
        
        if (!$penguji) {
            abort(403, 'Anda tidak terlibat dalam sidang ini.');
        }

        $pelaksanaan->load([
            'pendaftaranSidang.topik.mahasiswa.user',
            'pendaftaranSidang.topik.mahasiswa.prodi.parent.parent', // prodi -> jurusan -> fakultas
            'pendaftaranSidang.topik.usulanPembimbing.dosen.user',
            'pendaftaranSidang.jadwalSidang',
            'pengujiSidang.dosen.user',
            'nilai',
        ]);

        // Pisahkan pembimbing dan penguji
        $pembimbingList = $pelaksanaan->pengujiSidang->filter(function($p) {
            return str_starts_with($p->role, 'pembimbing_');
        })->sortBy('role');
        
        $pengujiList = $pelaksanaan->pengujiSidang->filter(function($p) {
            return str_starts_with($p->role, 'penguji_');
        })->sortBy('role');

        $jenis = $pelaksanaan->pendaftaranSidang->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';
        $jenisLabel = $jenis === 'sempro' ? 'Seminar Proposal' : 'Sidang Skripsi';
        $mahasiswa = $pelaksanaan->pendaftaranSidang->topik->mahasiswa;

        $pdf = Pdf::loadView('dosen.berita-acara.pdf', compact(
            'pelaksanaan',
            'pembimbingList',
            'pengujiList',
            'jenis',
            'mahasiswa'
        ));

        $filename = 'Berita_Acara_' . str_replace(' ', '_', $jenisLabel) . '_' . $mahasiswa->nim . '.pdf';

        return $pdf->download($filename);
    }
}
