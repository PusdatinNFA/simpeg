<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
    $jk = ISSET($pegawai_data->JENIS_KELAMIN) ? $pegawai_data->JENIS_KELAMIN : "";
    $hubungan = ($jk == "M") ? "IBU" : "AYAH";
?>
<div class="callout callout-info">
   <h4>Perhatian</h4>
   <ul>
        <li>Data yang anda masukan tidak otomatis mengubah data simpeg dan akan diverifikasi terlebih dahulu oleh kepegawaian.<br>
            Anda dapat mengecek pengajuan perubahan pada menu <b><a href="<?= base_url() ?>admin/kepegawaian/pegawai/peremajaan" target="_BLANK">Peremajaan Data</a></b></li>
        <li>Maksimal ukuran berkas yang diunggah sebesar 2MB.</li>
        <li>Pengisian data keluarga tidak berpengaruh pada tunjangan pasangan/anak, Pengajuan tunjangan pasangan/anak silahkan mengajukan melalui elawas</li>
    </ul>
 </div>
<div class='box box-warning' id="form-anak-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmanak"'); ?>
        <fieldset>
            <input id='id_data' type='hidden' class="form-control" name='id_data' maxlength='32' value="<?php echo set_value('id_data', isset($id) ? trim($id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input nama='PNS_NIP' type='hidden' nama="">
            <div class="control-group<?php echo form_error('PASANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label($hubungan, 'PASANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="PASANGAN" <?php echo $view ? "":"disabled"; ?> id="PASANGAN" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($data_pasangans) && is_array($data_pasangans) && count($data_pasangans)):?>
                        <?php foreach($data_pasangans as $record):?>
                            <option value="<?php echo $record->ID?>" <?php if(isset($detail_riwayat->PASANGAN))  echo  ($detail_riwayat->PASANGAN==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                            <?php endforeach;?>
                        <?php endif;?>
                    </select>
                    <span class='help-inline'><?php echo form_error('PASANGAN'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('NAMA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA", 'NAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='NAMA' maxlength='255' value="<?php echo set_value('NAMA', isset($detail_riwayat->NAMA) ? $detail_riwayat->NAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JENIS_KELAMIN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('JENIS_KELAMIN', 'JENIS_KELAMIN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_KELAMIN" <?php echo $view ? "":"disabled"; ?> id="JENIS_KELAMIN" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="M" <?php if(isset($detail_riwayat->JENIS_KELAMIN))  echo  ($detail_riwayat->JENIS_KELAMIN=="M") ? "selected" : ""; ?>>Laki-laki</option> 
                        <option value="F" <?php if(isset($detail_riwayat->JENIS_KELAMIN))  echo  ($detail_riwayat->JENIS_KELAMIN=="F") ? "selected" : ""; ?>>Perempuan</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_KELAMIN'); ?></span>
                </div>
            </div>    
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL LAHIR</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' <?php echo $view ? "":"disabled"; ?> class="form-control pull-right datepicker" name='TANGGAL_LAHIR'  value="<?php echo set_value('TANGGAL_LAHIR', isset($detail_riwayat->TANGGAL_LAHIR) ? $detail_riwayat->TANGGAL_LAHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_LAHIR'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('TEMPAT_LAHIR') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("TEMPAT LAHIR", 'TEMPAT_LAHIR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='TEMPAT_LAHIR' <?php echo $view ? "":"disabled"; ?> type='text' class="form-control" name='TEMPAT_LAHIR' maxlength='50' value="<?php echo set_value('TEMPAT_LAHIR', isset($detail_riwayat->TEMPAT_LAHIR) ? $detail_riwayat->TEMPAT_LAHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TEMPAT_LAHIR'); ?></span>
                </div>
            </div>     
               
            <div class="control-group<?php echo form_error('STATUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('STATUS', 'STATUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="STATUS_ANAK" <?php echo $view ? "":"disabled"; ?> id="STATUS_ANAK" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->STATUS_ANAK))  echo  ($detail_riwayat->STATUS_ANAK=="1") ? "selected" : ""; ?>>KANDUNG</option> 
                        <option value="2" <?php if(isset($detail_riwayat->STATUS_ANAK))  echo  ($detail_riwayat->STATUS_ANAK=="2") ? "selected" : ""; ?>>ANGKAT</option>
                        <option value="3" <?php if(isset($detail_riwayat->STATUS_ANAK))  echo  ($detail_riwayat->STATUS_ANAK=="3") ? "selected" : ""; ?>>TIRI</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('STATUS'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('STATUS_HIDUP') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label('STATUS HIDUP', 'STATUS_HIDUP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="STATUS_HIDUP" <?php echo $view ? "":"disabled"; ?> id="STATUS_HIDUP" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?php if(isset($detail_riwayat->STATUS_HIDUP))  echo  ($detail_riwayat->STATUS_HIDUP=="1") ? "selected" : ""; ?>>HIDUP</option> 
                        <option value="2" <?php if(isset($detail_riwayat->STATUS_HIDUP))  echo  ($detail_riwayat->STATUS_HIDUP=="2") ? "selected" : ""; ?>>MENINGGAL</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('STATUS_HIDUP'); ?></span>
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
        </div>
        <?php if($view){ ?>
        <div class="box-footer">
            <input type='submit' name='save' id="btnsaveanak" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
    var form = $("#form-anak-add");
     $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>
<script>
    $("#btnsaveanak").click(function(){
		$('#btnsaveanak').text('Sedang Mengirim...');
		$('#btnsaveanak').prop('disabled',true);
        submitdataanak();
        return false; 
    }); 
    $("#frma").submit(function(){
		$('#btnsaveanak').text('Sedang Mengirim...');
		$('#btnsaveanak').prop('disabled',true);
        submitdataanak();
        return false; 
    }); 
    function submitdataanak(){
        
        var json_url = "<?php echo base_url() ?>pegawai/data_keluarga/saveanakmandiri";
        var the_data = new FormData(document.getElementById("frmanak"));
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
                    $("#modal-global").trigger("sukses-tambah-anak");
                    $("#modal-global").modal("hide");
                    $("#modal-custom-global").modal("hide");
                    // loadkeluargaall();
					$('#btnsaveanak').prop('disabled',false);
					$('#btnsaveanak').text('Kirimkan Data');
                }
                else {
					$('#btnsaveanak').prop('disabled',false);
					$('#btnsaveanak').text('Kirimkan Data');
                    swal("Pemberitahuan!", data.msg, "error");
                    $("#form-anak-add .messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>