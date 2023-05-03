<div class='box box-warning' id="form-skp-add">
    <div class="box-body">
        <div class="messages">
        </div>
        <?php echo form_open($this->uri->uri_string(), 'id="frmkinerja"'); ?>
        <fieldset>
            <div class="col-md-12">
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Tahun</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->tahun ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">NIP</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->nip ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Nama</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->nama ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Instansi</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= isset($detail_riwayat->instansi) ? $detail_riwayat->instansi : 'Badan Riset dan Inovasi Nasional' ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Unit Organisasi</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->unit_kerja_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <legend>Kinerja</legend>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <!-- <div class="col-sm-2">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Hasil Kinerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="1">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Rating Hasil Kinerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->rating_hasil_kerja ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="col-sm-2">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Perilaku Kerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="1">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Rating Perilaku Kerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->rating_perilaku_kerja ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <!-- <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Kuadran Kerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="1">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Rating Kuadran Kerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->predikat_kinerja ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <legend>Pejabat Penilai</legend>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Status Penilai</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="ASN">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">NIP Penilai</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->nip_penilai_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Nama Penilai</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->nama_penilai_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6"></div>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Unit Organisasi Penilai</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->unit_kerja_penilai_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Jabatan Penilai</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input disabled="" type="text" class="form-control" value="<?= $detail_riwayat->jabatan_penilai_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <legend>Berkas</legend>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Berkas Digital</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <?php if (isset($detail_riwayat->id_arsip)) { ?>
                                        <a href="<?php echo base_url(); ?>admin/arsip/arsip_digital/viewdoc2/<?php echo $detail_riwayat->id_arsip; ?>" data-toggle='tooltip' class="btn btn-sm btn-warning show-modal" tooltip="Lihat dokumen" title="Lihat dokumen"><span class="fa fa-file-o"></span> Lihat</a>
                                    <?php } else { ?>
                                        <input disabled="" type="text" class="form-control" value="Belum ada berkas">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
    </div>
    <?php if (!$read_only) { ?>
        <div class="box-footer">
        </div>
    <?php } ?>
    <?php echo form_close(); ?>
</div>