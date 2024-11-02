<?php

namespace App\Models\ManajemenBelajar;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;

class NilaiTugas extends Model
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'nilai_tugas';

    protected $fillable = [
        'nilai',
        'komentar',
        'tugas_id',
        'guru_id',
        'siswa_id',
    ];
}
