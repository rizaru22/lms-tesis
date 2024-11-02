<?php

namespace App\Http\Controllers\Guru\ManajemenBelajar\Laporan;

use App\Exports\Guru\Laporan\AbsensiExports;
use App\Http\Controllers\Controller;
use App\Models\ManajemenBelajar\Jadwal\Belajar as JadwalBelajar;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\KelolaPengguna\Siswa;
use App\Models\ManajemenBelajar\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class AbsenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function absen()
    {
        $jadwals = JadwalBelajar::where('guru_id', Auth::user()->guru->id)
            ->whereHas("kelas.siswa")
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->get();

        return view('dashboard.guru.laporan.absen.index', [
            'jadwals' => $jadwals
        ]);
    }

    public function fetchDataAbsen(Request $request)
    {
        if (request()->ajax()) {
            $key = explode('_', $request->key_id); // Kelas_id_mapel_id
            $kelas_id = $key[0]; // Kelas_id
            $mapel_id = $key[1]; // mapel_id

            $jadwal = JadwalBelajar::where('kelas_id', $kelas_id)
                ->where('mapel_id', $mapel_id)
                ->first();

            $output = view('dashboard.guru.laporan.absen._data-nilai', [
                'jadwal' => $jadwal,
                'kelas_id' => $kelas_id,
                'mapel_id' => $mapel_id,
            ])->render();

            return response()->json($output);
        } else {
            abort(404);
        }
    }

    public function tableDataAbsen(Request $request)
    {
        if (request()->ajax()) {
            $key = explode('_', $request->key_id);
            $kelas_id = $key[0];
            $mapel_id = $key[1];
            $kelasArray = []; // Array untuk menampung data kelas

            $jadwals = JadwalBelajar::with(['kelas' => function ($q) {
                $q->with('siswa');
            }, 'mapel'])
                ->where('kelas_id', $kelas_id)
                ->where('mapel_id', $mapel_id)
                ->where('guru_id', Auth::user()->guru->id)
                ->get();


            foreach ($jadwals as $key => $kls) { // Looping untuk mengambil data kelas
                $kelasArray[$key] = $kls; // Menampung data kelas ke dalam array
            }

            /**
             * Jika ingin mengurutkan berdasarkan nilai tertinggi.
             * ->orderBy(NilaiTugas::select('nilai')->whereColumn('siswa_id', 'siswas.id'), 'desc')
             */
            $siswas = Siswa::with(['tugas', 'user', 'absens' => function ($q) {
                $q->with('jadwal');
            }])
                ->whereIn('kelas_id', collect($kelasArray)->pluck('kelas_id'))
                ->orderBy('nama', 'asc')
                ->get();

            $data = $siswas->transform(function ($item) { // Transformasi data
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
                                <div class="small text-secondary">' . $data->nim . '</div>
                            </div>
                        </a>
                    ';
                });

            for ($i = 1; $i <= 16; $i++) { // Looping untuk menambahkan kolom pertemuan 1 - 16
                $table->addColumn("p$i", function ($data) use ($i, $mapel_id) {
                    $absn = '-';

                    if ($data->absens->isNotEmpty()) { // Jika data absen tidak kosong
                        foreach ($data->absens as $absen) { // Looping untuk mengambil data absen
                            // Jika pertemuan sama dengan data pertemuan dan mapel_id sama dengan data mapel_id
                            if ($absen->pertemuan == $i && $absen->jadwal->mapel_id == $mapel_id) {
                                $absn = $absen->status ?
                                    '<span class="text-success">✓</span>' :
                                    '<span class="text-danger">✗</span>';
                            }
                        }
                    }

                    return $absn;
                });
            }

            $table->addColumn("total_hadir", function ($data) use ($mapel_id) {
                $total_hadir = 0;

                if ($data->absens->isNotEmpty()) { // Jika data absen tidak kosong
                    foreach ($data->absens as $absen) { // Looping untuk mengambil data absen
                        // Jika status absen sama dengan 1 dan mapel_id sama dengan data mapel_id
                        if ($absen->status == 1 && $absen->jadwal->mapel_id == $mapel_id) {
                            $total_hadir++;
                        }
                    }
                }

                return $total_hadir;
            });

            $pertemuan = array_fill(0, 16, ''); // Array untuk menampung data pertemuan
            foreach ($pertemuan as $key => $value) { // Looping untuk mengubah data pertemuan
                $pertemuan[$key] = "p" . ($key + 1); // Menambahkan huruf p di depan data pertemuan
            }

            return $table->rawColumns(array_merge(['siswa', 'total_hadir'], $pertemuan))
                ->make(true); // Membuat data menjadi json

        } else {
            abort(404);
        }
    }

    /**
     * Export to excel.
     *
     * @return \Illuminate\Http\Response
     * @param Kelas $kelas
     * @param Mapel $mapel
     */
    public function exports($kelas_id, $mapel_id)
    {
        $kelas = Kelas::find(decrypt($kelas_id));
        $mapel = Mapel::find(decrypt($mapel_id));
        $auth = Auth::user()->guru;

        $siswa = Siswa::with(['absens' => function ($q) use ($auth) { // Mengambil data absen
            $q->whereIn('parent', $auth->absens->pluck('id'));
        }, 'kelas', 'programkeahlian'])
            ->where('kelas_id', $kelas->id)
            ->orderBy('nama', 'asc')
            ->get();

        foreach ($siswa as $i => $sw) { // Looping untuk mengubah data absen
            $dataSw[$i] = [ // Menampung data siswa ke dalam array
                'nama' => $sw->nama,
                'nim' => $sw->nis,
                'kelas' => $sw->kelas->kode,
                'mapel' => $mapel->nama,
                'programkeahlian' => $sw->programkeahlian->nama,
            ];

            $total_hadir = 0; // Menampung total hadir

            for ($j = 1; $j <= 16; $j++) // Looping untuk menambahkan kolom pertemuan
            {
                $dataSw[$i]["p$j"] = '-'; // Menampung data absen ke dalam array

                if (!$sw->absens->isEmpty()) { // Jika data absen tidak kosong
                    foreach ($sw->absens as $absen) // Looping untuk menampilkan data absen
                    {
                        // Jika pertemuan sama dengan $j dan mapel_id sama dengan $mapel_id
                        if ($absen->pertemuan == $j && $absen->jadwal->mapel_id == $mapel->id)
                        {
                            $dataSw[$i]["p$j"] = $absen->status ? 'v' : 'x'; // Menampung data absen ke dalam array
                            $total_hadir = $absen->status ? $total_hadir + 1 : $total_hadir; // Menampung total hadir
                        }
                    }
                }
            }

            $dataSw[$i]['total_hadir'] = $total_hadir; // Menampung total hadir ke dalam array
        }

        ob_end_clean(); // menghapus semua isi buffer output

        $kelas = $dataSw[0]['kelas']; // Mengambil data kelas
        $mapel = Str::slug($dataSw[0]['mapel'], '_'); // Mengambil data mapel
        $fileName = "laporan_absensi_kelas_$kelas" . "_$mapel"; // Nama file excel

        // Export ke excel dengan nama file $fileName
        return Excel::download(new AbsensiExports(collect($dataSw)), $fileName . '.xlsx');
    }
}
