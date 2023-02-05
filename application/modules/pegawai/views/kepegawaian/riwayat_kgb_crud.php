<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php 
    $id = isset($detail_riwayat->id) ? $detail_riwayat->id : '';
    if ($mandiri) { ?>
        <div class="callout callout-info">
            <h4>Perhatian</h4>
            <ul>
            <?php if (!$id) echo '<li>Data yang anda masukan tidak otomatis mengubah data simpeg dan akan diverifikasi terlebih dahulu oleh kepegawaian.<br>
                Anda dapat mengecek pengajuan perubahan pada menu <b><a href="<?=base_url()?>admin/kepegawaian/pegawai/peremajaan" target="_BLANK">Peremajaan Data</a></b></li>';
                else echo '<li>Data yang tertera tidak dapat diubah, pegawai hanya  mengunggah dokumen sesuai dengan data yang sudah tertera. Jika ada kelasahan pada data, dapat menghubungi penanggungjawab kawasan masing-masing</li>' ?>
                <li>Maksimal ukuran berkas yang diunggah sebesar 2MB.</li>
            </ul>
        </div>
        </div>
    <?php }
    $readonly = '';
    if ($mandiri && $id)
        $readonly = 'readonly'; ?>
<div class='box box-warning' id="form-riwayat-kgb-add">
	<div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frm-kgb"'); ?>
    <fieldset>
       
            <input id='id' type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="row">   
                <div class="control-group<?php echo form_error('n_gol_ruang') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Golongan Ruang", 'Golongan Ruang', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <select name='n_golongan_id' class='form-control' id="n_golongan_id">
                            <?php 
                                foreach($list_golongan as $row){
                                    $selected = '';
                                    if($row->ID == $detail_riwayat->n_golongan_id){
                                        $selected = ' SELECTED ';
                                    } else {
                                        if ($mandiri && $id)
                                            $selected = ' disabled ';
                                    }
                                    echo "<option $selected value='$row->ID'>$row->NAMA_PANGKAT - $row->NAMA</option>";
                                }
                            ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('n_gol_ruang'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">TMT Golongan</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' <?=$readonly?> class="form-control pull-right datepicker" name='n_gol_tmt'  value="<?php echo set_value('n_gol_tmt', isset($detail_riwayat->n_gol_tmt) ? $detail_riwayat->n_gol_tmt : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_gol_tmt'); ?></span>
                    </div>
                </div> 

                <div class="control-group<?php echo form_error('n_masakerja_thn') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Masa Kerja (Thn)", 'Masa Kerja (Thn)', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_masakerja_thn' type='text' <?=$readonly?> class="form-control" name='n_masakerja_thn' maxlength='200' value="<?php echo set_value('n_masakerja_thn', isset($detail_riwayat->n_masakerja_thn) ? trim($detail_riwayat->n_masakerja_thn) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_masakerja_thn'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('n_masakerja_bln') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Masa Kerja (Bln)", 'Masa Kerja (Bln)', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_masakerja_bln' type='text' <?=$readonly?> class="form-control" name='n_masakerja_bln' maxlength='200' value="<?php echo set_value('n_masakerja_bln', isset($detail_riwayat->n_masakerja_bln) ? trim($detail_riwayat->n_masakerja_bln) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_masakerja_bln'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('n_gapok') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Gaji Pokok Baru", 'Gaji Pokok Baru', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_gapok' type='number' <?=$readonly?> class="form-control" name='n_gapok' maxlength='200' value="<?php echo set_value('n_gapok', isset($detail_riwayat->n_gapok) ? trim($detail_riwayat->n_gapok) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_gapok'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('pp_gaji_baru') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Dasar Peraturan KGB", 'pp_gaji_baru', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='pp_gaji_baru' type='text' <?=$readonly?> class="form-control" name='pp_gaji_baru' maxlength='200' value="<?php echo set_value('pp_gaji_baru', isset($detail_riwayat->pp_gaji_baru) ? trim($detail_riwayat->pp_gaji_baru) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('pp_gaji_baru'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('no_sk') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("NO SK", 'NO SK', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='no_sk' type='text' <?=$readonly?> class="form-control" name='no_sk' maxlength='200' value="<?php echo set_value('no_sk', isset($detail_riwayat->no_sk) ? trim($detail_riwayat->no_sk) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('no_sk'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">TANGGAL SK</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' <?=$readonly?> class="form-control pull-right datepicker" name='tgl_sk'  value="<?php echo set_value('tgl_sk', isset($detail_riwayat->tgl_sk) ? $detail_riwayat->tgl_sk : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('tgl_sk'); ?></span>
                    </div>
                </div> 
                <div class="control-group col-sm-6">
                    <label class="control-label">TMT KGB</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' <?=$readonly?> class="form-control pull-right datepicker" name='tmt_sk'   value="<?php echo set_value('tmt_sk', isset($detail_riwayat->tmt_sk) ? $detail_riwayat->tmt_sk : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('tmt_sk'); ?></span>
                    </div>
                </div>   
                <div class="control-group<?php echo form_error('n_jabatan_text') ? ' error' : ''; ?> col-sm-6">
                    <?php echo form_label("Jabatan", 'Jabatan', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='n_jabatan_text' type='text' <?=$readonly?> class="form-control" name='n_jabatan_text' maxlength='200' value="<?php echo set_value('n_jabatan_text', isset($detail_riwayat->n_jabatan_text) ? trim($detail_riwayat->n_jabatan_text) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_jabatan_text'); ?></span>
                    </div>
                </div>
                <div class="control-group col-sm-4">
                    <label class="control-label">TMT Jabatan</label>
                    <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                        <input type='text' <?=$readonly?> class="form-control pull-right datepicker" name='n_tmt_jabatan'  value="<?php echo set_value('n_tmt_jabatan', isset($detail_riwayat->n_tmt_jabatan) ? $detail_riwayat->n_tmt_jabatan : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('n_tmt_jabatan'); ?></span>
                    </div>
                </div>   
                <div class="control-group<?php echo form_error('pejabnama_pejabatat') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Nama Pejabat", 'nama_pejabat', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='nama_pejabat' type='text' <?=$readonly?> class="form-control" name='nama_pejabat' maxlength='200' value="<?php echo set_value('nama_pejabat', isset($detail_riwayat->nama_pejabat) ? trim($detail_riwayat->nama_pejabat) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('nama_pejabat'); ?></span>
                    </div>
                </div>
                <div class="control-group<?php echo form_error('nama_pejabat_lama') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Pejabat Lama", 'nama_pejabat_lama', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='nama_pejabat_lama' type='text' <?=$readonly?> class="form-control" name='nama_pejabat_lama' maxlength='200' value="<?php echo set_value('nama_pejabat_lama', isset($detail_riwayat->nama_pejabat_lama) ? trim($detail_riwayat->nama_pejabat_lama) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('nama_pejabat_lama'); ?></span>
                    </div>
                </div>
                
                <div class="control-group<?php echo form_error('kantor_pembayaran') ? ' error' : ''; ?> col-sm-12">
                    <?php echo form_label("Kantor Pembayaran", 'Kantor Pembayaran', array('class' => 'control-label')); ?>
                    <div class='controls'>
                        <input id='kantor_pembayaran' type='text' <?=$readonly?> class="form-control" name='kantor_pembayaran' maxlength='200' value="<?php echo set_value('kantor_pembayaran', isset($detail_riwayat->kantor_pembayaran) ? trim($detail_riwayat->kantor_pembayaran) : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('kantor_pembayaran'); ?></span>
                    </div>
                </div>
            <div class="control-group<?php echo form_error('unit_kerja_induk_id') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Unit Kerja", 'Unit Kerja Induk', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select id="unit_kerja_induk_id" name="unit_kerja_induk_id" class="select2 form-control">
                        <?php 
                            if($selectedUnitKerjaInduk){
                                echo "<option value='{$selectedUnitKerjaInduk->ID}'>{$selectedUnitKerjaInduk->NAMA_UNOR}</option>";
                            }
                        ?>
                    </select>        
                    <span class='help-inline'><?php echo form_error('unit_kerja_induk_id'); ?></span>
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
            <?php $show =  $this->auth->has_permission('riwayatkgb.Kepegawaian.ViewDraft') && !$mandiri ?>
            <fieldset <?=$show?"":"hidden"?>>
                <legend>Status KGB</legend>
                <div class="callout callout-warning">
                    <h4>Perhatian</h4>
                    <p>Checklist "Final" jika data yang dimasukan sudah final (akan tampil di profile pegawai). <br>
                    Jika melampirkan dokumen digital, maka statusnya otomatis final </p>
                </div>
                <div class="control-group col-sm-6">
                    <label for="inputNAMA" class="control-label">Final?</label>
                    <div class='controls'>
                        <input id='final' type='checkbox' name='final' value="1" <?php echo $detail_riwayat->final == "1" ? "checked" : ""; ?> />
                        <span class='help-inline'><?php echo form_error('final'); ?></span>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php if (!$read_only){ ?>
  		<div class="box-footer">
            <div class="messages">
            </div>
            <input type='submit' name='save' id="btnsavekgb" class='btn btn-primary' value="Simpan Data" /> 
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
  
    var form = $("#form-riwayat-kgb-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
	$("#btnsavekgb").click(function(){
		submitdatakgb();
		return false; 
	});	
	$("#frmA").submit(function(){
		submitdatakgb();
		return false; 
	});	
    $(document).ready(function(){
        $(".lazy-select2").each(function(i,o){
            $(this).select2(
                {
                    placeholder: $(this).data('placeholder'),
                    width: '100%',
                    minimumInputLength: $(this).data('minimum'),
                    allowClear: true,
                    ajax: {
                        url: $(this).data('url'),
                        dataType: 'json',
                        data: function(params) {
                            return {
                                term: params.term || '',
                                page: params.page || 1
                            }
                        },
                        cache: true
                    }
                }
            );
        });
    });
	function submitdatakgb(){
		var the_data = new FormData(document.getElementById("frm-kgb"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayat_kgb/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-kgb");
					 $("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-kgb-add .messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
$("#unit_kerja_induk_id").select2({
    placeholder: 'Cari Unit Kerja...',
    width: '100%',
    minimumInputLength: 5,
    allowClear: true,
    ajax: {
        url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    }
});
$("#n_masakerja_thn").change(function(){
	get_gapok();
});
$("#n_golongan_id").change(function(){
	get_gapok();
});
function get_gapok(){
    var the_data = $("#frm-kgb").serialize()
    var json_url = "<?php echo base_url() ?>pegawai/riwayat_kgb/gapok";
        $.ajax({    
        type: "POST",
        url: json_url,
        data: the_data,
        dataType: "json",
        success: function(data){ 
            if(data){
                $('#n_gapok').val(data.BASIC);
            }
        }});
    return false; 
}
</script>