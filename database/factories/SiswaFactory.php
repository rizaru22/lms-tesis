<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\ManajemenBelajar\{Programkeahlian,Kelas};
use App\Models\KelolaPengguna\Siswa;
use App\Models\RolePermission\Role;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KelolaPengguna\siswa>
 *
 * Jalankan di php artisan tinker, dengan cara:
 * siswa::factory()->count(10)->create();
 */
class SiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $noInduk = $this->faker->unique()->numerify('########');
        $nama = $this->faker->name;
        $email = $noInduk . '@mail.com';
        $foto = 'avatar.png';

        $user = User::factory()->create([
            'name' => $nama,
            'no_induk' => $noInduk,
            'email' => $email,
            'foto' => $foto,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => bcrypt($noInduk),
        ]);

        $user->roles()->attach(Role::where('name', 'siswa')->first());

        return [
            'nama' => $nama,
            'nis' => $noInduk,
            'email' => $email,
            'user_id' => $user->id,
            'programkeahlian_id' => Programkeahlian::all()->random()->id,
            'kelas_id' => Kelas::all()->random()->id,
        ];
    }
}
