<?php

namespace App\Models\KelolaPengguna;

use App\Models\User;
use App\Models\ManajemenBelajar\{Kelas, Programkeahlian, Absen, Tugas};
use App\Models\ManajemenBelajar\Ujian\{ UjianSiswa};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Siswa extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'siswas';

    protected $fillable = [
        'nama',
        'nis',
        'email',
        'user_id',
        'programkeahlian_id',
        'kelas_id',
        'user_id',
        'ortu_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function programkeahlian()
    {
        return $this->belongsTo(Programkeahlian::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function siswa_kelas()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas')->withTimestamps();
    }

    public function getCreatedAtAttribute()
    {
        return Carbon::parse($this->attributes['created_at'])->translatedFormat('l, d F Y');
    }

    public function absens()
    {
        return $this->hasMany(Absen::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function ujianSiswa()
    {
        return $this->hasMany(UjianSiswa::class);
    }

    public function presensiHariIni()
    {
        return $this->hasOne(Absen::class)
            ->whereNotNull('siswa_id')
            ->where('parent', '!=', 0)
            ->where('status', 1)
            ->whereDate('created_at', date('Y-m-d'));
    }

    public function presensi($jadwal_id)
    {
        return $this->absens()->whereNotNull('siswa_id')
            ->where('parent', '!=', 0)
            ->where('status', 1)
            ->where('jadwal_id', $jadwal_id)
            ->whereDate('created_at', date('Y-m-d'));
    }

    public function mengerjakanUjian($ujian_id)
    {
        return $this->ujianSiswa()
            ->where('ujian_id', $ujian_id)
            ->where('status', 1);
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new \Database\Factories\SiswaFactory();
    }

    public function ortu()
    {
        return $this->belongsTo(Ortu::class);
    }
}
