<?php

namespace App\Models\ManajemenBelajar\Ujian;

use App\Http\Controllers\Admin\ManajemenBelajar\MapelController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\{Kelas, Mapel};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ujian extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'ujians';

    protected $fillable = [
        'judul',
        'deskripsi',
        'durasi_ujian',
        'semester',
        'tipe_soal',
        'tipe_ujian',
        'random_soal',
        'random_jawaban',
        'lihat_hasil',
        'jadwal_ujian_id',
    ];

    public function jadwalUjian()
    {
        return $this->belongsTo(JadwalUjian::class);
    }

    public function ujianSiswa()
    {
        return $this->hasMany(UjianSiswa::class);
    }

    public function soalUjianPg()
    {
        return $this->hasMany(SoalUjianPg::class);
    }

    public function soalUjianEssay()
    {
        return $this->hasMany(SoalUjianEssay::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function getTipeSoalAttribute($value)
    {
        return $value == 'essay' ? 'Essay' : 'Pilihan Ganda';
    }
}
