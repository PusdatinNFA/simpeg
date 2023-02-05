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
<div class='box box-warning' id="form-profesor-riset-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(),"id=frmprofesorriset","form"); ?>
        <fieldset>
             <input id='id_data' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($id) ? trim($id) : ''); ?>" />
            <input type='hidden' class="form-control" name='pns_id' maxlength='32' value="<?php echo set_value('pns_id', isset($pns_id) ? trim($pns_id) : ''); ?>" />
            <input type='hidden' class="form-control" name='nip' maxlength='18' value="<?php echo set_value('nip', isset($nip) ? trim($nip) : ''); ?>" />
            
           <div class="control-group<?php echo form_error('nomor_sertifikat') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Nomor SK", 'nomor_sertifikat', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nomor_sertifikat' type='text' class="form-control" name='nomor_sertifikat' value="<?php echo set_value('nomor_sertifikat', isset($detail_riwayat->nomor_sertifikat) ? $detail_riwayat->nomor_sertifikat : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Tanggal</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='tanggal'  value="<?php echo set_value('tanggal', isset($detail_riwayat->tanggal) ? $detail_riwayat->tanggal : ''); ?>" />
					<span class='help-inline'><?php echo form_error('tanggal'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('kepakaran') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kepakaran", 'kepakaran', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='kepakaran' type='text' class="form-control" name='kepakaran' value="<?php echo set_value('kepakaran', isset($detail_riwayat->kepakaran) ? $detail_riwayat->kepakaran : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('kepakaran'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('instansi') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Instansi", 'instansi', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='instansi' type='text' class="form-control" name='instansi' value="<?php echo set_value('instansi', isset($detail_riwayat->instansi) ? $detail_riwayat->instansi : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('instansi'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('judul_orasi') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Judul Orasi", 'judul_orasi', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea id='keterangan' type='text' class="form-control" name='judul_orasi'><?php echo set_value('judul_orasi', isset($detail_riwayat->judul_orasi) ? $detail_riwayat->judul_orasi : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('judul_orasi'); ?></span>
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
            <a href="javascript:;" id="btnsaveprofesorriset" class="btn green btn-primary button-submit"> Simpan Data
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
    var form = $("#form-profesor-riset-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
    });
</script>
<script>
    $(".select2").select2({width: '100%'});
	$("#btnsaveprofesorriset").click(function(){
        $('#btnsaveprofesorriset').text('Sedang Mengirim...');
        $('#btnsaveprofesorriset').prop('disabled',true);
		submitdatapenghargaan();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdatapenghargaan();
		return false; 
	});	
	function submitdatapenghargaan(){
		var the_data = new FormData(document.getElementById("frmprofesorriset"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatprofesorriset/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-profesor-riset");
					$("#modal-custom-global").modal("hide");
                    $('#btnsaveprofesorriset').prop('disabled',false);
                    $('#btnsaveprofesorriset').text('Simpan Data');
                    $grid_daftar_penghargaan.ajax.reload();
                    $table.ajax.reload(null,true);
                }
                else {
                    $('#btnsaveprofesorriset').prop('disabled',false);
                    $('#btnsaveprofesorriset').text('Simpan Data');
                    $("#form-profesor-riset-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>