<?php

namespace App\Http\Controllers\Siswa\ManajemenBelajar\Ujian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Ujian\SoalUjianPg;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use App\Models\ManajemenBelajar\Ujian\UjianHasil;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class PilihanGandaController extends Controller
{
    /**
     * Halaman ujian pilihan ganda
     *
     * @param  mixed $id
     * @return void
     */
    public function ujian($id)
    {
        $jadwal = JadwalUjian::aktif()
            ->with('kelas', 'mapel', 'ujian')
            ->find(decrypt($id));

        $ujian = $jadwal->ujian;

        if ($jadwal->ended_at != null) {
            $ujianSiswa = UjianSiswa::with('ujian')->where([
                'siswa_id' => Auth::user()->siswa->id,
                'status' => 0
            ])->where('ended_at', '>=', now())->first();
        } else {
            $ujianSiswa = UjianSiswa::with('ujian')->where([
                'siswa_id' => Auth::user()->siswa->id,
                'status' => 0
            ])->first();
        }

        if ($ujianSiswa == null) {
            return redirect()->route('manajemen.pelajaran.ujian.siswa.index')
                ->with('error', 'Ujian ini sudah kamu kerjakan.');
        }

        return view('dashboard.siswa.ujian.pg', [
            'jadwal' => $jadwal,
            'ujian' => $ujian,
            'ujianSiswa' => $ujianSiswa,
        ]);
    }

    /**
     * Memulai ujian pilihan ganda
     *
     * @param  mixed $request
     * @return void
     */
    public function mulaiUjian(Request $request)
    {
        $jadwal = JadwalUjian::aktif()
            ->with('kelas', 'mapel', 'ujian')
            ->find(decrypt($request->jadwal_id));

        $current_time = strtotime('now');
        $exam_start_at = strtotime($jadwal->tanggal_ujian . ' ' . $jadwal->started_at);
        $max_time = strtotime('+120 minutes', $exam_start_at);

        if ($current_time >= $max_time) {
            return redirect()->back()
                ->with('error', 'Kamu terlambat! Silahkan menghubungi Guru pengawas.');
        }

        $ujian = $jadwal->ujian;

        $existingUjianSw = UjianSiswa::where('ujian_id', $ujian->id)
            ->where('siswa_id', Auth::user()->siswa->id)
            ->where('started_at', "!=", null)
            ->first();

        if ($existingUjianSw) { // jika sudah pernah mulai ujian
            return redirect()->route('manajemen.pelajaran.ujian.siswa.pg.ujian', encrypt($jadwal->id));
        } else {
            $updateUjianSw = UjianSiswa::where('ujian_id', $ujian->id)
                ->where('siswa_id', Auth::user()->siswa->id)
                ->first();

            $updateUjianSw->started_at = Carbon::now();
            $updateUjianSw->ended_at = Carbon::now()->addMinutes($ujian->durasi_ujian);
            $updateUjianSw->user_agent = $request->userAgent();
            $updateUjianSw->ip_address = $request->getClientIp();
            $updateUjianSw->created_at = Carbon::now();
            $updateUjianSw->updated_at = Carbon::now();
            $updateUjianSw->update();

            ($ujian->random_soal == 1) ? // random soal jika random_soal = 1
                $soal = $ujian->soalUjianPg()->inRandomOrder()->get() :
                $soal = $ujian->soalUjianPg;

            foreach ($soal as $key => $value) {

                $existingUjianHasil = UjianHasil::where('ujian_siswa_id', $updateUjianSw->id)
                    ->where('soal_ujian_pg_id', $value->id)
                    ->first();

                if ($existingUjianHasil) {
                    continue;
                } else {
                    $ujianHasil = new UjianHasil();
                    $ujianHasil->ujian_siswa_id = $updateUjianSw->id;
                    $ujianHasil->soal_ujian_pg_id = $value->id;
                    $ujianHasil->status = 0;
                    $ujianHasil->save();
                }
            }

            return redirect()->route('manajemen.pelajaran.ujian.siswa.pg.ujian', encrypt($jadwal->id));
        }
    }

