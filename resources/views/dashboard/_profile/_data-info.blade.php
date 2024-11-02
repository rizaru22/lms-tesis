<div class="active tab-pane fade show" id="profile">
    <div class="row">
        <div class="col-md-12">

            @if (Auth::user()->isGuru())
                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>NIP</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            {{ Auth::user()->no_induk }}
                        </div>
                    </div>
                </div>
            @elseif (Auth::user()->isSiswa())
                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>NIS</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            {{ Auth::user()->no_induk }}
                        </div>
                    </div>
                </div>

                {{-- @elseif (Auth::user()->isKepsek())
                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>NIP</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            {{ Auth::user()->no_induk }}
                        </div>
                    </div>
                </div> --}}
                @elseif (Auth::user()->isOrtu())
                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>NIK</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            {{ Auth::user()->no_induk }}
                        </div>
                    </div>
                </div>

            @endif

            <div class="col-lg-7">
                <div class="row mb-3">
                    <div class="col-lg-4 col-4">
                        <b>Nama</b>
                    </div>
                    <div class="col-lg-8 col-8">
                        {{ Auth::user()->name }}
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="row @if (!Auth::user()->isAdmin()) mb-3 @endif">
                    <div class="col-lg-4 col-4">
                        <b>Email</b>
                    </div>
                    <div class="col-lg-8 col-8">
                        {{ Auth::user()->email }}
                    </div>
                </div>
            </div>

            @if (Auth::user()->isGuru())
                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>Kode Guru</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            {{ Auth::user()->guru->kode }}
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>Kelas</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            @foreach (Auth::user()->guru->kelas->unique('kode') as $item)
                                <h6 class="m-0 p-2 bg-primary mb-1"
                                    style="border-radius: 4px;">
                                    <i class="fas fa-school mr-2"></i>
                                    <span>{{ $item->kode }}</span>
                                </h6>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-4 col-4">
                            <b>Mata Pelajaran</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            @foreach (Auth::user()->guru->mapels as $item)
                                <h6 class="m-0 p-2 bg-primary mb-1"
                                    style="border-radius: 4px;">
                                    <i class="fas fa-book mr-2"></i>
                                    <span>{{ $item->nama }}</span>
                                </h6>
                            @endforeach
                        </div>
                    </div>
                </div>
            @elseif (Auth::user()->isSiswa())
                <div class="col-lg-7">
                    <div class="row mb-3">
                        <div class="col-lg-4 col-4">
                            <b>Kelas</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            <h6>
                                <i class="fas fa-school mr-2 text-primary"></i>
                                <span>{{ Auth::user()->siswa->kelas->first()->kode }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="row">
                        <div class="col-lg-4 col-4">
                            <b>Program Keahlian</b>
                        </div>
                        <div class="col-lg-8 col-8">
                            <h6>
                                <i class="fas fa-book-open mr-2 text-primary"></i>
                                <span>{{ Auth::user()->siswa->programkeahlian->first()->nama }}</span>
                            </h6>
                        </div>
                    </div>
                </div>
            @endif

        </div> {{-- END col-md-12 --}}
    </div> {{-- END ROW --}}
</div> {{-- END active tab-pane profile --}}
