<?php

namespace App\Http\Controllers;

use App\Models\Ortu;
use Illuminate\Http\Request;

class OrtuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->cari){
            $cari=$request->cari;
            
            $data=Ortu::where('nama','like',"%".$cari."%")->paginate(5);
            
        }else{
            $data=Ortu::orderBy('nama')->paginate(5);
        }
        $data->appends($request->all());
        return view('ortu', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ortus = Ortu::all();
        return view('/ortu-tambah', compact('ortus'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data=$request->all();
        Ortu::create($data); // didapat dari model siswa
        return redirect('/ortu')->with('status','Data Berhasil Ditambahkan !!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ortu  $ortu
     * @return \Illuminate\Http\Response
     */
    public function show(Ortu $ortu)
    {
        return view('detailortu', compact('ortu'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ortu  $ortu
     * @return \Illuminate\Http\Response
     */
    public function edit(Ortu $ortu)
    {
        return view('ortu-ubah', compact('ortu') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ortu  $ortu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ortu $ortu)
    {
        $dl=Ortu::findOrfail($ortu->id); // tangkap data lama yg ada di model siswa dengan menangkap id
        $db=$request->all(); // tangkap semua permintaan data baru di simpan di variabel $db
        $dl->update($db); // update atau ubah $dl dengan $db
        return redirect('/ortu')->with('status','Data Berhasil Diubah !!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ortu  $ortu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ortu $ortu)
    {
        Ortu::destroy($ortu->id);
        return redirect('/ortu')->with('status','Data Berhasil Dihapus !!');
    }
}