    /**
     * Fetch soal ujian pilihan ganda
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function fetchSoal(Request $request, $id)
    {
        $soal = UjianHasil::with(['soalUjianPg' => function ($query) {
            $query->select('id', 'pertanyaan', 'pilihan_a', 'pilihan_b', 'pilihan_c', 'pilihan_d', 'pilihan_e');
        }])->where('ujian_siswa_id', decrypt($request->ujian_siswa_id))
            ->paginate(1, ['*'], 'soal');

        return response()->json($soal);
    }

    /**
     * Fetch daftar soal ujian pilihan ganda
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function fetchDaftarSoal(Request $request, $id)
    {
        $daftarSoal = UjianHasil::where('ujian_siswa_id', decrypt($request->ujian_siswa_id))
            ->select("id", "ujian_siswa_id", "soal_ujian_pg_id", "jawaban", "ragu")
            ->get();

        return response()->json($daftarSoal);
    }

    /**
     * Ragu-ragu soal ujian pilihan ganda
     *
     * @param  mixed $request
     * @return void
     */
    public function raguRagu(Request $request)
    {
        $ujianSiswa = UjianSiswa::with('ujian')->where([
            'siswa_id' => Auth::user()->siswa->id,
        ])->where('ended_at', '>=', now())->first();

        if ($ujianSiswa == null) {
            return response()->json([
                'status' => "jadwal_habis",
                'message' => 'Waktu ujian telah habis.'
            ]);
        } else {
            $soal = UjianHasil::find($request->id);
            $soal->ragu = $request->ragu;
            $soal->update();

            return response()->json("Ok..");
        }
    }

    /**
     * Simpan jawaban soal ujian pilihan ganda
     *
     * @param  mixed $request
     * @return void
     */
    public function simpanJawaban(Request $request)
    {
        $ujianSiswa = UjianSiswa::with('ujian')->where([
            'siswa_id' => Auth::user()->siswa->id,
        ])->where('ended_at', '>=', now())->first();

        if ($ujianSiswa == null) {
            return response()->json([
                'status' => "jadwal_habis",
                'message' => 'Waktu ujian telah habis.'
            ]);
        } else {
            $soal = UjianHasil::with('soalUjianPg')->find($request->id);

            $soal->jawaban = $request->jawaban;

            ($soal->soalUjianPg->jawaban_benar == $request->jawaban) ?
                $soal->status = 1 : // benar
                $soal->status = 0; // salah

            $soal->update();

            $this->hitungNilai($soal->ujian_siswa_id); // hitung nilai

            return response()->json("Ok..");
        }
    }

    /**
     * Ujian selesai
     *
     * @param  mixed $request
     * @return void
     */
    public function selesaiUjian(Request $request)
    {
        $ujianSw = UjianSiswa::with(['ujianHasil' => function ($q) {
            $q->with(['soalUjianPg' => function ($q) {
                $q->with('ujian');
            }]);
        }])->find(decrypt($request->ujian_siswa_id));

        if ($ujianSw->nilai != null) {
            $ujianSw->status = 1;
            $ujianSw->ended_at = Carbon::now();
            $ujianSw->update();

            $this->hitungNilai($ujianSw->id); // hitung nilai
            return response()->json($ujianSw);
        } else {
            $ujianSw->started_at = null; // ulangi ujian
            $ujianSw->ended_at = null; // ulangi ujian
            $ujianSw->update(); // update ujian_siswa

            $text = "<span class='font-weight-bold'>Kamu Tidak Mengerjakan Ujian</span> <hr>
            Silahkan mengulangi ujian jika jadwal ujian masih tersedia. Jika jadwal ujian sudah habis,
            Silahkan menghubungi Guru pengampu mata pelajaran ini untuk mengulangi :ujian";

            return response()->json([
                'status' => "nilai_kosong",
                'message' => $text
            ]);
        }


    }

    /**
     * Hitung nilai ujian pilihan ganda
     *
     * @param  mixed $ujianSwId
     * @return void
     */
    private function hitungNilai($ujianSwId)
    {
        $ujianSw = UjianSiswa::with('ujianHasil')->find($ujianSwId);
        $jumlahSoal = $ujianSw->ujianHasil->count();

        $benar = 0; // jumlah jawaban benar

        foreach ($ujianSw->ujianHasil as $key => $value) { // hitung jumlah jawaban benar
            if ($value->status == 1) { // jika jawaban benar
                $benar++; // tambah jumlah jawaban benar
            }
        }

        $nilai = ($benar / $jumlahSoal) * 100; // hitung nilai
        $ujianSw->nilai = $nilai; // update nilai
        $ujianSw->update();

        return $nilai; // return nilai
    }
}
