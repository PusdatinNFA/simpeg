<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
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
    <?php } ?>
<div class='box box-warning' id="form-riwayat-aktivitas-ilmiah-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm-aktivitas-ilmiah"'); ?>
    <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='nip' type='hidden' class="form-control" name='nip' maxlength='18' value="<?php echo set_value('nip', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
            <div class="control-group<?php echo form_error('scientific_activity_name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nama Aktivitas Ilmiah", 'scientific_activity_name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='scientific_activity_name' type='text' class="form-control" name='scientific_activity_name' maxlength='200' value="<?php echo set_value('scientific_activity_name', isset($detail_riwayat->scientific_activity_name) ? trim($detail_riwayat->scientific_activity_name) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('scientific_activity_name'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('scientific_activity_type_id') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Jenis Aktivitas Ilmiah", 'scientific_activity_type_id', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="scientific_activity_type_id">
                        <option value="0">-- Silahkan Pilih --</option>
                        <?php foreach ($scientific_activities as $act) { ?>
                            <option value="<?=$act->scientific_activity_type_id?>" <?php echo $act->scientific_activity_type_id==$detail_riwayat->scientific_activity_type_id? "selected":"" ?>><?=$act->scientific_activity_type_name?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('scientific_activity_type_id'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('starting_date') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Tanggal Mulai", 'starting_date', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input id='starting_date' type='text' class="form-control datepicker" name='starting_date' value="<?php echo set_value('starting_date', isset($detail_riwayat->starting_date) ? trim($detail_riwayat->starting_date) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('starting_date'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('starting_date') ? ' error' : ''; ?> col-sm-6 pull-right">
                <?php echo form_label("Tanggal Selesai", 'ending_date', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input id='ending_date' type='text' class="form-control datepicker" name='ending_date' value="<?php echo set_value('ending_date', isset($detail_riwayat->ending_date) ? trim($detail_riwayat->ending_date) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('starting_date'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('location') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Lokasi", 'location', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='location' type='text' class="form-control" name='location' maxlength='200' value="<?php echo set_value('location', isset($detail_riwayat->location) ? trim($detail_riwayat->location) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('location'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('country') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Negara", 'country', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="country">
                        <option value="0">-- Silahkan Pilih --</option>
                        <?php foreach ($countries as $country) { ?>
                            <option value="<?=$country->code?>" <?php echo $country->code==$detail_riwayat->country? "selected":"" ?>><?=$country->content?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('country'); ?></span>
                </div>
            </div>
			<div class="control-group<?php echo form_error('sponsor') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Sponsor", 'location', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='sponsor' type='text' class="form-control" name='sponsor' maxlength='200' value="<?php echo set_value('sponsor', isset($detail_riwayat->sponsor) ? trim($detail_riwayat->sponsor) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('sponsor'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('explanation') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Keterangan", 'explanation', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea class="form-control" name="explanation"><?php echo set_value('explanation', isset($detail_riwayat->explanation) ? trim($detail_riwayat->explanation) : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('explanation'); ?></span>
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
            <br>
            <div class="control-group col-sm-12">
                <label class="checkbox-inline">
                    <input type="checkbox" value="1" id="checkbox">Saya menyatakan bahwa data dan dokumen yang saya unggah pada laman ini adalah valid dan dapat dipertanggungjawabkan kebenarannya.
                </label>
            </div>
            <input type="text" class="hide" name='mandiri' value="<?=$mandiri?$mandiri:''?>">
            <input type="text" class="hide" name='id_update_mandiri' value="<?=$id_update_mandiri?$id_update_mandiri:''?>">
            </fieldset>
        </div>
        <?php if (!$read_only){ ?>
  		<div class="box-footer">
            <input type='submit' name='save' id="btnsaveaktivitas" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
	 $(".select2").select2({width: '100%'});
</script>
<script>
  
    var form = $("#form-riwayat-aktivitas-ilmiah-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsaveaktivitas").click(function(){
		submitdataaktivitas();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdataaktivitas();
		return false; 
	});	
	function submitdataaktivitas(){
        $('#btnsaveaktivitas').text('Sedang Mengirim...');
        $('#btnsaveaktivitas').prop('disabled',true);
        var checkbox = document.getElementById('checkbox').checked;
        if (!checkbox){
            swal("Peringatan!", "Mohon menceklist checkbox terlebih dahulu", "warning");
            return false;
        }
		var the_data = new FormData(document.getElementById("frm-aktivitas-ilmiah"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayataktivitasilmiah/save";
		 $.ajax({    
		 	type: "POST",
			url: json_url,
			data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
			success: function(data){ 
                $('#btnsaveaktivitas').prop('disabled',false);
                $('#btnsaveaktivitas').text('Simpan Data');
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-aktivitas-ilmiah");
					$("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-aktivitas-ilmiah-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>