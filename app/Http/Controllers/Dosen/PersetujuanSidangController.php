<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PendaftaranSidang;
use App\Models\TopikSkripsi;
use App\Models\UsulanPembimbing;
use Illuminate\Http\Request;

class PersetujuanSidangController extends Controller
{
    public function index(Request $request)
    {
        $dosen = auth()->user()->dosen;
        $jenis = $request->get('jenis', 'proposal');
        
        // Map jenis parameter ke nilai database
        $jenisDb = $jenis === 'proposal' ? 'seminar_proposal' : 'sidang_skripsi';

        // Dapatkan semua topik dimana dosen ini jadi pembimbing
        $topikIds = UsulanPembimbing::where('dosen_id', $dosen->id)
            ->where('status', 'diterima')
            ->pluck('topik_id');

        // Dapatkan pendaftaran sidang dari topik-topik tersebut
        $pendaftarans = PendaftaranSidang::whereIn('topik_id', $topikIds)
            ->where('jenis', $jenisDb)
            ->with(['topik.mahasiswa', 'topik.usulanPembimbings.dosen'])
            ->orderByRaw("
                CASE 
                    WHEN status_pembimbing_1 = 'menunggu' OR status_pembimbing_2 = 'menunggu' THEN 0 
                    ELSE 1 
                END
            ")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Tambahkan info pembimbing ke setiap pendaftaran
        foreach ($pendaftarans as $pendaftaran) {
            $usulanPembimbing = $pendaftaran->topik->usulanPembimbings
                ->where('dosen_id', $dosen->id)
                ->where('status', 'diterima')
                ->first();
            $pendaftaran->urutan_pembimbing = $usulanPembimbing ? $usulanPembimbing->urutan : null;
        }

        return view('dosen.persetujuan-sidang.index', compact('pendaftarans', 'jenis'));
    }

    public function show(PendaftaranSidang $pendaftaran)
    {
        $dosen = auth()->user()->dosen;

        // Cek apakah dosen adalah pembimbing dari topik ini
        $usulanPembimbing = UsulanPembimbing::where('topik_id', $pendaftaran->topik_id)
            ->where('dosen_id', $dosen->id)
            ->where('status', 'diterima')
            ->first();

        if (!$usulanPembimbing) {
            abort(403);
        }

        $pendaftaran->load(['topik.mahasiswa', 'topik.bidangMinat', 'topik.usulanPembimbings.dosen']);
        $pendaftaran->urutan_pembimbing = $usulanPembimbing->urutan;

        return view('dosen.persetujuan-sidang.show', compact('pendaftaran', 'usulanPembimbing'));
    }

    public function approve(Request $request, PendaftaranSidang $pendaftaran)
    {
        $dosen = auth()->user()->dosen;

        // Cek apakah dosen adalah pembimbing
        $usulanPembimbing = UsulanPembimbing::where('topik_id', $pendaftaran->topik_id)
            ->where('dosen_id', $dosen->id)
            ->where('status', 'diterima')
            ->first();

        if (!$usulanPembimbing) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'nullable|string',
        ]);

        // Update status berdasarkan urutan pembimbing
        $statusField = 'status_pembimbing_' . $usulanPembimbing->urutan;
        $catatanField = 'catatan_pembimbing_' . $usulanPembimbing->urutan;

        if ($pendaftaran->$statusField !== 'menunggu') {
            return back()->with('error', 'Anda sudah memberikan keputusan untuk pendaftaran ini.');
        }

        $pendaftaran->update([
            $statusField => 'disetujui',
            $catatanField => $request->catatan,
        ]);

        // Map jenis database ke parameter URL
        $jenisUrl = $pendaftaran->jenis === 'seminar_proposal' ? 'proposal' : 'skripsi';

        return redirect()->route('dosen.persetujuan-sidang.index', ['jenis' => $jenisUrl])
            ->with('success', 'Pendaftaran sidang berhasil disetujui.');
    }

    public function reject(Request $request, PendaftaranSidang $pendaftaran)
    {
        $dosen = auth()->user()->dosen;

        // Cek apakah dosen adalah pembimbing
        $usulanPembimbing = UsulanPembimbing::where('topik_id', $pendaftaran->topik_id)
            ->where('dosen_id', $dosen->id)
            ->where('status', 'diterima')
            ->first();

        if (!$usulanPembimbing) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'required|string',
        ]);

        // Update status berdasarkan urutan pembimbing
        $statusField = 'status_pembimbing_' . $usulanPembimbing->urutan;
        $catatanField = 'catatan_pembimbing_' . $usulanPembimbing->urutan;

        if ($pendaftaran->$statusField !== 'menunggu') {
            return back()->with('error', 'Anda sudah memberikan keputusan untuk pendaftaran ini.');
        }

        $pendaftaran->update([
            $statusField => 'ditolak',
            $catatanField => $request->catatan,
        ]);

        // Map jenis database ke parameter URL
        $jenisUrl = $pendaftaran->jenis === 'seminar_proposal' ? 'proposal' : 'skripsi';

        return redirect()->route('dosen.persetujuan-sidang.index', ['jenis' => $jenisUrl])
            ->with('success', 'Pendaftaran sidang berhasil ditolak.');
    }
}
