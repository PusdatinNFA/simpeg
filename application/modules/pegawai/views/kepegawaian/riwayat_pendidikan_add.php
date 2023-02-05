<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-pendidikan-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(),"id=frmpendidikan","form"); ?>
        <fieldset>
             <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('TINGKAT_PENDIDIKAN_ID') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label('Jenjang Pendidikan', 'TINGKAT_PENDIDIKAN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="TINGKAT_PENDIDIKAN_ID" id="TK_PENDIDIKAN" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($tk_pendidikans) && is_array($tk_pendidikans) && count($tk_pendidikans)):?>
                        <?php foreach($tk_pendidikans as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->TINGKAT_PENDIDIKAN_ID))  echo  (TRIM($detail_riwayat->TINGKAT_PENDIDIKAN_ID)==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                     
                    <span class='help-inline'><?php echo form_error('TINGKAT_PENDIDIKAN_ID'); ?></span>
                </div>
            </div>

            <!--rifkyz-->
            <div class="control-group<?php echo form_error('PENDIDIKAN_ID') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Pendidikan", 'PENDIDIKAN_ID', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="PENDIDIKAN_ID" id="SLCPENDIDIKAN_ID" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                            <?php 
                                if($selectedPendidikanID){
                                    echo "<option selected value='".$selectedPendidikanID->ID."'>".$selectedPendidikanID->NAMA."</option>";
                                }
                            ?>
                    </select>

                    <span class='help-inline'><?php echo form_error('PENDIDIKAN_ID'); ?></span>
                </div>
            </div>
            <!--rifkyz-->


           <div class="control-group<?php echo form_error('NAMA_SEKOLAH') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("NAMA Sekolah", 'NAMA_SEKOLAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_SEKOLAH' type='text' class="form-control" name='NAMA_SEKOLAH' maxlength='200' value="<?php echo set_value('NAMA_SEKOLAH', isset($detail_riwayat->NAMA_SEKOLAH) ? $detail_riwayat->NAMA_SEKOLAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_SEKOLAH'); ?></span>
                </div>
            </div>
            
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">Tgl Lulus</label>
				<div class="input-group date">
				  <div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				  </div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_LULUS'  value="<?php echo set_value('TANGGAL_LULUS', isset($detail_riwayat->TANGGAL_LULUS) ? $detail_riwayat->TANGGAL_LULUS : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_LULUS'); ?></span>
				</div>
			</div> 
            <div class="control-group<?php echo form_error('TAHUN_LULUS') ? ' error' : ''; ?> col-sm-3">
                <?php echo form_label("Tahun Lulus", 'GELAR_BELAKANG', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TAHUN_LULUS' type='text' class="form-control" name='TAHUN_LULUS' maxlength='11' value="<?php echo set_value('TAHUN_LULUS', isset($detail_riwayat->TAHUN_LULUS) ? $detail_riwayat->TAHUN_LULUS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TAHUN_LULUS'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('NOMOR_IJASAH') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("Nomor Ijasah", 'NOMOR_IJASAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_IJASAH' type='text' class="form-control" name='NOMOR_IJASAH' maxlength='32' value="<?php echo set_value('NOMOR_IJASAH', isset($detail_riwayat->NOMOR_IJASAH) ? $detail_riwayat->NOMOR_IJASAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_IJASAH'); ?></span>
                </div>
            </div>

            
             <div class="control-group<?php echo form_error('GELAR_DEPAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('Gelar Depan', 'GELAR_DEPAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_DEPAN' type='text' class="form-control" name='GELAR_DEPAN' maxlength='11' value="<?php echo set_value('GELAR_DEPAN', isset($detail_riwayat->GELAR_DEPAN) ? $detail_riwayat->GELAR_DEPAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_DEPAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('GELAR_BELAKANG') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Gelar Belakang", 'GELAR_BELAKANG', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='GELAR_BELAKANG' type='text' class="form-control" name='GELAR_BELAKANG' maxlength='11' value="<?php echo set_value('GELAR_BELAKANG', isset($detail_riwayat->GELAR_BELAKANG) ? $detail_riwayat->GELAR_BELAKANG : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('GELAR_BELAKANG'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">DIAKUI BKN?</label>
                <div class='controls'>
                    <input id='DIAKUI_BKN' type='checkbox' name='DIAKUI_BKN' value="1" <?php echo $detail_riwayat->DIAKUI_BKN == "1" ? "checked" : ""; ?> />
                    <span class='help-inline'><?php echo form_error('DIAKUI_BKN'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('TUGAS_BELAJAR') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("TUGAS BELAJAR", 'TUGAS_BELAJAR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="TUGAS_BELAJAR" id="TUGAS_BELAJAR" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->TUGAS_BELAJAR))  echo  (TRIM($detail_riwayat->TUGAS_BELAJAR)=="1") ? "selected" : ""; ?>>Tugas Belajar</option>
                        <option value="2" <?php if(isset($detail_riwayat->TUGAS_BELAJAR))  echo  (TRIM($detail_riwayat->TUGAS_BELAJAR)=="2") ? "selected" : ""; ?>>Izin Belajar</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('PENDIDIKAN_ID'); ?></span>
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
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">PENDIDIKAN TERAKHIR?</label>
                <div class='controls'>
                    <input id='PENDIDIKAN_TERAKHIR' type='checkbox' name='PENDIDIKAN_TERAKHIR' value="1" <?php echo $detail_riwayat->PENDIDIKAN_TERAKHIR == "1" ? "checked" : ""; ?> />
                    <span class='help-inline'><?php echo form_error('PENDIDIKAN_TERAKHIR'); ?></span>
                </div>
            </div> 
            <input type="text" class="hide" name='id_update_mandiri' value="<?=$id_update_mandiri?>">
        </fieldset>
        </div>
        <?php if (!$read_only){ ?>
  		<div class="box-footer">
            <a href="javascript:;" id="btnsavependidikan" class="btn green btn-primary button-submit"> Simpan Data
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
    var form = $("#form-pendidikan-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN_LULUS]",form).val(date.getFullYear());
    });
</script>
<script>
    $(".select2").select2({width: '100%'});
    $("#SLCPENDIDIKAN_ID").select2({
        placeholder: 'Cari Pendidikan...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/masterpendidikan/ajax");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    tkpendidikan : $('#TK_PENDIDIKAN').val()
                }
            },
            cache: true
        }
    });
	$("#btnsavependidikan").click(function(){
        $('#btnsavependidikan').text('Sedang Mengirim...');
        $('#btnsavependidikan').prop('disabled',true);
		submitdatapendidikanformal();
		return false; 
	});	
	$("#frma").submit(function(){
		submitdatapendidikanformal();
		return false; 
	});	
	function submitdatapendidikanformal(){
		var the_data = new FormData(document.getElementById("frmpendidikan"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatpendidikan/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-pendidikan");
					$("#modal-custom-global").modal("hide");
                    $('#btnsavependidikan').prop('disabled',false);
                    $('#btnsavependidikan').text('Simpan Data');
                    //grid_daftar_pendidikan.ajax.reload();
                    $table.ajax.reload(null,true);
                }
                else {
                    $('#btnsavependidikan').prop('disabled',false);
                    $('#btnsavependidikan').text('Simpan Data');
                    $("#form-pendidikan-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>