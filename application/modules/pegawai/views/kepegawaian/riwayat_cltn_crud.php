<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css" />
<?php
$id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
if ($mandiri) { ?>
	<div class="callout callout-info">
		<h4>Perhatian</h4>
            <ul>
                <li>Data yang anda masukan tidak otomatis mengubah data simpeg dan akan diverifikasi terlebih dahulu oleh kepegawaian.<br>
                                Anda dapat mengecek pengajuan perubahan pada menu <b><a href="<?=base_url()?>admin/kepegawaian/pegawai/peremajaan" target="_BLANK">Peremajaan Data</a></b></li>
                <li>Maksimal ukuran berkas yang diunggah sebesar 2MB.</li>
            </ul>
        </div>
	</div>
<?php } ?>
<div class='box box-danger' id="form-riwayat-cltn-add">
    <div class="box-body">
        <div class="messages">
        </div>
        <?php echo form_open($this->uri->uri_string(), 'id="frmriwayatcltn"'); ?>
        <fieldset>
            <input type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='pns_id' type='hidden' class="form-control" name='pns_id' maxlength='32' value="<?php echo set_value('pns_id', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input id='pns_nip' type='hidden' class="form-control" name='pns_nip' maxlength='18' value="<?php echo set_value('pns_nip', isset($PNS_NIP) ? trim($PNS_NIP) : ''); ?>" />
            <div class="control-group<?php echo form_error('jenis') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Jenis Cuti", 'jenis', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control" name="jenis">
                        <option value="">-- Pilih Jenis Cuti --</option>
                        <option value="1" <?php echo $detail_riwayat->jenis == '1'? 'selected' : '' ?>>CLTN</option>
                        <option value="2" <?php echo $detail_riwayat->jenis == '2'? 'selected' : '' ?>>Cuti Besar</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('jenis'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('cltn_id') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Jenis", 'cltn_id', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control" name="cltn_id">
                        <option value="01" <?php echo $detail_riwayat->cltn_id == '01'? 'selected' : '' ?>>Baru</option>
                        <option value="03" <?php echo $detail_riwayat->cltn_id == '03'? 'selected' : '' ?>>Perpanjangan</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('cltn_id'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('nomor_sk') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nomor_sk' type='text' class="form-control" name='nomor_sk' maxlength='32' value="<?php echo set_value('nomor_sk', isset($detail_riwayat->nomor_sk) ? trim($detail_riwayat->nomor_sk) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nomor_sk'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">SK TANGGAL</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='tanggal_sk' value="<?php echo set_value('tanggal_sk', isset($detail_riwayat->tanggal_sk) ? $detail_riwayat->tanggal_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_sk'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL AWAL</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='tanggal_awal' value="<?php echo set_value('tanggal_awal', isset($detail_riwayat->tanggal_awal) ? $detail_riwayat->tanggal_awal : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_awal'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL AKHIR</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='tanggal_akhir' value="<?php echo set_value('tanggal_akhir', isset($detail_riwayat->tanggal_akhir) ? $detail_riwayat->tanggal_akhir : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_awal'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('no_letter_bkn') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("No Surat BKN", 'no_letter_bkn', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='no_letter_bkn' type='text' class="form-control" name='no_letter_bkn' maxlength='32' value="<?php echo set_value('no_letter_bkn', isset($detail_riwayat->no_letter_bkn) ? trim($detail_riwayat->no_letter_bkn) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('no_letter_bkn'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">Tanggal Surat BKN</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control datepicker" name='tanggal_letter_bkn' value="<?php echo set_value('tanggal_letter_bkn', isset($detail_riwayat->tanggal_letter_bkn) ? $detail_riwayat->tanggal_letter_bkn : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_letter_bkn'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('status') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Masih Cuti?", 'status', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='status' type='checkbox' name='status' value="1" <?php echo $detail_riwayat->status ? 'checked' : '';?>/>
                    <span class='help-inline'><?php echo form_error('status'); ?></span>
                </div>
            </div>
            <?php if ($detail_riwayat->id_bkn){ ?>
            <div class="control-group col-sm-9">
                <label for="inputNAMA" class="control-label">ID BKN</label>
                <div class='controls'>
                    <input type='text' class="form-control pull-right" name='id_bkn' readonly value="<?php echo set_value('id_bkn', isset($detail_riwayat->id_bkn) ? $detail_riwayat->id_bkn : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('id_bknv'); ?></span>
                </div>
            </div>
            <?php } ?>
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
                    <?php if (!$read_only){ ?>
                        <?php $kode_arsip = uniqid(); ?>
                        <a href="#" kode='<?=$kode_arsip?>' class="btn btn-sm btn-success btn-daftar-arsip" data-toggle='tooltip' tooltip="Pilih dokumen" title="Pilih dokumen"><span class="fa fa-search"></span> pilih</a>
                        <a href="#" id='view_<?=$kode_arsip?>' class="btn btn-sm btn-info show-modal hide" data-toggle='tooltip' tooltip="Lihat dokumen baru" title="Lihat dokumen baru"><span class="fa fa-file-o"></span> lihat</a>
                    <?php } ?>
                        <input type="text" name='id_arsip' id='<?=$kode_arsip?>' class="hide" value="<?= $detail_riwayat->id_arsip ?>">
                        <?php if (isset($detail_riwayat->id_arsip)) { ?>
                            <a href="<?php echo base_url(); ?>admin/arsip/arsip_digital/viewdoc2/<?php echo $detail_riwayat->id_arsip; ?>" data-toggle='tooltip' class="btn btn-sm btn-warning show-modal" tooltip="Lihat dokumen" title="Lihat dokumen"><span class="fa fa-file-o"></span> Lihat</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <input type="text" class="hide" name='mandiri' value="<?=$mandiri?$mandiri:''?>">
            <input type="text" class="hide" name='id_update_mandiri' value="<?=$id_update_mandiri?$id_update_mandiri:''?>">
        </fieldset>
        <?php if (!$read_only){ ?>
        <div class="box-footer">
            <a href="javascript:;" id="btnsave_riwayatcltn" class="btn green btn-primary button-submit"> Simpan Data
                <i class="fa fa-save"></i>
            </a>
        </div>
        <?php } ?>
        <?php echo form_close(); ?>
    </div>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
    <script>
        var form = $("#form-riwayat-cltn-add");
        //Date picker
        $('.datepicker').datepicker({
        autoclose: true,format: 'yyyy-mm-dd'
        }).on("input change", function (e) {
            var date = $(this).datepicker('getDate');
        });
    </script>
    <script>
        $("#btnsave_riwayatcltn").click(function() {
            $('#btnsave_riwayatcltn').text('Sedang Mengirim...');
            $('#btnsave_riwayatcltn').prop('disabled',true);
            submitdatafrmriwayatcltn();
            return false;
        });
        $("#frmA").submit(function() {
            submitdatafrmriwayatcltn();
            return false;
        });

        function submitdatafrmriwayatcltn() {
            var the_data = new FormData(document.getElementById("frmriwayatcltn"));
            var json_url = "<?php echo base_url() ?>pegawai/riwayat_cltn/save";
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
                        $("#modal-custom-global").trigger("sukses-tambah-riwayat-cltn");
                        $("#modal-custom-global").modal("hide");
                        $("#modal-global").modal("hide");
                        $('#btnsave_riwayatcltn').prop('disabled',false);
                        $('#btnsave_riwayatcltn').text('Kirimkan Data');
                    } else {
                        $('#btnsave_riwayatcltn').prop('disabled',false);
                        $('#btnsave_riwayatcltn').text('Kirimkan Data');
                        $("#form-riwayat-cltn-add .messages").empty().append(data.msg);
                    }
                }
            });
            return false;
        }
    </script>