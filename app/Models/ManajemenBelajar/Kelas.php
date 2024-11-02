<?php

namespace App\Models\ManajemenBelajar;

use App\Models\KelolaPengguna\{Siswa, Guru};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManajemenBelajar\Jadwal\Belajar as Jadwal;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';

    protected $fillable = [
        'kode',
    ];

    public function sw()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_kelas')->withTimestamps();
    }

    public function gurus()
    {
        return $this->belongsToMany(Guru::class)->withTimestamps();
    }

    public function jadwal()
    {
        return $this->hasOne(Jadwal::class);
    }
}
