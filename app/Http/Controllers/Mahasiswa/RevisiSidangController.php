<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\PelaksanaanSidang;
use App\Models\RevisiSidang;
use App\Models\PengujiSidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RevisiSidangController extends Controller
{
    /**
     * Display list of revisi for mahasiswa
     */
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        // Get all pelaksanaan sidang untuk mahasiswa ini
        // Status bisa: terjadwal, berlangsung, selesai
        $pelaksanaanList = PelaksanaanSidang::whereHas('pendaftaranSidang.topik', function($query) use ($mahasiswa) {
                $query->where('mahasiswa_id', $mahasiswa->id);
            })
            ->whereIn('status', ['selesai', 'berlangsung']) // Include berlangsung status
            ->with([
                'pendaftaranSidang.topik',
                'pendaftaranSidang',
                'revisiSidang.dosen.user',
                'pengujiSidang' => function($query) {
                    $query->where('role', 'like', 'penguji_%')
                          ->whereNotNull('catatan_revisi')
                          ->where('catatan_revisi', '!=', '');
                }
            ])
            ->latest()
            ->get();
        
        return view('mahasiswa.revisi.index', compact('pelaksanaanList'));
    }

    /**
     * Show revisi form for specific pelaksanaan
     */
    public function show(PelaksanaanSidang $pelaksanaan)
    {
        // Check authorization
        $mahasiswa = auth()->user()->mahasiswa;
        if ($pelaksanaan->pendaftaranSidang->topik->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Unauthorized');
        }

        // Check if sidang sudah selesai
        if ($pelaksanaan->status !== 'selesai') {
            return redirect()->route('mahasiswa.revisi.index')
                ->with('error', 'Sidang belum selesai.');
        }

        // Get ALL penguji untuk debugging
        $allPenguji = PengujiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->with(['dosen.user'])
            ->get();
        
        // Debug: Log all penguji info
        \Log::info('=== DEBUG REVISI SIDANG ===');
        \Log::info('Pelaksanaan ID: ' . $pelaksanaan->id);
        \Log::info('Total Penguji: ' . $allPenguji->count());
        foreach($allPenguji as $p) {
            \Log::info("Penguji: {$p->dosen->user->name}, Role: {$p->role}, Catatan: " . ($p->catatan_revisi ?? 'NULL'));
        }
        
        // Filter ONLY penguji (bukan pembimbing) yang ada catatan revisi
        $pengujiList = $allPenguji->filter(function($penguji) {
            $isPenguji = str_starts_with($penguji->role, 'penguji_');
            $hasCatatan = !empty(trim($penguji->catatan_revisi ?? ''));
            \Log::info("Filter: {$penguji->dosen->user->name} - isPenguji: " . ($isPenguji ? 'YES' : 'NO') . ", hasCatatan: " . ($hasCatatan ? 'YES' : 'NO'));
            return $isPenguji && $hasCatatan;
        });

        // Get existing revisi yang sudah disubmit
        $revisiList = RevisiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->with('dosen.user')
            ->get()
            ->keyBy('dosen_id');

        // Check apakah semua revisi sudah disetujui
        $allApproved = $pengujiList->isNotEmpty() && $pengujiList->every(function($penguji) use ($revisiList) {
            return isset($revisiList[$penguji->dosen_id]) && 
                   $revisiList[$penguji->dosen_id]->status === 'disetujui';
        });

        return view('mahasiswa.revisi.show', compact('pelaksanaan', 'pengujiList', 'revisiList', 'allApproved'));
    }

    /**
     * Submit revisi untuk satu penguji
     */
    public function submitRevisi(Request $request, PelaksanaanSidang $pelaksanaan, $dosenId)
    {
        // Check authorization
        $mahasiswa = auth()->user()->mahasiswa;
        if ($pelaksanaan->pendaftaranSidang->topik->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'file_revisi' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'catatan_mahasiswa' => 'nullable|string|max:1000',
        ]);

        // Check if penguji exists for this pelaksanaan
        $penguji = PengujiSidang::where('pelaksanaan_sidang_id', $pelaksanaan->id)
            ->where('dosen_id', $dosenId)
            ->firstOrFail();

        // Upload file
        $file = $request->file('file_revisi');
        $fileName = 'revisi_' . $pelaksanaan->id . '_dosen_' . $dosenId . '_' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('revisi-sidang', $fileName, 'public');

        // Create or update revisi
        $revisi = RevisiSidang::updateOrCreate(
            [
                'pelaksanaan_sidang_id' => $pelaksanaan->id,
                'dosen_id' => $dosenId,
            ],
            [
                'file_revisi' => $filePath,
                'catatan' => $request->catatan_mahasiswa,
                'status' => 'menunggu',
                'tanggal_submit' => now(),
                'tanggal_validasi' => null,
            ]
        );

        return redirect()->route('mahasiswa.revisi.show', $pelaksanaan)
            ->with('success', 'Revisi berhasil disubmit. Menunggu validasi dosen.');
    }

    /**
     * Download file revisi yang sudah diupload
     */
    public function downloadRevisi(RevisiSidang $revisi)
    {
        // Check authorization
        $mahasiswa = auth()->user()->mahasiswa;
        if ($revisi->pelaksanaanSidang->pendaftaranSidang->topik->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Unauthorized');
        }

        if (!$revisi->file_revisi || !Storage::disk('public')->exists($revisi->file_revisi)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($revisi->file_revisi);
    }
}
