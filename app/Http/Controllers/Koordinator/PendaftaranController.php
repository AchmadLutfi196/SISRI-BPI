<?php

namespace App\Http\Controllers\Koordinator;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\PelaksanaanSidang;
use App\Models\PendaftaranSidang;
use App\Models\PengujiSidang;
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
            ->orderByRaw("CASE WHEN status_koordinator = 'menunggu' THEN 0 ELSE 1 END")
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

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';

        return view('koordinator.pendaftaran.show', compact('pendaftaran', 'dosens', 'jenis'));
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

        // Get available dosen for penguji (exclude pembimbing)
        $availableDosens = Dosen::where('prodi_id', $prodiId)
            ->whereNotIn('id', $pembimbingIds)
            ->with('user')
            ->inRandomOrder()
            ->get();

        if ($availableDosens->count() < 3) {
            return back()->with('error', 'Tidak cukup dosen tersedia untuk menjadi penguji (minimal 3 dosen selain pembimbing).');
        }

        // Pick 3 random penguji
        $penguji1 = $availableDosens->get(0);
        $penguji2 = $availableDosens->get(1);
        $penguji3 = $availableDosens->get(2);

        // Determine schedule based on jadwal sidang period
        $jadwalSidang = $pendaftaran->jadwalSidang;
        
        // Find available slot (date and room)
        $tanggalSidang = $this->findAvailableSlot($jadwalSidang);
        
        if (!$tanggalSidang) {
            return back()->with('error', 'Tidak ada slot waktu tersedia dalam periode sidang.');
        }

        // Find available room
        $tempat = $this->findAvailableRoom($tanggalSidang['datetime']);

        // Update status koordinator
        $pendaftaran->update([
            'status_koordinator' => 'disetujui',
            'catatan_koordinator' => 'Dijadwalkan secara otomatis oleh sistem.',
        ]);

        // Buat pelaksanaan sidang
        $pelaksanaan = PelaksanaanSidang::create([
            'pendaftaran_sidang_id' => $pendaftaran->id,
            'tanggal_sidang' => $tanggalSidang['datetime'],
            'tempat' => $tempat,
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
            'dosen_id' => $penguji1->id,
            'role' => 'penguji_1',
        ]);

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $penguji2->id,
            'role' => 'penguji_2',
        ]);

        PengujiSidang::create([
            'pelaksanaan_sidang_id' => $pelaksanaan->id,
            'dosen_id' => $penguji3->id,
            'role' => 'penguji_3',
        ]);

        $jenis = $pendaftaran->jenis === 'seminar_proposal' ? 'sempro' : 'sidang';
        
        $tanggalFormatted = \Carbon\Carbon::parse($tanggalSidang['datetime'])->format('d M Y H:i');
        $pengujiNames = $penguji1->user->name . ', ' . $penguji2->user->name . ', ' . $penguji3->user->name;

        return redirect()->route('koordinator.pendaftaran.index', ['jenis' => $jenis])
            ->with('success', "Sidang berhasil dijadwalkan otomatis pada {$tanggalFormatted} di {$tempat}. Penguji: {$pengujiNames}");
    }

    /**
     * Find available time slot based on jadwal sidang period
     */
    private function findAvailableSlot($jadwalSidang)
    {
        $startDate = \Carbon\Carbon::parse($jadwalSidang->tanggal_buka);
        $endDate = \Carbon\Carbon::parse($jadwalSidang->tanggal_tutup)->addDays(14); // Give 2 weeks buffer after registration closes
        
        // If start date is in the past, use tomorrow
        if ($startDate->isPast()) {
            $startDate = \Carbon\Carbon::tomorrow();
        }

        $timeSlots = ['08:00', '09:30', '11:00', '13:00', '14:30', '16:00'];
        
        $currentDate = $startDate->copy();
        
        while ($currentDate->lte($endDate)) {
            // Skip weekends
            if (!$currentDate->isWeekend()) {
                foreach ($timeSlots as $time) {
                    $datetime = $currentDate->format('Y-m-d') . ' ' . $time . ':00';
                    
                    // Check if this slot is available (no other sidang at this time)
                    $existingCount = PelaksanaanSidang::where('tanggal_sidang', $datetime)
                        ->where('status', '!=', 'selesai')
                        ->count();
                    
                    // Allow up to 3 concurrent sessions (different rooms)
                    if ($existingCount < 3) {
                        return [
                            'datetime' => $datetime,
                            'date' => $currentDate->format('Y-m-d'),
                            'time' => $time,
                        ];
                    }
                }
            }
            
            $currentDate->addDay();
        }
        
        return null;
    }

    /**
     * Find available room for a specific datetime
     */
    private function findAvailableRoom($datetime)
    {
        $defaultRooms = [
            'Ruang Sidang A - Gedung Teknik Lt. 3',
            'Ruang Sidang B - Gedung Teknik Lt. 3',
            'Ruang Sidang C - Gedung Teknik Lt. 4',
        ];
        
        foreach ($defaultRooms as $room) {
            $isUsed = PelaksanaanSidang::where('tanggal_sidang', $datetime)
                ->where('tempat', $room)
                ->where('status', '!=', 'selesai')
                ->exists();
            
            if (!$isUsed) {
                return $room;
            }
        }
        
        // If all default rooms are used, generate a new room name
        return 'Ruang Sidang ' . chr(65 + rand(3, 5)) . ' - Gedung Teknik Lt. ' . rand(2, 5);
    }
}