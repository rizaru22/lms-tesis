<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Guru\DashboardController;

use App\Http\Controllers\Guru\ManajemenBelajar\{
    UjianController,
    Jadwal\BelajarController
};
use App\Http\Controllers\Guru\ManajemenBelajar\Ujian\{
    PilihanGandaController,
    EssayController
};
use App\Http\Controllers\Guru\ManajemenBelajar\Laporan\{
    AbsenController as LaporanAbsen,
    TugasController as LaporanTugas,
    UjianController as LaporanUjian,
    NilaiController as LaporanNilai
};
use App\Http\Controllers\Guru\ManajemenBelajar\{
    KelasController,
    TugasController,
    MateriController,
    AbsenController
};

Route::group(['middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('guru.dashboard');

    Route::group(['prefix' => 'manajemen-pelajaran', 'as' => 'manajemen.pelajaran.'], function () {
        // Jadwal Belajar
        Route::group(['prefix' => 'jadwal', 'as' => 'jadwal.guru.'], function () {
            Route::group(['prefix' => 'pelajaran', 'as' => 'pelajaran.'], function() {
                Route::get('/', [BelajarController::class, 'index'])->name('index');
            });

            // Ujian
            Route::group(['prefix' => 'ujian', 'as' => 'ujian.'], function() {
                Route::get('/', [UjianController::class, 'index'])->name('index');
                Route::get('show/{id}', [UjianController::class, 'show'])->name('show');
                Route::get('get-nilai-essay-siswa/{jadwalId}', [UjianController::class, 'getNilaiEssaySiswa'])
                    ->name('getNilaiEssaySiswa');
                Route::put('simpan-nilai-essay-siswa', [UjianController::class, 'simpanNilaiEssaySiswa'])
                    ->name('simpanNilaiEssaySiswa');

                // Soal Pilihan Ganda
                Route::group(['prefix' => 'soal/pg', 'as' => 'soal.pg.'], function () {
                    Route::get('{id}', [PilihanGandaController::class, 'create'])->name('create');
                    Route::get('detail-nilai/{jadwalId}', [PilihanGandaController::class, 'detailNilai'])->name('detailNilai');
                    Route::post('store', [PilihanGandaController::class, 'store'])->name('store');
                    Route::get('list/{id}', [PilihanGandaController::class, 'list'])->name('list');
                    Route::post('import', [PilihanGandaController::class, 'import'])->name('import');
                    Route::get('edit/{id}', [PilihanGandaController::class, 'edit'])->name('edit');
                    Route::get('fetch-soal/{jadwalId}', [PilihanGandaController::class, 'fetch'])->name('fetch');
                    Route::put('update/{id}', [PilihanGandaController::class, 'update'])->name('update');
                    Route::get('nomer-soal/{jadwalId}', [PilihanGandaController::class, 'getNomer'])->name('getNomer');
                    Route::delete("hapus-soal/{jadwalId}", [PilihanGandaController::class, 'removeColumn'])->name('removeColumn');
                });

                // Soal Essay
                Route::group(['prefix' => 'soal/essay', 'as' => 'soal.essay.'], function () {
                    Route::get('create/{jadwalId}', [EssayController::class, 'create'])->name('create');
                    Route::post('store', [EssayController::class, 'store'])->name('store');
                    Route::get('list/{jadwalId}', [EssayController::class, 'list'])->name('list');
                    Route::post('import', [EssayController::class, 'import'])->name('import');
                    Route::get('edit/{jadwalId}', [EssayController::class, 'edit'])->name('edit');
                    Route::get('fetch-soal/{jadwalId}', [EssayController::class, 'fetch'])->name('fetch');
                    Route::put('update/{jadwalId}', [EssayController::class, 'update'])->name('update');
                    Route::get('nomer-soal/{jadwalId}', [EssayController::class, 'getNomer'])->name('getNomer');
                    Route::get('lihat-hasil-siswa/{jadwalId}', [EssayController::class, 'lihatHasilSiswa'])->name('lihatHasilSiswa');
                    Route::delete("hapus-soal/{jadwalId}", [EssayController::class, 'removeColumn'])->name('removeColumnSoal');
                });
            });
        });

        // Laporan
        Route::group(['prefix' => 'laporan', 'as' => 'laporan.guru.'], function () {
            Route::group(['prefix' => 'absen'], function () {
                Route::get('', [LaporanAbsen::class, 'absen'])->name('absen');
                Route::get('render-data-absen', [LaporanAbsen::class, 'fetchDataAbsen'])->name('fetch.data.absen');
                Route::get('fetch-table-absen', [LaporanAbsen::class, 'tableDataAbsen'])->name('fetch.table.absen');
                Route::get('exports/{kelas}/{mapel}', [LaporanAbsen::class, 'exports'])->name('exports.absen');
            });

            Route::group(['prefix' => 'nilai-tugas'], function () {
                Route::get('', [LaporanTugas::class, 'nilaiTugas'])->name('nilai.tugas');
                Route::get('render-data-nilai', [LaporanTugas::class, 'fetchDataNilai'])->name('fetch.data.nilai.tugas');
                Route::get('fetch-table-nilai', [LaporanTugas::class, 'tableDataNilai'])->name('fetch.table.nilai.tugas');
                Route::get('exports/{kelas}/{mapel}', [LaporanTugas::class, 'exportNilaiTugas'])->name('exports.nilaiTugas');
            });

            Route::group(['prefix' => 'nilai-ujian'], function () {
                Route::get('', [LaporanUjian::class, 'nilai_ujian'])->name('nilai.ujian');
                Route::get('fetch-data-nilai', [LaporanUjian::class, 'fetchDataNilai'])->name('fetch.data.nilai.ujian');
                Route::get('fetch-table-nilai', [LaporanUjian::class, 'tableDataNilai'])->name('fetch.table.nilai.ujian');
                Route::get('exports/{kelas}/{mapel}', [LaporanUjian::class, 'exports'])->name('exports.nilaiUjian');
            });

            Route::group(['prefix' => 'nilai'], function () {
                Route::get('', [LaporanNilai::class, 'nilai'])->name('nilai');
                Route::get('fetch-data-nilai', [LaporanNilai::class, 'fetchDataNilai'])->name('fetch.data.nilai');
                Route::get('fetch-table-nilai', [LaporanNilai::class, 'tableDataNilai'])->name('fetch.table.nilai');
                Route::get('exports/{kelas}/{mapel}', [LaporanNilai::class, 'exports'])->name('exports.nilai');
            });
        });

        // Kelas
        Route::group(['prefix' => 'kelas', 'as' => 'kelas.guru.'], function () {
            Route::get('{jadwalId}', [KelasController::class, 'index'])->name('index');
            Route::post('storeKehadiran', [KelasController::class, 'storeKehadiran'])->name('storeKehadiran');
            Route::get('info-kehadiran-sw/{jadwalId}', [KelasController::class, 'infoKehadiranMhs'])->name('infoKehadiranSw');
        });

        // Absen
        Route::group(['prefix' => 'absen', 'as' => 'absen.guru.'], function () {
            Route::get('/', [AbsenController::class, 'index'])->name('index');
            Route::get('create/{jadwalId}', [AbsenController::class, 'create'])->name('create');
            Route::post('store', [AbsenController::class, 'store'])->name('store');
            Route::get('edit/{absen}', [AbsenController::class, 'edit'])->name('edit');
            Route::put('{absen}', [AbsenController::class, 'update'])->name('update');
            Route::delete('{absen}', [AbsenController::class, 'destroy'])->name('delete');
        });

        // Materi
        Route::group(['prefix' => 'materi', 'as' => 'materi.guru.'], function () {
            Route::get('materi/{jadwalId}', [MateriController::class, 'index'])->name('index');
            Route::get('{jadwalId}', [MateriController::class, 'create'])->name('create');
            Route::post('store', [MateriController::class, 'store'])->name('store');
            Route::get('edit/{materi}', [MateriController::class, 'edit'])->name('edit');
            Route::post('{materi}', [MateriController::class, 'update'])->name('update');
            Route::delete('{materi}', [MateriController::class, 'destroy'])->name('delete');
            Route::post('store-slide', [MateriController::class, 'storeSlide'])->name('storeSlide');
            Route::get('edit-slide/{slide}', [MateriController::class, 'editSlide'])->name('editSlide');
            Route::put('update-slide/{slide}', [MateriController::class, 'updateSlide'])->name('updateSlide');
        });

        // Tugas
        Route::group(['prefix' => 'tugas', 'as' => 'tugas.guru.'], function () {
            Route::get('{jadwalId}', [TugasController::class, 'index'])->name('index');
            Route::get('create/{jadwalId}', [TugasController::class, 'create'])->name('create');
            Route::post('store', [TugasController::class, 'store'])->name('store');
            Route::get('table-sw-show/{tugasId}', [TugasController::class, 'tableSwShow'])->name('table.sw.show');
            Route::get('show/{tugasId}', [TugasController::class, 'show'])->name('show');
            Route::get('edit/{tugasId}', [TugasController::class, 'edit'])->name('edit');
            Route::put('{tugasId}', [TugasController::class, 'update'])->name('update');
            Route::delete('{tugasId}', [TugasController::class, 'destroy'])->name('delete');
            Route::get('show-nilai/{tugasId}', [TugasController::class, 'showNilai'])->name('showNilai');
            Route::post('store-nilai/{tugasId}', [TugasController::class, 'storeNilai'])->name('storeNilai');
        });
    });
});
