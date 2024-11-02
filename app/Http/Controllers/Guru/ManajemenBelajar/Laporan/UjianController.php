<?php

namespace App\Http\Controllers\Guru\ManajemenBelajar\Laporan;

use App\Exports\Guru\Laporan\NilaiUjianExports;
use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Siswa;
use Illuminate\Http\Request;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\ManajemenBelajar\Mapel;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class UjianController extends Controller
{
    public function nilai_ujian()
    {
        $kelas_id = Auth::user()->guru->kelas->pluck('id');
        $mapel_id = Auth::user()->guru->mapels->pluck('id');

        $jadwals = JadwalUjian::join('kelas', 'kelas.id', '=', 'jadwal_ujians.kelas_id')
            ->join('mapels', 'mapels.id', '=', 'jadwal_ujians.mapel_id')
            ->where('ujian_id', '!=', null)
            ->where('guru_id', Auth::user()->guru->id)
            ->whereIn('kelas_id', $kelas_id)
            ->whereIn('mapel_id', $mapel_id)
            ->select('kelas_id', 'mapel_id', 'guru_id', 'kelas.kode', 'mapels.kode as mapelKode', 'mapels.nama')
            ->orderBy('kelas.kode', 'ASC')
            ->groupBy(['kelas_id', 'mapel_id', 'guru_id', 'kode', 'mapelKode', 'nama'])
            ->get();

        return view('dashboard.guru.laporan.nilai_ujian.index', [
            'jadwals' => $jadwals,
        ]);
    }

    public function fetchDataNilai(Request $request)
    {
        $key = explode('_', $request->key_id);
        $kelas_id = $key[0];
        $mapel_id = $key[1];
        $guru_id = $key[2];

        $jadwals = JadwalUjian::with(['kelas', 'mapel', 'ujian'])
            ->where('kelas_id', $kelas_id)
            ->where('mapel_id', $mapel_id)
            ->where('guru_id', $guru_id)
            ->where('ujian_id', '!=', null)
            ->get();

        $output = view('dashboard.guru.laporan.nilai_ujian._data-nilai', [
            'data' => $jadwals->first(),
            'kelas_id' => $kelas_id,
            'mapel_id' => $mapel_id,
        ])->render();

        return response()->json($output);
    }

    public function tableDataNilai(Request $request)
    {
        $key = explode('_', $request->key_id);
        $kelas_id = $key[0];
        $mapel_id = $key[1];
        $guru_id = $key[2];

        $siswas = Siswa::with(['ujianSiswa' => function($q) {
            $q->with('ujian', 'ujian.jadwalUjian');
        }, 'user'])
            ->where('kelas_id', $kelas_id)
            ->orderBy('nama', 'ASC')
            ->get();

        $data = $siswas->transform(function ($item) use ($kelas_id, $mapel_id, $guru_id) {
            $item->kelas_id = $kelas_id;
            $item->mapel_id = $mapel_id;
            $item->guru_id = $guru_id;
            $item->foto = $item->user->foto;

            return $item;
        });

        if ($request->ajax()) {
            $table = datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('siswa', function ($data) {
                    if (file_exists('assets/image/users/' . $data->foto)) {
                        $avatar = asset('assets/image/users/' . $data->foto);
                    } else {
                        $avatar = asset('assets/image/avatar.png');
                    }

                    return '
                        <a href="javascript:void(0)" class="d-flex align-items-center" style="cursor: default">
                            <img src="' . $avatar . '" width="40" class="avatar rounded-circle me-3">
                            <div class="d-block ml-3">
                                <span class="fw-bold name-user">' . $data->nama . '</span>
                                <div class="small text-secondary">' . $data->nis . '</div>
                            </div>
                        </a>
                    ';
                });

            for ($i = 1; $i <= 8; $i++) { // Looping semester 1 - 8
                // Kolom semester 1 - 8
                $table->addColumn("s$i", function ($data) use ($i, $kelas_id, $mapel_id) {
                    // menampilkan nilai uts dan uas berdasarkan semester dengan kondisi jika nilai tidak kosong
                    $ujianSiswa = $data->ujianSiswa->where('ujian.semester',  $i)
                        ->where('ujian.jadwalUjian.kelas_id', $kelas_id)
                        ->where('ujian.jadwalUjian.mapel_id', $mapel_id)
                        ->where('nilai', '!=', null);

                    if ($ujianSiswa) { // Jika nilai tidak kosong
                        $uas = $ujianSiswa->where('ujian.tipe_ujian', 'uas')->first();
                        $uts = $ujianSiswa->where('ujian.tipe_ujian', 'uts')->first();

                        $nilai = "<div class='d-flex flex-column align-items-end'>";
                            if ($uts && $uas) {  // Jika nilai uts dan uas tidak kosong
                                $nilai .= "
                                    <div>$uts->nilai <span class='badge badge-success'>UTS</span></div>
                                    <div>$uas->nilai <span class='badge badge-info'>UAS</span></div>
                                ";
                            } else if ($uts) { // Jika nilai uts tidak kosong
                                $nilai .= "
                                    <div> $uts->nilai <span class='badge badge-success'>UTS</span></div>
                                ";
                            } else if ($uas) { // Jika nilai uas tidak kosong
                                $nilai .= "
                                    <div> $uas->nilai <span class='badge badge-info'>UAS</span></div>
                                ";
                            } else { // Jika nilai uts dan uas kosong
                                $nilai .= '-';
                            }
                        $nilai .= "</div>";

                        return $nilai;
                    } else {
                        return '-';
                    }
                });
            }

            $semester = array_fill(0, 8, ''); // Membuat array kosong dengan jumlah 8
            foreach ($semester as $key => $value) {
                $semester[$key] = "s" . ($key + 1); // Menambahkan prefix s pada array
            }

            return $table->rawColumns(array_merge(['siswa'], $semester))
                ->make(true);
        }
    }

    public function exports($kelas_id, $mapel_id)
    {
        $mapel = Mapel::find(decrypt($mapel_id));

        $siswa = Siswa::with(['ujianSiswa.ujian', 'user'])
            ->where('kelas_id', decrypt($kelas_id))
            ->orderBy('nama', 'ASC')
            ->get();

        foreach ($siswa as $i => $sw) {
            $dataSw[$i] = [
                'nama' => $sw->nama,
                'nis' => $sw->nis,
                'kelas' => $sw->kelas->kode,
                'mapel' => $mapel->nama,
                'programkeahlian' => $sw->programkeahlian->nama,
            ];

            for ($j = 1; $j <= 8; $j++) {
                $ujianSiswa = $sw->ujianSiswa->where('ujian.semester',  $j)
                    ->where('ujian.jadwalUjian.kelas_id', decrypt($kelas_id))
                    ->where('ujian.jadwalUjian.mapel_id', decrypt($mapel_id))
                    ->where('nilai', '!=', null);

                if ($ujianSiswa) {
                    $uas = $ujianSiswa->where('ujian.tipe_ujian', 'uas')->first();
                    $uts = $ujianSiswa->where('ujian.tipe_ujian', 'uts')->first();

                    if ($uts && $uas) {
                        $dataSw[$i]["s$j"] = "$uts->nilai (UTS) $uas->nilai (UAS)";
                    } else if ($uts) {
                        $dataSw[$i]["s$j"] = "$uts->nilai (UTS)";
                    } else if ($uas) {
                        $dataSw[$i]["s$j"] = "$uas->nilai (UAS)";
                    } else {
                        $dataSw[$i]["s$j"] = '-';
                    }
                } else {
                    $dataSw[$i]["s$j"] = '-';
                }
            }
        }

        ob_end_clean();

        $fileName = 'laporan_nilai_ujian_kelas_' . $dataSw[0]['kelas'] .
            '_' . Str::slug($dataSw[0]['mapel'], '_');

        return Excel::download(new NilaiUjianExports(collect($dataSw)), $fileName . '.xlsx');
    }
}
