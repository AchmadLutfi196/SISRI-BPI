<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\RevisiSidang;
use App\Models\PelaksanaanSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ValidasiRevisiController extends Controller
{
    /**
     * Display list of revisi yang perlu divalidasi
     */
    public function index()
    {
        $dosen = auth()->user()->dosen;
        
        // Get all revisi yang perlu divalidasi oleh dosen ini
        $revisiList = RevisiSidang::where('dosen_id', $dosen->id)
            ->with([
                'pelaksanaanSidang.pendaftaranSidang.topik.mahasiswa.user',
            ])
            ->latest('tanggal_submit')
            ->paginate(20);
        
        // Count status
        $menunggu = RevisiSidang::where('dosen_id', $dosen->id)
            ->where('status', 'menunggu')
            ->count();
        
        $disetujui = RevisiSidang::where('dosen_id', $dosen->id)
            ->where('status', 'disetujui')
            ->count();
        
        $revisiUlang = RevisiSidang::where('dosen_id', $dosen->id)
            ->where('status', 'revisi_ulang')
            ->count();
        
        return view('dosen.validasi-revisi.index', compact('revisiList', 'menunggu', 'disetujui', 'revisiUlang'));
    }

    /**
     * Show detail revisi untuk validasi
     */
    public function show(RevisiSidang $revisi)
    {
        // Check authorization
        $dosen = auth()->user()->dosen;
        if ($revisi->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized');
        }

        $revisi->load([
            'pelaksanaanSidang.pendaftaranSidang.topik.mahasiswa.user',
            'dosen.user',
        ]);

        return view('dosen.validasi-revisi.show', compact('revisi'));
    }

    /**
     * Approve revisi
     */
    public function approve(Request $request, RevisiSidang $revisi)
    {
        // Check authorization
        $dosen = auth()->user()->dosen;
        if ($revisi->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'catatan_validasi' => 'nullable|string|max:1000',
        ]);

        $revisi->update([
            'status' => 'disetujui',
            'catatan' => $request->catatan_validasi ?? $revisi->catatan,
            'tanggal_validasi' => now(),
        ]);

        return redirect()->route('dosen.validasi-revisi.index')
            ->with('success', 'Revisi berhasil disetujui.');
    }

    /**
     * Reject revisi (minta revisi ulang)
     */
    public function reject(Request $request, RevisiSidang $revisi)
    {
        // Check authorization
        $dosen = auth()->user()->dosen;
        if ($revisi->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'catatan_revisi' => 'required|string|max:1000',
        ]);

        $revisi->update([
            'status' => 'revisi_ulang',
            'catatan' => $request->catatan_revisi,
            'tanggal_validasi' => now(),
        ]);

        return redirect()->route('dosen.validasi-revisi.index')
            ->with('success', 'Revisi dikembalikan untuk diperbaiki mahasiswa.');
    }

    /**
     * Download file revisi untuk review
     */
    public function downloadRevisi(RevisiSidang $revisi)
    {
        // Check authorization
        $dosen = auth()->user()->dosen;
        if ($revisi->dosen_id !== $dosen->id) {
            abort(403, 'Unauthorized');
        }

        if (!$revisi->file_revisi || !Storage::disk('public')->exists($revisi->file_revisi)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($revisi->file_revisi);
    }
}
