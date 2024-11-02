<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrtuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ortus')->insert([
            [
                "nama" => "Ortu",
                "nik" => "88888888",
                "kode" => "OT",
                "alamat" => "Bener Meriah",
                "nohp" => "085360950382",
                "email" => "ortu@email.com",
                "user_id" => 5,
            ]
        ]);
    }
}
