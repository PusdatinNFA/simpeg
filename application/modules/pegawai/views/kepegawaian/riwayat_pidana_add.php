<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
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
<div class='box box-warning' id="form-pidana-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(),"id=frmpidana","form"); ?>
        <fieldset>
             <input id='id_data' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($id) ? trim($id) : ''); ?>" />
            <input type='hidden' class="form-control" name='pns_id' maxlength='32' value="<?php echo set_value('pns_id', isset($pns_id) ? trim($pns_id) : ''); ?>" />
            <input type='hidden' class="form-control" name='nip' maxlength='18' value="<?php echo set_value('nip', isset($nip) ? trim($nip) : ''); ?>" />
            
           <div class="control-group<?php echo form_error('nomor_putusan') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Nomor Putusan", 'nomor_putusan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nomor_putusan' type='text' class="form-control" name='nomor_putusan' value="<?php echo set_value('nomor_putusan', isset($detail_riwayat->nomor_putusan) ? $detail_riwayat->nomor_putusan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Tanggal</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='tanggal_putusan'  value="<?php echo set_value('tanggal_putusan', isset($detail_riwayat->tanggal_putusan) ? $detail_riwayat->tanggal_putusan : ''); ?>" />
					<span class='help-inline'><?php echo form_error('tanggal_putusan'); ?></span>
				</div>
			</div>

            <div class="control-group<?php echo form_error('jabatan') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jabatan saat putusan", 'jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='jabatan' type='text' class="form-control" name='jabatan' value="<?php echo set_value('jabatan', isset($detail_riwayat->jabatan) ? $detail_riwayat->jabatan : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('vonis') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Vonis", 'vonis', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea id='vonis' class="form-control" name='vonis'><?php echo set_value('vonis', isset($detail_riwayat->vonis) ? $detail_riwayat->vonis : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('vonis'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Tanggal Awal Hukuman</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='tanggal_awal'  value="<?php echo set_value('tanggal_awal', isset($detail_riwayat->tanggal_awal) ? $detail_riwayat->tanggal_awal : ''); ?>" />
					<span class='help-inline'><?php echo form_error('tanggal_awal'); ?></span>
				</div>
			</div>

            <div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Tanggal Akhir Hukuman</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='tanggal_akhir'  value="<?php echo set_value('tanggal_akhir', isset($detail_riwayat->tanggal_akhir) ? $detail_riwayat->tanggal_akhir : ''); ?>" />
					<span class='help-inline'><?php echo form_error('tanggal_akhir'); ?></span>
				</div>
			</div>
            <div class="control-group<?php echo form_error('kasus') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kasus", 'kasus', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea id='kasus' type='text' class="form-control" name='kasus'><?php echo set_value('kasus', isset($detail_riwayat->kasus) ? $detail_riwayat->kasus : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('kasus'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('status') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Status", 'status', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="status" id="status">
                        <option>-- Pilih Status --</option>
                        <option value="1" <?=$detail_riwayat->status==1?'selected':''?>>Sudah aktif kembali</option>
                        <option value="2" <?=$detail_riwayat->status==2?'selected':''?>>Sedang menjalani hukuman pidana</option>
                        <option value="3" <?=$detail_riwayat->status==3?'selected':''?>>Diberhentikan</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('status'); ?></span>
                </div>
            </div>
            
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
        </div>
        <?php if (!$read_only){ ?>
  		<div class="box-footer">
            <a href="javascript:;" id="btnsavepidana" class="btn green btn-primary button-submit"> Simpan Data
                <i class="fa fa-save"></i>
            </a>
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-pidana-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
    });
</script>
<script>
    $(".select2").select2({width: '100%'});
	$("#btnsavepidana").click(function(){
        $('#btnsavepidana').text('Sedang Mengirim...');
        $('#btnsavepidana').prop('disabled',true);
		submitdatapidana();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdatapidana();
		return false; 
	});	
	function submitdatapidana(){
		var the_data = new FormData(document.getElementById("frmpidana"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatpidana/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-pidana");
					$("#modal-custom-global").modal("hide");
                    $('#btnsavepidana').prop('disabled',false);
                    $('#btnsavepidana').text('Simpan Data');
                    $grid_daftar_penghargaan.ajax.reload();
                    $table.ajax.reload(null,true);
                }
                else {
                    $('#btnsavepidana').prop('disabled',false);
                    $('#btnsavepidana').text('Simpan Data');
                    $("#form-pidana-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>