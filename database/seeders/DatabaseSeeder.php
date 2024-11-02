<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\KelolaPengguna\Guru;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolePermissionSeeder::class,
            KelasSeeder::class,
            MapelSeeder::class,
            UserSeeder::class,
            // GuruSeeder::class,
            // KepsekSeeder::class,
            OrtuSeeder::class,
            ProdiProgramkeahlianSeeder::class,
            SiswaSeeder::class,
            PivotSiswaKelasSeeder::class,
            PivotGuruMapelSeeder::class,
            PivotGuruKelasSeeder::class,
            JadwalBelajarSeeder::class,
            JadwalUjianSeeder::class,
        ]);

    }
}
