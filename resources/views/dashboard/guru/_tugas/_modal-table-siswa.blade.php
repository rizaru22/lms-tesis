<div class="modal fade" id="modalListSiswa">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title font-weight-bold ml-2">

                </h5>
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body table-responsive p-2">
                <div class="row">
                    <div class="col-lg-12">
                        <div style="overflow: auto;max-height: 350px;">
                            <table id="tableSwShow" class="table table-hover">
                                <thead style="position: sticky; top:0;">
                                    <tr style="background: #e1e1e1">
                                        <th>No</th>
                                        <th>Daftar Siswa</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div> {{-- col-lg-12 --}}
                </div> {{-- row --}}
            </div> {{-- modal-body --}}

            <div class="modal-footer p-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>

        </div> {{-- modal-content --}}
    </div> {{-- modal-content --}}
</div>

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#swTotal").click(function(e) {
                e.preventDefault();
                showModalWithFilter(1);
            });

            $("#swTidak").click(function(e) {
                e.preventDefault();
                showModalWithFilter(0);
            });

            function showModalWithFilter(filter) {
                showTableInModal(filter);
            }

            // Show table in modal
            function showTableInModal(filter) {
                $.ajax({
                    type: "GET",
                    url: "{{ route('manajemen.pelajaran.tugas.guru.table.sw.show', encrypt($tugas->id)) }}",
                    data: {
                        filter: filter,
                        tugas_id: "{{ $tugas->id }}"
                    },
                    dataType: "json",
                    beforeSend: function() {
                        $("#tableSwShow tbody").html("");
                    },
                    success: function(res) {
                        $("#modalListSiswa").modal("show");

                        let siswa = res.siswa;
                        let title = filter == 0 ?
                            "<i class='fas fa-user-times text-danger mr-1'></i> Tidak Mengumpulkan Tugas" :
                            "<i class='fas fa-users text-primary mr-1'></i> Total Siswa";

                        $("#modalListSiswa .modal-title").html(title);

                        if (siswa == "") {
                            $("#tableSwShow tbody").append(`
                                <tr>
                                    <td colspan="2" class="text-center">Tidak ada siswa</td>
                                </tr>
                            `);

                            return
                        }

                        siswa.forEach((sw, index) => {
                            $("#tableSwShow tbody").append(`
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>
                                        <a href="javascript:void(0)" class="d-flex align-items-center"
                                            style="cursor: default">
                                            <img src="${sw.foto}" width="40" class="avatar rounded-circle me-3">
                                            <div class="d-block ml-3">
                                                <span class="fw-bold name-user">${sw.nama}</span>
                                                <div class="small text-secondary">${sw.nis}</div>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                            `);
                        });
                    }
                });
            }
        });
    </script>
@endpush
