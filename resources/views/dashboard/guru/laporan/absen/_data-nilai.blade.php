@php
    $nama_mpl = Auth::user()->guru->mapels->find($jadwal->mapel_id)->nama ?? $jadwal->mapel->nama;
    $title = "Laporan Absensi Kelas {$jadwal->kelas->kode} -  $nama_mpl"
@endphp

<div class="card card-primary card-outline">
    <div class="card-header">
        <div class="d-flex align-items-center justify-content-between">
            <h5 class="font-weight-bold p-0 m-0 title-absen" data-title="{{ $title }}">
                <i class="fas fa-chart-line text-primary mr-2"></i>
                {{ $title }}
            </h5>
        </div>
    </div>
</div>

<div class="card card-primary card-outline mb-2 sticky">
    <div class="card-header p-2">
        <div class="d-flex align-items-center">
            <a href="{{ route('manajemen.pelajaran.laporan.guru.exports.absen', [encrypt($kelas_id), encrypt($mapel_id)]) }}"
                class="btn btn-success btn-sm">
                <i class="fas fa-file-excel mr-1"></i>
                Ekspor Excel
            </a>
            <button id="cetakTable" class="btn btn-primary btn-sm ml-1">
                <i class="fas fa-print mr-1"></i> Cetak
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table id="tableLaporanAbsen" class="table table-hover laporan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    @for ($i = 1; $i <= 16; $i++)
                        <th>
                            P{{ $i }}
                        </th>
                    @endfor
                    <th>Total Hadir</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
