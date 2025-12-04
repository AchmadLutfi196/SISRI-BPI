<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\BimbinganHistory;
use App\Models\TopikSkripsi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BimbinganController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Profil mahasiswa belum lengkap. Silakan lengkapi data profil Anda.');
        }
        
        $topik = TopikSkripsi::where('mahasiswa_id', $mahasiswa->id)
            ->approved()
            ->first();

        if (!$topik) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda belum memiliki topik yang disetujui.');
        }

        $jenis = $request->get('jenis', 'proposal');
        $bimbingans = Bimbingan::where('topik_id', $topik->id)
            ->where('jenis', $jenis)
            ->with('dosen')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Check if there's any pending bimbingan
        $hasPendingBimbingan = Bimbingan::where('topik_id', $topik->id)
            ->where('jenis', $jenis)
            ->where('status', 'menunggu')
            ->exists();

        return view('mahasiswa.bimbingan.index', compact('bimbingans', 'topik', 'jenis', 'hasPendingBimbingan'));
    }

    public function create(Request $request)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Profil mahasiswa belum lengkap. Silakan lengkapi data profil Anda.');
        }
        
        $topik = TopikSkripsi::where('mahasiswa_id', $mahasiswa->id)
            ->approved()
            ->with('usulanPembimbing.dosen')
            ->first();

        if (!$topik) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda belum memiliki topik yang disetujui.');
        }

        $jenis = $request->get('jenis', 'proposal');
        
        // Check if there's any pending bimbingan
        $hasPendingBimbingan = Bimbingan::where('topik_id', $topik->id)
            ->where('jenis', $jenis)
            ->where('status', 'menunggu')
            ->exists();

        if ($hasPendingBimbingan) {
            return redirect()->route('mahasiswa.bimbingan.index', ['jenis' => $jenis])
                ->with('error', 'Anda masih memiliki bimbingan yang menunggu persetujuan. Silakan tunggu dosen menyetujui bimbingan sebelumnya.');
        }

        $pembimbings = $topik->usulanPembimbing()->approved()->with('dosen')->get();

        // Get pending bimbingan for each dosen (status: menunggu atau direvisi)
        $pendingBimbingan = Bimbingan::where('topik_id', $topik->id)
            ->where('jenis', $jenis)
            ->whereIn('status', ['menunggu', 'direvisi'])
            ->pluck('dosen_id')
            ->toArray();

        return view('mahasiswa.bimbingan.create', compact('topik', 'jenis', 'pembimbings', 'pendingBimbingan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:dosen,id',
            'jenis' => 'required|in:proposal,skripsi',
            'pokok_bimbingan' => 'required',
            'file_bimbingan' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'pesan_mahasiswa' => 'nullable',
        ]);

        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Profil mahasiswa belum lengkap. Silakan lengkapi data profil Anda.');
        }
        
        $topik = TopikSkripsi::where('mahasiswa_id', $mahasiswa->id)
            ->approved()
            ->first();

        if (!$topik) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda belum memiliki topik yang disetujui.');
        }

        // Check if there's pending bimbingan with the same dosen
        $pendingBimbingan = Bimbingan::where('topik_id', $topik->id)
            ->where('dosen_id', $request->dosen_id)
            ->where('jenis', $request->jenis)
            ->whereIn('status', ['menunggu', 'direvisi'])
            ->exists();

        if ($pendingBimbingan) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Anda masih memiliki bimbingan yang belum diproses oleh dosen ini. Harap tunggu hingga bimbingan sebelumnya disetujui atau ditolak.');
        }

        // Check if there's any pending bimbingan
        $hasPendingBimbingan = Bimbingan::where('topik_id', $topik->id)
            ->where('jenis', $request->jenis)
            ->where('status', 'menunggu')
            ->exists();

        if ($hasPendingBimbingan) {
            return redirect()->route('mahasiswa.bimbingan.index', ['jenis' => $request->jenis])
                ->with('error', 'Anda masih memiliki bimbingan yang menunggu persetujuan. Silakan tunggu dosen menyetujui bimbingan sebelumnya.');
        }

        $filePath = null;
        if ($request->hasFile('file_bimbingan')) {
            $filePath = $request->file('file_bimbingan')->store('bimbingan', 'public');
        }

        $bimbingan = Bimbingan::create([
            'topik_id' => $topik->id,
            'dosen_id' => $request->dosen_id,
            'jenis' => $request->jenis,
            'pokok_bimbingan' => $request->pokok_bimbingan,
            'file_bimbingan' => $filePath,
            'pesan_mahasiswa' => $request->pesan_mahasiswa,
            'status' => 'menunggu',
        ]);

        // Create history
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan->id,
            'status' => 'menunggu',
            'aksi' => 'diajukan',
            'catatan' => $request->pesan_mahasiswa,
            'oleh' => 'mahasiswa',
            'file' => $filePath,
        ]);

        return redirect()->route('mahasiswa.bimbingan.index', ['jenis' => $request->jenis])
            ->with('success', 'Bimbingan berhasil diajukan.');
    }

    public function show(Bimbingan $bimbingan)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Profil mahasiswa belum lengkap. Silakan lengkapi data profil Anda.');
        }
        
        if ($bimbingan->topik->mahasiswa_id !== $mahasiswa->id) {
            abort(403);
        }

        $bimbingan->load('histories');

        return view('mahasiswa.bimbingan.show', compact('bimbingan'));
    }

    public function uploadRevisi(Request $request, Bimbingan $bimbingan)
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Profil mahasiswa belum lengkap. Silakan lengkapi data profil Anda.');
        }
        
        if ($bimbingan->topik->mahasiswa_id !== $mahasiswa->id) {
            abort(403);
        }

        if ($bimbingan->status !== 'direvisi') {
            return back()->with('error', 'Hanya bimbingan dengan status revisi yang dapat diupload.');
        }

        $request->validate([
            'file_revisi' => 'required|file|mimes:pdf,doc,docx|max:10240',
            'pesan_mahasiswa' => 'nullable',
        ]);

        if ($bimbingan->file_revisi) {
            Storage::disk('public')->delete($bimbingan->file_revisi);
        }

        $filePath = $request->file('file_revisi')->store('revisi', 'public');

        $bimbingan->update([
            'file_revisi' => $filePath,
            'pesan_mahasiswa' => $request->pesan_mahasiswa,
            'status' => 'menunggu',
        ]);

        // Create history for revision upload
        BimbinganHistory::create([
            'bimbingan_id' => $bimbingan->id,
            'status' => 'menunggu',
            'aksi' => 'upload_revisi',
            'catatan' => $request->pesan_mahasiswa ?? 'Mahasiswa mengupload revisi',
            'oleh' => 'mahasiswa',
            'file' => $filePath,
        ]);

        return back()->with('success', 'Revisi berhasil diupload.');
    }
}
