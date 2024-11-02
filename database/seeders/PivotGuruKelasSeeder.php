<?php

namespace Database\Seeders;

use App\Models\KelolaPengguna\Guru;
use App\Models\ManajemenBelajar\Kelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PivotGuruKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kelas = Kelas::all();
        $guru = Guru::all();

        foreach ($guru as $dsn) {
            $dsn->kelas()->attach(
                $kelas->random(rand(1, $kelas->count()))->pluck('id')->take(3)->toArray()
            );

            foreach ($kelas as $kls) {
                $dsn->kelas()->updateExistingPivot(
                    ['kelas_id' => $kls->id],
                    ['mapel_id' => $dsn->mapels->random()->id],
                );
            }
        }
    }
}
