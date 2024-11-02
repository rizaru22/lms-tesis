<div class="list-group">
    <button type="button" class="list-group-item list-group-item-action active cursor_default">
        <h6 class="m-0 p-0 text-white font-weight-bold">Informasi Kelas</h6>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">Kelas</p>
            </div>
            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">{{ $jadwal->kelas->kode }}</p>
            </div>
        </div>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">Guru</p>
            </div>

            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">{{ $jadwal->guru->nama }}</p>
            </div>
        </div>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">Mata Pelajaran</p>
            </div>

            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">{{ $jadwal->mapel->kode }}</p>
            </div>
        </div>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">JAM</p>
            </div>

            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">{{ $jadwal->mapel->jam }}</p>
            </div>
        </div>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">Hari</p>
            </div>

            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">{{ $jadwal->hari }}</p>
            </div>
        </div>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">Masuk</p>
            </div>

            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">
                    {{ Carbon\Carbon::parse($jadwal->started_at)->translatedFormat('H:i') . ' WIB' }}</p>
            </div>
        </div>
    </button>
    <button type="button" class="list-group-item list-group-item-action cursor_default">
        <div class="row">
            <div class="col-lg-5 col-4">
                <p class="m-0 p-0 font-weight-bold">Keluar</p>
            </div>

            <div class="col-lg-7 col-4">
                <p class="m-0 p-0">
                    {{ Carbon\Carbon::parse($jadwal->ended_at)->translatedFormat('H:i') . ' WIB' }}
                </p>
            </div>
        </div>
    </button>
</div>
