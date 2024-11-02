<?php

namespace Database\Seeders;

use App\Models\ManajemenBelajar\GuruKelas;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;

class JadwalBelajarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create random jadwal
        $kelas = GuruKelas::all();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'];

        foreach ($kelas as $key => $val) {
            $hour = rand(00, 23);
            $minute = rand(00, 59);
            $started_at_hour = $hour + 2 > 23 ? 23 : $hour + 2; // tambah 2 jam
            $started_at_minute = $minute;

            ($hour < 10) ? $hour = '0' . $hour : $hour; // jika jam < 10, tambahkan 0 di depannya
            ($minute < 10) ? $minute = '0' . $minute : $minute; // jika menit < 10, tambahkan 0 di depannya

            ($started_at_hour < 10) ? // jika jam < 10, tambahkan 0 di depannya
                $started_at_hour = '0' . $started_at_hour :
                $started_at_hour;

            ($started_at_minute < 10) ? // jika menit < 10, tambahkan 0 di depannya
                $started_at_minute = '0' . $started_at_minute :
                $started_at_minute;

            JadwalBelajar::create([
                'hari' => $hari[array_rand($hari)],
                'started_at' => $hour . ':' . $minute,
                'ended_at' => $started_at_hour . ':' . $started_at_minute,
                'guru_id' => $val->guru_id,
                // 'kepsek_id' => $val->kepsek_id,
                // 'ortu_id' => $val->ortu_id,
                'kelas_id' => $val->kelas_id,
                'mapel_id' => $val->mapel_id,
            ]);
        }
    }
}
