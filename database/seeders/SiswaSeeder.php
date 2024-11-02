<?php

namespace Database\Seeders;

use App\Models\KelolaPengguna\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Siswa::create([
            "nama" => "Siswa",
            "nis" => "22222222",
            "email" => "siswa@email.com",
            "user_id" => 3,
            "Programkeahlian_id" => 1,
            "kelas_id" => 1,
            "ortu_id"=>1
        ]);
    }
}
