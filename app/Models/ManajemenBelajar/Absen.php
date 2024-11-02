<?php

namespace App\Models\ManajemenBelajar;
use App\Models\KelolaPengguna\{Siswa, Guru};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ManajemenBelajar\Jadwal\{Belajar as Jadwal};
use Spatie\MediaLibrary\InteractsWithMedia;

class Absen extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'absens';

    protected $fillable = [
        'siswa_id',
        'jadwal_id',
        'guru_id',
        'parent',
        'status',
        'rangkuman',
        'berita_acara',
        'pertemuan'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function getTanggalAttribute($value)
    {
        return Carbon::parse($this->attributes['created_at'])->translatedFormat('l, d F Y');
    }


}
