<?php

namespace App\Http\Controllers\Guru\ManajemenBelajar\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // data jadwals dengan sortBy hari sekarang
        $jadwalHariIni = JadwalBelajar::with('mapel', 'kelas')
            ->where('guru_id', Auth::user()->guru->id)
            ->where('hari', hari_ini())
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->paginate();

        $jadwals = JadwalBelajar::with('mapel', 'kelas')
            ->where('guru_id', Auth::user()->guru->id)
            ->where('hari', '!=', hari_ini())
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->paginate(6);

        return view('dashboard.guru.jadwal.pelajaran', [
            'jadwalHariIni' => $jadwalHariIni,
            'jadwals' => $jadwals
        ]);
    }
}
