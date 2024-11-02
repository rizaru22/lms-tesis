<?php

namespace App\Http\Controllers\Admin\KelolaPengguna;

use App\Http\Controllers\Controller;
use App\Models\KelolaPengguna\Guru;
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

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = guru::with('user', 'kelas', 'mapels')->get();

        /**
         * Kenapa saya menggunakan query builder dibandingkan dengan eloquent yang diatas?
         * Karena agar lebih cepat dalam menampilkan data ketika banyak data (big) yang ditampilkan.
         * contoh: ada 1000 data, maka query builder akan lebih cepat daripada menggunakan eloquent
        */
        $data = DB::table('gurus')
            ->join('users', 'users.id', '=', 'gurus.user_id')
            ->leftJoin('guru_kelas', 'guru_kelas.guru_id', '=', 'gurus.id')
            ->leftJoin('kelas', 'kelas.id', '=', 'guru_kelas.kelas_id')
            ->leftJoin('guru_mapel', 'guru_mapel.guru_id', '=', 'gurus.id')
            ->leftJoin('mapels', 'mapels.id', '=', 'guru_mapel.mapel_id')
            ->select('gurus.*', 'users.foto as foto', 'kelas.kode as nama_kelas', 'mapels.nama as nama_mapel')
            ->orderBy('gurus.nama', 'asc')
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
                ->addColumn('guru', function ($data) {
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
                                <div class="small text-secondary" >' . $data->nip . '</div>
                            </div>
                        </a>
                    ';
                })
                ->rawColumns(['action', 'guru'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('dashboard.admin.manage-users.guru', [
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
            'nip' => 'required|unique:gurus,nip|numeric|digits_between:8,15',
            'nama' => 'required',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|max:16|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'mapel' => 'required',
            'kelas' => 'required',
        ], [
            'nip.required' => 'NIP tidak boleh kosong',
            'nip.numeric' => 'NIP harus berupa angka',
            'nip.digits_between' => 'NIP harus berjumlah 8 - 15 digit',
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
                    $fotoBaru = uniqid('GR-') . '.' . $foto->extension();
                    // Resize
                    $resize = Image::make($foto->path());
                    $resize->fit(1000, 1000)->save($path . '/' . $fotoBaru);
                }

                User::create([
                    'name' => $request->nama,
                    'no_induk' => $request->nip,
                    'email' => $request->email,
                    'foto' => $fotoBaru ?? 'avatar.png',
                    'password' => Hash::make($request->password),
                    'email_verified_at' => now(),
                ])->assignRole('guru');

                $guru = Guru::create([
                    'nip' => $request->nip,
                    'nama' => $request->nama,
                    'kode' => Str::upper(Str::substr($request->nama, 0, 2) . Str::random(2, 'onlyAlpha')),
                    'email' => $request->email,
                    'user_id' => User::where('no_induk', $request->nip)->first()->id,
                ]);

                $guru->kelas()->attach($request->kelas);
                $guru->mapels()->attach($request->mapel);

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
        $data = Guru::with('user', 'mapels', 'kelas')->find($id);

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
        $guru = Guru::with('user')->where('id', $id)->first();

        if ($guru) {
            $validator = Validator::make($request->all(), [
                'nip' => 'required|numeric|digits_between:8,15',
                'nama' => 'required',
                'email' => 'required|email',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
                'mapel' => 'required',
                'kelas' => 'required',
            ], [
                'nip.required' => 'NIP tidak boleh kosong',
                'nip.numeric' => 'NIP harus berupa angka',
                'nip.digits_between' => 'NIP harus berjumlah 8 - 15 digit',
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

                        if (File::exists($path . $guru->user->foto)) {
                            File::delete($path . $guru->user->foto);
                        }

                        $foto = $request->file('foto');
                        $fotoBaru = uniqid('GR-') . '.' . $foto->extension();

                        // Resize
                        $resize = Image::make($foto->path());
                        $resize->fit(1000, 1000)->save($path . '/' . $fotoBaru);
                        $guru->user->foto = $fotoBaru;
                    }

                    // for table users
                    $guru->user->no_induk = $request->nip;
                    $guru->user->email = $request->email;
                    $guru->user->name = $request->nama;
                    $guru->user->update();

                    // for table guru
                    $guru->nip = $request->nip;
                    $guru->nama = $request->nama;
                    $guru->email = $request->email;

                    $guru->kelas()->sync($request->kelas);
                    $guru->mapels()->sync($request->mapel);
                    $guru->update();

                    // if ($guru->wasChanged('nama')) {
                    //     $guru->kode = Str::upper(Str::substr($request->nama, 0, 2) . Str::random(2, 'onlyAlpha'));
                    //     $guru->update();
                    // } else {
                    //     $guru->kode = $request->kode;
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
        $guru = Guru::with('user')->find($id);

        if ($guru) {
            DB::beginTransaction();
            try {
                $path = 'assets/image/users/';
                if (File::exists($path . $guru->user->foto)) {
                    File::delete($path . $guru->user->foto);
                }

                $guru->user->delete();
                $guru->user->roles()->detach();

                $guru->delete();
                $guru->mapels()->detach();
                $guru->kelas()->detach();

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
