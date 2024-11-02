@extends('layouts.dashboard')

@section('title', 'Data Mata Pelajaran')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline sticky">
                    <div class="card-header p-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="m-0 p-0 font-weight-bold ml-2">
                                <i class="fas fa-book-open text-primary mr-1"></i> @yield('title')
                            </h5>

                            <div>
                                <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalCreate">
                                    <i class="fas fa-plus mr-1"></i>
                                    Tambah
                                </button>
                                <button id="cetakTable" class="btn btn-primary btn-sm ml-1">
                                    <i class="fas fa-print mr-1"></i> Cetak
                                </button>
                                <button id="refreshTable" class="btn btn-warning btn-sm ml-1"
                                    data-toggle="tooltip" title="Refresh Table">
                                    <i class="fas fa-sync"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-primary card-outline">
                    <div class="card-body table-responsive">
                        <table class="table table-hover" id="tableMapel">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Mata Pelajaran</th>
                                    <th>Nama Mata Pelajaran</th>
                                    <th>JAM</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('dashboard.admin.manajemen-pelajaran._modal._modal-mapel')
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // DataTable
            var table = $('#tableMapel').DataTable({
                processing: true,
                serverSide: true,
                ordering: true,
                ajax: "{{ route('manajemen.pelajaran.mapel.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'kode', name: 'kode' },
                    { data: 'nama', name: 'nama' },
                    { data: 'jam', name: 'jam' },
                    { className: 'noPrint', data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $("#cetakTable").on("click", function(e) {
                e.preventDefault();
                table.button(0).trigger();
            });

            // refresh table
            $('#refreshTable').on('click', function() {
                table.ajax.reload(null, false);
            });

            // Insert data
            $('#formMapelAdd').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    method: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.submitAdd').attr('disabled', true);
                        $('.submitAdd').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('input.form-control').removeClass(
                            'is-invalid');
                    },
                    complete: function() {
                        $('.submitAdd').removeAttr('disabled');
                        $('.submitAdd').html('Simpan');
                    },
                    success: function(res) {
                        if (res.status == 400) {
                            if (res.tipe == 'validation') {
                                $.each(res.errors, function(key, val) {
                                    $('span.' + key + '_error').text(val[0]);
                                    $("input#add_" + key).addClass('is-invalid');
                                });
                            } else {
                                $('#modalCreate').modal('hide');

                                Swal.fire({
                                    icon: 'error',
                                    html: res.message,
                                });
                            }

                        } else {
                            $('#formMapelAdd')[0].reset();
                            $('#modalCreate').modal('hide');

                            table.ajax.reload(null, false);

                            setTimeout(function() {
                                Toast.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            }, 500);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "Anda tidak memiliki akses untuk melakukan ini!",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            // modal edit show data
            $('body').on('click', '.edit_btn', function() {

                var id = $(this).attr('id');

                $.ajax({
                    method: 'GET',
                    url: "{{ route('manajemen.pelajaran.mapel.show', ':id') }}".replace(':id', id),
                    success: function(res) {
                        if (res.status == 200) {
                            $("#modalEdit").modal('show');
                            $('#edit_id').val(id);
                            $('#edit_nama').val(res.data.nama);
                            $('#edit_jam').val(res.data.jam);
                        } else {

                            $(document).find('span.error-text').text('');
                            $(document).find('input.form-control')
                                .removeClass('is-invalid');

                            Swal.fire({
                                icon: 'warning',
                                html: res.message,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "Anda tidak memiliki akses untuk melakukan ini!",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            // update data
            $('#formMapelEdit').on('submit', function(e) {
                e.preventDefault();

                let id = $('#edit_id').val();

                $.ajax({
                    url: "{{ route('manajemen.pelajaran.mapel.update', ':id') }}".replace(':id', id),
                    type: $(this).attr('method'),
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.submitEdit').attr('disabled', true);
                        $('.submitEdit').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('input.form-control').removeClass(
                            'is-invalid');
                    },
                    complete: function() {
                        $('.submitEdit').removeAttr('disabled');
                        $('.submitEdit').html('Update');
                    },
                    success: function(res) {
                        if (res.status == 400) {
                            if (res.tipe == 'validation') {
                                $.each(res.errors, function(key, val) {
                                    $('span.edit_' + key + '_error').text(val[0]);
                                    $("input#edit_" + key).addClass('is-invalid');
                                });
                            } else {
                                $('#modalEdit').modal('hide');

                                Swal.fire({
                                    icon: 'error',
                                    html: res.message,
                                });
                            }
                        } else {
                            if (res.icon == 'success') {
                                $('#formMapelEdit')[0].reset();
                                $('#modalEdit').modal('hide');

                                table.ajax.reload(null, false);

                                setTimeout(function() {
                                    Toast.fire({
                                        icon: 'success',
                                        title: res.message
                                    });
                                }, 200);
                            } else {
                                $('#modalEdit').modal('hide');

                                setTimeout(function() {
                                    Toast.fire({
                                        icon: 'warning',
                                        title: res.message
                                    });
                                }, 200);
                            }
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "Anda tidak memiliki akses untuk melakukan ini!",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            // Show Delete Modal
            $('body').on('click', '.del_btn', function(e) {
                e.preventDefault();
                $('#modalDelete').modal('show');

                let id = $(this).attr('id');
                let name = $(this).data('name');

                $('#del_id').val(id);
                $('#text_del').text(`Apakah anda yakin ingin menghapus Mata Pelajaran \t"${name}"?`);
            });

            // Delete Data
            $("#formMapelDelete").on('submit', function(e) {
                e.preventDefault();

                let id = $('#del_id').val();

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('manajemen.pelajaran.mapel.delete', ':id') }}".replace(':id', id),
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}",
                    },
                    beforeSend: function() {
                        $('.btnDelete').attr('disabled', true);
                        $('.btnDelete').html('<i class="fas fa-spin fa-spinner"></i>');
                    },
                    complete: function() {
                        $('.btnDelete').removeAttr('disabled');
                        $('.btnDelete').html('Hapus');
                    },
                    success: function(res) {
                        if (res.status == 200) {
                            $('#modalDelete').modal('hide');

                            table.ajax.reload(null, false);

                            setTimeout(function() {
                                Toast.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            }, 200);
                        } else {
                            $('#modalDelete').modal('hide');

                            Swal.fire({
                                icon: 'error',
                                title: res.title,
                                html: res.message,
                                allowOutsideClick: false,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "Anda tidak memiliki akses untuk melakukan ini!",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });
        });
    </script>
@endpush
