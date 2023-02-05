<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/daterangepicker/daterangepicker.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php
$this->load->library('convert');
$convert = new convert();
?>
<div class="tab-pane active" id="<?php echo $TAB_ID; ?>">
    <?php if (!$readonly) { ?>
        <div class="callout callout-success">
            <h4>Perhatian</h4>
            <P>Halaman ini adalah halaman untuk memverifikasi perubahan data yang telah dilakukan mandiri oleh pegawai</P>
            <!-- <p>Silahkan checklist pada setiap kolom yang dirubah jika perubahan disetujui</p> -->
        </div>
    <?php } ?>
    <form role="form" action="#" id="frmprofile">
        <input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
        <div class="box box-info">

            <div class="box-body">
                <fieldset>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                NAMA PEGAWAI
                            </div>
                            <div class="form-group col-sm-8">
                                <b><a href="<?=base_url().'admin/kepegawaian/pegawai/profilen/'.$dataupdate->ref?>" target="_BLANK"><?php echo isset($dataupdate->NAMA) ? $dataupdate->NAMA : '-'; ?></a></b>
                            </div>
                        </div>
                    </div>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                JENIS DATA/RIWAYAT
                            </div>
                            <div class="form-group col-sm-8">
                                <b><?php echo isset($dataupdate->NAMA_KOLOM) ? $dataupdate->NAMA_KOLOM : '-'; ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                JENIS PDM
                            </div>
                            <div class="form-group col-sm-8">
                                <b><?php echo isset($dataupdate->DARI) ? "UBAH DATA" : 'TAMBAH BARU'; ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                <b> Perubahan Pada </b>
                            </div>
                            <div class="form-group col-sm-8">

                            </div>
                        </div>
                    </div>
                    <?php 
                    $perubahan = json_decode($dataupdate->PERUBAHAN);
                    $awal = json_decode($dataupdate->DARI);
                    if ($awal instanceof stdClass)
                        $awal = (array) $awal; ?>
                    <div class="control-group col-sm-12">
                        <div class="row" style="margin: 10px ;">
                            <table class="table table-responsive table-striped table-bordered">
                                <thead>
                                    <th>KOLOM</th>
                                    <th>DARI</th>
                                    <th>MENJADI</th>
                                </thead>
                                <tbody>                                    
                                    <?php 
                                        foreach ($perubahan as $key => $value) {
                                        if (trim($dataupdate->KOLOM) == 'DATA_PERSONAL') {
                                            if ($key == 'AGAMA_ID') { 
                                                $this->load->model('pegawai/agama_model');
                                                $agama1 = $this->agama_model->find((int)trim($awal[$key]));
                                                $agama2 = $this->agama_model->find((int)trim($value)); ?>
                                                <tr>
                                                    <td>Agama</td>
                                                    <td><?= $agama1? $agama1->NAMA : "" ?></td>
                                                    <td><?= $agama2? $agama2->NAMA : "" ?></td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td><?= $key ?></td>
                                                    <td><?= $awal[$key] ? $awal[$key] : "" ?></td>
                                                    <td><?= $value ?></td>
                                                </tr>
                                            <?php }
                                        } else if (trim($dataupdate->KOLOM) == 'RIWAYAT_PNS') {
                                            if ($key == 'JENIS_PENGADAAN') { 
                                                $this->load->model('pegawai/jenis_pengadaan_model');
                                                $pengadaan1 = $this->jenis_pengadaan_model->find((int)trim($awal[$key]));
                                                $pengadaan2 = $this->jenis_pengadaan_model->find((int)trim($value)); ?>
                                                <tr>
                                                    <td>Jenis_Pengadaan</td>
                                                    <td><?= $pengadaan1? $pengadaan1->nama : "" ?></td>
                                                    <td><?= $pengadaan2? $pengadaan2->nama : "" ?></td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr>
                                                    <td><?= $key ?></td>
                                                    <td><?= $awal[$key] ? $awal[$key] : "" ?></td>
                                                    <td><?= $value ?></td>
                                                </tr>
                                            <?php }
                                        } else { ?>
                                            <tr>
                                                <td><?= $key ?></td>
                                                <td><?= $awal[$key] ? $awal[$key] : "" ?></td>
                                                <td><?= $value ?></td>
                                            </tr>
                                    <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                TANGGAL PERUBAHAN
                            </div>
                            <div class="form-group col-sm-8">
                                <b><?php echo isset($dataupdate->UPDATE_TGL) ? $dataupdate->UPDATE_TGL : ''; ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                LEVEL UPDATE
                            </div>
                            <div class="form-group col-sm-8">
                                <b><?php echo isset($dataupdate->LEVEL_UPDATE) ? $dataupdate->LEVEL_UPDATE : '-'; ?></b>
                            </div>
                        </div>
                    </div>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                Status
                            </div>
                            <div class="form-group col-sm-4">
                                <?php
                                if (isset($dataupdate->STATUS)) {
                                    if ($dataupdate->STATUS == 1)
                                        $status = 'Diterima';
                                    else if ($dataupdate->STATUS == 0)
                                        $status = 'Ditolak';
                                    else if ($dataupdate->STATUS == 4)
                                        $status = 'Dibatalkan';
                                } else
                                    $status =  'Belum diverifikasi' ?>
                                <b><?= $status ?></b>
                            </div>
                            <div class="form-group col-sm-4">
                            </div>
                        </div>
                    </div>
                    <?php if ($dataupdate->KETERANGAN) { ?>
                        <div class="control-group col-sm-12">
                            <div class="row">
                                <div class="col-sm-4">
                                    KETERANGAN
                                </div>
                                <div class="form-group col-sm-8">
                                    <b><?= $dataupdate->KETERANGAN ?></b>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <br>
                    <div class="control-group col-sm-12">
                        <div class="row">
                            <div class="col-sm-4">
                                BERKAS
                            </div>
                            <div class="form-group col-sm-8">
                                <?php if ($dataupdate->ID_ARSIP) { ?>
                                    <a href="<?php echo base_url(); ?>admin/arsip/arsip_digital/viewdoc2/<?php echo $dataupdate->ID_ARSIP; ?>" class="btn btn-sm btn-warning show-modal" tooltip="Lihat dokumen"><span class="fa fa-file-o"></span> Lihat</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </fieldset>

                <!-- /.table-responsive -->

                <!-- /.box-body -->
                <!-- /.box-footer -->
            </div>
            <div class="box-footer">
                <button type="button" style="margin:5px ;" class="btn btn-sm btn-warning pull-right" data-dismiss="modal">Tutup</button>
                <?php foreach ($button as $btn) {
                    echo $btn;
                } ?>
            </div>
        </div>

    </form>
</div>

<script>
    $('#status_update').change(function() {
        var valstatus_update = $('#status_update').val();
        var valkode = $(this).attr("kode");
        var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/verifikasiupdatemandiri";
        $.ajax({
            type: "post",
            url: json_url,
            data: "valstatus_update=" + valstatus_update + "&kode=" + valkode,
            dataType: "json",
            success: function(data) {
                if (data.success) {
                    swal("Pemberitahuan!", data.msg, "success");
                    $table.ajax.reload(null, false);
                } else {
                    $(".messages").empty().append(data.msg);
                }
            }
        });
        return false;
    });
    $('body').on('click', '.btn-tolak', function(event) {
        event.preventDefault();
        var kode = $(this).attr("kode");
        swal({
                title: "Anda yakin tolak data pengajuan perubahan?",
                text: "Masukan keterangan penolakan",
                type: "input",
                showCancelButton: true,
                confirmButtonClass: 'btn-danger',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: "Tidak, Batalkan!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(inputValue) {
                if (inputValue) {
                    var post_data = "kode=" + kode + "&keterangan=" + inputValue;
                    $.ajax({
                        url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/tolakupdatemandiri",
                        type: "POST",
                        data: post_data,
                        dataType: "json",
                        timeout: 180000,
                        success: function(result) {
                            swal("Berhasil!", result.msg, "success");
                            $table.ajax.reload(null, true);
                            $("#modal-global").modal("hide");
                            $("#modal-custom-global").modal("hide");
                        },
                        error: function(error) {
                            alert(error);
                        }
                    });

                } else {
                    swal("Batal", "", "error");
                }
            });

    });

    $('body').on('click', '.btn-verif', function(event) {
        event.preventDefault();
        var kode = $(this).attr("kode");
        swal({
                title: "Anda yakin",
                text: "Menerima pengajuan perubahan?",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn-success',
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: "Tidak, Batalkan!",
                closeOnConfirm: false,
                closeOnCancel: false
            },
            function(isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/verifikasiupdatemandiri",
                        type: "POST",
                        data: "valstatus_update=1&kode=" + kode,
                        dataType: "json",
                        timeout: 180000,
                        success: function(result) {
                            swal("Berhasil!", result.msg, "success");
                            $table.ajax.reload(null, true);
                            $("#modal-global").modal("hide");
                            $("#modal-custom-global").modal("hide");
                        },
                        error: function(error) {
                            alert(error);
                        }
                    });

                } else {
                    swal("Batal", "", "error");
                }
            });
    });
</script>