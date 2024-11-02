<?php

namespace App\Models\ManajemenBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KelolaPengguna\{Siswa};
use App\Models\ManajemenBelajar\Jadwal\{Belajar as Jadwal};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Tugas extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [ 'jadwal_id', 'mapel_id', 'parent', 'judul', 'tipe', 'file_or_link', 'pertemuan', 'deskripsi', 'pengumpulan', 'sudah_dinilai', 'guru_id', 'Siswa_id'];

    protected $with = ['nilaiTugas'];

    public function Siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function parent()
    {
        return $this->belongsTo(Self::class, 'parent');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function nilaiTugas()
    {
        return $this->hasOne(NilaiTugas::class, 'tugas_id', 'id');
    }

    public function kelas()
    {
        return $this->hasOneThrough(Kelas::class, Jadwal::class, 'id', 'id', 'jadwal_id', 'kelas_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
