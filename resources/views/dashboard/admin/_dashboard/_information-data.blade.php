<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-cyan card-outline">

        <div class="inner">
            <h3>{{ $prodi }}</h3>

            <p class=" mb-2">Program Keahlian</p>
        </div>

        <div class="icon">
            <i class="fas fa-graduation-cap text-cyan"></i>
        </div>

        <a href="{{ route('manajemen.pelajaran.programkeahlian.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-cyan"></i>
        </a>
    </div>
</div> {{-- END Program Keahlian --}}

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-indigo card-outline">
        
        <div class="inner">
            <h3>{{ $prodi }}</h3>

            <p class=" mb-2">Program Studi</p>
        </div>

        <div class="icon">
            <i class="fas fa-book text-indigo"></i>
        </div>

        <a href="{{ route('manajemen.pelajaran.prodi.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-indigo"></i>
        </a>
    </div>
</div> {{-- END PRODI --}}

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-purple card-outline">
        <div class="inner">
            <h3>{{ $mapel }}</h3>

            <p class=" mb-2">Mata Pelajaran</p>
        </div>

        <div class="icon">
            <i class="fas fa-book-open text-purple"></i>
        </div>

        <a href="{{ route('manajemen.pelajaran.mapel.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-purple"></i>
        </a>
    </div>
</div> {{-- END MATAKULIAH --}}

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-pink card-outline">
        <div class="inner">
            <h3>{{ $kelas }}</h3>

            <p class=" mb-2">Kelas</p>
        </div>

        <div class="icon">
            <i class="fas fa-chalkboard-teacher text-pink"></i>
        </div>

        <a href="{{ route('manajemen.pelajaran.kelas.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-pink"></i>
        </a>
    </div>
</div> {{-- END KELAS --}}

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-red card-outline">
        <div class="inner">
            <h3>{{ $belajar }}</h3>

            <p class=" mb-2">Jadwal Pelajaran</p>
        </div>

        <div class="icon">
            <i class="fas fa-calendar text-red"></i>
        </div>

        <a href="{{ route('manajemen.pelajaran.jadwal.admin.pelajaran.index') }}" class="small-box-footer">
            Selengkapnya
            <i class="fas fa-arrow-circle-right text-red"></i>
        </a>
    </div>
</div> {{-- END JADWAL PELAJARAN --}}

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-orange card-outline">
        <div class="inner">
            <h3>{{ $ujian }}</h3>

            <p class=" mb-2">Jadwal Ujian</p>
        </div>

        <div class="icon">
            <i class="fas fa-calendar-alt text-orange"></i>
        </div>

        <a href="{{ route('manajemen.pelajaran.jadwal.admin.ujian.index') }}" class="small-box-footer">
            Selengkapnya
            <i class="fas fa-arrow-circle-right text-orange"></i>
        </a>
    </div>
</div> {{-- END JADWAL UJIAN --}}

{{-- <div class="col-lg-3 col-6">
    <div class="small-box bg-white card-yellow card-outline">
        <div class="inner">
            <h3>{{ $kepsek }}</h3>

            <p class=" mb-2">Kepala dan Pengawas Sekolah</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-tie text-yellow"></i>
        </div>

        <a href="{{ route('manage.users.kepsek.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-yellow"></i>
        </a>
    </div>
</div> END USER KEPSEK --}}



<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-yellow card-outline">
        <div class="inner">
            <h3>{{ $guru }}</h3>

            <p class=" mb-2">Guru</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-tie text-yellow"></i>
        </div>

        <a href="{{ route('manage.users.guru.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-yellow"></i>
        </a>
    </div>
</div> {{-- END USER Guru --}}


<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-yellow card-outline">
        <div class="inner">
            <h3>{{ $ortu }}</h3>

            <p class=" mb-2">Orang Tua Siswa</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-tie text-yellow"></i>
        </div>

        <a href="{{ route('manage.users.ortu.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-yellow"></i>
        </a>
    </div>
</div> {{-- END USER ORTU --}}







<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-green card-outline">
        <div class="inner">
            <h3>{{ $siswa }}</h3>

            <p class=" mb-2">Siswa</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-graduate text-green"></i>
        </div>

        <a href="{{ route('manage.users.siswa.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-green"></i>
        </a>
    </div>
</div> {{-- END USER SISWA --}}

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-teal card-outline">
        <div class="inner">
            <h3>{{ $users->count() }}</h3>

            <p class=" mb-2">Pengguna</p>
        </div>

        <div class="icon">
            <i class="fas fa-users text-teal"></i>
        </div>

        <a href="{{ route('manage.users.user.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-teal"></i>
        </a>
    </div>
</div> {{-- END USERS --}}
{{-- 
<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-cyan card-outline">
        <div class="inner">
            <h3>{{ $roles }}</h3>

            <p class=" mb-2">Role</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-tag text-cyan"></i>
        </div>

        <a href="{{ route('role.permission.role.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-cyan"></i>
        </a>
    </div>
</div> <!-- END ROLES --> --}}

{{-- <div class="col-lg-3 col-6">
    <div class="small-box bg-white card-indigo card-outline">
        <div class="inner">
            <h3>{{ $permissions }}</h3>

            <p class=" mb-2">Permission</p>
        </div>

        <div class="icon">
            <i class="fas fa-user-lock text-indigo"></i>
        </div>

        <a href="{{ route('role.permission.permission.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-indigo"></i>
        </a>
    </div>
</div> <!-- END PERMISSIONS -->

<div class="col-lg-3 col-6">
    <div class="small-box bg-white card-purple card-outline">
        <div class="inner">
            <h3>{{ $label_permissions }}</h3>

            <p class=" mb-2">Grup Permissions</p>
        </div>

        <div class="icon">
            <i class="fas fa-layer-group text-purple"></i>
        </div>

        <a href="{{ route('role.permission.label.permission.index') }}" class="small-box-footer"> Selengkapnya
            <i class="fas fa-arrow-circle-right text-purple"></i>
        </a>
    </div>
</div> <!-- END GROUP PERMISSIONS --> --}}

