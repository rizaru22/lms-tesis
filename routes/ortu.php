<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Ortu\DashboardController;

use App\Http\Controllers\Guru\ManajemenBelajar\Laporan\{
    AbsenController as LaporanAbsen,
    TugasController as LaporanTugas,
    UjianController as LaporanUjian,
    NilaiController as LaporanNilai
};

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('ortu.dashboard');
    // Route::get('absen', [OrtuController::class, 'absen'])->name('dashboard.ortu.absen');

    Route::group(['prefix' => 'manajemen-pelajaran', 'as' => 'manajemen.pelajaran.'], function () {
        Route::group(['prefix' => 'laporan', 'as' => 'laporan.ortu.'], function () {
            Route::group(['prefix' => 'absen'], function () {
                Route::get('', [LaporanAbsen::class, 'absen'])->name('absen');
                Route::get('render-data-absen', [LaporanAbsen::class, 'fetchDataAbsen'])->name('fetch.data.absen');
                Route::get('fetch-table-absen', [LaporanAbsen::class, 'tableDataAbsen'])->name('fetch.table.absen');
                Route::get('exports/{kelas}/{mapel}', [LaporanAbsen::class, 'exports'])->name('exports.absen');
            });
        });
    });
});
