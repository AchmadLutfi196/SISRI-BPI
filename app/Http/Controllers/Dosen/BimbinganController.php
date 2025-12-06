<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\BimbinganHistory;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function index(Request $request)
    {
        $dosen = auth()->user()->dosen;
        $jenis = $request->get('jenis', 'proposal');

        // Group bimbingan by mahasiswa
        $mahasiswaList = Bimbingan::where('dosen_id', $dosen->id)
            ->where('jenis', $jenis)
            ->with(['topik.mahasiswa'])
            ->get()
            ->groupBy('topik.mahasiswa_id')
            ->map(function ($bimbingans) {
                $mahasiswa = $bimbingans->first()->topik->mahasiswa;
                return [
                    'mahasiswa' => $mahasiswa,
                    'topik' => $bimbingans->first()->topik,
                    'total_bimbingan' => $bimbingans->count(),
                    'menunggu' => $bimbingans->where('status', 'menunggu')->count(),
                    'direvisi' => $bimbingans->where('status', 'direvisi')->count(),
                    'disetujui' => $bimbingans->where('status', 'disetujui')->count(),
                    'last_bimbingan' => $bimbingans->sortByDesc('created_at')->first(),
                ];
            })
            ->values();

        // Kuota info
        $kuotaInfo = [
            'kuota_1' => $dosen->kuota_pembimbing_1,
            'kuota_2' => $dosen->kuota_pembimbing_2,
            'terpakai_1' => $dosen->jumlah_bimbingan_1,
            'terpakai_2' => $dosen->jumlah_bimbingan_2,
            'sisa_1' => $dosen->sisa_kuota_1,
            'sisa_2' => $dosen->sisa_kuota_2,
        ];

        return view('dosen.bimbingan.index', compact('mahasiswaList', 'jenis', 'kuotaInfo'));
    }

    public function mahasiswaDetail(Request $request, $mahasiswaId)
    {
        $dosen = auth()->user()->dosen;
        $jenis = $request->get('jenis', 'proposal');

        // Get all bimbingan for this mahasiswa
        $bimbingans = Bimbingan::where('dosen_id', $dosen->id)
            ->where('jenis', $jenis)
            ->whereHas('topik', function($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            })
            ->with(['topik.mahasiswa'])
            ->orderBy('bimbingan_ke', 'asc')
            ->get();

        if ($bimbingans->isEmpty()) {
            abort(404);
        }

        $mahasiswa = $bimbingans->first()->topik->mahasiswa;
        $topik = $bimbingans->first()->topik;

        return view('dosen.bimbingan.mahasiswa-detail', compact('bimbingans', 'mahasiswa', 'topik', 'jenis'));
    }

    public function show(Bimbingan $bimbingan)
    {
        $dosen = auth()->user()->dosen;

        if ($bimbingan->dosen_id !== $dosen->id) {
            abort(403);
        }

        $bimbingan->load('topik.mahasiswa', 'histories');

        return view('dosen.bimbingan.show', compact('bimbingan'));
    }

    public function respond(Request $request, Bimbingan $bimbingan)
    {
        $dosen = auth()->user()->dosen;

        if ($bimbingan->dosen_id !== $dosen->id) {
            abort(403);
        }

        if ($bimbingan->status !== 'menunggu') {
            return back()->with('error', 'Bimbingan sudah divalidasi sebelumnya.');
        }

        $request->validate([
            'status' => 'required|in:direvisi,disetujui',
            'pesan_dosen' => 'required',
        ]);

        $bimbingan->update([
            'status' => $request->status,
            'pesan_dosen' => $request->pesan_dosen,
            'tanggal_respon' => now(),
        ]);

        // Create history for dosen response
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan->id,
            'status' => $request->status,
            'aksi' => 'direspon',
            'catatan' => $request->pesan_dosen,
            'oleh' => 'dosen',
        ]);

        $message = $request->status === 'disetujui' 
            ? 'Bimbingan berhasil disetujui.' 
            : 'Bimbingan perlu direvisi.';

        return redirect()->route('dosen.bimbingan.index', ['jenis' => $bimbingan->jenis])
            ->with('success', $message);
    }
}
