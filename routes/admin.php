<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\DashboardController;

use App\Http\Controllers\Admin\KelolaPengguna\{
    UserController,
    GuruController,
    SiswaController
};

use App\Http\Controllers\Admin\RolePermission\{
    RoleController,
    PermissionController,
    LabelPermissionController
};

use App\Http\Controllers\Admin\ManajemenBelajar\{
    KelasController,
    MapelController,
    ProgramkeahlianController,
    ProdiController,
    Jadwal\BelajarController,
    Jadwal\UjianController
};

Route::group(['middleware' => ['auth','admin']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::group(['prefix' => 'kelola-pengguna', 'as' => 'manage.users.'], function () {
        // User
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::post('store', [UserController::class, 'store'])->name('store');
            Route::put('{id}', [UserController::class, 'update'])->name('update');
            Route::get('{id}', [UserController::class, 'show'])->name('show');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('delete');
        });


    // Guru
            Route::group(['prefix' => 'guru', 'as' => 'guru.'], function () {
            Route::get('/', [GuruController::class, 'index'])->name('index');
            Route::post('store', [GuruController::class, 'store'])->name('store');
            Route::put('{id}', [GuruController::class, 'update'])->name('update');
            Route::get('{id}', [GuruController::class, 'show'])->name('show');
            Route::delete('{id}', [GuruController::class, 'destroy'])->name('delete');
        });


 // Ortu
 Route::group(['prefix' => 'ortu', 'as' => 'ortu.'], function () {
    Route::get('/', [App\Http\Controllers\Admin\KelolaPengguna\OrtuController::class, 'index'])->name('index');
    Route::post('store', [App\Http\Controllers\Admin\KelolaPengguna\OrtuController::class, 'store'])->name('store');
    Route::put('{id}', [App\Http\Controllers\Admin\KelolaPengguna\OrtuController::class, 'update'])->name('update');
    Route::get('{id}', [App\Http\Controllers\Admin\KelolaPengguna\OrtuController::class, 'show'])->name('show');
    Route::delete('{id}', [App\Http\Controllers\Admin\KelolaPengguna\OrtuController::class, 'destroy'])->name('delete');
});


        // siswa
        Route::group(['prefix' => 'siswa', 'as' => 'siswa.'], function () {
            Route::get('/', [SiswaController::class, 'index'])->name('index');
            Route::post('import', [SiswaController::class, 'import'])->name('import');
            Route::post('store', [SiswaController::class, 'store'])->name('store');
            Route::put('{id}', [SiswaController::class, 'update'])->name('update');
            Route::get('{id}', [SiswaController::class, 'show'])->name('show');
            Route::delete('{id}', [SiswaController::class, 'destroy'])->name('delete');
        });
    });

    // Role & Permission
    Route::group(['prefix' => 'role-permission', 'as' => 'role.permission.'], function () {
        // Role
        Route::group(['prefix' => 'role', 'as' => 'role.'], function () {
            Route::get('/', [RoleController::class, 'index'])->name('index');
            Route::post('store', [RoleController::class, 'store'])->name('store');
            Route::put('{id}', [RoleController::class, 'update'])->name('update');
            Route::get('{id}', [RoleController::class, 'show'])->name('show');
            Route::delete('{id}', [RoleController::class, 'destroy'])->name('delete');
            Route::get('fetch-permission/{id}', [RoleController::class, 'fetchPermission'])->name('fetch.permission');
        });


        // Permission
        Route::group(['prefix' => 'permission', 'as' => 'permission.'], function () {
            Route::get('/', [PermissionController::class, 'index'])->name('index');
            Route::post('store', [PermissionController::class, 'store'])->name('store');
            Route::put('{id}', [PermissionController::class, 'update'])->name('update');
            Route::get('{id}', [PermissionController::class, 'show'])->name('show');
            Route::delete('{id}', [PermissionController::class, 'destroy'])->name('delete');
        });

        // Label Permission
        Route::group(['prefix' => 'label-permission', 'as' => 'label.permission.'], function () {
            Route::get('/', [LabelPermissionController::class, 'index'])->name('index');
            Route::post('store', [LabelPermissionController::class, 'store'])->name('store');
            Route::put('{id}', [LabelPermissionController::class, 'update'])->name('update');
            Route::get('{id}', [LabelPermissionController::class, 'show'])->name('show');
            Route::delete('{id}', [LabelPermissionController::class, 'destroy'])->name('delete');
        });
    });

    Route::group(['prefix' => 'manajemen-pelajaran', 'as' => 'manajemen.pelajaran.'], function () {
        // Mata Pelajaran
        Route::group(['prefix' => 'mapel', 'as' => 'mapel.'], function () {
            Route::get('/', [ MapelController::class, 'index'])->name('index');
            Route::post('store', [ MapelController::class, 'store'])->name('store');
            Route::put('{id}', [ MapelController::class, 'update'])->name('update');
            Route::get('/fetch', [ MapelController::class, 'fetch'])->name('fetch');
            Route::get('{id}', [ MapelController::class, 'show'])->name('show');
            Route::delete('{id}', [ MapelController::class, 'destroy'])->name('delete');
        });

        // Program Keahlian
        Route::group(['prefix' => 'program-keahlian', 'as' => 'programkeahlian.'], function () {
            Route::get('/', [ProgramkeahlianController::class, 'index'])->name('index');
            Route::post('store', [ProgramkeahlianController::class, 'store'])->name('store');
            Route::put('{id}', [ProgramkeahlianController::class, 'update'])->name('update');
            Route::get('{id}', [ProgramkeahlianController::class, 'show'])->name('show');
            Route::delete('{id}', [ProgramkeahlianController::class, 'destroy'])->name('delete');
        });

        // Prodi
        Route::group(['prefix' => 'program-studi', 'as' => 'prodi.'], function () {
            Route::get('/', [ProdiController::class, 'index'])->name('index');
            Route::post('store', [ProdiController::class, 'store'])->name('store');
            Route::put('{id}', [ProdiController::class, 'update'])->name('update');
            Route::get('{id}', [ProdiController::class, 'show'])->name('show');
            Route::delete('{id}', [ProdiController::class, 'destroy'])->name('delete');
        });

        // Kelas
        Route::group(['prefix' => 'kelas', 'as' => 'kelas.'], function () {
            Route::get('/', [KelasController::class, 'index'])->name('index');
            Route::post('store', [KelasController::class, 'store'])->name('store');
            Route::put('{id}', [KelasController::class, 'update'])->name('update');
            Route::get('/fetch', [KelasController::class, 'fetch'])->name('fetch');
            Route::get('show/{id}', [KelasController::class, 'show'])->name('show');
            Route::get('{kode}', [KelasController::class, 'showKelas'])->name('showKelas');
            Route::delete('{id}', [KelasController::class, 'destroy'])->name('delete');
            Route::get('fetch-siswa/{kode}', [KelasController::class, 'fetchSiswa'])->name('fetchSiswa');
        });

        // Jadwal Belajar
        Route::group(['prefix' => 'jadwal', 'as' => 'jadwal.admin.'], function () {

            Route::group(['prefix' => 'pelajaran', 'as' => 'pelajaran.'], function () {
                Route::get('/', [BelajarController::class, 'index'])->name('index');
                Route::post('store', [BelajarController::class, 'store'])->name('store');
                Route::put('{id}', [BelajarController::class, 'update'])->name('update');
                Route::get('/fetch', [BelajarController::class, 'fetch'])->name('fetch');
                Route::get('{id}', [BelajarController::class, 'show'])->name('show');
                Route::delete('{id}', [BelajarController::class, 'destroy'])->name('delete');
                Route::delete('reset/{id}', [BelajarController::class, 'reset'])->name('reset');
            });

            Route::group(['prefix' => 'ujian', 'as' => 'ujian.'], function () {
                Route::get('/', [UjianController::class, 'index'])->name('index');
                Route::post('store', [UjianController::class, 'store'])->name('store');
                Route::delete('reset', [UjianController::class, 'reset'])->name('reset');
                Route::get('{id}', [UjianController::class, 'show'])->name('show');
                Route::put('{id}', [UjianController::class, 'update'])->name('update');
                Route::delete('{id}', [UjianController::class, 'destroy'])->name('delete');
            });

            // Route ini untuk menampilkan dropdown create data kelas dan mapel berdasarkan guru
            Route::get('dropdown/{id}', function ($id) {
                $kelas = DB::table('guru_kelas')
                    ->where('guru_id', $id)
                    ->join('kelas', 'guru_kelas.kelas_id', '=', 'kelas.id')
                    ->select('kelas.id', 'kelas.kode')
                    ->distinct() // agar tidak ada data yang sama
                    ->get('kelas_id');

                $mapel = DB::table('guru_mapel')
                    ->where('guru_id', $id)
                    ->join('mapels', 'guru_mapel.mapel_id', '=', 'mapels.id')
                    ->select('mapels.id', 'mapels.nama')
                    ->get('mapel_id');

                return response()->json([
                    'kelas' => $kelas,
                    'mapel' => $mapel
                ]);
            })->name('dropdown');

            // Route ini untuk menampilkan dropdown edit data kelas dan mapel berdasarkan guru
            Route::get('dropdown-edit/{id}', function ($id) {
                // hanya menerima request ajax
                if (request()->ajax()) {
                    // Query untuk menampilkan data kelas dan mapel berdasarkan guru
                    $kelas = DB::table('guru_kelas')
                        ->where('guru_id', $id)
                        ->join('kelas', 'guru_kelas.kelas_id', '=', 'kelas.id')
                        ->select('kelas.id', 'kelas.kode')
                        ->distinct() // agar tidak ada data yang sama
                        ->get('kelas_id');

                    $mapel = DB::table('guru_mapel')
                        ->where('guru_id', $id)
                        ->join('mapels', 'guru_mapel.mapel_id', '=', 'mapels.id')
                        ->select('mapels.id', 'mapels.nama')
                        ->get('mapel_id');

                    // Inisialisasi variabel output
                    $output = '';

                    // Kelas
                    $output .= '
                    <div class="form-group mb-3">
                        <label for="kelas">Kelas</label>
                        <select name="kelas" id="edit_kelas" class="form-control">';
                    foreach ($kelas as $kls) {
                        $output .= '<option value="' . $kls->id . '">' . $kls->kode . '</option>';
                    }
                    $output .= '</select>
                        <span class="invalid-feedback d-block error-text edit_kelas_error"></span>
                    </div>';

                    // mapel
                    $output .= '
                    <div class="form-group mb-3">
                        <label for="mapel">Mata Pelajaran</label>
                        <select name="mapel" id="edit_mapel" class="form-control">';
                    foreach ($mapel as $mtk) {
                        $output .= '<option value="' . $mtk->id . '">' . $mtk->nama . '</option>';
                    }
                    $output .= '</select>
                        <span class="invalid-feedback d-block error-text edit_mapel_error"></span>
                    </div>';

                    // mengembalikan output dalam bentuk json
                    return response()->json($output);
                } else {
                    // jika bukan request ajax, maka akan mengembalikan error 404
                    abort(404);
                }
            })->name('dropdownEdit');
        });
    });
});
