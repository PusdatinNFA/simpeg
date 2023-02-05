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
        </div>
    <?php } ?>
<div class='box box-warning' id="form-riwayat-karyatulis-ilmiah-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm-karyatulis-ilmiah"'); ?>
    <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='nip' type='hidden' class="form-control" name='nip' maxlength='18' value="<?php echo set_value('nip', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
            <div class="control-group<?php echo form_error('writer_name') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nama Penulis", 'writer_name', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='writer_name' type='text' class="form-control" name='writer_name' maxlength='200' value="<?php echo set_value('writer_name', isset($detail_riwayat->writer_name) ? trim($detail_riwayat->writer_name) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('writer_name'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('paper_title') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Judul Karya Tulis", 'paper_title', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='paper_title' type='text' class="form-control" name='paper_title' maxlength='200' value="<?php echo set_value('paper_title', isset($detail_riwayat->paper_title) ? trim($detail_riwayat->paper_title) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('paper_title'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('publish_year') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Tahun Publikasi", 'publish_year', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="publish_year">
                        <option value="0">-- Silahkan Pilih --</option>
                        <?php $year = date('Y'); 
                            for ($i=$year; $i >= $year-30 ; $i--) { ?>
                            <option value="<?=$i?>" <?php echo $i==$detail_riwayat->publish_year? "selected":"" ?>><?=$i?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('publish_year'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('publisher_id') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Penulis Ke ", 'publisher_id', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='publisher_id' type='number' class="form-control" name='publisher_id' maxlength='2' value="<?php echo set_value('publisher_id', isset($detail_riwayat->publisher_id) ? trim($detail_riwayat->publisher_id) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('publisher_id'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('publisher_detail') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Keterangan Publikasi", 'publisher_detail', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea class="form-control" name="publisher_detail"><?php echo set_value('publisher_detail', isset($detail_riwayat->publisher_detail) ? trim($detail_riwayat->publisher_detail) : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('publisher_detail'); ?></span>
                </div>
            </div>  
            <div class="control-group<?php echo form_error('scale') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Skala Publikasi", 'scale', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="scale">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="Nasional" <?php echo "Nasional"==$detail_riwayat->scale? "selected":"" ?>>Nasional</option>
                        <option value="Internasional" <?php echo "Internasional"==$detail_riwayat->scale? "selected":"" ?>>Internasional</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('scale'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('category') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kategori Publikasi", 'category', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="category">
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="Ilmiah" <?php echo "Ilmiah"==$detail_riwayat->category? "selected":"" ?>>Ilmiah</option>
                        <option value="Semi Populer" <?php echo "Semi Populer"==$detail_riwayat->category? "selected":"" ?>>Semi Populer</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('category'); ?></span>
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
            <input type='submit' name='save' id="btnsavekaryatulis" class='btn btn-primary' value="Simpan Data" /> 
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
  
    var form = $("#form-riwayat-karyatulis-ilmiah-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsavekaryatulis").click(function(){
		submitdatakaryatulis();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdatakaryatulis();
		return false; 
	});	
	function submitdatakaryatulis(){
        var checkbox = document.getElementById('checkbox').checked;
        if (!checkbox){
            swal("Peringatan!", "Mohon menceklist checkbox terlebih dahulu", "warning");
            return false;
        }
		var the_data = new FormData(document.getElementById("frm-karyatulis-ilmiah"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatkaryatulisilmiah/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-karyatulis-ilmiah");
					 $("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-karyatulis-ilmiah-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>