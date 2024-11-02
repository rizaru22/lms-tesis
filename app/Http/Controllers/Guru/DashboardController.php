<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ManajemenBelajar\Absen;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isGuru()) {
            // absen
            $absens = Absen::with('jadwal')->where([
                ['guru_id', Auth::user()->guru->id],
                ['parent', 0]
            ])->whereDate('created_at', Carbon::today())
                ->latest()
                ->get();

            // jadwal belajar
            $jadwals = JadwalBelajar::with('mapel', 'kelas')
                ->where('guru_id', Auth::user()->guru->id)
                ->get();

            // jadwal ujian yang belum dibuat ujian
            $buatUjians = JadwalUjian::with('ujian')
                ->whereDoesntHave('ujian')
                ->where('guru_id', Auth::user()->guru->id)
                ->get();

            // ujian
            $ujians = JadwalUjian::join('ujians', 'ujians.id', '=', 'jadwal_ujians.ujian_id')
                ->join('ujian_siswas', 'ujian_siswas.ujian_id', '=', 'ujians.id')
                ->where('jadwal_ujians.guru_id', Auth::user()->guru->id)
                ->where('status', '1')
                ->where('nilai', null)
                ->get();

            // jadwal belajar hari ini
            $jadwalHariIni = JadwalBelajar::with('absens')
                ->where('guru_id', Auth::user()->guru->id)
                ->where('hari', hari_ini())
                ->whereTime('started_at', '<=', date("H:i"))
                ->whereTime('ended_at', '>=', date("H:i"))
                ->get();

            return view('dashboard.guru.dashboard', [
                'belajar' => Auth::user()->guru->jadwalBelajar->count(),
                'ujian' => Auth::user()->guru->ujian->count(),
                'materi' => Auth::user()->guru->materis->count(),
                'tugas' => Auth::user()->guru->tugas->count(),
                'mapel' => Auth::user()->guru->mapels->count(),
                'absens' => $absens,
                'jadwals' => $jadwals,
                'ujians' => $ujians,
                'jadwalUjian' => $buatUjians,
                'jadwalHariIni' => $jadwalHariIni,
            ]);

        } else {
            abort(404);
        }
    }
}
