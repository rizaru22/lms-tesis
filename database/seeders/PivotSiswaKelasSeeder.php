<?php

namespace Database\Seeders;

use App\Models\ManajemenBelajar\Kelas;
use App\Models\KelolaPengguna\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PivotSiswaKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siswa = Siswa::all();

        foreach ($siswa as $sw) {
            $sw->siswa_kelas()->attach($sw->kelas_id);
        }
    }
}
