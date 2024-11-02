<?php

namespace App\Http\Controllers\Admin\ManajemenBelajar\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Guru;
use App\Models\KelolaPengguna\Siswa;
use Illuminate\Http\Request;
use App\Models\ManajemenBelajar\Jadwal\Ujian as JadwalUjian;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\ManajemenBelajar\Mapel;
use App\Models\ManajemenBelajar\Ujian\Ujian;
use App\Models\ManajemenBelajar\Ujian\UjianSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UjianController extends Controller
{
    public function index(Request $request)
    {
        $jadwals = JadwalUjian::with('kelas', 'mapel', 'guru', 'ujian')
            ->orderBy(Guru::select('nama')->whereColumn('id', 'guru_id'), 'asc')
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->orderBy(Mapel::select('nama')->whereColumn('id', 'mapel_id'), 'asc')
            ->get();

        if ($request->ajax()) {
            $data = $jadwals->transform(function ($item) {
                $item->tgl_ujian = date('Y-m-d', strtotime($item->tanggal_ujian));
                $item->guru_jadwal = $item->guru->nama;
                $item->kelas_jadwal = $item->kelas->kode;
                $item->mapel_jadwal = $item->mapel->nama;
                return $item;
            });

            if ($request->filterKelas != null) {
                $data = collect($data)->where('kelas_jadwal', $request->filterKelas)->all();
            }

            if ($request->filterMapel != null) {
                $data = collect($data)->where('mapel_jadwal', $request->filterMapel)->all();
            }

            if ($request->filterGuru != null) {
                $data = collect($data)->where('guru_jadwal', $request->filterGuru)->all();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) use ($jadwals) {
                    $button = '';
                    $jadwal = $jadwals
                        ->where('kelas_id', $data->kelas_id)
                        ->where('mapel_id', $data->mapel_id)
                        ->first();
                    $firstData = $jadwal && $data->id == $jadwal->id && $data->ujian != null;

                    $button .= '
                        <button type="button" name="edit" id="' . encrypt($data->id) . '"
                            class="edit_btn btn btn-warning btn-sm mt-1" data-toggle="tooltip"
                            title="Edit">
                            <i class="fas fa-pen"></i>
                        </button>
                    ';

                    $button .= '
                        <button type="button" name="delete" id="' . encrypt($data->id) . '"
                            class="del_btn btn btn-danger btn-sm mt-1" data-toggle="tooltip" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';

                    // Jika jadwal ujian sudah dibuat dan itu adalah data yang pertama,
                    // maka tombol reset akan muncul
                    if ($firstData) {
                        $button .= '
                            <button type="button" id="resetData" value="' . encrypt($data->id) . '"
                                class="btn bg-orange btn-sm mt-1" data-toggle="tooltip" title="Reset">
                                <i class="fas fa-sync-alt text-white"></i>
                            </button>
                        ';
                    }

                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.admin.manajemen-pelajaran.jadwal.ujian', [
            'data_kelas' => Kelas::all(),
            'data_mapel' => Mapel::all(),
            'data_guru' => Guru::all(),
            'jadwals' => $jadwals,
        ]);
    }

    public function reset(Request $request)
    {
        DB::beginTransaction(); // Start transaction!
        try {
            $jadwal = JadwalUjian::find(decrypt($request->id));

            $jadwals = JadwalUjian::whereHas('ujian')
                ->where('id', '!=', $jadwal->id)
                ->where('kelas_id', $jadwal->kelas_id)
                ->where('mapel_id', $jadwal->mapel_id)
                ->get();

            foreach ($jadwals as $jdwl) {
                $jdwl->delete();
            }

            if ($jadwal->ujian) {
                $jadwal->ujian->delete();
            }

            $jadwal->update([
                'ujian_id' => null,
                'status_ujian' => 'draft'
            ]);

            return response()->json([
                'status' => 200,
                'tipe' => 'success',
                'message' => 'Data berhasil direset!',
            ]);
        } catch (\Throwable $th) {
            DB::rollBack(); // Rollback transaction!

            return response()->json([
                'status' => 500,
                'tipe' => 'error',
                'message' => 'Terjadi kesalahan saat mereset data!<br>' . $th->getMessage(),
            ]);
        } finally {
            DB::commit(); // Commit transaction!
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_ujian' => 'required',
            'started' => 'required',
            'ended' => 'nullable',
            'guru' => 'required',
            'mapel' => 'required',
            'kelas' => 'required',
            'guru_can_manage' => 'required',
        ], [
            'tanggal_ujian.required' => 'Tidak boleh kosong!',
            'started.required' => 'Tidak boleh kosong!',
            'ended.required' => 'Tidak boleh kosong!',
            'guru.required' => 'Tidak boleh kosong!',
            'mapel.required' => 'Tidak boleh kosong!',
            'kelas.required' => 'Tidak boleh kosong!',
            'guru_can_manage.required' => 'Tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'tipe' => 'validation',
                'errors' => $validator->errors()->toArray(),
            ]);
        } else {
            DB::beginTransaction(); // Start transaction!
            try {
                $checkIfExists = JadwalUjian::where([
                    ['guru_id', $request->guru],
                    ['tanggal_ujian', $request->tanggal_ujian],
                    ['started_at', $request->started],
                    ['ended_at', $request->ended],
                    ['kelas_id', $request->kelas],
                    ['mapel_id', $request->mapel],
                ])->first();

                if ($checkIfExists) { // jika jadwal ujian sudah ada
                    return response()->json([
                        'status' => 400,
                        'message' => "Jadwal ujian sudah ada! Silahkan buat jadwal ujian lainnya."
                    ]);
                }

                $checkUjianMax = JadwalUjian::where([
                    ['kelas_id', $request->kelas],
                    ['mapel_id', $request->mapel],
                ])->get();

                if ($checkUjianMax->count() >= 2) // jika sudah ada 2 jadwal ujian
                {
                    $mapel_nm = $checkUjianMax->first()->mapel->nama;
                    $kelas_kd = $checkUjianMax->first()->kelas->kode;

                    return response()->json([
                        'status' => 400,
                        'message' => "Jadwal ujian <b>$mapel_nm</b> - <b>$kelas_kd</b> sudah mencapai batas maksimal (2x)"
                    ]);
                }

                JadwalUjian::create([
                    'tanggal_ujian' => $request->tanggal_ujian,
                    'started_at' => $request->started,
                    'ended_at' => $request->ended,
                    'guru_id' => $request->guru,
                    'status_ujian' => 'draft',
                    'mapel_id' => $request->mapel,
                    'kelas_id' => $request->kelas,
                    'guru_can_manage' => $request->guru_can_manage,
                ]);

                return response()->json([
                    'status' => 200,
                    'title' => "Berhasil!",
                    'message' => "Berhasil menyimpan data"
                ]);
            } catch (\Throwable $th) {
                DB::rollBack(); // rollback if error

                return response()->json([
                    'status' => 400,
                    'title' => "Terjadi kesalahan! saat menyimpan data!",
                    'message' => "Pesan: $th"
                ]);
            } finally {
                DB::commit(); // commit if success
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = JadwalUjian::with('kelas:id,kode', 'mapel:id,nama', 'guru')
            ->find(decrypt($id));

        $data->tanggal = date('Y-m-d', strtotime($data->tanggal_ujian));

        if (request()->ajax()) {
            if ($data) {
                return response()->json([
                    'status' => 200,
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'status' => 401,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        } else {
            abort(404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = decrypt($id);
        $jadwal = JadwalUjian::with('kelas', 'mapel', 'guru')->findOrFail($id);

        if ($jadwal) {
            $validator = Validator::make($request->all(), [
                'kelas' => 'required',
                'mapel' => 'required',
                'guru' => 'required',
                'tanggal_ujian' => 'required',
                'started_at' => 'required',
                'ended_at' => 'nullable',
                'guru_can_manage' => 'required',
            ], [
                'kelas.required' => 'Tidak boleh kosong!',
                'mapel.required' => 'Tidak boleh kosong!',
                'guru.required' => 'Tidak boleh kosong!',
                'tanggal_ujian.required' => 'Tidak boleh kosong!',
                'started_at.required' => 'Tidak boleh kosong!',
                'ended_at.required' => 'Tidak boleh kosong!',
                'guru_can_manage.required' => 'Tidak boleh kosong!',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->toArray(),
                    'status' => 400,
                    'tipe' => 'validation',
                ]);
            } else {
                $checkIfExist = JadwalUjian::where('id', '!=', $id)
                    ->where('kelas_id', $request->kelas)
                    ->where('mapel_id', $request->mapel)
                    ->where('guru_id', $request->guru)
                    ->where('started_at', $request->started_at)
                    ->where('ended_at', $request->ended_at)
                    ->where('tanggal_ujian', $request->tanggal_ujian)
                    ->first();

                if ($checkIfExist) {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Jadwal ini sudah ada',
                    ]);
                }

                $checkUjianMax = JadwalUjian::where([
                    ['kelas_id', $request->kelas],
                    ['mapel_id', $request->mapel],
                    ['id', '!=', $id]
                ])->get();

                if ($checkUjianMax->count() >= 2) // jika sudah ada 2 jadwal ujian
                {
                    $mapel_nm = $checkUjianMax->first()->mapel->nama;
                    $kelas_kd = $checkUjianMax->first()->kelas->kode;

                    return response()->json([
                        'status' => 400,
                        'message' => "Jadwal ujian <b>$mapel_nm</b> - <b>$kelas_kd</b> sudah mencapai batas maksimal (2x)"
                    ]);
                }

                DB::beginTransaction();
                try {
                    if ($jadwal->status_ujian == 'aktif') // jika status ujian aktif
                    {
                        $canUpdateJadwal = true;
                        $ujian = $jadwal->ujian;
                        $jadreq_kelas = $jadwal->kelas_id != $request->kelas;
                        $jadreq_mapel = $jadwal->mapel_id != $request->mapel;
                        $kelas_id = $request->kelas ?? $jadwal->kelas_id;
                        $mapel_id = $request->mapel ?? $jadwal->mapel_id;
                        $jadwals = JadwalUjian::with('ujian')->where('kelas_id', $kelas_id)
                            ->where('mapel_id', $mapel_id)
                            ->get();

                        // jika ada perubahan kelas atau mapel
                        if ($jadreq_kelas || $jadreq_mapel) {
                            // jika ada data jadwal
                            if ($jadwals->isNotEmpty()) {
                                $semester = 1;
                                $isSemesterDone = false;

                                // looping semester hingga semester 8
                                // tapi kenapa whilenya sampai 9?
                                // agar uts dan uas di semester 8 bisa dimasukkan.
                                while ($semester <= 9 && !$isSemesterDone) {

                                    if ($semester == 9) {
                                        $canUpdateJadwal = false;

                                        return response()->json([
                                            'status' => 400,
                                            'message' => 'Jadwal ujian sudah mencapai semester max',
                                        ]);
                                    }

                                    $uts = $jadwals
                                        ->where('ujian.semester', $semester)
                                        ->where('ujian.tipe_ujian', 'uts')
                                        ->first();

                                    $uas = $jadwals
                                        ->where('ujian.semester', $semester)
                                        ->where('ujian.tipe_ujian', 'uas')
                                        ->first();

                                    // jika sudah ada uts dan uas
                                    // di semester yang sama
                                    if ($uts && $uas) {
                                        $semester++; // lanjut ke semester berikutnya
                                    } else {
                                        $isSemesterDone = true; // keluar dari looping

                                        // update data ujian
                                        $ujian->update([
                                            'semester' => $semester,
                                            'tipe_ujian' => ($uas) ? 'uts' : 'uas',
                                        ]);
                                    }
                                }
                            }

                            if ($canUpdateJadwal) {
                                $jadwal->update([
                                    'kelas_id' => $request->kelas,
                                    'mapel_id' => $request->mapel,
                                ]);

                                if ($ujian->ujianSiswa) { // jika ada data ujian siswa
                                    foreach ($ujian->ujianSiswa as $ujianSw) {
                                        // hapus semua data ujian siswa
                                        $ujianSw->delete();
                                    }

                                    $siswa = Siswa::where('kelas_id', $request->kelas)->get();

                                    foreach ($siswa as $sw) {
                                        // buat ulang data ujian siswa
                                        // sesuai dengan kelasnya
                                        Ujiansiswa::create([
                                            'ujian_id' => $ujian->id,
                                            'siswa_id' => $sw->id,
                                        ]);
                                    }
                                }
                            }
                        } // end if perubahan
                    } else {
                        $jadwal->update([
                            'kelas_id' => $request->kelas,
                            'mapel_id' => $request->mapel,
                        ]);
                    }

                    $jadwal->update([
                        'guru_id' => $request->guru,
                        'tanggal_ujian' => $request->tanggal_ujian,
                        'started_at' => $request->started_at,
                        'ended_at' => $request->ended_at,
                        'guru_can_manage' => $request->guru_can_manage,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'message' => 'Berhasil memperbarui data',
                    ]);
                } catch (\Throwable $th) {
                    DB::rollBack();

                    return response()->json([
                        'status' => 400,
                        'title' => "Terjadi kesalahan! saat memperbarui data!",
                        'message' => "Pesan: $th"
                    ]);
                } finally {
                    DB::commit();
                }

            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $jadwal = JadwalUjian::with('kelas', 'mapel', 'guru')->find(decrypt($id));

        if ($jadwal) {
            DB::beginTransaction();
            try {
                $jadwal->delete();

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil menghapus data',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 400,
                    'title' => "Terjadi kesalahan! saat menghapus data!",
                    'message' => "Pesan: $th"
                ]);
            } finally {
                DB::commit();
            }
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }
}
