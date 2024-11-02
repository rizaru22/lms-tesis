<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiProgramkeahlianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // programkeahlian
        $programkeahlian = collect([
            'Teknologi dan Informasi',
            // 'Teknik dan Bisnis Sepeda Motor ',
            // 'Teknik Ketenaga Listrikan',
        ]);

        $programkeahlian->each(function ($programkeahlian) {
            $arr = explode(' ', $programkeahlian);
            $kode = '';

            foreach ($arr as $a) {
                // upper case substr
                $kode .= strtoupper(substr($a, 0, 1));
            }

            DB::table('programkeahlian')->insert([
                'kode' => $kode . '-' . rand(10, 99),
                'nama' => $programkeahlian,
            ]);
        });

        // Prodi
        $prodi = collect([
            'Rekayasa Perangkat Lunak',
            // 'Akutansi',
            // 'Ilmu Komunikasi'
        ]);

        $prodi->each(function ($prodi) {
            DB::table('prodis')->insert([
                'nama' => $prodi,
            ]);
        });

        // Prodi programkeahlian relation
        DB::table('prodi_programkeahlian')->insert([
            [
                'prodi_id' => 1,
                'programkeahlian_id' => 1,
            ],
            // [
            //     'prodi_id' => 2,
            //     'programkeahlian_id' => 2,
            // ],
            // [
            //     'prodi_id' => 3,
            //     'programkeahlian_id' => 3,
            // ],
        ]);
    }
}
