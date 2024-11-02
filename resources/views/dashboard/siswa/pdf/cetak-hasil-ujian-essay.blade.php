@extends('layouts.cetak-hasil')

@section('title', 'Bukti Hasil Ujian' . $jadwal->mapel->nama)

@section('content')
    <div class="py-2" style="background-color: #1F7A8E">
        <center>
            <img src="{{ asset('assets/image/logo.png') }}" alt="logo" width="100px">
            <h2 class="m-0 p-0 font-weight-bold text-white mt-1">
                {{ config('app.name') }}
            </h2>
            <h6 class="p-0 m-0 text-white mb-1">
                Learning Management System
            </h6>
        </center>
    </div>

    <center class="mt-4 mb-4">
        <p>Terima kasih Telah Mengikuti Ujian Online</p>
    </center>

    <table align="center">

        @php
            $start = Carbon\Carbon::parse($siswa->started_at); // waktu mulai
            $end = Carbon\Carbon::parse($siswa->ended_at); // waktu selesai
            $diff = $end->diffInSeconds($start); // waktu selesai dikurangi waktu mulai (dalam detik)
            $hours = floor($diff / 3600); // mengubah waktu ke jam
            $minutes = floor(($diff / 60) % 60); // mengubah waktu ke menit
            $seconds = $diff % 60; // mengubah waktu ke detik

            $jadwal->ended_at == null ? ($ended = '~') : ($ended = Carbon\Carbon::parse($jadwal->ended_at ?? ' ')->format('H:i:s'));

            $adaJwbanNull = $siswa->ujianHasil->where('jawaban', null)->count() > 0;
        @endphp

        <tr class="satu">
            <td width="120">Nama</td>
            <td width="300">: {{ $siswa->siswa->nama }}</td>
        </tr>

        <tr>
            <td>NIS</td>
            <td>: {{ $siswa->siswa->nis }}</td>
        </tr>

        <tr class="satu">
            <td>Tanggal Ujian</td>
            <td>: {{ Carbon\Carbon::parse($jadwal->tanggal_ujian)->isoFormat('dddd, D MMMM Y') }}
            </td>
        </tr>

        <tr>
            <td>Waktu Ujian</td>
            <td>: {{ Carbon\Carbon::parse($jadwal->started_at)->format('H:i:s') }} - {{ $ended }}
                WIB</td>
        </tr>

        <tr class="satu">
            <td>Mulai Ujian</td>
            <td>:
                {{ Carbon\Carbon::parse($siswa->started_at)->format('H:i:s') }} -
                {{ Carbon\Carbon::parse($siswa->ended_at)->format('H:i:s') }} WIB
            </td>
        </tr>

        <tr>
            <td>Lama Mengerjakan</td>
            <td>:


                {{ $hours }} Jam {{ $minutes }} Menit {{ $seconds }} Detik
            </td>
        </tr>

        <tr class="satu">
            <td>Mata Pelajaran</td>
            <td>: {{ $jadwal->mapel->nama }} - {{ $jadwal->mapel->kode }}</td>
        </tr>

        <tr>
            <td>Semester</td>
            <td>: {{ $jadwal->ujian->semester }}</td>
        </tr>

        <tr class="satu">
            <td>Tipe Ujian</td>
            <td>:
                {{ $jadwal->ujian->tipe_soal . " (" . strtoupper($jadwal->ujian->tipe_ujian) . ")" }}
            </td>
        </tr>

        <tr>
            <td>Jumlah Soal</td>
            <td>: {{ $siswa->ujianHasil->count() }} Soal</td>
        </tr>

        <tr class="satu">
            <td>Jumlah Benar</td>
            <td>: {{ $siswa->ujianHasil->where('status', '1')->count() }}</td>
        </tr>

        <tr>
            <td>Jumlah Salah</td>
            <td>:
                {{ $siswa->ujianHasil->where('status', '0')->where('jawaban', '!=', null)->count() }}</td>
        </tr>

        @if ($adaJwbanNull)
            <tr class="satu">
                <td>Tidak Jawab</td>
                <td>: {{ $siswa->ujianHasil->where('jawaban', null)->count() }}</td>
            </tr>

            <tr>
                <td>Nilai Ujian</td>
                <td>: {{ $siswa->nilai }}</td>
            </tr>
        @else
            <tr class="satu">
                <td>Nilai Ujian</td>
                <td>: {{ $siswa->nilai }}</td>
            </tr>
        @endif
    </table>

    <center class="mt-4 mb-4">
        <p class="text-uppercase">Simpan sebagai <b>bukti</b> bahwa anda telah <b>mengikuti ujian</b></p>
    </center>

    <table align="center" class="table table-bordered cetak_hasil">
        <thead>
            <tr class="satu">
                <th>No</th>
                <th>Pertanyaan</th>
                <th>Jawaban Kamu</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 8%;">Ragu</th>
                <th>Komentar Guru</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ujianHasil as $ujian)
                @php
                    $soal = $ujian->soalUjianEssay;
                    $jawaban = $soal->jawaban_benar;
                    $jawabanSw = $ujian->jawaban;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{!! $soal->pertanyaan !!}</td>
                    <td>{!! strtoupper($jawabanSw) !!}</td>
                    <td>
                        @if ($ujian->status == 0)
                            @if ($jawabanSw == null)
                                <span class="badge badge-secondary">Tidak Jawab</span>
                            @else
                                <span class="badge badge-danger">Salah</span>
                            @endif
                        @else
                            <span class="badge badge-success">Benar</span>
                        @endif
                    </td>
                    <td>
                        @if ($ujian->ragu == 1)
                            <span class="badge badge-warning">Ya</span>
                        @else
                            <span class="badge badge-secondary">Tidak</span>
                        @endif
                    </td>
                    <td>
                        @if ($ujian->komentar_guru == null)
                            -
                        @else
                            {{ $ujian->komentar_guru }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
