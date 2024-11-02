<?php

namespace Database\Factories;

use App\Models\KelolaPengguna\Ortu;
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
class OrtuFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ortu::class;

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

        $user->roles()->attach(Role::where('name', 'ortu')->first());

        return [
            'nama' => $nama,
            'nik' => $noInduk,
            'kode' => Str::random(6),
            'email' => $email,
            'user_id' => $user->id,
        ];
    }

    public function withRelation()
    {
        $kelas = Kelas::all();
        $mapel = Mapel::all();

        return $this->afterCreating(function (Ortu $ortu) use ($mapel, $kelas) {
            if ($mapel->count() > 0) {
                $ortu->mapels()->attach(
                    $mapel->filter(function ($mapel) use ($ortu) {
                        return !$ortu->mapels->contains('id', $mapel->id);
                    })
                    ->random(rand(1, $mapel->count()))
                    ->pluck('id')
                    ->toArray()
                );
            }

            if ($kelas->count() > 0) {
                $ortu->kelas()->attach(
                    $kelas->random(rand(1, $kelas->count())
                )->pluck('id')->toArray());
            }
        });
    }
}
