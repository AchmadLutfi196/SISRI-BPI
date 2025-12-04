<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PelaksanaanSidang;
use App\Models\PendaftaranSidang;
use App\Models\PengujiSidang;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class PendaftaranController extends Controller
{
    private function getProdiId()
    {
        $dosen = auth()->user()->dosen;
        $koordinator = $dosen?->activeKoordinatorProdi;
        return $koordinator?->prodi_id;
    }

    /**
     * Menampilkan daftar pendaftaran sempro/sidang yang perlu diproses
     */
    public function index(Request $request)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId) {
            return redirect()->route('dosen.dashboard')
                ->with('error', 'Anda tidak memiliki akses sebagai koordinator.');
        }

        $jenis = $request->get('jenis', 'sempro');
        $jenisDb = $jenis === 'sempro' ? 'seminar_proposal' : 'sidang_skripsi';

        // Ambil pendaftaran yang sudah disetujui kedua pembimbing
        $pendaftarans = PendaftaranSidang::where('jenis', $jenisDb)
            ->where('status_pembimbing_1', 'disetujui')
            ->where('status_pembimbing_2', 'disetujui')
            ->whereHas('jadwalSidang', function ($q) use ($prodiId) {
                $q->where('prodi_id', $prodiId);
            })
            ->with([
                'topik.mahasiswa.user',
                'topik.usulanPembimbing' => function ($q) {
                    $q->where('status', 'diterima')->orderBy('urutan');
                },
                'topik.usulanPembimbing.dosen.user',
                'jadwalSidang',
                'pelaksanaanSidang'
            ])
            // Urutan prioritas:
            // 1. Menunggu approval koordinator (paling atas)
            // 2. Dijadwalkan (tengah)
            // 3. Selesai (paling bawah)
            ->orderByRaw("
                CASE 
                    WHEN status_koordinator = 'menunggu' THEN 0
                    WHEN status_koordinator = 'disetujui' THEN 1
                    ELSE 2
                END
            ")
            ->orderByRaw("
                CASE 
                    WHEN EXISTS (
                        SELECT 1 FROM pelaksanaan_sidang 
                        WHERE pelaksanaan_sidang.pendaftaran_sidang_id = pendaftaran_sidang.id 
                        AND pelaksanaan_sidang.status = 'selesai'
                    ) THEN 1
                    ELSE 0
                END
            ")
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('koordinator.pendaftaran.index', compact('pendaftarans', 'jenis'));
    }

    /**
     * Detail pendaftaran
     */
    public function show(PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        $pendaftaran->load([
            'topik.mahasiswa.user',
            'topik.usulanPembimbing' => function ($q) {
                $q->where('status', 'diterima')->orderBy('urutan');
            },
            'topik.usulanPembimbing.dosen.user',
            'topik.bimbingan' => function ($q) {
                $q->orderBy('created_at', 'desc')->limit(5);
            },
            'jadwalSidang',
            'pelaksanaanSidang.pengujiSidang.dosen.user',
            'pelaksanaanSidang.nilai.dosen.user'
        ]);

        // Daftar dosen untuk penguji (exclude pembimbing)
        $pembimbingIds = $pendaftaran->topik->usulanPembimbing->pluck('dosen_id')->toArray();
        $dosens = Dosen::where('prodi_id', $prodiId)
            ->whereNotIn('id', $pembimbingIds)
            ->with('user')
            ->get();

        // Daftar ruangan aktif
        $ruangans = Ruangan::active()->orderBy('nama')->get();

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return view('koordinator.pendaftaran.show', compact('pendaftaran', 'dosens', 'ruangans', 'jenis'));
    }

    /**
     * Setujui pendaftaran dan jadwalkan pelaksanaan
     */
    public function approve(Request $request, PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        $request->validate([
            'tanggal_sidang' => 'required|date|after:today',
            'tempat' => 'required|string|max:255',
            'penguji_1_id' => 'required|exists:dosen,id',
            'penguji_2_id' => 'required|exists:dosen,id|different:penguji_1_id',
            'penguji_3_id' => 'required|exists:dosen,id|different:penguji_1_id|different:penguji_2_id',
            'catatan' => 'nullable|string',
        ]);

        // Cek bentrokan jadwal (tanggal & tempat yang sama)
        $existingSchedule = PelaksanaanSidang::where('tanggal_sidang', $request->tanggal_sidang)
            ->where('tempat', $request->tempat)
            ->where('status', '!=', 'selesai')
            ->first();

        if ($existingSchedule) {
            return back()->withInput()->with('error', 'Jadwal bentrok! Ruangan "' . $request->tempat . '" sudah digunakan pada tanggal tersebut untuk sidang lain.');
        }

        // Update status koordinator
        $pendaftaran->update([
            'status_koordinator' => 'disetujui',
            'catatan_koordinator' => $request->catatan,
        ]);

        // Buat pelaksanaan sidang
        $pelaksanaan = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaran->id,
            'tanggal_sidang' => $request->tanggal_sidang,
            'tempat' => $request->tempat,
            'status' => 'dijadwalkan',
        ]);

        // Tambahkan pembimbing sebagai penguji
        $pembimbing = $pendaftaran->topik->usulanPembimbing()
            ->where('status', 'diterima')
            ->orderBy('urutan')
            ->get();

        foreach ($pembimbing as $p) {
            PengujiSidang::create([
                'pelaksanaan_sidang_id' => $pelaksanaan->id,
                'dosen_id' => $p->dosen_id,
                'role' => 'pembimbing_' . $p->urutan,
            ]);
        }

        // Tambahkan penguji 1
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $request->penguji_1_id,
            'role' => 'penguji_1',
        ]);

        // Tambahkan penguji 2
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $request->penguji_2_id,
            'role' => 'penguji_2',
        ]);

        // Tambahkan penguji 3
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $request->penguji_3_id,
            'role' => 'penguji_3',
        ]);

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return redirect()->route('koordinator.pendaftaran.index', ['jenis' => $jenis])
            ->with('success', 'Pendaftaran disetujui dan sidang berhasil dijadwalkan.');
    }

    /**
     * Tolak pendaftaran
     */
    public function reject(Request $request, PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'required|string|max:500',
        ]);

        $pendaftaran->update([
            'status_koordinator' => 'ditolak',
            'catatan_koordinator' => $request->catatan,
        ]);

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return redirect()->route('koordinator.pendaftaran.index', ['jenis' => $jenis])
            ->with('success', 'Pendaftaran ditolak.');
    }

    /**
     * Edit jadwal pelaksanaan yang sudah ada
     */
    public function editPelaksanaan(PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        if (!$pendaftaran->pelaksanaanSidang) {
            return redirect()->back()->with('error', 'Pelaksanaan sidang belum dijadwalkan.');
        }

        $pendaftaran->load([
            'topik.mahasiswa.user',
            'topik.usulanPembimbing' => function ($q) {
                $q->where('status', 'diterima')->orderBy('urutan');
            },
            'topik.usulanPembimbing.dosen.user',
            'pelaksanaanSidang.pengujiSidang.dosen.user'
        ]);

        // Daftar dosen untuk penguji (exclude pembimbing)
        $pembimbingIds = $pendaftaran->topik->usulanPembimbing->pluck('dosen_id')->toArray();
        $dosens = Dosen::where('prodi_id', $prodiId)
            ->whereNotIn('id', $pembimbingIds)
            ->with('user')
            ->get();

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return view('koordinator.pendaftaran.edit-pelaksanaan', compact('pendaftaran', 'dosens', 'jenis'));
    }

    /**
     * Update jadwal pelaksanaan
     */
    public function updatePelaksanaan(Request $request, PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        $pelaksanaan = $pendaftaran->pelaksanaanSidang;

        if (!$pelaksanaan || $pelaksanaan->status === 'selesai') {
            return redirect()->back()->with('error', 'Tidak dapat mengubah jadwal yang sudah selesai.');
        }

        $request->validate([
            'tanggal_sidang' => 'required|date',
            'tempat' => 'required|string|max:255',
            'penguji_1_id' => 'required|exists:dosen,id',
            'penguji_2_id' => 'required|exists:dosen,id|different:penguji_1_id',
            'penguji_3_id' => 'required|exists:dosen,id|different:penguji_1_id|different:penguji_2_id',
        ]);

        // Cek bentrokan jadwal (tanggal & tempat yang sama, exclude current)
        $existingSchedule = PelaksanaanSidang::where('tanggal_sidang', $request->tanggal_sidang)
            ->where('tempat', $request->tempat)
            ->where('id', '!=', $pelaksanaan->id)
            ->where('status', '!=', 'selesai')
            ->first();

        if ($existingSchedule) {
            return back()->withInput()->with('error', 'Jadwal bentrok! Ruangan "' . $request->tempat . '" sudah digunakan pada tanggal tersebut untuk sidang lain.');
        }

        // Update pelaksanaan
        $pelaksanaan->update([
            'tanggal_sidang' => $request->tanggal_sidang,
            'tempat' => $request->tempat,
        ]);

        // Update penguji (hapus penguji lama, tambah baru)
        $pelaksanaan->pengujiSidang()->whereIn('role', ['penguji_1', 'penguji_2', 'penguji_3'])->delete();

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $request->penguji_1_id,
            'role' => 'penguji_1',
        ]);

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $request->penguji_2_id,
            'role' => 'penguji_2',
        ]);

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $request->penguji_3_id,
            'role' => 'penguji_3',
        ]);

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return redirect()->route('koordinator.pendaftaran.index', ['jenis' => $jenis])
            ->with('success', 'Jadwal pelaksanaan berhasil diperbarui.');
    }

    /**
     * Tandai sidang selesai
     */
    public function completePelaksanaan(PelaksanaanSidang $pelaksanaan)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pelaksanaan->pendaftaranSidang->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        $pelaksanaan->update([
            'status' => 'selesai',
        ]);

        $jenis = $pelaksanaan->pendaftaranSidang->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return redirect()->route('koordinator.pendaftaran.index', ['jenis' => $jenis])
            ->with('success', 'Sidang telah ditandai selesai.');
    }

    /**
     * Auto approve - sistem otomatis pilih tanggal, waktu, ruangan, dan 3 penguji
     */
    public function autoApprove(PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        if ($pendaftaran->status_koordinator !== 'menunggu') {
            return back()->with('error', 'Pendaftaran sudah diproses sebelumnya.');
        }

        // Get pembimbing IDs to exclude from penguji
        $pembimbingIds = $pendaftaran->topik->usulanPembimbing()
            ->where('status', 'diterima')
            ->pluck('dosen_id')
            ->toArray();

        // Determine schedule based on jadwal sidang period
        $jadwalSidang = $pendaftaran->jadwalSidang;
        
        // Find available slot (date, time, room, and 3 available penguji)
        $schedule = $this->findAvailableSchedule($jadwalSidang, $pembimbingIds, $prodiId);
        
        if (!$schedule) {
            return back()->with('error', 'Tidak ada slot waktu dan penguji tersedia dalam periode sidang. Pastikan ada minimal 3 dosen yang tidak sedang menguji di waktu yang sama.');
        }

        // Update status koordinator
        $pendaftaran->update([
            'status_koordinator' => 'disetujui',
            'catatan_koordinator' => 'Dijadwalkan secara otomatis oleh sistem.',
        ]);

        // Buat pelaksanaan sidang
        $pelaksanaan = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaran->id,
            'tanggal_sidang' => $schedule['datetime'],
            'tempat' => $schedule['room'],
            'status' => 'dijadwalkan',
        ]);

        // Tambahkan pembimbing sebagai penguji
        $pembimbing = $pendaftaran->topik->usulanPembimbing()
            ->where('status', 'diterima')
            ->orderBy('urutan')
            ->get();

        foreach ($pembimbing as $p) {
            PengujiSidang::create([
                'pelaksanaan_sidang_id' => $pelaksanaan->id,
                'dosen_id' => $p->dosen_id,
                'role' => 'pembimbing_' . $p->urutan,
            ]);
        }

        // Tambahkan 3 penguji
        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $schedule['penguji'][0]->id,
            'role' => 'penguji_1',
        ]);

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $schedule['penguji'][1]->id,
            'role' => 'penguji_2',
        ]);

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $schedule['penguji'][2]->id,
            'role' => 'penguji_3',
        ]);

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';
        
        $tanggalFormatted = \Carbon\Carbon::parse($schedule['datetime'])->format('d M Y H:i');
        $pengujiNames = $schedule['penguji'][0]->user->name . ', ' . $schedule['penguji'][1]->user->name . ', ' . $schedule['penguji'][2]->user->name;

        return redirect()->route('koordinator.pendaftaran.index', ['jenis' => $jenis])
            ->with('success', "Sidang berhasil dijadwalkan otomatis pada {$tanggalFormatted} di {$schedule['room']}. Penguji: {$pengujiNames}");
    }

    /**
     * Find available schedule (datetime, room, and 3 penguji) with conflict checking
     * Pelaksanaan sidang dijadwalkan dalam rentang periode pendaftaran atau setelahnya
     */
    private function findAvailableSchedule($jadwalSidang, $pembimbingIds, $prodiId)
    {
        $tanggalBuka = \Carbon\Carbon::parse($jadwalSidang->tanggal_buka);
        $tanggalTutup = \Carbon\Carbon::parse($jadwalSidang->tanggal_tutup);
        $today = \Carbon\Carbon::today();
        
        // Tentukan tanggal mulai pelaksanaan:
        // 1. Jika masih dalam periode pendaftaran, mulai dari besok (minimal)
        // 2. Jika sudah lewat tanggal tutup, mulai dari besok
        // 3. Jika belum masuk periode, mulai dari tanggal buka
        if ($today->lt($tanggalBuka)) {
            // Belum masuk periode pendaftaran, mulai dari tanggal buka
            $startDate = $tanggalBuka->copy();
        } else {
            // Sudah masuk atau lewat periode, mulai dari besok
            $startDate = \Carbon\Carbon::tomorrow();
        }
        
        // Pastikan tanggal mulai masih dalam atau setelah periode pendaftaran
        if ($startDate->lt($tanggalBuka)) {
            $startDate = $tanggalBuka->copy();
        }
        
        // Periode pelaksanaan: sampai 14 hari setelah tanggal tutup
        $endDate = $tanggalTutup->copy()->addDays(14);

        $timeSlots = ['08:00', '09:30', '11:00', '13:00', '14:30', '16:00'];
        
        // Get available rooms from database
        $rooms = Ruangan::active()->orderBy('nama')->get();
        
        if ($rooms->isEmpty()) {
            return null;
        }

        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            // Skip weekends
            if (!$currentDate->isWeekend()) {
                foreach ($timeSlots as $time) {
                    $datetime = $currentDate->format('Y-m-d') . ' ' . $time . ':00';
                    
                    // Check if pembimbing are available at this datetime
                    if (!$this->arePembimbingAvailable($pembimbingIds, $datetime)) {
                        continue; // Pembimbing busy, try next slot
                    }
                    
                    // Find available room for this datetime
                    $availableRoom = $this->findAvailableRoomForDatetime($rooms, $datetime);
                    
                    if (!$availableRoom) {
                        continue; // No room available at this time, try next slot
                    }
                    
                    // Find 3 available penguji for this datetime
                    $availablePenguji = $this->findAvailablePengujiForDatetime($datetime, $pembimbingIds, $prodiId);
                    
                    if (count($availablePenguji) >= 3) {
                        return [
                            'datetime' => $datetime,
                            'room' => $availableRoom->nama,
                            'penguji' => array_slice($availablePenguji, 0, 3),
                        ];
                    }
                }
            }
            
            $currentDate->addDay();
        }
        
        return null;
    }

    /**
     * Check if all pembimbing are available at a specific datetime
     */
    private function arePembimbingAvailable($pembimbingIds, $datetime)
    {
        if (empty($pembimbingIds)) {
            return true;
        }
        
        // Check if any pembimbing is already assigned to another sidang at this datetime
        $busyPembimbing = PengujiSidang::whereIn('dosen_id', $pembimbingIds)
            ->whereHas('pelaksanaanSidang', function ($q) use ($datetime) {
                $q->where('tanggal_sidang', $datetime)
                  ->whereIn('status', ['dijadwalkan', 'selesai']);
            })
            ->exists();
        
        return !$busyPembimbing;
    }

    /**
     * Find available room from database for a specific datetime
     */
    private function findAvailableRoomForDatetime($rooms, $datetime)
    {
        foreach ($rooms as $room) {
            $isUsed = PelaksanaanSidang::where('tanggal_sidang', $datetime)
                ->where('tempat', $room->nama)
                ->whereIn('status', ['dijadwalkan', 'selesai'])
                ->exists();
            
            if (!$isUsed) {
                return $room;
            }
        }
        
        return null;
    }

    /**
     * Find available penguji (dosen not in another sidang at same datetime)
     */
    private function findAvailablePengujiForDatetime($datetime, $pembimbingIds, $prodiId)
    {
        // Get all dosen in prodi (exclude pembimbing)
        $allDosens = Dosen::where('prodi_id', $prodiId)
            ->whereNotIn('id', $pembimbingIds)
            ->with('user')
            ->get();
        
        // Get dosen IDs who are busy at this datetime (already assigned as penguji/pembimbing)
        $busyDosenIds = PengujiSidang::whereHas('pelaksanaanSidang', function ($q) use ($datetime) {
            $q->where('tanggal_sidang', $datetime)
              ->whereIn('status', ['dijadwalkan', 'selesai']);
        })->pluck('dosen_id')->toArray();
        
        // Filter out busy dosen
        $availableDosens = $allDosens->filter(function ($dosen) use ($busyDosenIds) {
            return !in_array($dosen->id, $busyDosenIds);
        })->shuffle()->values()->all();
        
        return $availableDosens;
    }

    /**
     * Download dokumen pendaftaran
     */
    public function downloadDokumen(PendaftaranSidang $pendaftaran)
    {
        $prodiId = $this->getProdiId();

        if (!$prodiId || $pendaftaran->jadwalSidang->prodi_id !== $prodiId) {
            abort(403);
        }

        if (!$pendaftaran->file_dokumen) {
            return back()->with('error', 'Dokumen tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $pendaftaran->file_dokumen);
        
        if (!file_exists($path)) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        return response()->download($path, $pendaftaran->file_dokumen_original_name ?? 'dokumen.pdf');
    }
}