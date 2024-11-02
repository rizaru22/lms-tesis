<?php

namespace App\Models\ManajemenBelajar\Jadwal;

use App\Models\KelolaPengguna\{Guru};
use App\Models\ManajemenBelajar\{Kelas, Mapel};
use App\Models\ManajemenBelajar\Ujian\{Ujian as UjianModel};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ujian extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'jadwal_ujians';

    protected $fillable = [
        'tanggal_ujian',
        'status_ujian',
        'started_at',
        'ended_at',
        'guru_id',
        'kelas_id',
        'mapel_id',
        'ujian_id',
        'guru_can_manage'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function ujian()
    {
        return $this->belongsTo(UjianModel::class, 'ujian_id');
    }

    public function getStartedAtAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function getEndedAtAttribute($value)
    {
        return $value ? date('H:i', strtotime($value)) : null;
    }

    public function getTanggalUjianAttribute($value)
    {
        return $value ? date('d-m-Y', strtotime($value)) : null;
    }

    public function scopeAktif($query)
    {
        return $query->where('status_ujian', 'aktif');
    }

    public function scopeNonaktif($query)
    {
        return $query->where('status_ujian', 'nonaktif');
    }

    public function scopeDraft($query)
    {
        return $query->where('status_ujian', 'draft');
    }
}
