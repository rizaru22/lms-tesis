<?php

namespace App\Http\Controllers\Admin\ManajemenBelajar\Jadwal;

use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Guru;
use App\Models\ManajemenBelajar\GuruKelas;
use Illuminate\Http\Request;
use App\Models\ManajemenBelajar\Jadwal\Belajar;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\ManajemenBelajar\Materi;
use App\Models\ManajemenBelajar\Mapel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class BelajarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = Belajar::with('kelas', 'mapel', 'guru')
            ->orderBy(Kelas::select('kode')->whereColumn('id', 'kelas_id'), 'asc')
            ->orderBy(Mapel::select('nama')->whereColumn('id', 'mapel_id'), 'asc')
            ->get();

        if ($request->ajax()) {
            $data = $data->transform(function ($item) {
                $item->kelas_jadwal = $item->kelas->kode;
                $item->mapel_jadwal = $item->mapel->nama;
                $item->guru_jadwal = $item->guru->nama;

                return $item;
            });

            if ($request->filterKelas != null) {
                $data = collect($data)->where('kelas_jadwal', $request->filterKelas)->all();
            }

            if ($request->filtermapel != null) {
                $data = collect($data)->where('mapel_jadwal', $request->filterMapel)->all();
            }

            if ($request->filterGuru != null) {
                $data = collect($data)->where('guru_jadwal', $request->filterGuru)->all();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit_btn btn btn-warning btn-sm mr-1 mt-1" data-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></button>';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" class="del_btn btn btn-danger btn-sm mt-1" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.admin.manajemen-pelajaran.jadwal.pelajaran', [
            'data_kelas' => Kelas::all(),
            'data_mapel' => Mapel::all(),
            'data_guru' => Guru::all(),
            'data_hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu', 'Minggu'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkIfExist = Belajar::where('kelas_id', $request->kelas)
            ->where('mapel_id', $request->mapel)
            ->where('guru_id', $request->guru)
            ->first();

        if ($checkIfExist) {
            return response()->json([
                'status' => 401,
                'message' => '<span class="font-weight-bold">Mata pelajaran di kelas ini sudah ada!</span> <hr> Silahkan pilih mata pelajaran yang lain.',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'kelas' => 'required',
            'mapel' => 'required',
            'guru' => 'required',
            'hari' => 'required',
            'started' => 'required',
            'ended' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()->toArray(),
                'status' => 400,
            ]);
        } else {
            DB::beginTransaction();
            try {
                Belajar::create([
                    'kelas_id' => $request->kelas,
                    'mapel_id' => $request->mapel,
                    'guru_id' => $request->guru,
                    'hari' => $request->hari,
                    'started_at' => $request->started,
                    'ended_at' => $request->ended,
                ]);

                DB::table('guru_kelas')->insert([
                    'guru_id' => $request->guru,
                    'kelas_id' => $request->kelas,
                    'mapel_id' => $request->mapel,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                $kelas = Kelas::find($request->kelas);

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 401,
                    'title' => "Terjadi kesalahan! saat menyimpan data!",
                    'message' => "Pesan: $th"
                ]);
            } finally {
                DB::commit();
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
        $data = Belajar::with(['kelas:id,kode', 'mapel:id,nama', 'guru', 'absens' => function ($q) {
            $q->where("parent", '0');
        }])->find($id);

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
        $data = Belajar::with('kelas', 'mapel', 'guru')->find($id);

        if ($data) {
            $checkIfExist = Belajar::where('id', '!=', $id)
                ->where('kelas_id', $request->kelas)
                ->where('mapel_id', $request->mapel)
                ->where('guru_id', $request->guru)
                ->first();

            if ($checkIfExist) {
                return response()->json([
                    'status' => 401,
                    'message' => '<span class="font-weight-bold">Mata Pelajaran di kelas ini sudah ada!</span> <hr> Silahkan pilih mata pelajaran yang lain.',
                ]);
            }

            $validator = Validator::make($request->all(), [
                'kelas' => 'required',
                'mapel' => 'required',
                'guru' => 'required',
                'hari' => 'required',
                'started' => 'required',
                'ended' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()->toArray(),
                    'status' => 400,
                ]);
            } else {
                DB::beginTransaction();
                try {
                    DB::table('guru_kelas')
                        ->where('guru_id', $data->guru_id)
                        ->where('kelas_id', $data->kelas_id)
                        ->where('mapel_id', $data->mapel_id)
                        ->update([
                            'mapel_id' => $request->mapel ?? $data->mapel_id,
                            'kelas_id' => $request->kelas ?? $data->kelas_id,
                        ]);

                    $data->update([
                        'kelas_id' => $request->kelas,
                        'mapel_id' => $request->mapel,
                        'guru_id' => $request->guru,
                        'hari' => $request->hari,
                        'started_at' => $request->started,
                        'ended_at' => $request->ended,
                    ]);

                    return response()->json([
                        'status' => 200,
                        'message' => 'Berhasil memperbarui data',
                    ]);
                } catch (\Throwable $th) {
                    DB::rollBack();

                    return response()->json([
                        'status' => 401,
                        'title' => "Terjadi kesalahan! saat memperbarui data!",
                        'message' => "Pesan: $th"
                    ]);
                } finally {
                    DB::commit();
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function reset(Request $request)
    {
        // Reset semua yang berhubungan dengan jadwal belajar ini
        $belajar = Belajar::with('absens', 'tugas')->find($request->id);

        if ($request->ajax()) {
            DB::beginTransaction();
            try {
                foreach ($belajar->absens as $absen) {
                    $materi = Materi::where('pertemuan', $absen->pertemuan)
                        ->where('kelas_id', $belajar->kelas_id)
                        ->where('mapel_id', $belajar->mapel_id)
                        ->first();

                    if ($materi && $materi->tipe == 'pdf') {
                        if (File::exists('assets/file/materi/' . $materi->file_or_link)) {
                            File::delete('assets/file/materi/' . $materi->file_or_link);
                        }
                    }

                    if ($materi) {
                        $materi->delete();
                    }

                    $absen->delete();
                }

                foreach ($belajar->tugas as $tugas) {
                    $tugas->delete();
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil mereset data',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 400,
                    'title' => "Terjadi kesalahan! saat mereset data!",
                    'message' => "Pesan: $th"
                ]);
            } finally {
                DB::commit();
            }
        } else {
            abort(404);
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
        $jadwal = Belajar::with('kelas', 'mapel', 'guru')->find($id);

        if ($jadwal) {
            DB::beginTransaction();
            try {
                // check jika guru tidak mengampu matabelajar lain
                $checkGuru = GuruKelas::where('kelas_id', $jadwal->kelas_id)
                    ->where('guru_id', $jadwal->guru_id)
                    ->where('mapel_id', null)
                    ->get();

                if ($checkGuru->isNotEmpty()) { // jika tidak ada matabelajar yang diampu
                    GuruKelas::where('kelas_id', $jadwal->kelas_id)
                        ->where('guru_id', $jadwal->guru_id)
                        ->where('mapel_id', $jadwal->mapel_id)
                        ->delete();
                } else { // jika ada matabelajar yang diampu
                   GuruKelas::where('kelas_id', $jadwal->kelas_id)
                        ->where('guru_id', $jadwal->guru_id)
                        ->where('mapel_id', $jadwal->mapel_id)
                        ->update([
                            'mapel_id' => null,
                        ]);
                }

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
