<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class="callout callout-info">
   <h4>Perhatian</h4>
   <p>Data yang anda masukan akan diverifikasi oleh kepegawaian Satker/Upt anda setelah itu data akan diverifikasi ulang oleh Biro SDM</p>
 </div>
<div class='box box-warning' id="form-penghargaan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(),"id=frmpenghargaanmandiri","form"); ?>
        <fieldset>
             <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('ID_JENIS_PENGHARGAAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Jenis Penghargaan', 'ID_JENIS_PENGHARGAAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="ID_JENIS_PENGHARGAAN" id="ID_JENIS_PENGHARGAAN" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($jenis_penghargaans) && is_array($jenis_penghargaans) && count($jenis_penghargaans)):?>
                        <?php foreach($jenis_penghargaans as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->ID_JENIS_PENGHARGAAN))  echo  (TRIM($detail_riwayat->ID_JENIS_PENGHARGAAN)==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                     
                    <span class='help-inline'><?php echo form_error('ID_JENIS_PENGHARGAAN'); ?></span>
                </div>
            </div>


           <div class="control-group<?php echo form_error('SK_NOMOR') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Nomor SK", 'SK_NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SK_NOMOR' type='text' class="form-control" name='SK_NOMOR' maxlength='50' value="<?php echo set_value('sk_nomor', isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Tanggal SK</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='SK_TANGGAL'  value="<?php echo set_value('SK_TANGGAL', isset($detail_riwayat->SK_TANGGAL) ? $detail_riwayat->SK_TANGGAL : ''); ?>" />
					<span class='help-inline'><?php echo form_error('SK_TANGGAL'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('SURAT_USUL') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Surat Usul", 'SURAT_USUL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SURAT_USUL' type='text' class="form-control" name='SURAT_USUL' maxlength='50' value="<?php echo set_value('SURAT_USUL', isset($detail_riwayat->SURAT_USUL) ? $detail_riwayat->SURAT_USUL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SURAT_USUL'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Katerangan", 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea id='keterangan' type='text' class="form-control" name='KETERANGAN' maxlength='200'><?php echo set_value('KETERANGAN', isset($detail_riwayat->KETERANGAN) ? $detail_riwayat->KETERANGAN : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
                </div>
            </div>

            
            <div class="control-group col-sm-9">
                <label for="inputNAMA" class="control-label">Berkas</label>
                <div class='controls'>
                    <div id="form_upload">
                      <input id="file_dokumen" name="file_dokumen" class="file" type="file" data-show-upload="false" data-preview-file-type="pdf">
                    </div>
                    
                </div>
            </div> 
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label"><br></label>
                <div class='controls'>
                    
                    <?php if(isset($detail_riwayat->FILE_BASE64) && $detail_riwayat->FILE_BASE64 != ""){ ?>
                        <a href="<?php echo base_url(); ?>pegawai/riwayatpenghargaan/viewdoc/<?php echo $detail_riwayat->ID; ?>" class="btn btn-warning show-modal" tooltip="Lihat dokumen <?php echo isset($detail_riwayat->NOMOR_IJASAH) ? $detail_riwayat->NOMOR_IJASAH : ''; ?>" ><span class="fa fa-file-o"></span> Lihat</a>
                    <?php } ?>  
                </div>
            </div>
        </fieldset>
        </div>
  		<div class="box-footer">
            <a href="javascript:;" id="btnsavepenghargaan" class="btn green btn-primary button-submit"> Simpan Data
                <i class="fa fa-save"></i>
            </a>
        </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-penghargaan-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        // if(date)$("[name=TAHUN_LULUS]",form).val(date.getFullYear());
    });
</script>
<script>
    $(".select2").select2({width: '100%'});
	$("#btnsavepenghargaan").click(function(){
        $('#btnsavepenghargaan').text('Sedang Mengirim...');
        $('#btnsavepenghargaan').prop('disabled',true);
		submitdatapenghargaan();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdatapenghargaan();
		return false; 
	});	
	function submitdatapenghargaan(){
		var the_data = new FormData(document.getElementById("frmpenghargaanmandiri"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatpenghargaan/savemandiri";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-penghargaan");
					$("#modal-custom-global").modal("hide");
                    $('#btnsavepenghargaan').prop('disabled',false);
                    $('#btnsavepenghargaan').text('Simpan Data');
                    $grid_daftar_penghargaan.ajax.reload();
                    $table.ajax.reload(null,true);
                }
                else {
                    $('#btnsavepenghargaan').prop('disabled',false);
                    $('#btnsavepenghargaan').text('Simpan Data');
                    $("#form-penghargaan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>