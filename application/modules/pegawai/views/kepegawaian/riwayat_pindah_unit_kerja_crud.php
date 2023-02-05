<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css" />
<?php
$id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
?>
<div class='box box-danger' id="form-diklat-fungsional-add">
    <div class="box-body">
        <div class="messages">
        </div>
        <?php echo form_open($this->uri->uri_string(), 'id="frmpindahunit"'); ?>
        <fieldset>
            <input id='ID' type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="control-group<?php echo form_error('INSTANSI') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('INSTANSI', 'INSTANSI', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type='text' class="form-control" name='NAMA_INSTANSI' value="<?php echo set_value('NAMA_INSTANSI', isset($detail_riwayat->NAMA_INSTANSI) ? $detail_riwayat->NAMA_INSTANSI : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('INSTANSI'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ID_SATUAN_KERJA') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('Satuan Kerja Baru', 'ID_SATUAN_KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="ID_SATUAN_KERJA" id="ID_SATUAN_KERJA" class="form-control select2 " width="100%">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)) : ?>
                            <?php foreach ($recsatker as $record) : ?>
                                <option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_SATUAN_KERJA))  echo (trim($detail_riwayat->ID_SATUAN_KERJA) == trim($record->ID)) ? "selected" : ""; ?>>[<?php echo $record->ID ?>] <?php echo $record->NAMA_UNOR; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <option value="N">Tidak ada</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('ID_SATUAN_KERJA'); ?></span>
                </div>
            </div>            
            <div class="control-group<?php echo form_error('eselon1') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('Satuan Kerja Baru (jika tidak ada)', 'NAMA_SATUAN_KERJA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type='text' class="form-control" name='NAMA_SATUAN_KERJA' value="<?php echo set_value('NAMA_SATUAN_KERJA', isset($detail_riwayat->NAMA_SATUAN_KERJA) ? $detail_riwayat->NAMA_SATUAN_KERJA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NAMA_SATUAN_KERJA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ID_UNOR_BARU') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('Unit Organisasi Baru', 'Unit Organisasi Baru', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="ID_UNOR_BARU" id="ID_UNOR_BARU" class="form-control select2">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($recunor) && is_array($recunor) && count($recunor)) : ?>
                            <?php foreach ($recunor as $record) : ?>
                                <option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_UNOR_BARU))  echo (trim($detail_riwayat->ID_UNOR_BARU) == trim($record->ID)) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('ID_UNOR_BARU'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NAMA_UNOR_BARU') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('Unit Organisasi Baru (jika tidak ada)', 'NAMA_UNOR_BARU', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input type='text' class="form-control" name='NAMA_UNOR_BARU' value="<?php echo set_value('NAMA_UNOR_BARU', isset($detail_riwayat->NAMA_UNOR_BARU) ? $detail_riwayat->NAMA_UNOR_BARU : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ID_SATKER_PENUGASAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('SATUAN KERJA PENUGASAN', 'ID_SATKER_PENUGASAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <?php //echo $detail_riwayat->ID_SATUAN_KERJA; 
                    ?>
                    <select name="ID_SATKER_PENUGASAN" id="ID_SATKER_PENUGASAN" class="form-control select2  col-sm-12" width="100%">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)) : ?>
                            <?php foreach ($recsatker as $record) : ?>
                                <option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_SATUAN_KERJA_PANUGASAN))  echo (trim($detail_riwayat->ID_SATUAN_KERJA_PANUGASAN) == trim($record->ID)) ? "selected" : ""; ?>>[<?php echo $record->ID ?>] <?php echo $record->NAMA_UNOR; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <option value="N">Tidak ada</option>
                    </select>
                    <span class='help-inline'>Silahkan masukan satuan kerja penugasan, jika penugasannya bukan di satker induknya</span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('SK_NOMOR') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SK_NOMOR' type='text' class="form-control" name='SK_NOMOR' maxlength='32' value="<?php echo set_value('SK_NOMOR', isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TMT SK</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='TMT_SK' value="<?php echo set_value('TMT_SK', isset($detail_riwayat->TMT_SK) ? $detail_riwayat->TMT_SK : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TMT_SK'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">TANGGAL SK</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='SK_TANGGAL' value="<?php echo set_value('SK_TANGGAL', isset($detail_riwayat->SK_TANGGAL) ? $detail_riwayat->SK_TANGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_TANGGAL'); ?></span>
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

            <div class="control-group<?php echo form_error('pengelola_sdm_kawasan_id') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("Kawasan SDM", 'pengelola_sdm_kawasan_id', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" name="pengelola_sdm_kawasan_id">
                        <?php
                        if (isset($pengelolasdms) && is_array($pengelolasdms) && count($pengelolasdms)) :
                            echo '<option value="">-- Silahkan Pilih --</option>';
                            foreach ($pengelolasdms as $pengelolasdm) :
                                $selected = $detail_riwayat->pengelola_sdm_kawasan_id == $pengelolasdm->id ? 'selected' : "";
                                echo "<option $selected value='$pengelolasdm->id'>" . strtoupper($pengelolasdm->nama) . "</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>
            </div>
    </div>
    <div class="box-footer">
        <input type='submit' name='save' id="btnsavefrmpindahunit" class='btn btn-primary' value="Simpan Data" />
    </div>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
    var form = $("#frmpindahunit-add");
</script>
<script>
    $(".select2").select2({
        width: '100%'
    });
</script>
<script>
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    });
    $('#ID_SATUAN_KERJA').change(function() {
        var valuesatker = $('#ID_SATUAN_KERJA').val();
        $("#ID_UNOR_BARU").empty().append("<option>loading...</option>"); //show loading...

        var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getbysatker?satker=" + encodeURIComponent(valuesatker);
        $.getJSON(json_url, function(data) {
            $("#ID_UNOR_BARU").empty();
            if (data == "") {
                $("#ID_UNOR_BARU").append("<option value=\"\">Silahkan Pilih </option>");
            } else {
                $("#ID_UNOR_BARU").append("<option value=\"\">Silahkan Pilih</option>");
                for (i = 0; i < data.id.length; i++) {
                    $("#ID_UNOR_BARU").append("<option value=\"" + data.id[i] + "\">" + data.nama[i] + "</option>");
                }
            }

        });
        return false;
    });
    $("#btnsavefrmpindahunit").click(function() {
        $('#btnsavefrmpindahunit').text('Sedang Mengirim...');
        submitdatafrmpindahunit();
        return false;
    });
    $("#frma").submit(function() {
        submitdatafrmpindahunit();
        return false;
    });

    function submitdatafrmpindahunit() {
        var the_data = new FormData(document.getElementById("frmpindahunit"));
        var json_url = "<?php echo base_url() ?>pegawai/riwayatpindahunitkerja/save";
        $.ajax({
            type: "POST",
            url: json_url,
            data: the_data,
            dataType: "json",
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function(data) {
                if (data.success) {
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-pindah_unit_kerja");
                    $("#modal-custom-global").modal("hide");
                } else {
                    $('#btnsavefrmpindahunit').text('Simpan');
                    $('#btnsavefrmpindahunit').prop('disabled',false);
                    $(".messages").empty().append(data.msg);
                }
            }
        });
        return false;
    }
</script>