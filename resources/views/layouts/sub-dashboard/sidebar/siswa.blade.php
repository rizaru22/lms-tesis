@include('layouts.sub-dashboard.sidebar._brand')

<div class="sidebar">

    @include('layouts.sub-dashboard.sidebar._profile-info')

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">MAIN</li>
            <li class="nav-item">
                <a href="{{ route('siswa.dashboard') }}" class="nav-link {{ setActive('siswa.dashboard') }}">
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
            <li class="nav-item">
                <a id="kelasSiswa" href="javascript:void(0)"
                    class="nav-link kelas {{ setActive(['manajemen.pelajaran.kelas.siswa.index', 'manajemen.pelajaran.kelas.siswa.materi', 'manajemen.pelajaran.kelas.siswa.tugas']) }}">
                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                    <p>
                        Kelas
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a id="ujianSiswa" href="#" class="nav-link {{ setActive(['manajemen.pelajaran.ujian.siswa.pg.ujian', 'manajemen.pelajaran.ujian.siswa.essay.ujian']) }}">
                    <i class="nav-icon fa fa-file-alt"></i>
                    <p>
                        Ujian
                    </p>
                </a>
            </li>


            <li class="nav-item {{ menuOpen(['manajemen.pelajaran.jadwal.siswa.index', 'manajemen.pelajaran.ujian.siswa.index']) }}">
                <a href="javascript:void(0)"
                    class="nav-link {{ setActive('manajemen.pelajaran.jadwal.siswa.index', 'manajemen.pelajaran.ujian.siswa.index') }}">
                    <i class="nav-icon fa fa-calendar-alt"></i>
                    <p>
                        Jadwal
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview"
                    style="{{ d_block(['manajemen.pelajaran.jadwal.siswa.index', 'manajemen.pelajaran.ujian.siswa.index']) }}">
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.jadwal.siswa.index') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.siswa.index']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.ujian.siswa.index') }}"
                            class="nav-link {{ setActive('manajemen.pelajaran.ujian.siswa.index') }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ujian</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.ujian.siswa.riwayatUjian') }}" class="nav-link {{ setActive(['manajemen.pelajaran.ujian.siswa.riwayatUjian']) }}">
                    <i class="nav-icon fa fa-history"></i>
                    <p>
                        Riwayat Ujian
                    </p>
                </a>
            </li>

            <li class="nav-item mb-2 mt-1">
                <a href="#" class="nav-link btn-danger text-white" id="logoutButton">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p class="font-weight-bold">LOGOUT</p>
                </a>
            </li>

        </ul>
    </nav>

</div>

@push('js')
    <script>
        $(document).ready(function() {
            let ujianSw = $("#ujianSiswa"),
                kelasSw = $("#kelasSiswa");

            if (ujianSw.hasClass('active')) {
                ujianSw.removeClass("d-none");
                ujianSw.removeAttr('href');
                ujianSw.attr('href', 'javascript:void(0)');
            } else {
                ujianSw.addClass("d-none");
            }

            if (kelasSw.hasClass('active')) {
                kelasSw.removeClass("d-none");

                kelasSw.removeAttr('href');
                kelasSw.attr('href', 'javascript:void(0)');
            } else {
                kelasSw.addClass("d-none");

                // ALERT JIKA BELUM MEMILIH KELAS
                kelasSw.click(function() {
                    Swal.fire({
                        icon: 'warning',
                        html: 'Untuk mengakses menu kelas, silahkan pilih kelas terlebih dahulu di menu <b>Jadwal Kuliah</b>.',
                        allowOutsideClick: false,
                        confirmButtonText: 'Oke, paham!',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href =
                                "{{ route('manajemen.pelajaran.jadwal.siswa.index') }}";
                        }
                    });
                });
            }
        });
    </script>
@endpush
