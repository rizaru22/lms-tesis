@extends('layouts.dashboard')

@section('title', 'Nilai Peserta Didik')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline sticky">
                    <div class="card-header p-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="m-0 p-0 font-weight-bold ml-2">
                                <i class="fas text-primary fa-calendar-check mr-1"></i>
                                Nilai Peserta Didik
                            </h5>
                            <div>
                                <button id="cetakTable" class="btn btn-primary btn-sm ml-1">
                                    <i class="fas fa-print mr-1"></i> Cetak
                                </button>
                                <button id="refreshTable" class="btn btn-warning btn-sm ml-1"
                                    data-toggle="tooltip" title="Refresh Table">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline">

                    <div class="card-body table-responsive">
                        <table id="tableLaporanNilai" class="table table-hover laporan">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Siswa</th>
                                    <th colspan="14" class="text-center bg-primary">TUGAS</th>
                                    <th rowspan="2" class="text-center bg-primary">Rata Rata</th>
                                    <th rowspan="2" class="text-center">UTS</th>
                                    <th rowspan="2" class="text-center">UAS</th>
                                    <th rowspan="2" class="text-center">Total</th>
                                </tr>
                
                                <tr>
                                    @for ($i = 1; $i <= 14; $i++)
                                        <th>
                                            P{{ $i }}
                                        </th>
                                    @endfor
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>

    {{-- @include('dashboard.guru.laporan.nilai._data-nilai') --}}
@endsection

