{{-- Modal create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-centered">
            <div class="modal-header p-2">
                <h5 class="modal-title font-weight-bold ml-2">Form - Tambah Permission</h5>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formPermissionAdd" action="{{ route('role.permission.permission.store') }}" autocomplete="off"
                method="POST">
                @csrf
                @method('POST')

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="title">Nama Permission</label>
                        <input type="text" class="form-control" id="add_name" name="name"
                            placeholder="Masukkan nama permission disini!" autofocus>
                        <span class="invalid-feedback d-block error-text name_error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="title">Grup Permission</label>
                        <select name="label" id="add_label" class="form-control">
                            <option value="">Semua</option>
                            @foreach ($labelcruds as $label)
                                <option value="{{ $label->id }}">{{ $label->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback d-block error-text label_error"></span>
                    </div>
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
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-centered">
            <div class="modal-header p-2">
                <h5 class="modal-title font-weight-bold ml-2">Form - Edit Permission</h5>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="formPermissionEdit" action="#" autocomplete="off" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" id="edit_id">

                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="title">Nama Permission</label>
                        <input type="text" class="form-control" id="edit_name" name="name"
                            placeholder="Masukkan nama permission disini!" autofocus>
                        <span class="invalid-feedback d-block error-text edit_name_error"></span>
                    </div>
                    <div class="form-group mb-3">
                        <label for="title">Grup Permission</label>
                        <select name="label" id="edit_label" class="form-control">
                            <option value="">Semua</option>
                            @foreach ($labelcruds as $label)
                                <option value="{{ $label->id }}">
                                    {{ $label->name }}
                                </option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback d-block error-text edit_label_error"></span>
                    </div>
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
<div class="modal fade" id="modalDelete" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">

            <form action="" method="DELETE" id="formLabelDelete">
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
