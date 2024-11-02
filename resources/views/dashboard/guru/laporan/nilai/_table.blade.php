<table>
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Nama</th>
            <th rowspan="2">NIS</th>
            <th rowspan="2">Kelas</th>
            <th rowspan="2">Mata Pelajaran</th>
            <th rowspan="2">Program Keahlian</th>
            <th colspan="14" class="text-center">Tugas</th>
            <th rowspan="2" class="text-center">Rata-rata</th>
            <th rowspan="2" class="text-center">UTS</th>
            <th rowspan="2" class="text-center">UAS</th>
            <th rowspan="2" class="text-center">Total</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= 14; $i++)
                <th>
                    P{{ $i }}
                </th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach ($siswa as $siswa)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $siswa['nama'] }}</td>
                <td>{{ $siswa['nis'] }}</td>
                <td>{{ $siswa['kelas'] }}</td>
                <td>{{ $siswa['mapel'] }}</td>
                <td>{{ $siswa['programkeahlian'] }}</td>
                @for($i = 1; $i <= 14; $i++)
                    <td>{{ $siswa["p$i"] }}</td>
                @endfor
                <td>{{ $siswa['rata_rata'] }}</td>
                <td>{{ $siswa['nilai_uts'] }}</td>
                <td>{{ $siswa['nilai_uas'] }}</td>
                <td>{{ $siswa['total'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
