<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-warning' id="form-istri-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmistri"'); ?>
        <fieldset>
            <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input nama='PNS_NIP' type='hidden' nama="">
            <div class="control-group<?php echo form_error('PNS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('PNS', 'PNS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type="checkbox" <?php echo $view ? "":"disabled"; ?> value="1" name="PNS" <?php if(isset($detail_riwayat->PNS))  echo  ($detail_riwayat->PNS=="1") ? "checked" : ""; ?>>
                     
                </div>
            </div>   
             
             
            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA", 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' type='text' <?php echo $view ? "":"disabled"; ?> class="form-control" name='NAMA' maxlength='32' value="<?php echo set_value('NAMA', isset($detail_riwayat->NAMA) ? $detail_riwayat->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL NIKAH</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_MENIKAH'  value="<?php echo set_value('TANGGAL_MENIKAH', isset($detail_riwayat->TANGGAL_MENIKAH) ? $detail_riwayat->TANGGAL_MENIKAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_MENIKAH'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('AKTE_NIKAH') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("AKTE NIKAH", 'AKTE_NIKAH', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='AKTE_NIKAH' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='AKTE_NIKAH' maxlength='32' value="<?php echo set_value('AKTE_NIKAH', isset($detail_riwayat->AKTE_NIKAH) ? $detail_riwayat->AKTE_NIKAH : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('AKTE_NIKAH'); ?></span>
                </div>
            </div>     
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL MENINGGAL</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_MENINGGAL'  value="<?php echo set_value('TANGGAL_MENINGGAL', isset($detail_riwayat->TANGGAL_MENINGGAL) ? $detail_riwayat->TANGGAL_MENINGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_MENINGGAL'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('AKTE_MENINGGAL') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("AKTE MENINGGAL", 'AKTE_MENINGGAL', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='AKTE_MENINGGAL' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='AKTE_MENINGGAL' maxlength='32' value="<?php echo set_value('AKTE_MENINGGAL', isset($detail_riwayat->AKTE_MENINGGAL) ? $detail_riwayat->AKTE_MENINGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('AKTE_MENINGGAL'); ?></span>
                </div>
            </div>
            
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL CERAI</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_CERAI'  value="<?php echo set_value('TANGGAL_CERAI', isset($detail_riwayat->TANGGAL_CERAI) ? $detail_riwayat->TANGGAL_CERAI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_CERAI'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('AKTE_CERAI') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("AKTE_CERAI", 'AKTE_CERAI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='AKTE CERAI' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='AKTE_CERAI' maxlength='32' value="<?php echo set_value('AKTE_CERAI', isset($detail_riwayat->AKTE_CERAI) ? $detail_riwayat->AKTE_CERAI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('AKTE_CERAI'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KARSUS') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("KARIS/KARSU", 'KARSUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KARSUS' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='KARSUS' maxlength='32' value="<?php echo set_value('KARSUS', isset($detail_riwayat->KARSUS) ? $detail_riwayat->KARSUS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KARSUS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('STATUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('STATUS', 'STATUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="STATUS" <?php echo $view ? "":"disabled"; ?> id="STATUS" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->STATUS))  echo  ($detail_riwayat->STATUS=="1") ? "selected" : ""; ?>>MENIKAH</option> 
                        <option value="2" <?php if(isset($detail_riwayat->STATUS))  echo  ($detail_riwayat->STATUS=="2") ? "selected" : ""; ?>>CERAI HIDUP</option>
                        <option value="3" <?php if(isset($detail_riwayat->STATUS))  echo  ($detail_riwayat->STATUS=="3") ? "selected" : ""; ?>>CERAI MATI</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-8">
                <label for="inputNAMA" class="control-label">Berkas Akta</label>
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
                    <?php if (!empty($view)){ ?>
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
            <input type="text" class="hide" name='id_update_mandiri' value="<?=$id_update_mandiri?>">
        </fieldset>
            <!-- <?php if ($this->auth->has_permission('Data_keluarga.Kepegawaian.VerifikasiS')) { ?>
	        <fieldset>
	            <legend>Verifikasi Data (SATKER)</legend>
	            <div class="callout callout-warning">
	               <h4>Checklist "TERIMA DATA" jika data yang dimasukan oleh pegawai diterima oleh satker</h4>
	             </div>
	            <div class="control-group col-sm-6">
	                <label for="inputNAMA" class="control-label">TERIMA DATA?</label>
	                <div class='controls'>
	                    <input id='STATUS_SATKER' type='checkbox' name='STATUS_SATKER' value="1" <?php echo $detail_riwayat->STATUS_SATKER == "1" ? "checked" : ""; ?> />
	                    <span class='help-inline'><?php echo form_error('STATUS_SATKER'); ?></span>
	                </div>
	            </div> 
	        </fieldset>
	        <?php } ?>
	        <?php if ($this->auth->has_permission('Data_keluarga.Kepegawaian.VerifikasiB')) { ?>
	        <fieldset>
	            <legend>Verifikasi Data (BIRO)</legend>
	            <div class="callout callout-warning">
	               <h4>Checklist "TERIMA DATA" jika data yang dimasukan oleh pegawai diterima oleh BIRO</h4>
	             </div>
	            <div class="control-group col-sm-6">
	                <label for="inputNAMA" class="control-label">TERIMA DATA?</label>
	                <div class='controls'>
	                    <input id='STATUS_BIRO' type='checkbox' name='STATUS_BIRO' value="1" <?php echo $detail_riwayat->STATUS_BIRO == "1" ? "checked" : ""; ?> />
	                    <span class='help-inline'><?php echo form_error('STATUS_BIRO'); ?></span>
	                </div>
	            </div> 
            </fieldset>
            <?php } ?>
        </div> -->
        <?php if(!$read_only){ ?>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsaveistri" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-istri-add");
     $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script>
    $("#btnsaveistri").click(function(){
        submitdataistri();
        return false; 
    }); 
    $("#frma").submit(function(){
        submitdataistri();
        return false; 
    }); 
    function submitdataistri(){
        
        var json_url = "<?php echo base_url() ?>pegawai/data_keluarga/saveistri";
         $.ajax({    
            type: "POST",
            url: json_url,
            data: $("#frmistri").serialize(),
            dataType: "json",
            success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-istri");
                    $("#modal-global").modal("hide");
                    $("#modal-custom-global").modal("hide");
                    // loadkeluargaall();
                }
                else {
                    $("#form-istri-add .messages").empty().append(data.msg);
                }
            }});

        return false; 
    }
</script>