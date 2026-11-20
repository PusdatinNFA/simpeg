<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css" />
<?php
$id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
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
<?php }
$readonly = ($mandiri && $id) ? 'readonly' : ''; ?>
<div class='box box-warning' id="form-kepangkatan-add">
    <div class="box-body">
        <div class="messages">
        </div>
        <?php echo form_open($this->uri->uri_string(), 'id="frmkepangkatan"'); ?>
        <fieldset>
            <input type='hidden' class="form-control" name='ID' maxlength='32' value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
            <input id='NIP_BARU' type='hidden' class="form-control" name='NIP_BARU' maxlength='18' value="<?php echo set_value('NIP_BARU', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
            <input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32' value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label('Jenis KP', 'Jenis KP', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="KODE_JENIS_KP" id="KODE_JENIS_KP" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($jenis_kps) && is_array($jenis_kps) && count($jenis_kps)) :
                            $test = ''; ?>
                            <?php foreach ($jenis_kps as $record) : ?>
                                <option value="<?php echo $record->ID ?>" <?= $record->ID == $detail_riwayat->KODE_JENIS_KP ? 'selected' : ($mandiri ? 'disabled' : '') ?>><?php echo $record->ID . " | " . $record->NAMA . ""; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('KODE_JENIS_KP'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label('Jenis Riwayat', 'Jenis Riwayat', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="JENIS_RIWAYAT" id="JENIS_RIWAYAT" class="form-control" required>
                        <option value="">-- Silahkan Pilih --</option>
                        <option value="1" <?= $detail_riwayat->JENIS_RIWAYAT == '1' ? 'selected' : "" ?>>Pengangkatan</option>
                        <option value="2" <?= $detail_riwayat->JENIS_RIWAYAT == '2' ? 'selected' : "" ?>>Perpanjangan</option>
                    </select>
                    <span class='help-inline'><?php echo form_error('JENIS_RIWAYAT'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label('GOLONGAN', 'GOLONGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select name="ID_GOLONGAN" id="ID_GOLONGAN" class="form-control">
                        <option value="">-- Silahkan Pilih --</option>
                        <?php if (isset($golongans) && is_array($golongans) && count($golongans)) : ?>
                            <?php foreach ($golongans as $record) : ?>
                                <option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->GOLONGAN))  echo ($detail_riwayat->ID_GOLONGAN == $record->ID) ? "selected" : ($mandiri ? "disabled" : ""); ?>><?php echo $record->NAMA . ""; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('ID_GOLONGAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TMT AWAL</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" <?= $readonly ?> name='TMT_GOLONGAN' id="TMT_GOLONGAN" value="<?php echo set_value('TMT_GOLONGAN', isset($detail_riwayat->TMT_GOLONGAN) ? $detail_riwayat->TMT_GOLONGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TMT_GOLONGAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TMT AKHIR</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" <?= $readonly ?> name='TMT_GOLONGAN_AKHIR' id="TMT_GOLONGAN_AKHIR" value="<?php echo set_value('TMT_GOLONGAN_AKHIR', isset($detail_riwayat->TMT_GOLONGAN_AKHIR) ? $detail_riwayat->TMT_GOLONGAN_AKHIR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TMT_GOLONGAN_AKHIR'); ?></span>
                </div>
            </div>

            <div class="control-group<?php echo form_error('SK_NOMOR') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='SK_NOMOR' type='text' class="form-control" name='SK_NOMOR' maxlength='32' <?= $readonly ?> value="<?php echo set_value('SK_NOMOR', isset($detail_riwayat->SK_NOMOR) ? $detail_riwayat->SK_NOMOR : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_NOMOR'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">SK TANGGAL</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" <?= $readonly ?> name='SK_TANGGAL' value="<?php echo set_value('SK_TANGGAL', isset($detail_riwayat->SK_TANGGAL) ? $detail_riwayat->SK_TANGGAL : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('SK_TANGGAL'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('NOMOR BKN') ? ' error' : ''; ?> col-sm-9">
                <?php echo form_label("NOMOR BKN", 'NOMOR BKN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='NOMOR_BKN' type='text' class="form-control" <?= $readonly ?> name='NOMOR_BKN' maxlength='32' value="<?php echo set_value('NOMOR_BKN', isset($detail_riwayat->NOMOR_BKN) ? $detail_riwayat->NOMOR_BKN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('NOMOR_BKN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-3">
                <label for="inputNAMA" class="control-label">TANGGAL BKN</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" <?= $readonly ?> name='TANGGAL_BKN' value="<?php echo set_value('TANGGAL_BKN', isset($detail_riwayat->TANGGAL_BKN) ? $detail_riwayat->TANGGAL_BKN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('TANGGAL_BKN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JUMLAH_ANGKA_KREDIT_UTAMA') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("JUMLAH ANGKA KREDIT", 'JUMLAH ANGKA KREDIT UTAMA', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_ANGKA_KREDIT_UTAMA' readonly type='text' class="form-control" name='JUMLAH_ANGKA_KREDIT_UTAMA' maxlength='32' value="<?php echo set_value('JUMLAH_ANGKA_KREDIT_UTAMA', isset($detail_riwayat->JUMLAH_ANGKA_KREDIT_UTAMA) ? $detail_riwayat->JUMLAH_ANGKA_KREDIT_UTAMA : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_ANGKA_KREDIT_UTAMA'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('JUMLAH ANGKA KREDIT TAMBAHAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("JUMLAH ANGKA KREDIT TAMBAHAN", 'JUMLAH ANGKA KREDIT TAMBAHAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='JUMLAH_ANGKA_KREDIT_TAMBAHAN' readonly type='text' class="form-control" name='JUMLAH_ANGKA_KREDIT_TAMBAHAN' maxlength='32' value="<?php echo set_value('JUMLAH_ANGKA_KREDIT_TAMBAHAN', isset($detail_riwayat->JUMLAH_ANGKA_KREDIT_TAMBAHAN) ? $detail_riwayat->JUMLAH_ANGKA_KREDIT_TAMBAHAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('JUMLAH_ANGKA_KREDIT_TAMBAHAN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('MK_GOLONGAN_TAHUN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("MASA KERJA GOLONGAN TAHUN", 'MASA KERJA GOLONGAN TAHUN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MK_GOLONGAN_TAHUN' type='number' <?= $readonly ?> class="form-control" name='MK_GOLONGAN_TAHUN' maxlength='32' value="<?php echo set_value('MK_GOLONGAN_TAHUN', isset($detail_riwayat->MK_GOLONGAN_TAHUN) ? $detail_riwayat->MK_GOLONGAN_TAHUN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MK_GOLONGAN_TAHUN'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('MK_GOLONGAN_BULAN') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("MASA KERJA GOLONGAN BULAN", 'MASA KERJA GOLONGAN BULAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='MK_GOLONGAN_BULAN' type='number' <?= $readonly ?> class="form-control" name='MK_GOLONGAN_BULAN' maxlength='32' value="<?php echo set_value('MK_GOLONGAN_BULAN', isset($detail_riwayat->MK_GOLONGAN_BULAN) ? $detail_riwayat->MK_GOLONGAN_BULAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('MK_GOLONGAN_BULAN'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <label for="inputNAMA" class="control-label">GAJI POKOK</label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-money"></i>
                    </div>
                    <input type='number' class="form-control pull-right" <?= $readonly ?> name='BASIC' id="BASIC" value="<?php echo set_value('BASIC', isset($detail_riwayat->BASIC) ? $detail_riwayat->BASIC : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('BASIC'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('KETERANGAN') ? ' error' : ''; ?> col-sm-12">
                <?php echo form_label("KETERANGAN", 'KETERANGAN', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='KETERANGAN' type='text' class="form-control" <?= $readonly ?> name='KETERANGAN' maxlength='32' value="<?php echo set_value('KETERANGAN', isset($detail_riwayat->KETERANGAN) ? $detail_riwayat->KETERANGAN : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('KETERANGAN'); ?></span>
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
            <input type="text" class="hide" name='mandiri' value="<?= $mandiri ? $mandiri : '' ?>">
            <input type="text" class="hide" name='id_update_mandiri' value="<?= $id_update_mandiri ? $id_update_mandiri : '' ?>">


            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">PANGKAT TERAKHIR (Active)?</label>
                <div class='controls'>
                    <input id='PANGKAT_TERAKHIR' type='checkbox' name='PANGKAT_TERAKHIR' value="1" <?php echo $detail_riwayat->PANGKAT_TERAKHIR == "1" ? "checked" : ""; ?> />
                    <span class='help-inline'><?php echo form_error('PANGKAT_TERAKHIR'); ?></span>
                </div>
            </div>
        </fieldset>
    </div>
    <?php if (!$read_only) { ?>
        <div class="box-footer">
            <div class="messages">
            </div>
            <a href="javascript:;" id="btnsavekepangkatan" class="btn green btn-primary button-submit"> Simpan Data
                <i class="fa fa-save"></i>
            </a>

        </div>
    <?php } ?>
    <?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js" type="text/javascript"></script>
<script>
    var form = $("#form-kepangkatan-add");
    //Date picker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'yyyy-mm-dd'
    }).on("input change", function(e) {
        var date = $(this).datepicker('getDate');
        if (date) $("[name=TAHUN]", form).val(date.getFullYear());
    });
    $('.select2').select2();
</script>
<script>
    $("#btnsavekepangkatan").click(function() {
        get_gapok();
        $('#btnsavekepangkatan').prop('disabled',true);
        $('#btnsavekepangkatan').text('Sedang Mengirim...');
        submitdatakepangkatan();
        return false;
    });
    $("#frmkepangkatana").submit(function() {
        submitdata();
        return false;
    });

    function submitdatakepangkatan() {
        var the_data = new FormData(document.getElementById("frmkepangkatan"));
        var json_url = "<?php echo base_url() ?>pegawai/riwayatkepangkatanpppk/save";
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
                    $("#modal-custom-global").trigger("sukses-tambah-riwayat-kepangkatan");
                    $("#modal-custom-global").modal("hide");
                    $('#btnsavekepangkatan').prop('disabled',false);
                    $('#btnsavekepangkatan').text('Simpan Data');
                    $table.ajax.reload(null, true);
                } else {
                    $('#btnsavekepangkatan').prop('disabled',false);
                    $('#btnsavekepangkatan').text('Simpan Data');
                    $("#form-kepangkatan-add .messages").empty().append(data.msg);
                }
            }
        });
        return false;
    }
    $("#MK_GOLONGAN_TAHUN").change(function() {
        get_gapok();
    });
    $("#ID_GOLONGAN").change(function() {
        get_gapok();
    });
    $("#TMT_GOLONGAN").change(function() {
        get_gapok();
    });

    $("#MK_GOLONGAN_TAHUN").focus(function() {
        get_gapok();
    });
    $("#ID_GOLONGAN").focus(function() {
        get_gapok();
    });
    $("#TMT_GOLONGAN").focus(function() {
        get_gapok();
    });
    get_gapok();
    function get_gapok() {
        var tmt = $('#TMT_GOLONGAN').val();
        if (tmt != '') {
            tmt = new Date(tmt.replace(/(\d{4})-(\d{2})-(\d{2})/, "$1/$2/$3"))
            var cutoff = new Date('2024', '00', '01');
            if (tmt >= cutoff) {
                var mk = $("#MK_GOLONGAN_TAHUN").val();
                var gol = $("#ID_GOLONGAN").val();
                var json_url = "<?php echo base_url() ?>pegawai/riwayat_kgb/gapok_pppk";
                $.ajax({
                    type: "POST",
                    url: json_url,
                    data: {
                        'n_golongan_id': gol,
                        'n_masakerja_thn': mk
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data) {
                            $('#BASIC').val(data.BASIC);
                        }
                    }
                });
                return false;
            }
        }

    }
</script>