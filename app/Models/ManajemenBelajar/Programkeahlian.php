<?php

namespace App\Models\ManajemenBelajar;

use App\Models\KelolaPengguna\Siswa;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programkeahlian extends Model
{
    use HasFactory;

    protected $table = 'programkeahlian';

    protected $fillable = [
        'nama',
        'kode',
    ];

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'programkeahlian_id');
    }

    public function prodi()
    {
        return $this->hasMany(Prodi::class);
    }
}
