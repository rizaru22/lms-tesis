<?php

namespace App\Imports\Admin;

use App\Models\KelolaPengguna\Siswa;
use App\Models\ManajemenBelajar\Programkeahlian;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Facades\Excel;

class SiswaImport implements WithHeadingRow, ToModel
{
    /**
     * Import data guru
     *
     * @param  mixed $file
     * @return void
     */
    public function excel($file) {
        Excel::import(new SiswaImport, $file);
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $project_name = Str::slug(config('app.name'), '-');

        $sw_nama = $row['nama'];
        $sw_nis = $row['nis'];
        $sw_email = $row['email'] ?? "{$sw_nis}@{$project_name}.com";
        $sw_kelas = Kelas::where('kode', $row['kode_kls'])->first();
        $sw_programkeahlian = Programkeahlian::where('kode', $row['kode_pk'])->first();
        $check_nis = User::where('no_induk', $sw_nis)->first();

        // check if kelas exists
        if (!$sw_kelas) return abort(500, "Kelas {$row['kode_kls']} tidak ditemukan, silahkan cek kembali.");

        // check if programkeahlian exists
        if (!$sw_programkeahlian) return abort(500, "Program keahlian {$row['kode_pk']} tidak ditemukan, silahkan cek kembali.");

        // Check if nis already exists
        if ($check_nis) return abort(500, "NIS {$sw_nis} sudah terdaftar, mohon diganti atau hapus nis tersebut.");

        $user = User::create([
            'name' => $sw_nama,
            'no_induk' => $sw_nis,
            'email' => $sw_email,
            'password' => bcrypt($sw_nis),
            'foto' => 'avatar.png',
        ])->assignRole('siswa');

        $sw = Siswa::create([
            'nis' => $sw_nis,
            'nama' => $sw_nama,
            'email' => $sw_email,
            'user_id' => $user->id,
            'programkeahlian_id' => $sw_programkeahlian->id,
            'kelas_id' => $sw_kelas->id,
        ]);

        $sw->siswa_kelas()->attach($sw->kelas_id);
    }
}
