<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Models\PelaksanaanSidang;
use App\Models\PengujiSidang;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $dosen = auth()->user()->dosen;

        $pengujiSidangs = PengujiSidang::where('dosen_id', $dosen->id)
            ->with(['pelaksanaanSidang.pendaftaranSidang.topik.mahasiswa', 'pelaksanaanSidang.nilais'])
            ->whereHas('pelaksanaanSidang', function ($query) {
                $query->where('status', 'selesai');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('dosen.nilai.index', compact('pengujiSidangs'));
    }

    public function create(PelaksanaanSidang $pelaksanaan)
    {
        $dosen = auth()->user()->dosen;

        // Cek apakah dosen adalah penguji
        $penguji = PengujiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->first();

        if (!$penguji) {
            abort(403);
        }

        $pelaksanaan->load('pendaftaranSidang.topik.mahasiswa');

        // Cek apakah sudah memberi nilai
        $existingNilai = Nilai::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->first();

        return view('dosen.nilai.create', compact('pelaksanaan', 'penguji', 'existingNilai'));
    }

    public function store(Request $request, PelaksanaanSidang $pelaksanaan)
    {
        $dosen = auth()->user()->dosen;

        // Cek apakah dosen adalah penguji
        $penguji = PengujiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->first();

        if (!$penguji) {
            abort(403);
        }

        $request->validate([
            'jenis_nilai' => 'required|in:bimbingan,ujian',
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable',
        ]);

        // Cek apakah sudah memberi nilai dengan jenis yang sama
        $existingNilai = Nilai::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->where('jenis_nilai', $request->jenis_nilai)
            ->first();

        if ($existingNilai) {
            return back()->with('error', 'Anda sudah memberikan nilai ' . $request->jenis_nilai . '.');
        }

        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $dosen->id,
            'jenis_nilai' => $request->jenis_nilai,
            'nilai' => $request->nilai,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dosen.nilai.index')
            ->with('success', 'Nilai berhasil disimpan.');
    }

    public function update(Request $request, Nilai $nilai)
    {
        $dosen = auth()->user()->dosen;

        if ($nilai->dosen_id !== $dosen->id) {
            abort(403);
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable',
        ]);

        $nilai->update([
            'nilai' => $request->nilai,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('dosen.nilai.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }
}
