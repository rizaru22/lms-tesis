<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KelolaPengguna\{Guru, Siswa,Ortu};
use App\Models\ManajemenBelajar\{
    Programkeahlian,
    Prodi,
    Mapel,
    Kelas,
    Jadwal\Belajar,
    Jadwal\Ujian,
};
use App\Models\User;
use App\Models\RolePermission\{
    Role,
    Permission,
    LabelPermission
};

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {

            $last_login = User::where('id', '!=', Auth::id())
                ->orderBy('last_seen', 'desc')
                ->take(25)
                ->get();

            return view('dashboard.admin.index', [
                'guru' => Guru::count(),
                // 'kepsek' => Kepsek::count(),
                'ortu' => Ortu::count(),
                'siswa' => Siswa::count(),
                // 'program_keahlian' => Program::count(),
                'prodi' => Prodi::count(),
                'mapel' => Mapel::count(),
                'kelas' => Kelas::count(),
                'belajar' => Belajar::count(),
                'ujian' => Ujian::count(),
                'users' => User::where('id', '!=', Auth::id())->get(),
                'roles' => Role::count(),
                'permissions' => Permission::count(),
                'label_permissions' => LabelPermission::count(),
                'last_login' => $last_login,
            ]);

        } else {
            abort(404);
        }
    }
}
