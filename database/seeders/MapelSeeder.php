<?php

namespace Database\Seeders;

use App\Models\ManajemenBelajar\Mapel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mapels = collect([
            'Dasar Pemrograman',
            'Pemrograman Web',
          
        ]);

        $mapels->each(function ($mapel) {
            $arr = explode(' ', $mapel);
            $kode = '';

            foreach ($arr as $a) {
                // upper case substr
                $kode .= strtoupper(substr($a, 0, 1));
            }

            Mapel::create([
                'kode' => $kode . '-' . rand(10, 99),
                'nama' => $mapel,
                'jam' => rand(1,9),
            ]);
        });
    }
}
