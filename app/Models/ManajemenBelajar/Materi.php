<?php

namespace App\Models\ManajemenBelajar;

use App\Models\KelolaPengguna\Guru;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Materi extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'materis';

    protected $fillable = [
        'judul',
        'tipe',
        'file_or_link',
        'pertemuan',
        'deskripsi',
        'guru_id',
        'kelas_id',
        'mapel_id',
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
}
