:@extends('layouts.dashboard')

@section('title', 'Data Permission')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="card card-primary card-outline sticky">
                    <div class="card-header p-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="m-0 p-0 font-weight-bold ml-2">
                                <i class="fa fa-user-lock text-primary mr-1"></i> @yield('title')
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
                    @if ($labelcruds->isNotEmpty())
                        <div class="card-header p-2">
                            <div class="row">
                                <div class="col-lg-4 col-12">
                                    <select id="filterGrupPermission" class="form-control filter">
                                        <option value="">Semua</option>
                                        @foreach ($labelcruds as $label)
                                            <option value="{{ $label->name }}">{{ $label->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card-body table-responsive">
                        <table id="tablePermission" class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Permission</th>
                                    <th>Grup Permission</th>
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

    @include('dashboard.admin.role-permissions._modal._modal-permission')

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // focus input modal when show modal
            $('body').on('shown.bs.modal', '.modal', function() {
                $(this).find(":input:not(:button):visible:enabled:not([readonly]):first").focus();
            });

            // convert input to lowercase
            $('input').on('keyup', function() {
                $(this).val($(this).val().toLowerCase());
            });

            let grup_permission = $('#filterGrupPermission').val();

            // datatable
            var table = $('#tablePermission').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('role.permission.permission.index') }}",
                    type: 'GET',
                    data: function(d) {
                        d.label = grup_permission;
                    }
                },
                bLengthChange: true,
                columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'name', name: 'name'},
                {data: 'label', name: 'label'},
                {
                    className: 'noPrint',
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }],
            });

            $("#cetakTable").on("click", function(e) {
                e.preventDefault();
                table.button(0).trigger();
            });

            // filter
            $(".filter").on('change', function() {
                grup_permission = $('#filterGrupPermission').val();
                table.ajax.reload(null, false);
            });

            // refresh table
            $('#refreshTable').on('click', function() {
                $('#filterGrupPermission').val('').trigger('change');
                table.ajax.reload(null, false);
            });

            function initSelect2(id, placeholder, dropdownParent) {
                let dropdownParentVal = null;

                if (dropdownParent) {
                    dropdownParentVal = $(dropdownParent);
                }

                $(id).select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    dropdownParent: dropdownParentVal,
                });
            }

            initSelect2('#filterGrupPermission', 'Filter Grup Permission');
            initSelect2('#add_label', 'Pilih group permission', '#modalCreate');
            initSelect2('#edit_label', 'Pilih group permission', '#modalEdit');

            // insert data
            $('#formPermissionAdd').on('submit', function(e) {
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
                                $("input#add_" + key).addClass('is-invalid');
                            });
                        } else {
                            $('#formPermissionAdd')[0].reset();
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

            // reset modal create
            $('#modalCreate').on('hidden.bs.modal', function() {
                $("#add_label").select2("val", ' ');
            });

            // SHOW MODAL EDIT
            $(document).on('click', '.edit_btn', function(e) {
                e.preventDefault();

                let id = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('role.permission.permission.show', ':id') }}".replace(':id', id),
                    success: function(res) {
                        if (res.status == 200) {
                            $("#modalEdit").modal('show');
                            let data = res.data;

                            $('#edit_id').val(id);
                            $('#edit_name').val(data.name);
                            $('#edit_label').val(data.label_permissions[0].id)
                                .trigger('change');
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

            // UPDATE PERMISSION
            $('#formPermissionEdit').on('submit', function(e) {
                e.preventDefault();

                let id = $('#edit_id').val();

                $.ajax({
                    type: "PUT",
                    url: "{{ route('role.permission.permission.update', ':id') }}".replace(':id',
                        id),
                    data: {
                        "name": $('#edit_name').val(),
                        "label": $('#edit_label').val(),
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
                        $('.submitEdit').html('Edit');
                    },
                    success: function(res) {
                        if (res.status == 200) {
                            $('#formPermissionEdit')[0].reset();
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
                                $("input#edit_" + key).addClass('is-invalid');
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

            // Show Delete
            $(document).on('click', '.del_btn', function(e) {
                e.preventDefault();
                $('#modalDelete').modal('show');

                let id = $(this).val();
                let name = $(this).data('name');

                $('#del_id').val(id);
                $('#text_del').text(`Apakah anda yakin ingin menghapus data permission \t"${name}" ?`);
            });

            // process deleting
            $("#formLabelDelete").on('submit', function(e) {
                e.preventDefault();

                let id = $('#del_id').val();

                $.ajax({
                    type: "DELETE",
                    url: "{{ route('role.permission.permission.delete', ':id') }}".replace(':id',
                        id),
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
                                msg = res.message
                            } else {
                                msg = res.title
                            }

                            Swal.fire({
                                icon: 'error',
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
