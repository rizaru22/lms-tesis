<?php

namespace App\Http\Controllers\Siswa\ManajemenBelajar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Mapel;
use App\Models\ManajemenBelajar\Ujian\SoalUjianPg;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use App\Models\ManajemenBelajar\Ujian\UjianHasil;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class UjianController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = JadwalUjian::aktif()
            ->with(['ujian' => function ($q) {
                $q->with(['ujianSiswa' => function ($q) {
                    $q->where('siswa_id', Auth::user()->siswa->id)->where('status', 0);
                }]);
            }, 'kelas', 'mapel'])
            ->where('kelas_id', Auth::user()->siswa->kelas->id)
            ->whereHas('ujian', function ($q) {
                $q->whereHas('ujianSiswa', function ($q) {
                    $q->where('status', 0)->where('siswa_id', Auth::user()->siswa->id);
                });
            })
            ->orderBy(mapel::select('nama')->whereColumn('id', 'mapel_id'), 'asc')
            ->latest()
            ->get();


        if ($request->ajax()) {

            $data = $jadwals->transform(function ($item) {
                $item->mapel_ujian = $item->mapel->nama;
                $item->judul_ujian = $item->ujian->judul;
                $item->tanggal = Carbon::parse($item->tanggal_ujian)->isoFormat('dddd, D MMMM Y');

                $jam = floor($item->ujian->durasi_ujian / 60);
                $menit = $item->ujian->durasi_ujian % 60;
                $item->durasi = $item->ujian->durasi_ujian . ' Menit';

                $item->tipe_soal = $item->ujian->tipe_soal;

                return $item;
            });

            if ($request->filterMapel != null) {
                $data = collect($data)->where('mapel_ujian', $request->filterMapel)->all();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $current_time = strtotime('now'); // waktu sekarang
                    $exam_start_at = strtotime($data->tanggal_ujian . ' ' . $data->started_at);
                    $exam_end_at = strtotime($data->tanggal_ujian . ' ' . $data->ended_at);

                    // jika jadwal sudah saatnya maka tombol kerjakan akan muncul
                    if ($data->ended_at != null) { // jika ada jam selesai
                        if ($current_time >= $exam_start_at && $current_time <= $exam_end_at) {
                            $button = '
                                <button id="' . encrypt($data->id) . '"  class="btn btn-primary btn-sm btnMulai"
                                    data-toggle="tooltip" title="Kerjakan">
                                    <i class="fas fa-pen"></i>
                                </button>
                            ';
                        } else if ($current_time > $exam_end_at) { // jika sudah lewat dari jadwal
                            $button = '
                                <button class="btn btn-secondary btn-sm sudah_selesai">
                                    <i class="fas fa-pen"></i>
                                </button>
                                ';
                        } else if ($current_time < $exam_start_at) { // jika belum mulai
                            $button = '
                                <button class="btn btn-secondary btn-sm belum_mulai"
                                    data-mulai="' . $data->started_at . '">
                                    <i class="fas fa-pen"></i>
                                </button>
                            ';
                        }
                    } else { // jika tidak ada jam selesai

                        if ($current_time >= $exam_start_at) { // jika sudah mulai

                            // jika sudah lewat 15 menit dari jam mulai maka tidak bisa kerjakan
                            $max_time = strtotime('+120 minutes', $exam_start_at);

                            if ($current_time >= $max_time) {
                                return '
                                    <button class="btn btn-secondary btn-sm "
                                        data-toggle="tooltip" title="Kamu sudah terlambat">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                ';
                            }

                            $button = '
                                <button id="' . encrypt($data->id) . '" class="btn btn-primary btn-sm btnMulai"
                                    data-toggle="tooltip" title="Kerjakan">
                                    <i class="fas fa-pen"></i>
                                </button>
                            ';
                        } else { // jika belum mulai
                            $button = '
                                <button class="btn btn-secondary btn-sm belum_mulai"
                                    data-mulai="' . $data->started_at . '">
                                    <i class="fas fa-pen"></i>
                                </button>
                            ';
                        }


                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.siswa.ujian.index', [
            'jadwals' => $jadwals
        ]);
    }

    public function riwayatUjian(Request $request)
    {
        $jadwals = JadwalUjian::aktif()
            ->with('ujian', 'kelas', 'mapel')
            ->where('kelas_id', Auth::user()->siswa->kelas->id)
            ->whereHas('ujian', function ($q) {
                $q->whereHas('ujianSiswa', function ($q) {
                    $q->where('status', 1)->where('siswa_id', Auth::user()->siswa->id);
                });
            })
            ->orderBy(Mapel::select('nama')->whereColumn('id', 'mapel_id'), 'asc')
            ->latest()
            ->get();

        if ($request->ajax()) {

            $data = $jadwals->transform(function ($item) {
                $item->mapel_ujian = $item->mapel->nama;
                $item->judul_ujian = $item->ujian->judul;
                $item->tanggal = Carbon::parse($item->tanggal_ujian)->isoFormat('dddd, D MMMM Y');
                $item->tipe_soal = $item->ujian->tipe_soal;

                return $item;
            });

            if ($request->filterMapel != null) {
                $data = collect($data)->where('mapel_ujian', $request->filterMapel)->all();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {

                    if ($data->ujian->lihat_hasil == 1) {
                        $button = '
                            <button id="' . encrypt($data->id) . '" class="btn btn-primary btn-sm btn_lihat">
                                Lihat Hasil <i class="fas fa-external-link-alt ml-1"></i>
                            </button>
                        ';
                    } else {
                        $button = '
                            <button class="btn btn-success btn-sm cursor_default">
                                Mengerjakan <i class="fas fa-check-circle ml-1"></i>
                            </button>
                        ';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.siswa.ujian.riwayat-ujian', [
            'jadwals' => $jadwals,
        ]);
    }

    public function informasiUjian($id)
    {
        if (request()->ajax()) {
            $jadwal = JadwalUjian::aktif()
                ->with('kelas', 'mapel', 'ujian')
                ->find(decrypt($id));

            ($jadwal->ujian->tipe_soal == 'Pilihan Ganda') ?
                $soal = $jadwal->ujian->soalUjianPg->count() :
                $soal = $jadwal->ujian->soalUjianEssay->count();

            $jam = floor($jadwal->ujian->durasi_ujian / 60);
            $menit = $jadwal->ujian->durasi_ujian % 60;

            ($menit == 0) ?
                $durasi = $jam . ' Jam' :
                $durasi = $jam . ' Jam ' . $menit . ' Menit';

            ($jadwal->ended_at == null) ?
                $waktu_selesai = '-' :
                $waktu_selesai = $jadwal->ended_at . ' WIB';

            $data = [
                'judul_ujian'   => $jadwal->ujian->judul,
                'mapel'        => $jadwal->mapel->nama,
                'waktu_mulai'   => $jadwal->started_at . ' WIB',
                'jumlah_soal'   => $soal . ' Soal',
                'tanggal_ujian' => Carbon::parse($jadwal->tanggal_ujian)->isoFormat('dddd, D MMMM Y'),
                'kelas'         => $jadwal->kelas->kode,
                'tipe_soal'     => $jadwal->ujian->tipe_soal,
                'tipe_ujian'    => strtoupper($jadwal->ujian->tipe_ujian),
                'waktu_selesai' => $waktu_selesai,
                'durasi_ujian'  => $jadwal->ujian->durasi_ujian . " Menit" . " (" . $durasi . ")",
                'deskripsi'     => $jadwal->ujian->deskripsi,
                'jadwal_id'     => encrypt($jadwal->id),
                'semester'      => $jadwal->ujian->semester,
                'ujianDimulai'  => $jadwal->ujian->ujianSiswa->where('siswa_id', Auth::user()->siswa->id)->where('started_at', "!=", null)->first(),
            ];

            return response()->json($data);
        } else {
            abort(404);
        }
    }

    public function hasilUjian($id)
    {
        $jadwal = JadwalUjian::with('ujian', 'kelas', 'mapel')
            ->where('id', decrypt($id))
            ->first();

        $ujian = Ujian::with('ujianSiswa')
            ->where('jadwal_ujian_id', decrypt($id))
            ->first();

        $siswa = UjianSiswa::with(['ujianHasil' => function ($q) {
            $q->with('soalUjianPg', 'soalUjianEssay');
        }, 'siswa', 'ujian'])
            ->where('ujian_id', $ujian->id)
            ->where('siswa_id', Auth::user()->siswa->id)
            ->first();

        ($jadwal->ujian->tipe_soal == 'Pilihan Ganda') ?
            $soal = $jadwal->ujian->soalUjianPg->count() :
            $soal = $jadwal->ujian->soalUjianEssay->count();

        $jam = floor($jadwal->ujian->durasi_ujian / 60);
        $menit = $jadwal->ujian->durasi_ujian % 60;

        ($menit == 0) ?
            $durasi = $jam . ' Jam' :
            $durasi = $jam . ' Jam ' . $menit . ' Menit';

        ($jadwal->ended_at == null) ?
            $waktu_selesai = '-' :
            $waktu_selesai = $jadwal->ended_at . ' WIB';

        $data = [
            'judul_ujian'   => $jadwal->ujian->judul,
            'mapel'        => $jadwal->mapel->nama,
            'waktu_mulai'   => $jadwal->started_at . ' WIB',
            'jumlah_soal'   => $soal . ' Soal',
            'tanggal_ujian' => Carbon::parse($jadwal->tanggal_ujian)->isoFormat('dddd, D MMMM Y'),
            'kelas'         => $jadwal->kelas->kode,
            'tipe_soal'     => $jadwal->ujian->tipe_soal,
            'tipe_ujian'    => strtoupper($jadwal->ujian->tipe_ujian),
            'waktu_selesai' => $waktu_selesai,
            'durasi_ujian'  => $jadwal->ujian->durasi_ujian . " Menit" . " (" . $durasi . ")",
            'deskripsi'     => $jadwal->ujian->deskripsi,
            'jadwal_id'     => encrypt($jadwal->id),
            'semester'      => $jadwal->ujian->semester,
            'salah'         => $siswa->ujianHasil->where('status', '0')->where("jawaban", "!=", null)->count() ?? 0,
            'benar'         => $siswa->ujianHasil->where('status', '1')->count() ?? 0,
            'kosong'        => $siswa->ujianHasil->where('status', '0')->where("jawaban", null)->count() ?? 0,
            'ragu'          => $siswa->ujianHasil->where('ragu', '1')->count() ?? 0,
            'belum_dinilai' => $siswa->ujianHasil->where('status', '2')->count(),
            'nilai_ujian'   => $siswa->nilai ?? '<span class="badge badge-secondary">Nihil</span>',
        ];

        return response()->json([
            'data' => $data,
            'ujian' => $siswa,
        ]);
    }

    public function cetakHasilUjian($id)
    {
        $jadwal = JadwalUjian::with('ujian', 'kelas', 'mapel')
            ->where('id', decrypt($id))
            ->first();

        $ujian = Ujian::with('ujianSiswa')
            ->where('jadwal_ujian_id', $jadwal->id)
            ->first();

        $siswa = UjianSiswa::with(['ujianHasil' => function ($q) {
            $q->with('soalUjianPg', 'soalUjianEssay');
        }])->where('ujian_id', $ujian->id)
            ->where('siswa_id', Auth::user()->siswa->id)
            ->first();

        $tipe_soal = $ujian->tipe_soal == 'Essay' ? 'essay' : 'pg';

        $ujianHasil = UjianHasil::where('ujian_siswa_id', $siswa->id)
            ->where('soal_ujian_' . $tipe_soal . '_id', '!=', null)
            ->get();

        // return view('dashboard.siswa.pdf.cetak-hasil-ujian-' . $tipe_soal, [
        //     'jadwal' => $jadwal,
        //     'ujian' => $ujian,
        //     'siswa' => $siswa,
        //     'ujianHasil' => $ujianHasil,
        // ]);

        /**
         * Jika mau menggunakan view untuk edit pdf, uncomment diatas ini
         * tapi harus di comment semua yang di bawah.
         * */

        $pdf = PDF::loadview('dashboard.siswa.pdf.cetak-hasil-ujian-' . $tipe_soal, [
            'jadwal' => $jadwal,
            'ujian' => $ujian,
            'siswa' => $siswa,
            'ujianHasil' => $ujianHasil,
        ]);

        ($ujian->tipe_ujian == 'uts') ?
            $tipe = 'UTS_' :
            $tipe = 'UAS_';

        $fileName = 'Bukti_Ujian_' . $tipe . Auth::user()->no_induk .
            '_' . str_replace(' ', '_', strtoupper($jadwal->mapel->nama));

        return $pdf->stream($fileName . '.pdf');
    }
}
