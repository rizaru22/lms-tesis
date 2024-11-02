@include('layouts.sub-dashboard.sidebar._brand')

<div class="sidebar">

    @include('layouts.sub-dashboard.sidebar._profile-info')

    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-header">MAIN</li>
            <li class="nav-item">
                <a href="{{ route('ortu.dashboard') }}" class="nav-link {{ setActive('ortu.dashboard') }}">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>

            {{-- <li class="nav-header">AKUN</li> --}}
            <li class="nav-item">
                <a href="{{ route('profile.index') }}" class="nav-link {{ setActive('profile.index') }}">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Profile
                    </p>
                </a>
            </li>

            <li class="nav-header">LAPORAN PROSES PBM</li>

            <li class="nav-item">
                <a href="{{ route('manajemen.pelajaran.laporan.ortu.absen') }}" class="nav-link  @yield('absen')">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Absensi
                  </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="/nilai" class="nav-link  @yield('nilai')">
                  <i class="nav-icon fas fa-circle"></i>
                  <p>
                    Nilai Peserta Didik
                  </p>
                </a>
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


