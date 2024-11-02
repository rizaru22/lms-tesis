<?php

namespace App\Http\Controllers;

use App\Models\Nilaisiswa;
use Illuminate\Http\Request;

class NilaisiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $nilaisiswa=nilaisiswa::all();
        // return view('nilai');
        return view('dashboard.ortu.nilai');
        return view('dashboard.ortu.absen');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nilaisiswa  $nilaisiswa
     * @return \Illuminate\Http\Response
     */
    public function show(Nilaisiswa $nilaisiswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Nilaisiswa  $nilaisiswa
     * @return \Illuminate\Http\Response
     */
    public function edit(Nilaisiswa $nilaisiswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nilaisiswa  $nilaisiswa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nilaisiswa $nilaisiswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nilaisiswa  $nilaisiswa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nilaisiswa $nilaisiswa)
    {
        //
    }
}
