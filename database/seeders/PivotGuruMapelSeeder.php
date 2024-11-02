<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KelolaPengguna\Guru;
use App\Models\ManajemenBelajar\Mapel;

class PivotGuruMapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mapel = Mapel::all();

        foreach (Guru::all() as $d) {
            $d->mapels()->attach(
                $mapel->random(rand(1, $mapel->count()))->pluck('id')->take(3)->toArray()
            );
        }
    }
}
