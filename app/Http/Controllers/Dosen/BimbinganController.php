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

        $bimbingans = Bimbingan::where('dosen_id', $dosen->id)
            ->where('jenis', $jenis)
            ->with('topik.mahasiswa')
            ->orderByRaw("CASE WHEN status = 'menunggu' THEN 0 ELSE 1 END")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Kuota info
        $kuotaInfo = [
            'kuota_1' => $dosen->kuota_pembimbing_1,
            'kuota_2' => $dosen->kuota_pembimbing_2,
            'terpakai_1' => $dosen->jumlah_bimbingan_1,
            'terpakai_2' => $dosen->jumlah_bimbingan_2,
            'sisa_1' => $dosen->sisa_kuota_1,
            'sisa_2' => $dosen->sisa_kuota_2,
        ];

        return view('dosen.bimbingan.index', compact('bimbingans', 'jenis', 'kuotaInfo'));
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
