<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Nilai;
use App\Models\PelaksanaanSidang;
use App\Models\PengujiSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiSidangController extends Controller
{
    /**
     * Display a listing of the resource - only for sidang_skripsi
     */
    public function index()
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->route('dashboard')->with('error', 'Anda tidak terdaftar sebagai dosen.');
        }

        // Get penugasan as penguji/pembimbing for sidang_skripsi
        $penugasan = PengujiSidang::with([
            'pelaksanaanSidang.pendaftaranSidang.topik.mahasiswa.user',
            'pelaksanaanSidang.pendaftaranSidang.topik'
        ])
            ->where('dosen_id', $dosen->id)
            ->whereHas('pelaksanaanSidang.pendaftaranSidang', function ($query) {
                $query->where('jenis', 'sidang_skripsi');
            })
            ->whereHas('pelaksanaanSidang', function ($query) {
                $query->whereIn('status', ['dijadwalkan', 'selesai']);
            })
            ->get();

        return view('dosen.nilai.sidang.index', compact('penugasan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(PelaksanaanSidang $pelaksanaan)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Anda tidak terdaftar sebagai dosen.');
        }

        // Check if this is sidang_skripsi
        if ($pelaksanaan->pendaftaranSidang->jenis !== 'sidang_skripsi') {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Pelaksanaan ini bukan sidang skripsi.');
        }

        // Get penugasan for this dosen (penguji atau pembimbing)
        $penugasan = PengujiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->first();

        if (!$penugasan) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Anda tidak ditugaskan untuk sidang ini.');
        }

        // Check existing nilai - using pelaksanaan_sidang_id + dosen_id + jenis_nilai
        $nilaiExisting = Nilai::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->where('jenis_nilai', 'ujian')
            ->first();

        return view('dosen.nilai.sidang.create', compact('pelaksanaan', 'penugasan', 'nilaiExisting'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PelaksanaanSidang $pelaksanaan)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Anda tidak terdaftar sebagai dosen.');
        }

        // Check if this is sidang_skripsi
        if ($pelaksanaan->pendaftaranSidang->jenis !== 'sidang_skripsi') {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Pelaksanaan ini bukan sidang skripsi.');
        }

        // Get penugasan (penguji atau pembimbing)
        $penugasan = PengujiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->first();

        if (!$penugasan) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Anda tidak ditugaskan untuk sidang ini.');
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        // Check if nilai already exists
        $existingNilai = Nilai::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosen->id)
            ->where('jenis_nilai', 'ujian')
            ->first();

        if ($existingNilai) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Nilai sudah ada, gunakan fungsi update.');
        }

        Nilai::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $dosen->id,
            'jenis_nilai' => 'ujian',
            'nilai' => $request->nilai,
            'catatan' => $request->catatan,
        ]);

        // Update catatan_revisi di penguji_sidang jika ada catatan
        if ($request->filled('catatan')) {
            $penugasan->update([
                'catatan_revisi' => $request->catatan,
            ]);
        }

        return redirect()->route('dosen.nilai-sidang.index')->with('success', 'Nilai sidang skripsi berhasil disimpan.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nilai $nilai)
    {
        $user = Auth::user();
        $dosen = Dosen::where('user_id', $user->id)->first();

        if (!$dosen) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Anda tidak terdaftar sebagai dosen.');
        }

        // Check ownership using dosen_id
        if ($nilai->dosen_id !== $dosen->id) {
            return redirect()->route('dosen.nilai-sidang.index')->with('error', 'Anda tidak berhak mengubah nilai ini.');
        }

        $request->validate([
            'nilai' => 'required|numeric|min:0|max:100',
            'catatan' => 'nullable|string',
        ]);

        $nilai->update([
            'nilai' => $request->nilai,
            'catatan' => $request->catatan,
        ]);

        // Update catatan_revisi di penguji_sidang
        $penugasan = PengujiSidang::where('pelaksanaan_sidang_id', $nilai->pelaksanaan_sidang_id)
            ->where('dosen_id', $dosen->id)
            ->first();
        
        if ($penugasan) {
            $penugasan->update([
                'catatan_revisi' => $request->catatan,
            ]);
        }

        return redirect()->route('dosen.nilai-sidang.index')->with('success', 'Nilai sidang skripsi berhasil diperbarui.');
    }
}
