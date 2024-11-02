<?php

namespace App\Models\KelolaPengguna;

use App\Models\User;
use App\Models\ManajemenBelajar\{Absen, Jadwal, Kelas, Mapel, Materi, NilaiTugas, Tugas};
use App\Models\ManajemenBelajar\Jadwal\Belajar;
use App\Models\ManajemenBelajar\Jadwal\Ujian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia;

class Ortu extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'ortus';

    protected $fillable = [
        'nama',
        'nik',
        'kode',
        'email',
        'alamat',
        'nohp',
        'user_id',
    ];

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class)->withPivot('mapel_id')->withTimestamps();
    }

    public function mapels(): BelongsToMany
    {
        return $this->belongsToMany(Mapel::class)->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwalBelajar()
    {
        return $this->hasMany(Belajar::class);
    }

    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    public function absens()
    {
        return $this->hasMany(Absen::class);
    }

    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    public function nilai_tugas()
    {
        return $this->hasMany(NilaiTugas::class);
    }

    public function ujian()
    {
        return $this->hasMany(Ujian::class);
    }

    public function absenTodayPerJadwal($jadwal_id)
    {
        return $this->hasOne(Absen::class)
                ->where('jadwal_id', $jadwal_id)
                ->whereDate('created_at', date('Y-m-d'));
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return new \Database\Factories\OrtuFactory();
    }

    public function anak()
    {
        return $this->hasMany(Siswa::class);
    }
}
