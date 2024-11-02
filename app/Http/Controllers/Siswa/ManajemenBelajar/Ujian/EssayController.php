<?php

namespace App\Http\Controllers\Siswa\ManajemenBelajar\Ujian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Ujian\SoalUjianEssay;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use App\Models\ManajemenBelajar\Ujian\UjianHasil;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class EssayController extends Controller
{
    /**
     * Halaman ujian essay
     *
     * @param  mixed $id
     * @return void
     */
    public function ujian($id)
    {
        $jadwal = JadwalUjian::aktif()
            ->with('kelas', 'mapel', 'ujian')
            ->find(decrypt($id)); // decrypt($id) = JadwalUjian->id

        $ujian = $jadwal->ujian;

        if ($jadwal->ended_at != null) { // jika ended_at tidak null
            $ujianSiswa = UjianSiswa::with('ujian')->where([
                'siswa_id' => Auth::user()->siswa->id,
                'status' => 0
            ])->where('ended_at', '>=', now())->first();
        } else { // jika ended_at null
            $ujianSiswa = UjianSiswa::with('ujian')->where([
                'siswa_id' => Auth::user()->siswa->id,
                'status' => 0
            ])->first();
        }

        if ($ujianSiswa == null) { // jika sudah pernah mengerjakan ujian
            return redirect()->route('manajemen.pelajaran.ujian.siswa.index')
                ->with('error', 'Ujian ini sudah kamu kerjakan.');
        }

        return view('dashboard.siswa.ujian.essay', [
            'jadwal' => $jadwal,
            'ujian' => $ujian,
            'ujianSiswa' => $ujianSiswa,
        ]);
    }

    /**
     * Mulai ujian
     *
     * @param  mixed $request
     * @return void
     */
    public function mulaiUjian(Request $request) // mulai ujian
    {
        $jadwal = JadwalUjian::aktif()
            ->with('kelas', 'mapel', 'ujian')
            ->find(decrypt($request->jadwal_id));

        $current_time = strtotime('now');
        $exam_start_at = strtotime($jadwal->tanggal_ujian . ' ' . $jadwal->started_at);
        $max_time = strtotime('+120 minutes', $exam_start_at);

        if ($current_time >= $max_time) {
            return redirect()->back()
                ->with('error', 'Kamu terlambat! Silahkan menghubungi guru pengawas.');
        }

        $ujian = $jadwal->ujian;

        // cek apakah sudah pernah mulai ujian
        $existingUjianSw = UjianSiswa::where('ujian_id', $ujian->id)
            ->where('siswa_id', Auth::user()->siswa->id)
            ->where('started_at', "!=", null)
            ->first();

        if ($existingUjianSw) { // jika sudah pernah mulai ujian
            return redirect()
                ->route('manajemen.pelajaran.ujian.siswa.essay.ujian', encrypt($jadwal->id));
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

            ($ujian->random_soal == 1) ? // jika random soal = 1
                $soal = $ujian->soalUjianEssay()->inRandomOrder()->get() :
                $soal = $ujian->soalUjianEssay;

            foreach ($soal as $key => $value) { // insert soal ke ujian_hasil
                $existingUjianHasil = UjianHasil::where('ujian_siswa_id', $updateUjianSw->id)
                    ->where('soal_ujian_essay_id', $value->id)
                    ->first();

                if ($existingUjianHasil) { // jika sudah ada soal di ujian_hasil
                    continue; // skip
                } else { // jika belum ada soal di ujian_hasil
                    $ujianHasil = new UjianHasil();
                    $ujianHasil->ujian_siswa_id = $updateUjianSw->id;
                    $ujianHasil->soal_ujian_essay_id = $value->id;
                    $ujianHasil->status = 0;
                    $ujianHasil->save();
                }
            }

            // redirect ke halaman ujian yang functionnya di atas
            return redirect()->route('manajemen.pelajaran.ujian.siswa.essay.ujian', encrypt($jadwal->id));
        }
    }

    /**
     * MENAMPILKAN SOAL UJIAN ESSAY DENGAN PAGINATION AJAX
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function fetchSoal(Request $request, $id)
    {
        $soal = UjianHasil::with(['soalUjianEssay' => function ($query) {
            $query->select('id', 'pertanyaan');
        }])->where('ujian_siswa_id', decrypt($request->ujian_siswa_id))
            ->paginate(1, ['*'], 'soal');

        return response()->json($soal);
    }

    /**
     * MENAMPILKAN DAFTAR SOAL UJIAN ESSAY
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return void
     */
    public function fetchDaftarSoal(Request $request, $id)
    {
        $daftarSoal = UjianHasil::where('ujian_siswa_id', decrypt($request->ujian_siswa_id))
            ->select("id", "ujian_siswa_id", "soal_ujian_essay_id", "jawaban", "ragu")
            ->get();

        return response()->json($daftarSoal);
    }

    /**
     * RADIO BUTTON RAGU-RAGU UNTUK SOAL UJIAN ESSAY
     *
     * @param  mixed $request
     * @return void
     */
    public function raguRagu(Request $request)
    {
        $ujianSiswa = UjianSiswa::with('ujian')->where([
            'siswa_id' => Auth::user()->siswa->id,
        ])->where('ended_at', '>=', now())->first();

        if ($ujianSiswa == null) { // jika waktu ujian sudah habis
            return response()->json([
                'status' => "jadwal_habis",
                'message' => 'Waktu ujian telah habis.'
            ]);
        } else { // jika lagi mengerjakan ujian
            $soal = UjianHasil::find($request->id);
            $soal->ragu = $request->ragu;
            $soal->update();

            return response()->json("Ok..");
        }
    }

    /**
     * SIMPAN JAWABAN UNTUK SOAL UJIAN ESSAY
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
            $soal = UjianHasil::with('soalUjianEssay')->find($request->id);
            $soal->jawaban = $request->jawaban;

            /**
             * kenapa status 2? ini untuk proses pengecekan nilai buat guru
             * jika soalnya sudah dinilai sama guru.
            */
            $soal->status = 2;

            $soal->update();

            return response()->json("Ok..");
        }
    }

    /**
     * SELESAIKAN UJIAN ESSAY
     *
     * @param  mixed $request
     * @return void
     */
    public function selesaiUjian(Request $request)
    {
        $ujianSw = UjianSiswa::with(['ujianHasil' => function ($q) {
            $q->with(['soalUjianEssay' => function ($q) {
                $q->with('ujian');
            }]);
        }])->find(decrypt($request->ujian_siswa_id));

        if ($ujianSw->ujianHasil->first()->jawaban != null) {
            $ujianSw->status = 1; // 1 = selesai
            $ujianSw->ended_at = Carbon::now(); // update waktu selesai ujian
            $ujianSw->update(); // update ujian_siswa

            return response()->json($ujianSw);
        } else {
            $ujianSw->started_at = null; // update waktu mulai ujian
            $ujianSw->ended_at = null; // update waktu selesai ujian
            $ujianSw->update(); // update ujian_siswa

            foreach ($ujianSw->ujianHasil as $key => $ujnHasil) {
                $ujnHasil->status = 2; // 2 = belum dinilai
                $ujnHasil->update(); // update ujian_hasil
            }

            $text = "<span class='font-weight-bold'>Kamu Tidak Mengerjakan Ujian</span> <hr>
            Silahkan mengulangi ujian jika jadwal ujian masih tersedia. Jika jadwal ujian sudah habis,
            Silahkan menghubungi guru pengampu mata pelajaran ini untuk mengulangi :ujian";

            return response()->json([
                'status' => "nilai_kosong",
                'message' => $text
            ]);
        }
    }
}
