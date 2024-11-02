{{-- Modal --}}
<div class="modal fade" id="modalDetailNilai">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title font-weight-bold ml-2">Detail Nilai {{ $jadwal->mapel->nama }}</h5>

                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="card card-primary card-outline">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>Nama</b>
                                            </div>
                                            <div id="namaSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>NIS</b>
                                            </div>
                                            <div id="nisSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>Dimulai</b>
                                            </div>
                                            <div id="startedAtSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>Selesai</b>
                                            </div>
                                            <div id="endedAtSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>Durasi</b>
                                                <i class="fas fa-info-circle ml-1 text-primary"
                                                    data-toggle="tooltip" title="*Durasi siswa dalam mengerjakan soal. (Berapa lama mengerjakan ujian)">
                                                </i>
                                            </div>
                                            <div id="durationSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>IP Address</b>
                                            </div>
                                            <div id="ipAddressSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row mb-2">
                                            <div class="col-lg-4 col-4">
                                                <b>User Agent</b>
                                            </div>
                                            <div id="userAgentSw" class="col-lg-8 col-8"></div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <div style="overflow: auto;max-height: 320px;">
                            <table class="table table-hover">
                                <thead style="position: sticky; top:0;  z-index: 1;">
                                    <tr style="background: #e1e1e1">
                                        <th class="text-center">#</th>
                                        <th style="width: 46%;">Pertanyaan</th>
                                        <th style="width: 20%">Kunci Jawaban</th>
                                        <th style="width: 20%">Jawaban Siswa</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Ragu</th>
                                    </tr>
                                </thead>
                                <tbody id="detailNilai"></tbody>
                            </table>
                        </div>

                    </div> <!-- /.col-lg-12 -->
                </div> <!-- /.row -->
            </div> <!-- /.modal-body -->

            <div class="modal-footer p-2">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
