<?php

namespace App\Http\Controllers\Admin\KelolaPengguna;

use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Ortu;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\ManajemenBelajar\Mapel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class OrtuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = ortu::with('user', 'kelas', 'mapels')->get();

        /**
         * Kenapa saya menggunakan query builder dibandingkan dengan eloquent yang diatas?
         * Karena agar lebih cepat dalam menampilkan data ketika banyak data (big) yang ditampilkan.
         * contoh: ada 1000 data, maka query builder akan lebih cepat daripada menggunakan eloquent
        */
        $data = DB::table('ortus')
            ->join('users', 'users.id', '=', 'ortus.user_id')
            ->leftJoin('ortu_kelas', 'ortu_kelas.ortu_id', '=', 'ortus.id')
            ->leftJoin('kelas', 'kelas.id', '=', 'ortu_kelas.kelas_id')
            ->leftJoin('mapel_ortu', 'mapel_ortu.ortu_id', '=', 'ortus.id')
            ->leftJoin('mapels', 'mapels.id', '=', 'mapel_ortu.mapel_id')
            ->select('ortus.*', 'users.foto as foto', 'kelas.kode as nama_kelas', 'mapels.nama as nama_mapel')
            ->orderBy('ortus.nama', 'asc')
            ->get();

        if (request()->ajax()) {
            $data = $data->groupBy('user_id')->map(function ($grouped) { // groupBy() untuk mengelompokkan data berdasarkan user_id
                $first = $grouped->first(); // first() untuk mengambil data pertama
                $first->nama_kelas = $grouped->pluck('nama_kelas')->unique(); // pluck() untuk mengambil data dari kolom nama_kelas
                $first->nama_mapel = $grouped->pluck('nama_mapel')->unique(); // pluck() untuk mengambil data dari kolom nama_mapel
                return $first;
            })->values(); // values() untuk mengembalikan array

            return datatables()->of($data)
                ->addColumn('action', function ($data) {
                    $button = '
                        <div class="d-flex justify-content-center m-0 p-0">
                            <button type="button" name="edit" id="' . $data->id . '" data-toggle="tooltip" title="Edit" class="editBtn btn btn-warning btn-sm mr-1 mt-1"><i class="fas fa-pen"></i></button>
                            <button type="button" name="delete" id="' . $data->id . '" data-toggle="tooltip" title="Hapus" class="delBtn btn btn-danger btn-sm mt-1"><i class="fas fa-trash"></i></button>
                        </div>
                    ';
                    return $button;
                })
                ->addColumn('ortu', function ($data) {
                    if (file_exists('assets/image/users/' . $data->foto)) {
                        $avatar = asset('assets/image/users/' . $data->foto);
                    } else {
                        $avatar = asset('assets/image/avatar.png');
                    }

                    return '
                        <a href="javascript:void(0)" class="d-flex align-items-center" style="cursor: default">
                            <img src="' . $avatar . '" width="40" class="avatar rounded-circle me-3">
                            <div class="d-block ml-3" >
                                <span class="fw-bold name-user">' . $data->nama . '</span>
                                <div class="small text-secondary" >' . $data->nik . '</div>
                            </div>
                        </a>
                    ';
                })
                ->rawColumns(['action', 'ortu'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('dashboard.admin.manage-users.ortu', [
            'mapels' => Mapel::all(),
            'kelas' => Kelas::all(),
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
        $validator = Validator::make($request->all(), [
            'nik' => 'required|unique:ortus,nik|numeric|digits_between:8,15',
            'nama' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|max:16|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'mapel' => 'required',
            'kelas' => 'required',
        ], [
            'nik.required' => 'NIK tidak boleh kosong',
            'nik.numeric' => 'NIK harus berupa angka',
            'nik.digits_between' => 'NIK harus berjumlah 8 - 15 digit',
            'nama.required' => 'Nama tidak boleh kosong',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minimal berjumlah 8 karakter',
            'password.max' => 'Password maksimal berjumlah 16 karakter',
            'password.confirmed' => 'Password tidak sama',
            'foto.image' => 'Foto harus berupa gambar',
            'foto.mimes' => 'Foto harus berupa gambar dengan format jpg, jpeg, atau png',
            'foto.max' => 'Foto maksimal berukuran 1 MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray()
            ]);
        } else {
            DB::beginTransaction();
            try {
                if ($request->hasFile('foto')) {
                    $path = 'assets/image/users/';
                    $foto = $request->file('foto');
                    $fotoBaru = uniqid('ORT-') . '.' . $foto->extension();
                    // Resize
                    $resize = Image::make($foto->path());
                    $resize->fit(1000, 1000)->save($path . '/' . $fotoBaru);
                }

                User::create([
                    'name' => $request->nama,
                    'no_induk' => $request->nik,
                    'email' => $request->email,
                    'foto' => $fotoBaru ?? 'avatar.png',
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                ])->assignRole('ortu');

                $ortu = Ortu::create([
                    'nik' => $request->nik,
                    'nama' => $request->nama,
                    'kode' => Str::upper(Str::substr($request->nama, 0, 2) . Str::random(2, 'onlyAlpha')),
                    'email' => $request->email,
                    'user_id' => User::where('no_induk', $request->nik)->first()->id,
                ]);

                $ortu->kelas()->attach($request->kelas);
                $ortu->mapels()->attach($request->mapel);

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil menyimpan data'
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 401,
                    'title' => 'Terjadi Kesalahan saat menyimpan data',
                    'message' => "message: $th"
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
        $data = Ortu::with('user', 'mapels', 'kelas')->find($id);

        if (request()->ajax()) {
            if ($data) {
                return response()->json([
                    'status' => '200',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => '404',
                    'message' => 'Data Guru Tidak Ditemukan'
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
        $ortu = Ortu::with('user')->where('id', $id)->first();

        if ($ortu) {
            $validator = Validator::make($request->all(), [
                'nik' => 'required|numeric|digits_between:8,15',
                'nama' => 'required',
                'email' => 'required|email',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
                'mapel' => 'required',
                'kelas' => 'required',
            ], [
                'nik.required' => 'nik tidak boleh kosong',
                'nik.numeric' => 'nik harus berupa angka',
                'nik.digits_between' => 'nik harus berjumlah 8 - 15 digit',
                'nama.required' => 'Nama tidak boleh kosong',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'foto.image' => 'Foto harus berupa gambar',
                'foto.mimes' => 'Foto harus berupa gambar dengan format jpg, jpeg, atau png',
                'foto.max' => 'Foto maksimal berukuran 1 MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors()->toArray()
                ]);
            } else {
                DB::beginTransaction();
                try {

                    if ($request->hasFile('foto')) {
                        $path = 'assets/image/users/';

                        if (File::exists($path . $ortu->user->foto)) {
                            File::delete($path . $ortu->user->foto);
                        }

                        $foto = $request->file('foto');
                        $fotoBaru = uniqid('ORTU-') . '.' . $foto->extension();

                        // Resize
                        $resize = Image::make($foto->path());
                        $resize->fit(1000, 1000)->save($path . '/' . $fotoBaru);
                        $ortu->user->foto = $fotoBaru;
                    }

                    // for table users
                    $ortu->user->no_induk = $request->nik;
                    $ortu->user->email = $request->email;
                    $ortu->user->name = $request->nama;
                    $ortu->user->update();

                    // for table ortu
                    $ortu->nik = $request->nik;
                    $ortu->nama = $request->nama;
                    $ortu->email = $request->email;

                    $ortu->kelas()->sync($request->kelas);
                    $ortu->mapels()->sync($request->mapel);
                    $ortu->update();

                    // if ($ortu->wasChanged('nama')) {
                    //     $ortu->kode = Str::upper(Str::substr($request->nama, 0, 2) . Str::random(2, 'onlyAlpha'));
                    //     $ortu->update();
                    // } else {
                    //     $ortu->kode = $request->kode;
                    // }

                    return response()->json([
                        'status' => 200,
                        'title' => 'Berhasil',
                        'message' => 'Berhasil memperbarui data'
                    ]);
                } catch (\Throwable $th) {
                    DB::rollBack();

                    return response()->json([
                        'status' => 401,
                        'title' => 'Terjadi Kesalahan saat memperbarui data',
                        'message' => "message: $th"
                    ]);
                } finally {
                    DB::commit();
                }
            }
        } else {
            return response()->json([
                'status' => 401,
                'title' => '',
                'message' => 'Data tidak ditemukan'
            ]);
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
        $ortu = Ortu::with('user')->find($id);

        if ($ortu) {
            DB::beginTransaction();
            try {
                $path = 'assets/image/users/';
                if (File::exists($path . $ortu->user->foto)) {
                    File::delete($path . $ortu->user->foto);
                }

                $ortu->user->delete();
                $ortu->user->roles()->detach();

                $ortu->delete();
                $ortu->mapels()->detach();
                $ortu->kelas()->detach();

                return response()->json([
                    'status' => 200,
                    'title' => 'Berhasil',
                    'message' => "Berhasil menghapus data"
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();

                return response()->json([
                    'status' => 401,
                    'title' => 'Terjadi Kesalahan saat menghapus data',
                    'message' => "message: $th"
                ]);
            } finally {
                DB::commit();
            }
        } else {
            return response()->json([
                'status' => 401,
                'title' => '',
                'message' => 'Data tidak ditemukan'
            ]);
        }
    }
}
