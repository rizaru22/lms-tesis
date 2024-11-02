{{-- Modal create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreate" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-centered">
            <div class="modal-header p-2">
                <h5 class="modal-title font-weight-bold ml-2" id="modalCreate">Form - Tambah Role</h5>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formRoleAdd" action="{{ route('role.permission.role.store') }}" autocomplete="off" method="POST">
                @csrf
                @method('POST')

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="title">Nama Role</label>
                        <input type="text" class="form-control" id="add_name" name="name"
                            placeholder="Masukkan nama role disini!" autofocus>
                        <span class="invalid-feedback d-block error-text name_error"></span>
                    </div>
                    @if ($permissions->count() > 0)
                        <div class="form-group mb-3">
                            <label for="title" class="d-flex">
                                Pilih Permission
                                <input type="checkbox" value="" class="ml-1" id="checkAllAdd">
                            </label>
                            <div class="form-control h-100" id="add_permissions">
                                <div class="row" style="margin-left: -9px;margin-bottom: 4px">
                                    @foreach ($permissions as $permission)
                                        <ul class="list-group mx-1">
                                            <li class="list-group-item mt-1 bg-info text-white">
                                                {{ $permission->name }}
                                            </li>

                                            @foreach ($permission->permissions as $item)
                                                <li class="list-group-item">
                                                    <div class="form-check">

                                                        <input id="add{{ $item->id }}" name="permissions[]"
                                                            class="form-check-input checkPermissionAdd" type="checkbox"
                                                            value="{{ $item->id }}">

                                                        <label for="add{{ $item->id }}"
                                                            class="form-check-label checks">
                                                            {{ $item->name }}
                                                        </label>

                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                            </div>

                            <span class="invalid-feedback d-block error-text permissions_error"></span>
                        </div>
                    @endif
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="submitAdd btn btn-success">
                        Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content modal-centered">
            <div class="modal-header p-2">
                <h5 class="modal-title font-weight-bold ml-2" id="modalEdit">Form - Edit Role</h5>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formRoleEdit" action="#" autocomplete="off" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="role_id">

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="title">Nama Role</label>
                        <input type="text" class="form-control" id="edit_name" name="name"
                            placeholder="Masukkan nama role disini!" readonly>
                        <span class="invalid-feedback d-block error-text edit_name_error"></span>
                    </div>
                    @if ($permissions->count() > 0)
                        <div id="editPermissions" class="form-group mb-3">
                            <label for="title" class="d-flex">
                                Pilih Permission
                                <input type="checkbox" value="" class="ml-1" id="checkAllEdit">
                            </label>

                            <div class="form-control h-100" id="edit_permissions">
                                <div class="row" style="margin-left: -9px;margin-bottom: 4px">
                                    @foreach ($permissions as $permission)
                                        <ul class="list-group mx-1">
                                            <li class="list-group-item mt-1 bg-info text-white">
                                                {{ $permission->name }}
                                            </li>

                                            @foreach ($permission->permissions as $item)
                                                <li class="list-group-item">
                                                    <div class="form-check">

                                                        <input id="edit{{ $item->id }}" name="permissions[]"
                                                            class="form-check-input checkPermissionEdit" type="checkbox"
                                                            value="{{ $item->id }}">

                                                        <label for="edit{{ $item->id }}"
                                                            class="form-check-label checks">
                                                            {{ $item->name }}
                                                        </label>

                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                            </div>

                            <span class="invalid-feedback d-block error-text edit_permissions_error"></span>
                        </div>
                    @endif
                </div>
                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="submitEdit btn btn-warning">
                        Edit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal delete --}}
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="deleteTitle"
    aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">

            <form action="" method="DELETE" id="formRoleDelete">
                @csrf
                @method('DELETE')

                <div class="modal-body">
                    <input id="del_id" type="hidden" name="id">
                    <p id="text_del"></p>
                </div>

                <div class="modal-footer p-2">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger btnDelete">
                        Hapus
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
