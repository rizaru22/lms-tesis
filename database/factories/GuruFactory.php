<?php

namespace Database\Factories;

use App\Models\KelolaPengguna\Guru;
use App\Models\ManajemenBelajar\{Mapel, Kelas};
use App\Models\ManajemenBelajar\Jadwal\Belajar;
use App\Models\RolePermission\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KelolaPengguna\Guru>
 */
class GuruFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Guru::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
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
            'password' => bcrypt($noInduk)
        ]);

        $user->roles()->attach(Role::where('name', 'guru')->first());

        return [
            'nama' => $nama,
            'nip' => $noInduk,
            'kode' => Str::random(6),
            'email' => $email,
            'user_id' => $user->id,
        ];
    }

    public function withRelation()
    {
        $kelas = Kelas::all();
        $mapel = Mapel::all();

        return $this->afterCreating(function (Guru $guru) use ($mapel, $kelas) {
            if ($mapel->count() > 0) {
                $guru->mapels()->attach(
                    $mapel->filter(function ($mapel) use ($guru) {
                        return !$guru->mapels->contains('id', $mapel->id);
                    })
                    ->random(rand(1, $mapel->count()))
                    ->pluck('id')
                    ->toArray()
                );
            }

            if ($kelas->count() > 0) {
                $guru->kelas()->attach(
                    $kelas->random(rand(1, $kelas->count())
                )->pluck('id')->toArray());
            }
        });
    }
}
