<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css" />
<?php
if ($mandiri) { ?>
    <div class="callout callout-info">
        <h4>Perhatian</h4>
        <ul>
            <li>Data yang anda masukan tidak otomatis mengubah data simpeg dan akan diverifikasi terlebih dahulu oleh kepegawaian.<br>
                Anda dapat mengecek pengajuan perubahan pada menu <b><a href="<?= base_url() ?>admin/kepegawaian/pegawai/peremajaan" target="_BLANK">Peremajaan Data</a></b></li>
            <li>Maksimal ukuran berkas yang diunggah sebesar 2MB.</li>
        </ul>
    </div>
    </div>
<?php } ?>
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
                                    <input type="text" class="form-control" name="tahun" value="<?= $detail_riwayat->tahun ?>">
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
                                    <input type="text" class="form-control" value="<?= $NIP_BARU ?>">
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
                                    <input type="text" class="form-control" value="<?= $nama ?>">
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
                                    <input type="text" class="form-control" value="<?= isset($detail_riwayat->instansi) ? $detail_riwayat->instansi : 'Badan Pembinaan Ideologi Pancasila' ?>">
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
                                    <input type="text" class="form-control" value="<?= $detail_riwayat->unit_kerja_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <legend>Kinerja</legend>
                </div>
                <div class="row row-cols-sm-12 row-cols-12">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Rating Hasil Kinerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" class="form-control" name="rating_hasil_kerja" value="<?= $detail_riwayat->rating_hasil_kerja ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-12">
                                    <label class="font-view-instansi form-label">Rating Perilaku Kerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" name="rating_perilaku_kerja" class="form-control" value="<?= $detail_riwayat->rating_perilaku_kerja ?>">
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
                                    <label class="font-view-instansi form-label">Rating Kuadran Kerja</label>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <input type="text" class="form-control" name="predikat_kinerja" value="<?= $detail_riwayat->predikat_kinerja ?>">
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
                                    <input type="text" class="form-control" value="ASN">
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
                                    <input type="text" class="form-control" name="nip_penilai_realisasi" value="<?= $detail_riwayat->nip_penilai_realisasi ?>">
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
                                    <input type="text" name="nama_penilai_realisasi" class="form-control" value="<?= $detail_riwayat->nama_penilai_realisasi ?>">
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
                                    <input type="text" name="unit_kerja_penilai_realisasi" class="form-control" value="<?= $detail_riwayat->unit_kerja_penilai_realisasi ?>">
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
                                    <input type="text" name="jabatan_penilai_realisasi" class="form-control" value="<?= $detail_riwayat->jabatan_penilai_realisasi ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <legend>Berkas</legend>
                <div class="control-group col-sm-8">
                    <label for="inputNAMA" class="control-label">Berkas</label>
                    <div class='controls'>
                        <div id="form_upload">
                            <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-show-upload="false" data-preview-file-type="pdf">
                        </div>

                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label for="inputNAMA" class="control-label"><br></label>
                    <div class='controls'>
                        <div class=" btn-group">
                            <?php if (!$read_only) { ?>
                                <?php $kode_arsip = uniqid(); ?>
                                <a href="#" kode='<?= $kode_arsip ?>' class="btn btn-sm btn-success btn-daftar-arsip" data-toggle='tooltip' tooltip="Pilih dokumen" title="Pilih dokumen"><span class="fa fa-search"></span> pilih</a>
                                <a href="#" id='view_<?= $kode_arsip ?>' class="btn btn-sm btn-info show-modal hide" data-toggle='tooltip' tooltip="Lihat dokumen baru" title="Lihat dokumen baru"><span class="fa fa-file-o"></span> lihat</a>
                            <?php } ?>
                            <input type="text" name='id_arsip' id='<?= $kode_arsip ?>' class="hide" value="<?= $detail_riwayat->id_arsip ?>">
                            <?php if (isset($detail_riwayat->id_arsip)) { ?>
                                <a href="<?php echo base_url(); ?>admin/arsip/arsip_digital/viewdoc2/<?php echo $detail_riwayat->id_arsip; ?>" data-toggle='tooltip' class="btn btn-sm btn-warning show-modal" tooltip="Lihat dokumen" title="Lihat dokumen"><span class="fa fa-file-o"></span> Lihat</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <input type="text" class="hide" name='nip' value="<?= $NIP_BARU?>">
                <input type="text" class="hide" name='ref' value="<?= $detail_riwayat->ref?>">
                <input type="text" class="hide" name='mandiri' value="<?= $mandiri ? $mandiri : '' ?>">
                <input type="text" class="hide" name='id_update_mandiri' value="<?= $id_update_mandiri ? $id_update_mandiri : '' ?>">
            </div>
        </fieldset>
    </div>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
    <?php if (!$read_only) { ?>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsavekinerja" class='btn btn-primary' value="Simpan Data" />
        </div>
    <?php } ?>
    <?php echo form_close(); ?>
</div>
<script>
    var form = $("#form-riwayat-kinerja-add");
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    }).on("input change", function(e) {
        var date = $(this).datepicker('getDate');
        if (date) $("[name=TAHUN]", form).val(date.getFullYear());
    });
</script>
<script>
    $("#btnsavekinerja").click(function() {
        submitdatakinerja();
        return false;
    });
    $("#frmA").submit(function() {
        submitdatakinerja();
        return false;
    });

    function submitdatakinerja() {
        var the_data = new FormData(document.getElementById("frmkinerja"));
        var json_url = "<?php echo base_url() ?>pegawai/riwayatkinerja/save";
        $.ajax({
            type: "POST",
            url: json_url,
            data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function(data) {
                if (data.success) {
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-kinerja");
                    $("#modal-custom-global").modal("hide");
                } else {
                    $("#form-riwayat-kinerja-add .messages").empty().append(data.msg);
                }
            }
        });
        return false;
    }
</script>