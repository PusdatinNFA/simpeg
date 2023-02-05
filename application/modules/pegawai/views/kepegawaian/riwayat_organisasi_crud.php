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
                <li>Data organisasi yang dimasukkan hanya yang berhubungan dengan pekerjaan sebagai ASN.</li>
                <li>Maksimal ukuran berkas yang diunggah sebesar 2MB.</li>
            </ul>
        </div>
        </div>
    <?php } ?>
<div class='box box-warning' id="form-riwayat-organisasi-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm-organisasi"'); ?>
    <fieldset>
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='nip' type='hidden' class="form-control" name='nip' maxlength='18' value="<?php echo set_value('nip', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
            <div class="control-group<?php echo form_error('nama_organisasi') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Nama Organisasi", 'nama_organisasi', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nama_organisasi' type='text' class="form-control" name='nama_organisasi' maxlength='200' value="<?php echo set_value('nama_organisasi', isset($detail_riwayat->nama_organisasi) ? trim($detail_riwayat->nama_organisasi) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama_organisasi'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('posisi') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Posisi", 'posisi', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='posisi' type='text' class="form-control" name='posisi' maxlength='200' value="<?php echo set_value('posisi', isset($detail_riwayat->posisi) ? trim($detail_riwayat->posisi) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('posisi'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('starting_date') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Tanggal Mulai", 'tanggal_mulai', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input id='tanggal_mulai' type='text' class="form-control datepicker" name='tanggal_mulai' value="<?php echo set_value('tanggal_mulai', isset($detail_riwayat->tanggal_mulai) ? trim($detail_riwayat->tanggal_mulai) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_mulai'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('tanggal_selesai') ? ' error' : ''; ?> col-sm-6 pull-right">
                <?php echo form_label("Tanggal Selesai", 'tanggal_selesai', array('class' => 'control-label')); ?>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input id='tanggal_selesai' type='text' class="form-control datepicker" name='tanggal_selesai' value="<?php echo set_value('tanggal_selesai', isset($detail_riwayat->tanggal_selesai) ? trim($detail_riwayat->tanggal_selesai) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_selesai'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('lokasi') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Lokasi", 'lokasi', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='lokasi' type='text' class="form-control" name='lokasi' maxlength='200' value="<?php echo set_value('lokasi', isset($detail_riwayat->lokasi) ? trim($detail_riwayat->lokasi) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('lokasi'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('country_id') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Negara", 'country_id', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="country_id">
                        <option value="0">-- Silahkan Pilih --</option>
                        <?php foreach ($countries as $country) { ?>
                            <option value="<?=$country->code?>" <?php echo $country->code==$detail_riwayat->country_id? "selected":"" ?>><?=$country->content?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('country_id'); ?></span>
                </div>
            </div>
			<!-- <div class="control-group<?php echo form_error('sponsor') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Sponsor", 'sponsor', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='sponsor' type='text' class="form-control" name='sponsor' maxlength='200' value="<?php echo set_value('sponsor', isset($detail_riwayat->sponsor) ? trim($detail_riwayat->sponsor) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('sponsor'); ?></span>
                </div>
            </div>  -->
            <div class="control-group<?php echo form_error('explanation') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Keterangan", 'keterangan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <textarea class="form-control" name="keterangan"><?php echo set_value('keterangan', isset($detail_riwayat->keterangan) ? trim($detail_riwayat->keterangan) : ''); ?></textarea>
                    <span class='help-inline'><?php echo form_error('keterangan'); ?></span>
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
            <input type='submit' name='save' id="btnsaveorganisasi" class='btn btn-primary' value="Simpan Data" /> 
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
  
    var form = $("#form-riwayat-organisasi-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsaveorganisasi").click(function(){
		submitdataaktivitas();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdataaktivitas();
		return false; 
	});	
	function submitdataaktivitas(){
		var the_data = new FormData(document.getElementById("frm-organisasi"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatorganisasi/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-organisasi");
					 $("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-organisasi-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
</script>