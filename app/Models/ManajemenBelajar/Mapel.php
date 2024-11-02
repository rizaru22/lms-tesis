<?php

namespace App\Models\ManajemenBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;

    protected $table = 'mapels';

    protected $fillable = [
        'kode',
        'nama',
        'jam',
    ];

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel');
    }

    public function materis()
    {
        return $this->hasMany(Materi::class);
    }
}
