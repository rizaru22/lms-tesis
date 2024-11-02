<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gurus')->insert([
            [
                "nama" => "Guru",
                "nip" => "11111111",
                "kode" => "GR",
                "email" => "guru@email.com",
                "user_id" => 2,
            ]
        ]);
    }
}
