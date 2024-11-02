<?php

namespace App\Models\ManajemenBelajar\Jadwal;

use App\Models\ManajemenBelajar\{Absen, Kelas, Mapel, Materi, NilaiTugas, Tugas};
use App\Models\KelolaPengguna\{Guru, User};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Belajar extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'jadwals';

    protected $fillable = [
        'hari',
        'started_at',
        'ended_at',
        'kelas_id',
        'mapel_id',
        'guru_id',
    ];

    public $timestamps = false;

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function absens()
    {
        return $this->hasMany(Absen::class, 'jadwal_id');
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class, 'jadwal_id');
    }

    public function jadwalOrtu()
    {
        return $this->hasMany(\App\Models\Ortu::class);
    }
}
