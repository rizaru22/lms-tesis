<a href="javascript:void(0)" class="list-group-item list-group-item-action cursor_default">
    <i class="fas fa-users text-primary mr-1"></i>
    Total Siswa

    <span class="badge badge-primary badge-pill float-right position-relative" style="top: 2px;">
        {{ $siswa }}
        {{-- {{ $siswa->count()}} --}}
    </span>
</a>


<a href="javascript:void(0)" class="list-group-item list-group-item-action cursor_default">
    <i class="fas fa-user-check text-success mr-1"></i>
    Total Hadir
    <span class="badge badge-success badge-pill float-right position-relative" style="top: 2px;">
        {{  $siswaHadir }}
        {{-- {{ $siswaHadir->count() }} --}}
    </span>
</a>


<a href="javascript:void(0)" class="list-group-item list-group-item-action cursor_default">
    <i class="fas fa-user-times text-danger mr-1"></i>
    Total Tidak Hadir
    <span class="badge badge-danger badge-pill float-right position-relative" style="top: 2px;">
        {{ $siswaTidakHadir }}
        {{-- {{ $siswaTidakHadir->count() }} --}}
    </span>
</a>
