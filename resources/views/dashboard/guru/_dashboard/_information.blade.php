<div class="row">

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white card-cyan card-outline">
            <div class="inner">
                <h3>{{ $belajar }}</h3>

                <p class="mb-2">Jadwal Pelajaran</p>
            </div>

            <div class="icon">
                <i class="fas fa-calendar-alt text-cyan"></i>
            </div>

            <a href="{{ route('manajemen.pelajaran.jadwal.guru.pelajaran.index') }}" class="small-box-footer">
                Selengkapnya
                <i class="fas fa-arrow-circle-right text-cyan"></i>
            </a>
        </div> {{-- END KULIAH --}}
    </div>

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white card-indigo card-outline">
            <div class="inner">
                <h3>{{ $ujian }}</h3>

                <p class="mb-2">Ujian</p>
            </div>

            <div class="icon">
                <i class="fas fa-file-alt text-indigo"></i>
            </div>

            <a href="{{ route('manajemen.pelajaran.jadwal.guru.ujian.index') }}" class="small-box-footer">
                Selengkapnya
                <i class="fas fa-arrow-circle-right text-indigo"></i>
            </a>
        </div>
    </div>{{-- END UJIAN --}}

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white card-purple card-outline">
            <div class="inner">
                <h3>{{ $materi }}</h3>

                <p class="mb-2">Materi</p>
            </div>

            <div class="icon">
                <i class="fas fa-book text-purple"></i>
            </div>

            <a href="{{ route('manajemen.pelajaran.jadwal.guru.pelajaran.index') }}" class="small-box-footer">
                Selengkapnya
                <i class="fas fa-arrow-circle-right text-purple"></i>
            </a>
        </div>
    </div> {{-- END MATERI --}}

    <div class="col-lg-3 col-6">
        <div class="small-box bg-white card-pink card-outline">
            <div class="inner">
                <h3>{{ $tugas }}</h3>

                <p class="mb-2">Tugas</p>
            </div>

            <div class="icon">
                <i class="fas fa-book-open text-pink"></i>
            </div>

            <a href="{{ route('manajemen.pelajaran.jadwal.guru.pelajaran.index') }}" class="small-box-footer">
                Selengkapnya
                <i class="fas fa-arrow-circle-right text-pink"></i>
            </a>
        </div>
    </div> {{-- END TUGAS --}}

</div>
