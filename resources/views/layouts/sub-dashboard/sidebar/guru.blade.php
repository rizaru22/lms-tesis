@include('layouts.sub-dashboard.sidebar._brand')

<div class="sidebar">

    @include('layouts.sub-dashboard.sidebar._profile-info')

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">MAIN</li>
            <li class="nav-item">
                <a href="{{ route('guru.dashboard') }}" class="nav-link {{ setActive('guru.dashboard') }}">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            <li class="nav-header">AKUN</li>
            <li class="nav-item">
                <a href="{{ route('profile.index') }}" class="nav-link {{ setActive('profile.index') }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Profile
                    </p>
                </a>
            </li>

            <li class="nav-header">MANAJEMEN PELAJARAN</li>

            {{-- <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.jadwal.guru.pelajaran.index') }}"
                    class="nav-link {{ setActive('manage.users.guru.index') }}">
                    <i class="nav-icon fas fa-book-open"></i>
                    <p>
                        Modul Ajar / RPP
                    </p>
                </a>
            </li> --}}


            <li class="nav-item">
                <a id="kelasGuru" href="{{ route('manajemen.pelajaran.jadwal.guru.pelajaran.index') }}"
                    class="nav-link kelas {{ setActive(['manajemen.pelajaran.kelas.index', 'manajemen.pelajaran.kelas.guru.index', 'manajemen.pelajaran.materi.guru.index', 'manajemen.pelajaran.tugas.guru.index', 'manajemen.pelajaran.tugas.guru.show']) }}">
                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                    <p>
                        Kelas
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a id="ujianGuru" href="#"
                    class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.guru.ujian.show', 'manajemen.pelajaran.jadwal.guru.ujian.soal.pg.create', 'manajemen.pelajaran.jadwal.guru.ujian.soal.pg.edit', 'manajemen.pelajaran.jadwal.guru.ujian.soal.essay.create', 'manajemen.pelajaran.jadwal.guru.ujian.soal.essay.edit']) }}">
                    <i class="nav-icon fa fa-file-alt"></i>
                    <p>
                        Ujian
                    </p>
                </a>
            </li>

            <li
                class="nav-item {{ menuOpen(['manajemen.pelajaran.jadwal.guru.kuliah.index', 'manajemen.pelajaran.jadwal.guru.ujian.index']) }}">
                <a href="javascript:void(0)"
                    class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.guru.pelajaran.index', 'manajemen.pelajaran.jadwal.guru.ujian.index']) }}">
                    <i class="nav-icon fa fa-calendar-alt"></i>
                    <p>
                        Jadwal
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview"
                    style="{{ d_block(['manajemen.pelajaran.jadwal.guru.pelajaran.index', 'manajemen.pelajaran.jadwal.guru.ujian.index']) }}">
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.jadwal.guru.pelajaran.index') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.guru.pelajaran.index']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.jadwal.guru.ujian.index') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.guru.ujian.index']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ujian</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.absen.guru.index') }}"
                    class="nav-link {{ setActive('manajemen.pelajaran.absen.guru.index') }}">
                    <i class="nav-icon fa fa-calendar-check"></i>
                    <p>
                        Absensi Hari Ini
                    </p>
                </a>
            </li>

            <li class="nav-item {{ menuOpen(['manajemen.pelajaran.laporan.guru.absen', 'manajemen.pelajaran.laporan.guru.nilai.tugas', 'manajemen.pelajaran.laporan.guru.nilai.ujian', 'manajemen.pelajaran.laporan.guru.nilai']) }}">
                <a href="javasript:void(0)"
                    class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.absen', 'manajemen.pelajaran.laporan.guru.nilai.tugas', 'manajemen.pelajaran.laporan.guru.nilai.ujian', 'manajemen.pelajaran.laporan.guru.nilai']) }}">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        Laporan
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview"
                    style="{{ d_block(['manajemen.pelajaran.laporan.guru.absen', 'manajemen.pelajaran.laporan.guru.nilai.tugas', 'manajemen.pelajaran.laporan.guru.nilai.ujian', 'manajemen.pelajaran.laporan.guru.nilai']) }}">
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.laporan.guru.absen') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.absen']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Absensi</p>
                        </a>
                    </li>

{{-- pengganti --}}

{{-- <li class="nav-item">
    <a href="{{ route('manajemen.pelajaran.laporan.guru.nilai.tugas') }}"
        class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.nilai.tugas']) }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Nilai Tugas</p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('manajemen.pelajaran.laporan.guru.nilai.ujian') }}" class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.nilai.ujian']) }}">
        <i class="far fa-circle nav-icon"></i>
        <p>Nilai Ujian</p>
    </a>
</li> --}}


{{-- yang diganti --}}

                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.laporan.guru.nilai') }}" class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.nilai']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>
                                Nilai <small class="text-muted">(Tugas & Ujian)</small>
                            </p>
                        </a>
                    </li>

{{-- yang di komentari ADA MASALAH --}}

                    {{-- <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.laporan.guru.nilai.tugas') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.nilai.tugas']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Nilai Tugas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.laporan.guru.nilai.ujian') }}" class="nav-link {{ setActive(['manajemen.pelajaran.laporan.guru.nilai.ujian']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Nilai Ujian</p>
                        </a>
                    </li> --}}
                    
{{-- yang di komentari --}}

                </ul>
            </li>

            <li class="nav-item mb-2 mt-1">
                <a href="javascript:void(0)" class="nav-link btn-danger text-white" id="logoutButton">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p class="font-weight-bold">LOGOUT</p>
                </a>
            </li>

        </ul>
    </nav>

</div>

@push('js')
    <script>
        let ujianGuru = $("#ujianGuru"),
            kelasGuru = $("#kelasGuru");

        if (ujianGuru.hasClass('active')) {
            ujianGuru.removeClass("d-none")
                .removeAttr('href')
                .attr('href', 'javascript:void(0)');
        } else {
            ujianGuru.addClass("d-none");
        }

        if (kelasGuru.hasClass('active')) {
            kelasGuru.removeClass("d-none")
                .removeAttr('href')
                .attr('href', 'javascript:void(0)');
        } else {
            kelasGuru.addClass("d-none");
        }
    </script>
@endpush
