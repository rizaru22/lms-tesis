<?php

namespace App\Models\ManajemenBelajar\Ujian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KelolaPengguna\Guru;

class UjianHasil extends Model
{
    use HasFactory;

    protected $table = 'ujian_siswa_hasils';

    protected $fillable = [
        'ujian_siswa_id',
        'soal_ujian_pg_id',
        'soal_ujian_essay_id',
        'jawaban',
        'ragu',
        'status',
        'guru_id',
        'komentar_guru',
        'skor'
    ];

    public function ujianSiswa()
    {
        return $this->belongsTo(UjianSiswa::class, 'ujian_siswa_id');
    }

    public function soalUjianPg()
    {
        return $this->belongsTo(SoalUjianPg::class, 'soal_ujian_pg_id');
    }

    public function soalUjianEssay()
    {
        return $this->belongsTo(SoalUjianEssay::class, 'soal_ujian_essay_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
