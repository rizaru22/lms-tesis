<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Ujian\Ujian;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isSiswa()) {

            $jadwals = JadwalBelajar::with('mapel', 'kelas', 'guru', 'tugas')
                ->where('kelas_id', Auth::user()->siswa->kelas->id)
                ->get();

            $jadwalHariIni = JadwalBelajar::with('mapel', 'kelas')
                ->where('kelas_id', Auth::user()->siswa->kelas->id)
                ->where('hari', hari_ini())
                ->whereTime('started_at', '<=', date("H:i"))
                ->whereTime('ended_at', '>=', date("H:i"))
                ->get();

            $riwayatUjian = JadwalUjian::aktif()
                ->with('ujian', 'kelas', 'mapel')
                ->where('kelas_id', Auth::user()->siswa->kelas->id)
                ->whereHas('ujian', function ($q) {
                    $q->whereHas('ujianSiswa', function ($q) {
                        $q->where('status', 1)->where('siswa_id', Auth::user()->siswa->id);
                    });
                })
                ->latest()
                ->get();

            $jadwalUjian = JadwalUjian::aktif()
                ->with(['ujian' => function ($q) {
                    $q->with(['ujiansiswa' => function ($q) {
                        $q->where('siswa_id', Auth::user()->siswa->id)->where('status', 0);
                    }]);
                }, 'kelas', 'mapel'])
                ->where('kelas_id', Auth::user()->siswa->kelas->id)
                ->whereHas('ujian', function ($q) {
                    $q->whereHas('ujianSiswa', function ($q) {
                        $q->where('status', 0)->where('siswa_id', Auth::user()->siswa->id);
                    });
                })
                ->orderBy(Ujian::select('id')->whereColumn('ujian_id', 'ujians.id')
                    ->orderBy("semester", "desc")->limit(1), "asc")
                ->get();

            return view('dashboard.siswa.dashboard', [
                'jadwals' => $jadwals,
                'jadwalHariIni' => $jadwalHariIni,
                'jadwalUjian' => $jadwalUjian,
                'riwayatUjian' => $riwayatUjian,
            ]);
        } else {
            abort(404);
        }
    }
}
