:@extends('layouts.dashboard')

@section('title', 'Data Role')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary card-outline sticky">
                    <div class="card-header p-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="m-0 p-0 font-weight-bold ml-2">
                                <i class="fa fa-user-tag text-primary mr-1"></i> @yield('title')
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

                        <div class="alert card card-outline card-primary alert-dismissible in-table info fade show"
                        role="alert">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary mr-3"></i>
                            <p style="line-height: 1.2">
                                Sebenarnya, kalo dikasih permission itu tidak ada perbedaannya. Jadi di aplikasi ini hak aksesnya
                                menggunakan role, dan sebagai default role yang ada adalah admin, dosen, dan mahasiswa. Jika
                                kamu mengerti tentang role dan permission, kamu boleh menambahkan role yang baru. Untuk dokumentasinya
                                tentang role dan permission, kamu bisa baca di
                                <a class="text-primary" href="https://spatie.be/docs/laravel-permission/v5/introduction" target="_blank">Disini</a>.

                            </p>
                        </div>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                        <table id="tableRole" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Role</th>
                                    <th>Permission</th>
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

    @include("dashboard.admin.role-permissions._modal._modal-role")
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find(":input:not(:button):visible:enabled:not([readonly]):first").focus();
            });

            $('input').on('keyup', function() {
                $(this).val($(this).val().toLowerCase());
            });

            // check all permission
            $('#checkAllAdd').click(function(e) {
                if ($(this).is(':checked')) {
                    $('.checkPermissionAdd').prop('checked', true);
                } else {
                    $('.checkPermissionAdd').prop('checked', false);
                }
            });

            // check all permission
            $('.checkPermissionAdd').click(function(e) {
                let checkedLength = $('.checkPermissionAdd').filter(':checked').length;
                let checkedCount = $('input:checkbox[name="permissions"]:checked').length;

                if (checkedLength == 0) { // jika tidak ada yang dicentang
                    if ($('#checkAllAdd').is(':checked')) { // jika check all dicentang
                        $('#checkAllAdd').prop('checked', false); // uncheck all
                    }
                } else if (checkedLength == $('.checkPermissionAdd').length) { // jika semua dicentang
                    if (!$('#checkAllAdd').is(':checked')) { // jika check all tidak dicentang
                        $('#checkAllAdd').prop('checked', true); // check all
                    }
                }
            });

            // datatable
            var table = $('#tableRole').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('role.permission.role.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                    { data: 'name', name: 'name' },
                    { data: 'label', name: 'label', },
                    { className: 'noPrint', data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $("#cetakTable").on("click", function(e) {
                e.preventDefault();
                table.button(0).trigger();
            });

            $("#refreshTable").on("click", function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            // insert data
            $('#formRoleAdd').on('submit', function(e) {
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
                        $('.submitAdd').html('Tambah');
                    },
                    success: function(res) {
                        if (res.status == 400) {
                            $.each(res.errors, function(key, val) {
                                $('span.' + key + '_error').text(val[0]);
                                $(".form-control#add_" + key).addClass('is-invalid');
                            });
                        } else {
                            $('#formRoleAdd')[0].reset();
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
                                html: "Anda tidak memiliki akses untuk melakukan ini",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            // SHOW MODAL EDIT
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();

                let id = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('role.permission.role.show', ':id') }}".replace(':id', id),
                    success: function(res) {
                        if (res.status == 200) {
                            $("#modalEdit").modal('show');

                            $('#role_id').val(id);
                            $('#edit_name').val(res.data.name);

                            $.ajax({
                                type: "GET",
                                url: "{{ url('admin/role-permission/role/fetch-permission') }}/" +
                                    id,
                                success: function(res) {
                                    // edit permmision append res
                                    $('#edit_permissions').html(res);

                                    $('#checkAllEdit').click(function(e) {
                                        if ($(this).is(':checked')) {
                                            $('.checkPermissionEdit').prop(
                                                'checked', true);
                                        } else {
                                            $('.checkPermissionEdit').prop(
                                                'checked', false);
                                        }
                                    });

                                }
                            });

                        } else {
                            $("#modalEdit").modal('hide');
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
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            });

            // unchecked all checkbox when modal edit closed
            $('#modalEdit').on('hidden.bs.modal', function() {
                $('#checkAllEdit').prop('checked', false);
                $('.checkPermissionEdit').prop('checked', false);
            });

            // UPDATE ROLE
            $('#formRoleEdit').on('submit', function(e) {
                e.preventDefault();

                let id = $('#role_id').val();

                let permissions = [];
                $('.checkPermissionEdit').each(function() {
                    if ($(this).is(':checked')) {
                        permissions.push($(this).val());
                    }
                });

                $.ajax({
                    type: "PUT",
                    url: "{{ route('role.permission.role.update', ':id') }}".replace(':id', id),
                    data: {
                        "name": $('#edit_name').val(),
                        "permissions": permissions,
                        "_token": "{{ csrf_token() }}",
                    },
                    dataType: 'JSON',
                    beforeSend: function() {
                        $('.submitEdit').attr('disabled', true);
                        $('.submitEdit').html('<i class="fas fa-spin fa-spinner"></i>');
                        $(document).find('span.error-text').text('');
                        $(document).find('input.form-control').removeClass(
                            'is-invalid');
                    },
                    complete: function() {
                        $('.submitEdit').removeAttr('disabled');
                        $('.submitEdit').html('Edit <i class="fas fa-pen"></i>');
                    },
                    success: function(res) {
                        if (res.status == 200) {
                            $('#modalEdit').modal('hide');

                            table.ajax.reload(null, false);

                            setTimeout(function() {
                                Toast.fire({
                                    icon: 'success',
                                    title: res.message
                                });
                            }, 500);
                        } else if (res.status == 201) {
                            $('#modalEdit').modal('hide');
                            Swal.fire({
                                icon: 'warning',
                                html: res.message,
                                showConfirmButton: false,
                                timer: 2000
                            });
                        } else if (res.status == 400) {
                            $.each(res.errors, function(key, val) {
                                $('span.edit_' + key + '_error').text(val[0]);
                                $(".form-control#edit_" + key).addClass('is-invalid');
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "Anda tidak memiliki akses untuk melakukan ini",
                                allowOutsideClick: false,
                            });
                        } else {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    }
                });
            });

            // Show Delete
            $(document).on('click', '.del_btn', function(e) {
                e.preventDefault();
                $('#modalDelete').modal('show');

                let id = $(this).val();
                let name = $(this).data('name');

                $('#del_id').val(id);
                $('#text_del').text(`Apakah anda yakin ingin menghapus role ${name} ?`);
            });

            // process deleting
            $("#formRoleDelete").on('submit', function(e) {
                e.preventDefault();

                let id = $('#del_id').val();

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('role.permission.role.delete', ':id') }}".replace(':id', id),
                    data: {
                        "id": id,
                        "_token": "{{ csrf_token() }}",
                    },
                    dataType: "json",
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
                            }, 500);
                        } else {
                            $('#modalDelete').modal('hide');

                            if (res.title == null) {
                                msg = ''
                            } else {
                                msg = res.title
                            }

                            Swal.fire({
                                icon: 'error',
                                title: msg,
                                html: res.message,
                                allowOutsideClick: false,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        if (xhr.status == 403) {
                            Swal.fire({
                                icon: 'error',
                                html: "Anda tidak memiliki akses untuk melakukan ini",
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
