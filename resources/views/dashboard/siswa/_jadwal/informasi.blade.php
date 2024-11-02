<div class="card-header text-center {{ $jadwal->hari == hari_ini()
    ? 'bg-success' : 'bg-secondary' }}">
<h4>{{ $jadwal->mapel->nama }}</h4>
<p class="m-0 p-0">
    {{ $jadwal->hari }} - {{ $jadwal->started_at . ' s.d. ' . $jadwal->ended_at . ' WIB'}}
</p>
</div>
<div class="card-body">
{{-- list bootstrap --}}
<ul class="list-group list-group-flush">
    <li class="list-group-item">
        <div class="row">
            <div class="col-lg-6 col-6">
                <p class="m-0 p-0 font-weight-bold">Kelas</p>
            </div>
            <div class="col-lg-6 col-6 text-muted font-weight-bold">
                <p class="m-0 p-0">{{ $jadwal->kelas->kode }}</p>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-lg-6 col-6">
                <p class="m-0 p-0 font-weight-bold">Guru</p>
            </div>
            <div class="col-lg-6 col-6 text-muted font-weight-bold">
                <p class="m-0 p-0">{{ $jadwal->guru->nama }}</p>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-lg-6 col-6">
                <p class="m-0 p-0 font-weight-bold">Kode Mata Pelajaran</p>
            </div>
            <div class="col-lg-6 col-6 text-muted font-weight-bold">
                <p class="m-0 p-0">{{ $jadwal->mapel->kode }}</p>
            </div>
        </div>
    </li>
    <li class="list-group-item">
        <div class="row">
            <div class="col-lg-6 col-6">
                <p class="m-0 p-0 font-weight-bold">JAM</p>
            </div>
            <div class="col-lg-6 col-6 text-muted font-weight-bold">
                <p class="m-0 p-0">{{ $jadwal->mapel->jam }}</p>
            </div>
        </div>
    </li>
</ul>
</div>
