<?php

namespace App\Http\Controllers\Admin\ManajemenBelajar;

use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Guru;
use App\Models\ManajemenBelajar\Mapel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Mapel::all();

        if (request()->ajax()) {
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" id="' . $data->id . '" class="edit_btn btn btn-warning btn-sm mr-1 mt-1" data-toggle="tooltip" title="Edit"><i class="fas fa-pen"></i></button>';
                    $button .= '<button type="button" name="delete" id="' . $data->id . '" data-name="' . $data->nama . '" class="del_btn btn btn-danger btn-sm mt-1" data-toggle="tooltip" title="Hapus"><i class="fas fa-trash"></i></button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('dashboard.admin.manajemen-pelajaran.mapel');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        if ($request->ajax()) {
            $mapels = [];
            if ($request->has('q')) {
                $search = $request->q;
                $mapels = Mapel::select('id', 'nama')->where('nama', 'LIKE', "%$search%")
                    ->limit(5)
                    ->get();
            } else {
                $mapels = Mapel::select('id', 'nama')->limit(5)->get();
            }

            return response()->json($mapels);
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
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required|string|min:3|unique:mapels,nama',
                'jam' => 'required|numeric|between:1,9',
            ],
            [
                'jam.between' => 'Hanya boleh berisi 1 sampai 9',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'tipe' => 'validation',
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            DB::beginTransaction();
            try {

                $nama = $request->nama;

                Mapel::create([
                    'nama' => $nama,
                    'kode' => $this->getKodeFromName($nama),
                    'jam' => $request->jam,
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
        $mapel = Mapel::find($id);

        if (request()->ajax()) {
            if ($mapel) {
                return response()->json([
                    'status' => 200,
                    'data' => $mapel
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
            'nama' => 'required|string|min:3|unique:mapels,nama,' . $id,
            'jam' => 'required|numeric|between:1,9',
        ], [
            'jam.between' => 'Hanya boleh berisi 1 sampai 9',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'tipe' => 'validation',
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            DB::beginTransaction();
            try {
                $mapel = Mapel::find($id);

                if ($request->nama != $mapel->nama) {
                    $nama = $request->nama;
                    $mapel->nama = $nama;
                    $mapel->kode = $this->getKodeFromName($nama);
                }

                $mapel->jam = $request->jam ?? $mapel->jam;

                if ($mapel->isDirty()) {
                    $mapel->update();

                    return response()->json([
                        'status' => 200,
                        'icon' => 'success',
                        'message' => "Berhasil memperbarui data",
                    ]);
                } else {
                    return response()->json([
                        'status' => 200,
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
        $mapel = Mapel::find($id);

        DB::beginTransaction();
        try {
            $uses = Guru::join('guru_mapel', 'guru_mapel.guru_id', '=', 'gurus.id')
                ->where('guru_mapel.mapel_id', $id)
                ->get();

            if ($uses->count() > 0) {
                return response()->json([
                    'status' => 400,
                    'title' => "Oops!",
                    'message' => "Mata Pelajaran ini <strong>$mapel->nama</strong> tidak dapat dihapus karena masih digunakan! <br>Ada <strong>" . $uses->count() . " Guru</strong> yang masih menggunakan Mata pelajaran ini.",
                ]);
            } else {
                $mapel->delete();

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
    }

    /**
     * Get kode from name
     *
     * @param string $nama
     * @return string
     */
    public function getKodeFromName($nama)
    {
        $arr = explode(' ', $nama);
        $kode = '';
        foreach ($arr as $a) {
            $kode .= strtoupper(substr($a, 0, 1));
        }

        return $kode . '-' . rand(10, 99);
    }
}
