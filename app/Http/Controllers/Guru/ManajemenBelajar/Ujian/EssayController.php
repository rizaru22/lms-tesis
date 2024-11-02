<?php

namespace App\Http\Controllers\Guru\ManajemenBelajar\Ujian;

use App\Http\Controllers\Controller;
use App\Imports\Guru\Ujian\SoalUjianEssayImports;
use App\Models\KelolaPengguna\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Ujian\SoalUjianEssay;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use App\Models\ManajemenBelajar\Ujian\UjianHasil;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class EssayController extends Controller
{
    public function lihatHasilSiswa(Request $request, $jadwal_id)
    {
        if ($request->ajax()) {

            $ujianSw = UjianSiswa::with(['ujianHasil' => function ($q) {
                $q->with('soalUjianPg', 'soalUjianEssay');
            }, 'siswa', 'ujian'])
                ->where('id', decrypt($request->ujianSwId))
                ->first();

            $start = Carbon::parse($ujianSw->started_at); // waktu mulai
            $end = Carbon::parse($ujianSw->ended_at); // waktu selesai
            $diff = $end->diffInSeconds($start); // waktu selesai dikurangi waktu mulai (dalam detik)
            $hours = floor($diff / 3600); // mengubah waktu ke jam
            $minutes = floor(($diff / 60) % 60); // mengubah waktu ke menit
            $seconds = $diff % 60; // mengubah waktu ke detik

            $data = [
                'namaSw'       => $ujianSw->siswa->nama,
                'nisSw'        => $ujianSw->siswa->nis,
                'userAgentSw'  => $ujianSw->user_agent,
                'ipAddressSw'  => $ujianSw->ip_address,
                'startedAtSw'  => Carbon::parse($ujianSw->started_at)->translatedFormat('l, d M Y - H:i') . ' WIB',
                'endedAtSw'    => Carbon::parse($ujianSw->ended_at)->translatedFormat('l, d M Y - H:i') . ' WIB',
                'durationSw'   => $hours . ' Jam ' . $minutes . ' Menit ' . $seconds . ' Detik',
            ];

            return response()->json([
                'data' => $data,
                'ujianSw' => $ujianSw,
            ], 200);
        } else {
            abort(404);
        }
    }

    public function create(Request $request, $jadwalId)
    {
        $jadwal = JadwalUjian::with(['ujian' => function ($q) {
            $q->with('soalUjianEssay');
        }, 'mapel', 'kelas'])->where('guru_id', Auth::user()->guru->id)->find(decrypt($jadwalId));

        if (!$jadwal) { // jika jadwal tidak ditemukan
            return redirect()->route('manajemen.belajar.jadwal.guru.ujian.index');
        }

        // jika jadwal start dan end tidak null
        if ($jadwal->started_at != null && $jadwal->ended_at != null) {
            // convert to duration
            $start = Carbon::parse($jadwal->started_at);
            $end = Carbon::parse($jadwal->ended_at);
            $duration = $start->diffInMinutes($end); // waktu selesai dikurangi waktu mulai (dalam menit)
        } else {
            $duration = 0;
        }

        $jadwals = JadwalUjian::with('ujian')
            ->where("kelas_id", $jadwal->kelas_id)
            ->where("mapel_id", $jadwal->mapel_id)
            ->where("guru_id", Auth::user()->guru->id)
            ->get();

        if ($jadwals->isEmpty()) { // jika jadwal kosong
            $semester = 1;
            $tipe_ujian = ['uts', 'uas'];
        } else {

            $semester = 1; // semester awal
            $isSemesterDone = false; // semester selesai

            while ($semester <= 9 && !$isSemesterDone) { // looping semester maksimal 8 semester
                if ($semester == 9) { // jika semester sudah 8
                    return redirect()->route('manajemen.belajar.jadwal.guru.ujian.index')
                        ->with('error', 'Maksimal 8 semester, hubungi admin untuk mengulang semester.');
                }

                $uts = $jadwals
                    ->where("kelas_id", $jadwal->kelas_id)
                    ->where("mapel_id", $jadwal->mapel_id)
                    ->where("guru_id", Auth::user()->guru->id)
                    ->where('ujian.semester', $semester)->where('ujian.tipe_ujian', 'uts')
                    ->first();

                $uas = $jadwals
                    ->where("kelas_id", $jadwal->kelas_id)
                    ->where("mapel_id", $jadwal->mapel_id)
                    ->where("guru_id", Auth::user()->guru->id)
                    ->where('ujian.semester', $semester)->where('ujian.tipe_ujian', 'uas')
                    ->first();

                if ($uts != null && $uas != null) { // jika didalam semester sudah ada ujian uts dan uas
                    $semester++; // semester ditambah 1
                } else { // jika didalam semester belum ada ujian uts dan uas
                    $isSemesterDone = true; // semester selesai

                    if ($uts != null) { // jika didalam semester sudah ada ujian uts
                        $tipe_ujian = ['uas']; // tipe ujian hanya uas
                    } else if ($uas != null) { // jika didalam semester sudah ada ujian uas
                        $tipe_ujian = ['uts']; // tipe ujian hanya uts
                    } else { // jika didalam semester belum ada ujian uts dan uas
                        $tipe_ujian = ['uts', 'uas']; // tipe ujian uts dan uas
                    }
                }
            }
        }

        return view('dashboard.guru.ujian.soal.essay.create', [
            'jadwal' => $jadwal,
            'duration' => $duration,
            'semester' => $semester,
            'tipe_ujian' => $tipe_ujian,
        ]);
    }

    public function store(Request $request)
    {
        $jadwal = JadwalUjian::with(['ujian' => function ($q) {
            $q->with('soalUjianEssay');
        }, 'kelas', 'mapel'])->where('guru_id', Auth::user()->guru->id)
            ->find(decrypt($request->jadwal_id));

        $siswa = Siswa::with('kelas')->whereHas('kelas', function ($q) use ($jadwal) {
            $q->where('kelas_id', $jadwal->kelas->id);
        })->get();

        DB::beginTransaction();
        try {
            $ujian = Ujian::create([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'durasi_ujian' => $request->durasi,
                'tipe_soal' => 'essay',
                'tipe_ujian' => $request->tipe_ujian,
                'random_soal' => $request->random_soal,
                'lihat_hasil' => $request->lihat_hasil,
                'jadwal_ujian_id' => $jadwal->id,
                'semester' => $request->semester ?? null,
            ]);

            JadwalUjian::where('id', $jadwal->id)->update([ // update jadwal ujian
                'ujian_id' => $ujian->id,
                'status_ujian' => 'aktif',
            ]);

            foreach ($siswa as $key => $sw) { // insert siswa ke ujian siswa
                UjianSiswa::create([ // insert siswa ke ujian siswa
                    'ujian_id' => $ujian->id,
                    'siswa_id' => $sw->id,
                ]);
            }

            foreach ($request->soal as $key => $value) { // insert soal
                SoalUjianEssay::create([ // insert soal
                    'nomer_soal' => $key + 1,
                    'pertanyaan' => $value,
                    'ujian_id' => $ujian->id,
                ]);
            }

            return Redirect::route('manajemen.belajar.jadwal.guru.ujian.show', encrypt($jadwal->id))
                ->with('success', "Berhasil menambahkan ujian!");
        } catch (\Throwable $th) {
            DB::rollBack();

            return redirect()->back()->with('error', $th->getMessage());
        } finally {
            DB::commit();
        }
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string|max:100',
            'deskripsi' => 'required|string|max:1000',
            'durasi' => 'required|numeric',
            'random_soal' => 'required',
            'lihat_hasil' => 'required',
            'tipe_ujian' => 'required',
            'file' => 'required|mimes:xls,xlsx,csv|max:1024',
            'semester' => 'nullable',
        ], [
            'judul.required' => 'Judul ujian tidak boleh kosong.',
            'judul.string' => 'Judul ujian harus berupa string.',
            'judul.max' => 'Judul ujian maksimal 100 karakter.',
            'deskripsi.required' => 'Deskripsi ujian tidak boleh kosong.',
            'deskripsi.string' => 'Deskripsi ujian harus berupa string.',
            'deskripsi.max' => 'Deskripsi ujian maksimal 1000 karakter.',
            'durasi.required' => 'Durasi ujian tidak boleh kosong.',
            'durasi.numeric' => 'Durasi ujian harus berupa angka.',
            'random_soal.required' => 'Random soal tidak boleh kosong.',
            'lihat_hasil.required' => 'Lihat hasil tidak boleh kosong.',
            'tipe_ujian.required' => 'Tipe ujian tidak boleh kosong.',
            'file.required' => 'File tidak boleh kosong.',
            'file.mimes' => 'File harus berupa xls, xlsx, atau csv.',
            'file.max' => 'File maksimal 1 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'tipe' => 'validation',
                'errors' => $validator->errors()->toArray(),
            ]);
        } else {
            DB::beginTransaction(); // start transaction
            try {
                $jadwal = JadwalUjian::with(['ujian' => function ($q) {
                    $q->with('soalUjianEssay');
                }, 'mapel', 'kelas'])->where('guru_id', Auth::user()->guru->id)
                    ->find(decrypt($request->jadwal_id));

                $siswa = Siswa::with('kelas')->whereHas('kelas', function ($q) use ($jadwal) {
                    $q->where('kelas_id', $jadwal->kelas->id);
                })->get();

                $ujian = Ujian::create([
                    'judul' => $request->judul,
                    'deskripsi' => $request->deskripsi,
                    'durasi_ujian' => $request->durasi,
                    'tipe_soal' => 'essay',
                    'tipe_ujian' => $request->tipe_ujian,
                    'random_soal' => $request->random_soal,
                    'lihat_hasil' => $request->lihat_hasil,
                    'jadwal_ujian_id' => $jadwal->id,
                    'semester' => $request->semester ?? null,
                ]);

                JadwalUjian::where('id', $jadwal->id)->update([
                    'ujian_id' => $ujian->id,
                    'status_ujian' => 'aktif',
                ]);

                foreach ($siswa as $key => $sw) {
                    UjianSiswa::create([
                        'ujian_id' => $ujian->id,
                        'siswa_id' => $sw->id,
                    ]);
                }

                $import = new SoalUjianEssayImports($ujian->id); // import soal
                $import->import($request->file('file')); // import soal

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil mengimport soal ujian!',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 400,
                    'message' => $th->getMessage(),
                ]);
            } finally {
                DB::commit();
            }
        }
    }

    public function list(Request $request, $jadwalId)
    {
        $jadwal = JadwalUjian::with('ujian')
            ->where('guru_id', Auth::user()->guru->id)
            ->find(decrypt($jadwalId));

        if ($request->ajax()) {
            $soal = SoalUjianEssay::where('ujian_id', $jadwal->ujian->id)
                ->paginate(1, ['*'], 'soal');

            $daftar_soal = SoalUjianEssay::where('ujian_id', $jadwal->ujian->id)
                ->select('id', 'nomer_soal')
                ->paginate(5, ['*'], 'daftar_soal');

            return response()->json([
                'soal' => $soal,
                'daftar_soal' => $daftar_soal
            ]);
        } else {
            abort(404);
        }
    }

    public function edit(Request $request, $jadwalId)
    {
        $jadwal = JadwalUjian::with('ujian')
            ->where('guru_id', Auth::user()->guru->id)
            ->find(decrypt($jadwalId));

        if (!$jadwal) {
            return redirect()->route('manajemen.belajar.jadwal.guru.ujian.index');
        }

        $ujian = $jadwal->ujian;
        $soalEssays = $jadwal->ujian->soalUjianEssay()->get();

        if ($jadwal->started_at != null && $jadwal->ended_at != null) {
            // convert to duration
            $start = Carbon::parse($jadwal->started_at); // start time
            $end = Carbon::parse($jadwal->ended_at); // end time
            $duration = $start->diffInMinutes($end); // duration in minutes
        } else {
            $duration = 0;
        }

        return view('dashboard.guru.ujian.soal.essay.edit', [
            'jadwal' => $jadwal,
            'ujian' => $ujian,
            'duration' => $duration,
            'soalEssays' => $soalEssays,
        ]);
    }

    public function fetch($jadwalId)
    {
        $jadwal = JadwalUjian::find(decrypt($jadwalId));

        $soals = SoalUjianEssay::where('ujian_id', $jadwal->ujian->id)->get();

        $output = '';

        foreach ($soals as $key => $soal) {
            $no = $key + 1;

            $output .= '
                <div class="card soalUjian" id="soal_' . $no . '" data-id="' . $no . '">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold m-0 p-0">Soal No. ' . $no . '</h6>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="form-group">
                            <textarea required name="soal[]" id="soal" class="form-control soal_ujian" data-id="' . $no . '"
                            rows="5" placeholder="Masukkan soal ujian.">' . $soal->pertanyaan . '</textarea>
                        </div>
                    </div>
                </div>
            ';
        }

        return response()->json($output);
    }

    public function update(Request $request, $jadwalId)
    {
        DB::beginTransaction();
        try {
            $jadwal = JadwalUjian::with(['ujian' => function ($q) {
                $q->with('soalUjianEssay');
            }, 'mapel', 'kelas'])->where('guru_id', Auth::user()->guru->id)->find(decrypt($jadwalId));

            $mapel_id = $jadwal->mapel_id;
            $kelas_id = $jadwal->kelas_id;
            $ujian = $jadwal->ujian;
            $soal = $jadwal->ujian->soalUjianEssay;

            if ($jadwal->guru_can_manage == '1') {
                $jadwal->update([
                    'status_ujian' => $request->status_ujian ?? $jadwal->status_ujian,
                    'started_at' => $request->started_at ?? $jadwal->started_at,
                    'ended_at' => $request->ended_at,
                    'tanggal_ujian' => $request->tanggal_ujian ?? $jadwal->tanggal_ujian,
                ]);
            }

            // Section tukeran tipe ujian (UTS <-> UAS) berdasarkan kelas, mapel, dan semester yang sama
            // ujian1 = ujian yang lagi diedit, ujian2 = ujian yang akan ditukar
            $ujian1 = $ujian->whereHas('jadwalUjian', function ($q) use ($mapel_id, $kelas_id) {
                $q->where('mapel_id', $mapel_id)->where('kelas_id', $kelas_id);
            })->where('semester', $ujian->semester)->first();

            if ($ujian1 && $ujian1->tipe_ujian == $request->tipe_ujian) {
                $ujian->tipe_ujian = ($ujian1->tipe_ujian == 'uts') ? 'uas' : 'uts';
            }

            $ujian2 = Ujian::whereHas('jadwalUjian', function ($q) use ($mapel_id, $kelas_id) {
                    $q->where('mapel_id', $mapel_id)->where('kelas_id', $kelas_id);
                })
                ->where('id', '!=', $ujian->id)
                ->where('semester', $ujian->semester)
                ->where('tipe_ujian', $request->tipe_ujian)
                ->first();

            if ($ujian2 && $ujian2->tipe_ujian == $request->tipe_ujian) {
                $ujian2->update([
                    'tipe_ujian' => ($ujian2->tipe_ujian == 'uts') ? 'uas' : 'uts',
                ]);
            }
            // End section tukeran tipe ujian

            $ujian->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'durasi_ujian' => $request->durasi,
                'tipe_soal' => 'essay',
                'tipe_ujian' => $request->tipe_ujian,
                'random_soal' => $request->random_soal,
                'lihat_hasil' => $request->lihat_hasil,
                'semester' => $request->semester ?? null,
            ]);


            foreach ($request->soal as $key => $value) {
                /*
                    jika soal yang diinput lebih banyak dari soal yang ada di database
                    atau soal yang ada di database kosong maka buat soal baru
                */
                if ($key >= count($soal) || count($soal) == 0) {
                    SoalUjianEssay::create([
                        'nomer_soal' => $key + 1, // nomer soal dimulai dari 1
                        'pertanyaan' => $value,
                        'ujian_id' => $ujian->id,
                    ]);
                } else { // jika tidak maka update soal yang ada
                    $soal[$key]->update([
                        'nomer_soal' => $key + 1, // nomer soal dimulai dari 1
                        'pertanyaan' => $request->soal[$key],
                        'ujian_id' => $ujian->id,
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil memperbarui ujian!',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 400,
                'message' => $th->getMessage(),
            ]);
        } finally {
            DB::commit();
        }
    }

    public function removeColumn(Request $request, $id)
    {
        $soal = SoalUjianEssay::where('ujian_id', $request->ujian_id)
            ->where('nomer_soal', $request->soal_id)->first();

        if ($soal == null) {
            return response()->json([
                'status' => 400,
                'message' => 'Soal tidak ditemukan!',
            ]);
        } else {
            $soal->delete();
            UjianHasil::where('soal_ujian_essay_id', $soal->id)->delete();

            // mengambil semua soal dalam ujian yang sesuai
            $soals = SoalUjianEssay::where('ujian_id', $request->ujian_id)
                ->orderBy('nomer_soal')
                ->get();

            // mengubah nilai nomer_soal pada setiap soal sesuai dengan-
            // indeksnya dalam array yang disortir.
            foreach ($soals as $index => $soal) {
                $soal->nomer_soal = $index + 1;
                $soal->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil menghapus soal ujian!',
            ]);
        }
    }
}
