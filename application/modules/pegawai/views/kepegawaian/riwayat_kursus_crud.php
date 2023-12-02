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
<div class='box box-warning' id="form-riwayat-kursus-add">
    <div class="box-body">
        <div class="messages">
        </div>
    <?php echo form_open($this->uri->uri_string(), 'id="frmkursus"'); ?>
    <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="control-group<?php echo form_error('tipe_kursus') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('TIPE PELATIHAN', 'Jenis ', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="TIPE_KURSUS" id="TIPE_KURSUS" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <!-- <option value="Seminar" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Seminar") ? "selected" : ""; ?> >Seminar/Workshop</option>
                        <option value="Kursus" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Kursus") ? "selected" : ""; ?> >Kursus <i>(Course)</i></option>
                        <option value="Bimtek" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Bimtek") ? "selected" : ""; ?> >Bimtek/Sosialisasi</option>
                        <option value="Coaching" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Coaching") ? "selected" : ""; ?> ><i>Coaching/Mentoring</i></option>
                        <option value="Magang" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Magang") ? "selected" : ""; ?> >Magang/Praktik Kerja <i>(On the Job Training)</i></option>
                        <option value="Postdoc" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Postdoc") ? "selected" : ""; ?> >Pascadoctoral</option>
                        <option value="Periset_Tamu" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Periset_tamu") ? "selected" : ""; ?> >Periset tamu</option>
                        <option value="Riset_Bersama" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Riset_Bersama") ? "selected" : ""; ?> >Riset Bersama</option>
                        <option value="Sertifikat" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Sertifikat") ? "selected" : ""; ?> >Sertifikat</option>
                        <option value="Pelatihan_Lainnya" <?php if(isset($detail_riwayat->TIPE_KURSUS))  echo  (TRIM($detail_riwayat->TIPE_KURSUS)=="Pelatihan_Lainnya") ? "selected" : ""; ?> >Pelatihan Lainnya</option> -->
                        <?php foreach ($jenis_diklat as $diklat) { ?>
                        <option value="<?=$diklat->id?>" <?=$detail_riwayat->TIPE_KURSUS==$diklat->id?'selected':''?>><?=$diklat->jenis_diklat?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('TIPE_KURSUS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JENIS_KURSUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("JENIS PELATIHAN", 'JENIS_KURSUS', array('class' => 'control-label')); ?>
                <div class='controls'>
                <select name="JENIS_KURSUS" id="JENIS_KURSUS" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php foreach ($jenis as $record) { ?>                            
                            <option value="<?=$record->id?>" <?php if(isset($detail_riwayat->JENIS_KURSUS))  echo  (TRIM($detail_riwayat->JENIS_KURSUS)== $record->id ) ? "selected" : ""; ?> ><?=$record->nama?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_KURSUS'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_KURSUS') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NAMA PELATIHAN", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NAMA_KURSUS' type='text' class="form-control" name='NAMA_KURSUS' maxlength='200' value="<?php echo set_value('NAMA_KURSUS', isset($detail_riwayat->NAMA_KURSUS) ? trim($detail_riwayat->NAMA_KURSUS) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_KURSUS'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL MULAI</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TANGGAL_KURSUS'  value="<?php echo set_value('TANGGAL_KURSUS', isset($detail_riwayat->TANGGAL_KURSUS) ? $detail_riwayat->TANGGAL_KURSUS : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_KURSUS'); ?></span>
                </div>
            </div> 
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL SELESAI</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                    <input type='text' class="form-control pull-right datepicker" name='TANGGAL_SELESAI'  value="<?php echo set_value('TANGGAL_SELESAI', isset($detail_riwayat->TANGGAL_SELESAI) ? $detail_riwayat->TANGGAL_SELESAI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_SELESAI'); ?></span>
                </div>
            </div> 
            <div class="control-group<?php echo form_error('LAMA_KURSUS') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("LAMA (JAM)", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='LAMA_KURSUS' type='number' class="form-control" name='LAMA_KURSUS' maxlength='5' value="<?php echo set_value('LAMA_KURSUS', isset($detail_riwayat->LAMA_KURSUS) ? trim($detail_riwayat->LAMA_KURSUS) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('LAMA_KURSUS'); ?></span>
                </div>
            </div>
             
            <div class="control-group<?php echo form_error('NO_SERTIFIKAT') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("NO SERTIFIKAT", 'MASA BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NO_SERTIFIKAT' type='text' class="form-control" name='NO_SERTIFIKAT' maxlength='200' value="<?php echo set_value('NO_SERTIFIKAT', isset($detail_riwayat->NO_SERTIFIKAT) ? trim($detail_riwayat->NO_SERTIFIKAT) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NO_SERTIFIKAT'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('INSTITUSI_PENYELENGGARA') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("PENYELENGGARA", 'INSTITUSI_PENYELENGGARA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='INSTITUSI_PENYELENGGARA' type='text' class="form-control" name='INSTITUSI_PENYELENGGARA' maxlength='200' value="<?php echo set_value('INSTITUSI_PENYELENGGARA', isset($detail_riwayat->INSTITUSI_PENYELENGGARA) ? trim($detail_riwayat->INSTITUSI_PENYELENGGARA) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('INSTITUSI_PENYELENGGARA'); ?></span>
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
            <input type='submit' name='save' id="btnsavekursus" class='btn btn-primary' value="Simpan Data" /> 
        </div>
        <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>

<script>
     $(".select2").select2({width: '100%'});
     $("#TIPE_KURSUS").select2({width: '100%'});
</script>
<script>
     
    var form = $("#form-riwayat-kursus-add");
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    }).on("input change", function (e) {
        var date = $(this).datepicker('getDate');
        if(date)$("[name=TAHUN]",form).val(date.getFullYear());
    });
</script>
<script>
    $("#btnsavekursus").click(function(){
        submitdatakursus();
        return false; 
    }); 
    $("#frmA").submit(function(){
        submitdatakursus();
        return false; 
    }); 
    function submitdatakursus(){
        var the_data = new FormData(document.getElementById("frmkursus"));
        var json_url = "<?php echo base_url() ?>pegawai/riwayatkursus/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-kursus");
                    $("#modal-custom-global").modal("hide");
                }
                else {
                    $("#form-riwayat-kursus-add .messages").empty().append(data.msg);
                }
            }});
        return false; 
    }
</script>