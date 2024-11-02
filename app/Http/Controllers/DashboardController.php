<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\isAdmin;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else if (Auth::user()->isGuru()) {
                return redirect()->route('guru.dashboard');
            } else if (Auth::user()->isSiswa()) {
                return redirect()->route('siswa.dashboard');
            // } else if (Auth::user()->isKepsek()) {
            //       return redirect()->route('kepsek.dashboard');
            } else if (Auth::user()->isOrtu()) {
                return redirect()->route('ortu.dashboard');
            }

        }

        return view('dashboard.index');
    }
}
