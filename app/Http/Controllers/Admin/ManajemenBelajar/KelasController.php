<?php

namespace App\Http\Controllers\Admin\ManajemenBelajar;

use App\Http\Controllers\Controller;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\KelolaPengguna\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('kelas')
            ->leftJoin('siswa_kelas', 'kelas.id', '=', 'siswa_kelas.kelas_id')
            ->leftJoin('siswas', 'siswa_kelas.siswa_id', '=', 'siswas.id')
            ->leftJoin('guru_kelas', 'kelas.id', '=', 'guru_kelas.kelas_id')
            ->leftJoin('gurus', 'guru_kelas.guru_id', '=', 'gurus.id')
            ->select(
                'kelas.*',
                DB::raw('GROUP_CONCAT(siswas.nama SEPARATOR ", ") as siswa'),
                DB::raw('GROUP_CONCAT(gurus.nama SEPARATOR ", ") as guru_mengajar')
            )
            ->groupBy('kelas.id', 'kelas.kode', 'kelas.created_at', 'kelas.updated_at')
            ->get();

        $kelas = Kelas::with('siswa')->get();

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('daftar_siswa', function ($data) use ($kelas) {
                    $countSw = 0; // inisialisasi jumlah siswa
                    foreach ($kelas as $kls) { // cari kelas
                        if ($kls->id == $data->id) { // jika kelas ditemukan
                            $countSw = count($kls->siswa); // hitung jumlah siswa
                        }
                    }

                    if ($data->siswa) { // jika kelas memiliki siswa
                        $swArr = explode(', ', $data->siswa);
                        $uniqueArr = array_unique($swArr);

                        // jika jumlah siswa lebih dari 10 dan harus unik
                        if (count($uniqueArr) >= 10) {
                            $siswa = array_slice($uniqueArr, 0, 10);
                            return implode(', ', $siswa) . ', <b>dan ' . ($countSw - count($siswa)) . ' siswa lainnya..</b>'; // tampilkan 10 siswa dan sisanya
                        } else { // jika jumlah siswa kurang dari 10
                            return implode(', ', $uniqueArr);
                        }
                    } else {
                        return "<span class='badge badge-danger'>Belum memiliki siswa</span>";
                    }
                })
                ->addColumn('guru_mengajar', function ($data) {
                    $guruArr = explode(', ', $data->guru_mengajar);
                    $uniqueArr = array_unique($guruArr);
                    $guru = implode(', ', $uniqueArr);

                    return $data->guru_mengajar ? $guru : "<span class='badge badge-danger'>Belum memiliki guru</span>";
                })
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" id="' . $data->id . '" class="edit_btn btn btn-warning btn-sm mr-1 mt-1" data-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></button>';
                    $button .= '<button type="button" id="' . $data->id . '" data-kode="' . $data->kode . '" class="del_btn btn btn-danger btn-sm mt-1" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action', 'daftar_siswa', 'guru_mengajar'])
                ->make(true);
        }

        return view('dashboard.admin.manajemen-pelajaran.kelas');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $kelas = [];
            if ($request->has('q')) {
                $search = $request->q;
                $kelas = Kelas::select('id', 'kode')->where('kode', 'LIKE', "%$search%")
                    ->limit(5)
                    ->get();
            } else {
                $kelas = Kelas::select('id', 'kode')->limit(5)->get();
            }
            return response()->json($kelas);
        } else {
            abort(404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|unique:kelas,kode'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            DB::beginTransaction();
            try {
                Kelas::create([
                    'kode' => $request->kode,
                ]);

                return response()->json([
                    'status' => 200,
                    'message' => "Berhasil menyimpan data",
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 400,
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
        $kelas = Kelas::find($id);

        if (request()->ajax()) {
            if ($kelas) {
                return response()->json([
                    'status' => 200,
                    'data' => $kelas
                ]);
            } else {
                return response()->json([
                    'status' => 400,
                    'message' => 'Data tidak ditemukan!'
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
        $validator = Validator::make($request->all(), [
            'kode' => 'required|string|unique:kelas,kode,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            DB::beginTransaction();
            try {
                $kelas = Kelas::find($id);

                $kelas->kode = $request->kode;

                if ($kelas->isDirty()) {
                    $kelas->update();

                    return response()->json([
                        'status' => 200,
                        'message' => "Berhasil memperbarui data",
                    ]);
                } else {
                    return response()->json([
                        'status' => 201,
                        'message' => "Tidak ada perubahan data",
                    ]);
                }
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kelas = Kelas::find($id);

        if ($kelas) {
            DB::beginTransaction();
            try {
                $usesSiswa = $kelas->siswa->count();
                $usesGuru = $kelas->gurus->count();

                if ($usesSiswa > 0 || $usesGuru > 0) {

                    if ($usesSiswa > 0 && $usesGuru > 0) {
                        $msg = "Kelas ini sedang digunakan oleh $usesSiswa siswa dan $usesGuru guru";
                    } else if ($usesGuru > 0) {
                        $msg = "Kelas ini sedang digunakan oleh $usesGuru guru";
                    } else if ($usesSiswa > 0) {
                        $msg = "Kelas ini sedang digunakan oleh $usesSiswa siswa";
                    }

                    return response()->json([
                        'status' => 400,
                        'title' => "Gagal menghapus data!",
                        'message' => $msg
                    ]);
                } else {
                    $kelas->delete();

                    return response()->json([
                        'status' => 200,
                        'message' => "Berhasil menghapus data",
                    ]);
                }
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
                'message' => 'Data tidak ditemukan!'
            ]);
        }
    }
}
