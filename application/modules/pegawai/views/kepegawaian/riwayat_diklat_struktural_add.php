<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>

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
<div class='box box-warning' id="form-diklat-struktural-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmdiklatstruktural"'); ?>
        <fieldset>
            <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input nama='PNS_NIP' type='hidden' nama="">
            
            <div class="control-group<?php echo form_error('ID_DIKLAT') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('NAMA', 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                	<select name="ID_DIKLAT" id="ID_DIKLAT" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jenis_diklats) && is_array($jenis_diklats) && count($jenis_diklats)):?>
						<?php foreach($jenis_diklats as $record):?>
							<option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_DIKLAT))  echo  ($detail_riwayat->ID_DIKLAT==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
							<?php endforeach;?>
						<?php endif;?>
					</select>
                    <span class='help-inline'><?php echo form_error('PENDIDIKAN_ID'); ?></span>
                </div>
            </div>            
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL MULAI</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL'  value="<?php echo set_value('TANGGAL', isset($detail_riwayat->TANGGAL) ? $detail_riwayat->TANGGAL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('Tgl'); ?></span>
				</div>
			</div>
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TANGGAL SELESAI</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_SELESAI'  value="<?php echo set_value('TANGGAL_SELESAI', isset($detail_riwayat->TANGGAL_SELESAI) ? $detail_riwayat->TANGGAL_SELESAI : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_SELESAI'); ?></span>
				</div>
			</div> 
            <div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TAHUN</label>
				<div class="input-group date">
					<input type='text' class="form-control pull-right " name='TAHUN'  value="<?php echo set_value('TAHUN', isset($detail_riwayat->TAHUN) ? $detail_riwayat->TAHUN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('Tgl'); ?></span>
				</div>
			</div> 
			<div class="control-group<?php echo form_error('LAMA') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("LAMA (JAM)", 'LAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='LAMA' type='number' class="form-control" name='LAMA' maxlength='5' value="<?php echo set_value('LAMA', isset($detail_riwayat->LAMA) ? trim($detail_riwayat->LAMA) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('LAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NOMOR') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NOMOR", 'NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR' type='text' class="form-control" name='NOMOR' maxlength='255' value="<?php echo set_value('NOMOR', isset($detail_riwayat->NOMOR) ? $detail_riwayat->NOMOR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR'); ?></span>
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
        </div>
        <?php if (!$read_only){ ?>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsavestrktural" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
    var form = $("#form-diklat-struktural-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsavestrktural").click(function(){
		submitdatastruktural();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdatastruktural();
		return false; 
	});	
	function submitdatastruktural(){
		var the_data = new FormData(document.getElementById("frmdiklatstruktural"));
		var json_url = "<?php echo base_url() ?>pegawai/diklatstruktural/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-diklat-struktural");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-diklat-struktural-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>
