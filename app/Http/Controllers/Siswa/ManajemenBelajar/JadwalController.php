<?php

namespace App\Http\Controllers\Siswa\ManajemenBelajar;

use App\Http\Controllers\Controller;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jadwalHariIni = JadwalBelajar::with('mapel', 'kelas')
            ->where('kelas_id', Auth::user()->siswa->kelas->id)
            ->where('hari', hari_ini())
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->paginate();

        $jadwals = JadwalBelajar::with('mapel','kelas','guru','tugas')
            ->where('kelas_id', Auth::user()->siswa->kelas->id)
            ->where('hari', '!=', hari_ini())
            ->paginate(6);

        return view('dashboard.siswa.jadwal', [
            'jadwals' => $jadwals,
            'jadwalHariIni' => $jadwalHariIni
        ]);
    }
}
