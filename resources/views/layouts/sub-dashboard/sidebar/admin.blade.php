@include('layouts.sub-dashboard.sidebar._brand')

<div class="sidebar">

    @include('layouts.sub-dashboard.sidebar._profile-info')

    {{--
        Function setActive(), menuOpen() is defined in app/Helpers/Helper.php
    --}}

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
            data-accordion="false">

            <li class="nav-header">MAIN</li>
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ setActive('admin.dashboard') }}">
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
             <li class="nav-item {{ menuOpen(['manajemen.pelajaran.jadwal.admin.pelajaran.index', 'manajemen.pelajaran.jadwal.admin.ujian.index']) }}">
                <a href="javascript:void(0)" class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.admin.pelajaran.index', 'manajemen.pelajaran.jadwal.admin.ujian.index']) }}">
                    <i class="nav-icon fa fa-calendar-alt"></i>
                    <p>
                        Jadwal
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>

                <ul class="nav nav-treeview" style="{{ d_block(['manajemen.pelajran.jadwal.admin.pelajaran.index', 'manajemen.kuliah.jadwal.admin.ujian.index']) }}">
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.jadwal.admin.pelajaran.index') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.admin.pelajaran.index']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Pelajaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('manajemen.pelajaran.jadwal.admin.ujian.index') }}"
                            class="nav-link {{ setActive(['manajemen.pelajaran.jadwal.admin.ujian.index']) }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Ujian</p>
                        </a>
                    </li>
                </ul>
            </li>


            {{-- <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.prodi.index') }}"
                    class="nav-link {{ setActive('manajemen.pelajaran.prodi.index') }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>
                        Program Studi
                    </p>
                </a>
            </li> --}}

            <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.programkeahlian.index') }}"
                    class="nav-link {{ setActive('manajemen.pelajaran.programkeahlian.index') }}">
                    <i class="nav-icon fas fa-graduation-cap"></i>
                    <p>
                        Program Keahlian
                    </p>
                </a>
            </li>

          

            <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.mapel.index') }}"
                    class="nav-link {{ setActive('manajemen.pelajaran.mapel.index') }}">
                    <i class="nav-icon fa fa-book-open"></i>
                    <p>
                        Mata Pelajaran
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.kelas.index') }}"
                    class="nav-link {{ setActive('manajemen.pelajaran.kelas.index') }}">
                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                    <p>
                        Kelas
                    </p>
                </a>
            </li>

            <li class="nav-header">KELOLA PENGGUNA</li>


            {{-- <li class="nav-item">
                <a href="{{ route('manage.users.kepsek.index') }}"
                    class="nav-link {{ setActive('manage.users.kepsek.index') }}">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                        Pengawas & Kepala
                    </p>
                </a>
            </li> --}}

            <li class="nav-item">
                <a href="{{ route('manage.users.guru.index') }}"
                    class="nav-link {{ setActive('manage.users.guru.index') }}">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                        Guru
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('manage.users.ortu.index') }}"
                    class="nav-link {{ setActive('manage.users.ortu.index') }}">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                        Orang Tua Siswa
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('manage.users.siswa.index') }}"
                    class="nav-link {{ setActive('manage.users.siswa.index') }}">
                    <i class="nav-icon fas fa-user-graduate"></i>
                    <p>
                        Siswa
                    </p>
                </a>

            {{-- </li>
            <li class="nav-item">
                <a href="{{ route('manage.users.user.index') }}"
                    class="nav-link {{ setActive('manage.users.user.index') }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Pengguna
                    </p>
                </a>
            </li>

            <li class="nav-header">ROLE & PERMISSION</li>
            <li class="nav-item">
                <a href="{{ route('role.permission.role.index') }}"
                    class="nav-link {{ setActive('role.permission.role.index') }}">
                    <i class="nav-icon fas fa-user-tag"></i>
                    <p>
                        Role
                    </p>
                </a>
            </li> --}}

            {{--
                NOTE: SEBENARNYA FITUR INI GA BERGUNA, KARENA SUDAH ADA ROLE(ADMIN, GURU, SISWA) SETIAP PENGGUNA-NYA
                MAKANYA SAYA COMMENT, JIKA INGIN MENGGUNAKANNYA, SILAHKAN HAPUS KOMENTAR-NYA.
            --}}


            {{-- <li class="nav-item">
                <a href="{{ route('role.permission.permission.index') }}"
                    class="nav-link {{ setActive('role.permission.permission.index') }}">
                    <i class="nav-icon fas fa-user-lock"></i>
                    <p>
                        Permission
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('role.permission.label.permission.index') }}"
                    class="nav-link {{ setActive('role.permission.label.permission.index') }}">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>
                        Grup Permission
                    </p>
                </a>
            </li> --}}

            <li class="nav-item mb-2 mt-1">
                <a href="javascript:void(0)" class="nav-link btn-danger text-white" id="logoutButton">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p class="font-weight-bold">LOGOUT</p>
                </a>
            </li>

        </ul>
    </nav>

</div>
