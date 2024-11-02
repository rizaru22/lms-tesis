<?php

namespace App\Models\ManajemenBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Prodi extends Model
{
    use HasFactory;

    protected $table = 'prodis';

    protected $fillable = [
        'nama',
    ];

    public function siswas()
    {
        return $this->hasMany(Siswa::class, 'prodi_id');
    }

    public function prodi()
    {
        return $this->belongsToMany(Prodi::class, 'prodi_programkeahlian')->withTimestamps();
    }
}
