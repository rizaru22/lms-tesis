<?php

namespace App\Models\ManajemenBelajar\Ujian;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KelolaPengguna\Siswa;

class UjianSiswa extends Model
{
    use HasFactory;

    protected $table = 'ujian_siswas';

    public $timestamps = false;

    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'started_at',
        'ended_at',
        'nilai',
        'user_agent',
        'status',
        'ip_address',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ujianHasil()
    {
        return $this->hasMany(UjianHasil::class, 'ujian_siswa_id');
    }
}
