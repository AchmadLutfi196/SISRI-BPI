<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use App\Models\PendaftaranSidang;
use App\Models\UsulanPembimbing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dosen = auth()->user()->dosen;

        // Dapatkan topik dimana dosen ini jadi pembimbing
        $topikIds = UsulanPembimbing::where('dosen_id', $dosen->id)
            ->where('status', 'diterima')
            ->pluck('topik_id');
        
        // Hitung pendaftaran sidang menunggu persetujuan dosen ini
        $sidangMenunggu = 0;
        $pendaftarans = PendaftaranSidang::whereIn('topik_id', $topikIds)->get();
        foreach ($pendaftarans as $pendaftaran) {
            $usulan = UsulanPembimbing::where('topik_id', $pendaftaran->topik_id)
                ->where('dosen_id', $dosen->id)
                ->where('status', 'diterima')
                ->first();
            if ($usulan) {
                $statusField = 'status_pembimbing_' . $usulan->urutan;
                if ($pendaftaran->$statusField === 'menunggu') {
                    $sidangMenunggu++;
                }
            }
        }

        $stats = [
            'usulan_menunggu' => UsulanPembimbing::where('dosen_id', $dosen->id)->pending()->count(),
            'usulan_diterima' => UsulanPembimbing::where('dosen_id', $dosen->id)->approved()->count(),
            'bimbingan_menunggu' => Bimbingan::where('dosen_id', $dosen->id)->pending()->count(),
            'total_bimbingan' => Bimbingan::where('dosen_id', $dosen->id)->count(),
            'sidang_menunggu' => $sidangMenunggu,
        ];

        $recentUsulan = UsulanPembimbing::where('dosen_id', $dosen->id)
            ->pending()
            ->with('topik.mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentBimbingan = Bimbingan::where('dosen_id', $dosen->id)
            ->pending()
            ->with('topik.mahasiswa')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact('dosen', 'stats', 'recentUsulan', 'recentBimbingan'));
    }
}
