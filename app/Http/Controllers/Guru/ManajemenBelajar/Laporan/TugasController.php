<?php

namespace App\Http\Controllers\Guru\ManajemenBelajar\Laporan;

use App\Exports\Guru\Laporan\NilaiTugasExports;
use App\Http\Controllers\Controller;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\KelolaPengguna\Siswa;
use App\Models\ManajemenBelajar\Mapel;
use App\Models\NilaiTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class TugasController extends Controller
{
    public function nilaiTugas()
    {
        $jadwals = JadwalBelajar::where('guru_id', Auth::user()->guru->id)
            ->whereHas('tugas')
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->get();

        return view('dashboard.guru.laporan.nilai_tugas.index', [
            'jadwals' => $jadwals,
        ]);
    }

    public function fetchDataNilai(Request $request)
    {
        if (request()->ajax()) {
            $key = explode('_', $request->key_id);
            $kelas_id = $key[0];
            $mapel_id = $key[1];
            $guru_id = $key[2];

            $jadwal = JadwalBelajar::where('kelas_id', $kelas_id)
                ->where('mapel_id', $mapel_id)
                ->first();

            $output = view('dashboard.guru.laporan.nilai_tugas._data-nilai', [
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
        if (request()->ajax()) {
            $key = explode('_', $request->key_id);
            $kelas_id = $key[0];
            $mapel_id = $key[1];
            $kelasArray = [];

            $jadwals = JadwalBelajar::with([
                'kelas' => fn ($q) => $q->with('siswa'),
                'mapel'
            ])
                ->where('kelas_id', $kelas_id)
                ->where('mapel_id', $mapel_id)
                ->where('guru_id', Auth::user()->guru->id)
                ->get();

            foreach ($jadwals as $key => $kls) {
                $kelasArray[$key] = $kls;
            }
            /**
             * Jika ingin mengurutkan berdasarkan nilai tertinggi.
             * ->orderBy(NilaiTugas::select('nilai')->whereColumn('siswa_id', 'siswas.id'), 'desc')
             */
            $siswas = Siswa::with('tugas', 'user')
                ->whereIn('kelas_id', collect($kelasArray)->pluck('kelas_id'))
                ->orderBy('nama', 'asc')
                ->get();

            $data = $siswas->transform(function ($item) {
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

            $pertemuan = array_fill(0, 14, '');
            foreach ($pertemuan as $key => $value) {
                $pertemuan[$key] = "p" . ($key + 1);
            }

            return $table->rawColumns(array_merge(['siswa'], $pertemuan))
                ->make(true);
        } else {
            abort(404);
        }
    }

    public function exportNilaiTugas($kelas, $mapel)
    {
        $kelas = Kelas::find(decrypt($kelas));
        $mapel = Mapel::find(decrypt($mapel));

        $auth = Auth::user()->guru;

        $siswa = Siswa::with(['tugas' => function ($q) use ($auth) {
            $q->whereIn('parent', $auth->tugas->pluck('id'))
                ->select('id', 'siswa_id', 'pertemuan', 'mapel_id');
        }, 'tugas.mapel', 'tugas.nilaiTugas', 'kelas'])
            ->where('kelas_id', $kelas->id)
            ->orderBy('nama', 'asc')
            ->get();

        foreach ($siswa as $i => $sw) {

            $dataSw[$i] = [
                'nama' => $sw->nama,
                'nis' => $sw->nis,
                'kelas' => $sw->kelas->kode,
                'mapel' => $mapel->nama,
                'programkeahlian' => $sw->programkeahlian->nama,
            ];

            for ($j = 1; $j <= 14; $j++) {
                $dataSw[$i]["p$j"] = '-'; // nilai default

                foreach ($sw->tugas as $tugas) { // looping tugas siswa
                    if ($tugas->mapel_id == $mapel->id) { // jika mapel_id sama dengan $mapel_id
                        if ($tugas->pertemuan == $j) { // jika pertemuan sama dengan $i
                            if ($tugas->nilaiTugas) { // jika nilai tugas ada
                                $dataSw[$i]["p$j"] = $tugas->nilaiTugas->nilai;
                            } else {
                                $dataSw[$i]["p$j"] = '0'; // jika nilai tugas tidak ada
                            }
                        }
                    }
                }
            }
        }

        ob_end_clean();

        $fileName = 'laporan_nilai_tugas_kelas_' . $dataSw[0]['kelas'] .
            '_' . Str::slug($dataSw[0]['mapel'], '_');

        return Excel::download(new NilaiTugasExports(collect($dataSw)), $fileName . '.xlsx');
    }
}
