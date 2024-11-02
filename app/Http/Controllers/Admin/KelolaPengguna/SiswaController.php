<?php

namespace App\Http\Controllers\Admin\KelolaPengguna;

use App\Http\Controllers\Controller;
use App\Imports\Admin\SiswaImport;
use App\Models\ManajemenBelajar\Programkeahlian;
use App\Models\ManajemenBelajar\Kelas;
use App\Models\KelolaPengguna\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('siswas')
            ->leftJoin('programkeahlian', 'siswas.programkeahlian_id', '=', 'programkeahlian.id')
            ->leftJoin('kelas', 'siswas.kelas_id', '=', 'kelas.id')
            ->leftJoin('users', 'siswas.user_id', '=', 'users.id')
            ->selectRaw('siswas.*, programkeahlian.nama as programkeahlian_nama, kelas.kode as kelas_kode, foto')
            ->orderBy('siswas.kelas_id', 'asc')
            ->get();

        if (request()->ajax()) {
            $data = $data->transform(function ($item) {
                $item->programkeahlian_nama = $item->programkeahlian_nama;
                $item->kelas_kode = $item->kelas_kode;
                $item->register_at = Carbon::parse($item->created_at)->translatedFormat('d F Y');
                return $item;
            });

            if (request()->input('f_kelas') != null) {
                $data = collect($data)->where('kelas_kode', request()->f_kelas)->all();
            }

            if (request()->input('f_programkeahlian') != null) {
                $data = collect($data)->where('programkeahlian_nama', request()->f_programkeahlian)->all();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $button = '<button type="button" name="edit" value="' . $data->id . '" data-toggle="tooltip" title="Edit" class="edit_btn btn btn-warning btn-sm mr-1 mt-1"><i class="fas fa-pen"></i></button>';
                    $button .= '<button type="button" name="delete" value="' . $data->id . '" data-name="' . $data->nama . '" data-toggle="tooltip" title="Hapus" class="del_btn btn btn-danger btn-sm mt-1"><i class="fas fa-trash"></i></button>';
                    return $button;
                })
                ->addColumn('siswa', function ($data) {
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
                                <div class="small text-secondary" >' . $data->nis . '</div>
                            </div>
                        </a>
                    ';
                })
                ->rawColumns(['action', 'siswa'])
                ->make(true);
        }

        return view('dashboard.admin.manage-users.siswa', [
            'programkeahlian' => programkeahlian::get(),
            'kelas' => Kelas::get(),
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
            'nis' => 'required|unique:siswas,nis|numeric|digits_between:8,8',
            'nama' => 'required|alpha_space|string|min:3',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|max:16|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'programkeahlian' => 'required',
            'kelas' => 'required',
        ], [
            'nis.required' => 'NIS tidak boleh kosong',
            'nis.numeric' => 'NIS harus berupa angka',
            'nis.digits_between' => 'NIS harus berjumlah 8 digit',
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.alpha_space' => 'Nama hanya boleh berisi huruf dan spasi',
            'email.required' => 'Email tidak boleh kosong',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password tidak boleh kosong',
            'password.min' => 'Password minisal berjumlah 8 karakter',
            'password.max' => 'Password maksimal berjumlah 16 karakter',
            'password.confirmed' => 'Password tidak sama',
            'foto.image' => 'Foto harus berupa gambar',
            'foto.mimes' => 'Foto harus berupa gambar dengan format jpg, jpeg, atau png',
            'foto.max' => 'Foto maksimal berukuran 1 MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->errors()->toArray(),
            ]);
        } else {
            DB::beginTransaction();
            try {
                if ($request->hasFile('foto')) {
                    $path = 'assets/image/users/';
                    $foto = $request->file('foto');
                    $fotoBaru = uniqid('SW-') . '.' . $foto->extension();
                    // Resize
                    $resize = Image::make($foto->path());
                    $resize->fit(1000, 1000)->save($path . '/' . $fotoBaru);
                }

                User::create([
                    'name' => $request->nama,
                    'no_induk' => $request->nis,
                    'email' => $request->email,
                    'password' => bcrypt($request->password),
                    'foto' => $fotoBaru ?? 'avatar.png',
                ])->assignRole('siswa');

                $siswa = Siswa::create([
                    'nis' => $request->nis,
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'programkeahlian_id' => $request->programkeahlian,
                    'kelas_id' => $request->kelas,
                    'user_id' => User::where('no_induk', $request->nis)->first()->id,
                ]);

                $siswa->siswa_kelas()->attach($request->kelas);

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
        $data = Siswa::with('user', 'programkeahlian', 'kelas')->find($id);

        if (request()->ajax()) {
            if ($data) {
                return response()->json([
                    'status' => '200',
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'status' => '404',
                    'message' => 'Data siswa Tidak Ditemukan'
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
        $siswa = Siswa::with('user')->find($id);

        if ($siswa) {
            $validator = Validator::make($request->all(), [
                'nis' => 'required|numeric|digits_between:8,8|unique:siswas,nis,' . $siswa->id,
                'nama' => 'required|alpha_space|string|min:3',
                'email' => 'required|email|unique:users,email,' . $siswa->user->id,
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
                'programkeahlian' => 'required',
                'kelas' => 'required',
            ], [
                'nis.required' => 'NIS tidak boleh kosong',
                'nis.numeric' => 'NIS harus berupa angka',
                'nis.digits_between' => 'NIS harus berjumlah 8 digit',
                'nama.required' => 'Nama tidak boleh kosong',
                'nama.alpha_space' => 'Nama hanya boleh berisi huruf dan spasi',
                'email.required' => 'Email tidak boleh kosong',
                'email.email' => 'Email tidak valid',
                'foto.image' => 'Foto harus berupa gambar',
                'foto.mimes' => 'Foto harus berupa gambar dengan format jpg, jpeg, atau png',
                'foto.max' => 'Foto maksimal berukuran 1 MB',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 400,
                    'errors' => $validator->errors()->toArray(),
                ]);
            } else {
                DB::beginTransaction();
                try {
                    if ($request->hasFile('foto')) {
                        $path = 'assets/image/users/';
                        if (File::exists($path . $siswa->user->foto)) {
                            File::delete($path . $siswa->user->foto);
                        }
                        $foto = $request->file('foto');
                        $fotoBaru = uniqid('SW-') . '.' . $foto->extension();
                        // Resize
                        $resize = Image::make($foto->path());
                        $resize->fit(1000, 1000)->save($path . '/' . $fotoBaru);

                        $siswa->user->foto = $fotoBaru;
                    }

                    // untuk update data di table users
                    $siswa->user->name = $request->nama;
                    $siswa->user->no_induk = $request->nis;
                    $siswa->user->email = $request->email;
                    $siswa->user->update();

                    // untuk update data di table siswas
                    $siswa->nis = $request->nis;
                    $siswa->nama = $request->nama;
                    $siswa->email = $request->email;
                    $siswa->programkeahlian_id = $request->programkeahlian;
                    $siswa->kelas_id = $request->kelas;
                    $siswa->update();

                    $siswa->siswa_kelas()->sync($request->kelas);

                    return response()->json([
                        'status' => 200,
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
                'message' => 'Data Siswa Tidak Ditemukan'
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
        $siswa = Siswa::with('user')->find($id);

        if ($siswa) {
            DB::beginTransaction();
            try {
                $path = 'assets/image/users/';
                if (File::exists($path . $siswa->user->foto)) {
                    File::delete($path . $siswa->user->foto);
                }

                $siswa->user->delete();
                $siswa->user->roles()->detach();
                $siswa->delete();
                $siswa->siswa_kelas()->detach();

                return response()->json([
                    'status' => 200,
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
                'message' => 'Data Siswa Tidak Ditemukan'
            ]);
        }
    }

    /**
     * Import data from excel.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:5120',
        ], [
            'file_excel.required' => 'File excel tidak boleh kosong',
            'file_excel.mimes' => 'File excel harus berupa file dengan format xlsx, xls, atau csv',
            'file_excel.max' => 'File excel maksimal berukuran 5 MB',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'val' => true,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        DB::beginTransaction();
        try {
            $import = new SiswaImport();
            $import->excel($request->file('file_excel'));

            return response()->json([
                'status' => 200,
                'message' => 'Berhasil mengimport data siswa'
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'status' => 400,
                'message' => "{$th->getMessage()}"
            ]);
        } finally {
            DB::commit();
        }
    }
}
