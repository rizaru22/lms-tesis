<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            "name" => "Admin",
            "email" => "admin@mail.com",
            "no_induk" => "00000000",
            "foto" => "avatar.png",
            "password" => bcrypt("00000000"),
            "email_verified_at" => now(),
        ])->assignRole('admin');

        User::create([
            "name" => "Guru",
            "email" => "guru@mail.com",
            "no_induk" => "11111111",
            "foto" => "avatar.png",
            "password" => bcrypt("11111111"),
            "email_verified_at" => now(),
        ])->assignRole('guru');

        User::create([
            "name" => "Siswa",
            "email" => "siswa@mail.com",
            "no_induk" => "22222222",
            "foto" => "avatar.png",
            "password" => bcrypt("22222222"),
            "email_verified_at" => now(),
        ])->assignRole('siswa');

       User::create([
            "name" => "Ortu",
            "email" => "ortu@mail.com",
            "no_induk" => "88888888",
            "foto" => "avatar.png",
            "password" => bcrypt("88888888"),
            "email_verified_at" => now(),
        ])->assignRole('ortu');


    }
}
