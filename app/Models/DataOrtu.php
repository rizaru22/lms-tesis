<?php

namespace App\Models;
use App\Models\ManajemenBelajar\Jadwal\Belajar;

trait DataOrtu
{

        public function getJadwalOrtuSiswa()
        {
            $ortuId = \Auth::user()->ortu->id;
            $mapel_ortu = MapelOrtu::where('ortu_id', $ortuId)
                ->get()->pluck('mapel_id')->toArray();
            if(!is_null($mapel_ortu)) {
                return Belajar::whereIn('mapel_id', $mapel_ortu)->get();
            }
            throw new \Exception('Terjadi error');
        }

}