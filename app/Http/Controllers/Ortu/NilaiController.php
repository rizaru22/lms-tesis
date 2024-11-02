<?php

namespace App\Http\Controllers\Guru\ManajemenBelajar\Laporan;

use App\Exports\Guru\Laporan\NilaiExport;
use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Siswa;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\ManajemenBelajar\Mapel;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    public function nilai()
    {
        $jadwals = JadwalBelajar::where('guru_id', Auth::user()->guru->id)
            ->whereHas('kelas.siswa')
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->get();

        return view('dashboard.guru.laporan.nilai.index', [
            'jadwals' => $jadwals,
        ]);
    }

    public function fetchDataNilai(Request $request)
    {
        if (request()->ajax())
        {
            $key = explode('_', $request->key_id);
            $kelas_id = $key[0];
            $mapel_id = $key[1];
            $guru_id = $key[2];

            $jadwal = JadwalBelajar::where('kelas_id', $kelas_id)
                ->where('mapel_id', $mapel_id)
                ->first();

            $output = view('dashboard.guru.laporan.nilai._data-nilai', [
                'jadwal' => $jadwal,
                'kelas_id' => $kelas_id,
                'mapel_id' => $mapel_id,
            ])->render();

            return response()->json($output);
        } else {
            abort(404);
        }
    }

    public function tableDataNilai(Request $request)
    {
        if ($request->ajax())
        {
            $key = explode('_', $request->key_id);
            $kelas_id = $key[0];
            $mapel_id = $key[1];
            $guru_id = $key[2];

            $siswas = Siswa::with([
                'ujianSiswa' => fn ($q) => $q->with('ujian.jadwalUjian'),
                'user'
            ])
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

            for ($i = 1; $i <= 14; $i++) { // 14 adalah jumlah pertemuan
                // $i adalah pertemuan ke-
                $table->addColumn("p$i", function ($data) use ($i, $mapel_id) {

                    $nilai = '-'; // nilai default

                    foreach ($data->tugas as $tugas) { // looping tugas siswa
                        if ($tugas->pertemuan == $i)  { // jika pertemuan sama dengan $i
                            if ($mapel_id == $tugas->mapel_id) { // jika mapel_id sama dengan $mapel_id
                                if ($tugas->nilaiTugas) { // jika nilai tugas ada
                                    $nilai = $tugas->nilaiTugas->nilai;
                                } else { // jika nilai tugas tidak ada
                                    $nilai = 0;
                                }
                            }
                        }
                    }

                    return $nilai;
                });
            }

            $table->addColumn('rata_rata', function ($data) use ($mapel_id) {
                $nilai = 0;
                $jumlah = 0;

                foreach ($data->tugas as $tugas) {
                    if ($mapel_id == $tugas->mapel_id) {
                        if ($tugas->nilaiTugas) {
                            $nilai += $tugas->nilaiTugas->nilai;
                            $jumlah++;
                        }
                    }
                }

                return $jumlah > 0 ? $nilai / $jumlah : 0;
            });

            $table->addColumn('nilai_uts', function ($data) use ($kelas_id, $mapel_id) {
                $ujianSiswa = $data->ujianSiswa->where('nilai', '!=', null);

                if ($ujianSiswa) {
                    $uts = $ujianSiswa->where('ujian.tipe_ujian', 'uts')->first();
                    $nilai = $uts ? intval($uts->nilai) : 0;
                }

                return $nilai ?? 0;
            });

            $table->addColumn('nilai_uas', function ($data) use ($mapel_id) {
                $ujianSiswa = $data->ujianSiswa->where('nilai', '!=', null);

                if ($ujianSiswa) {
                    $uas = $ujianSiswa->where('ujian.tipe_ujian', 'uas')->first();
                    $nilai = $uas ? intval($uas->nilai) : 0;
                }

                return $nilai ?? 0;
            });

            $table->addColumn('total', function ($data) use ($mapel_id) {
                $totalNilaiTugas = 0;
                $jumlahTugas = 0;
                $ujianSiswa = $data->ujianSiswa->where('nilai', '!=', null);

                foreach ($data->tugas as $tugas) {
                    if ($mapel_id == $tugas->mapel_id) {
                        if ($tugas->nilaiTugas) {
                            $totalNilaiTugas += $tugas->nilaiTugas->nilai;
                            $jumlahTugas++;
                        }
                    }
                }

                if ($ujianSiswa) {
                    $uts = $ujianSiswa->where('ujian.tipe_ujian', 'uts')->first();
                    $uas = $ujianSiswa->where('ujian.tipe_ujian', 'uas')->first();

                    $nilaiUts = $uts ? intval($uts->nilai) : 0;
                    $nilaiUas = $uas ? intval($uas->nilai) : 0;
                }

                if ($totalNilaiTugas !== 0 || $jumlahTugas !== 0 || $nilaiUts !== 0 || $nilaiUas !== 0) {
                    if ($totalNilaiTugas == 0 || $jumlahTugas == 0) {
                        $nilai = ($nilaiUts == 0) ? ($nilaiUas * 0.4) : ($nilaiUts * 0.3);
                    } else {
                        $nilai = ($totalNilaiTugas / $jumlahTugas) * 0.3 + $nilaiUts * 0.3 + $nilaiUas * 0.4;
                    }
                } else {
                    $nilai = 0;
                }

                return round($nilai, 1) ?? 0;
            });

            $pertemuan = array_fill(0, 14, '');

            foreach ($pertemuan as $key => $value) {
                $pertemuan[$key] = "p" . ($key + 1);
            }

            return $table->rawColumns(array_merge(
                ['siswa'],
                $pertemuan
            ))->make(true);
        }
    }

    public function exports($kelas_id, $mapel_id)
    {
        $kelas = Kelas::find(decrypt($kelas_id));
        $mapel = Mapel::find(decrypt($mapel_id));
        $auth = Auth::user()->guru;

        $siswas = Siswa::with(['tugas' => function ($q) use ($auth) {
            $q->whereIn('parent', $auth->tugas->pluck('id'))
                ->select('id', 'siswa_id', 'pertemuan', 'mapel_id');
        }, 'tugas.mapel', 'tugas.nilaiTugas', 'kelas'])
            ->where('kelas_id', $kelas->id)
            ->orderBy('nama', 'asc')
            ->get();

        foreach ($siswas as $i => $sw)
        {
            $totalNilaiTugas = 0;
            $jumlahTugas = 0;

            $data[$i] = [
                'nama' => $sw->nama,
                'nis' => $sw->nis,
                'kelas' => $sw->kelas->kode,
                'mapel' => $mapel->nama,
                'programkeahlian' => $sw->programkeahlian->nama,
                'rata_rata' => 0,
                'nilai_uts' => 0,
                'nilai_uas' => 0,
                'total' => 0,
            ];

            for ($j = 1; $j <= 14; $j++)
            {
                $data[$i]["p$j"] = '-';

                foreach ($sw->tugas as $tugas) {
                    if ($tugas->mapel_id == $mapel->id) {
                        if ($tugas->pertemuan == $j)  {
                            if ($tugas->nilaiTugas) {
                                $data[$i]["p$j"] = $tugas->nilaiTugas->nilai;

                                $totalNilaiTugas += $tugas->nilaiTugas->nilai;
                                $jumlahTugas++;
                            } else {
                                $data[$i]["p$j"] = 0;
                            }
                        }
                    }
                }
            }

            $ujianSiswa = $sw->ujianSiswa->where('nilai', '!=', null);

            if ($ujianSiswa) {
                $uts = $ujianSiswa->where('ujian.tipe_ujian', 'uts')->first();
                $uas = $ujianSiswa->where('ujian.tipe_ujian', 'uas')->first();

                $data[$i]['nilai_uts'] = $uts ? intval($uts->nilai) : 0;
                $data[$i]['nilai_uas'] = $uas ? intval($uas->nilai) : 0;
            }

            $data[$i]['rata_rata'] = $jumlahTugas > 0 ? $totalNilaiTugas / $jumlahTugas : 0;
            $data[$i]['total'] = floatval(($data[$i]['rata_rata'] * 0.3) + ($data[$i]['nilai_uts'] * 0.3) + ($data[$i]['nilai_uas'] * 0.4));
        }

        ob_end_clean();

        $fileName = "laporan_nilai_{$kelas->kode}_" . Str::slug($mapel->nama, '_');
        return Excel::download(new NilaiExport(collect($data)), $fileName . '.xlsx');
    }
}
