<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
// use App\Models\ManajemenBelajar\Absen;
// use Carbon\Carbon;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
// use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
// use App\Models\ManajemenBelajar\Ujian\Ujian;
// use App\Models\ManajemenBelajar\Ujian\UjianSiswa;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isOrtu()) {
            return view('dashboard.ortu.dashboard', [
            ]);

        } else {
            abort(404);
        }
    }
}
