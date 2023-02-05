<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css" media="all" rel="stylesheet" type="text/css" />
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
<div class='box box-danger' id="form-riwayat-angka-kredit-add">
    <div class="box-body">
        <div class="messages">
        </div>
        <?php echo form_open($this->uri->uri_string(), 'id="frmriwayatangkakredit"'); ?>
        <fieldset style="margin-top: 20px;">
            <input type='hidden' class="form-control" name='id' maxlength='32' value="<?php echo set_value('id', isset($detail_riwayat->id) ? trim($detail_riwayat->id) : ''); ?>" />
            <input id='pns_id' type='hidden' class="form-control" name='pns_id' maxlength='32' value="<?php echo set_value('pns_id', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
            <input id='nip' type='hidden' class="form-control" name='nip' maxlength='18' value="<?php echo set_value('nip', isset($PNS_NIP) ? trim($PNS_NIP) : ''); ?>" />
            
            <div class="control-group<?php echo form_error('no_sk') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='no_sk' type='text' class="form-control" name='no_sk' maxlength='32' value="<?php echo set_value('no_sk', isset($detail_riwayat->no_sk) ? trim($detail_riwayat->no_sk) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('no_sk'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">SK TANGGAL</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker2" name='tgl_sk' value="<?php echo set_value('tgl_sk', isset($detail_riwayat->tgl_sk) ? $detail_riwayat->tgl_sk : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tgl_sk'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">PERIODE AWAL</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='tanggal_awal' value="<?php echo set_value('tanggal_awal', isset($detail_riwayat->bln_mulai) && isset($detail_riwayat->thn_mulai) ? $detail_riwayat->thn_mulai.'-'.$detail_riwayat->bln_mulai : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_awal'); ?></span>
                </div>
            </div>
            <div class="control-group col-sm-6">
                <label for="inputNAMA" class="control-label">PERIODE AKHIR</label>
                <div class="input-group date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input type='text' class="form-control pull-right datepicker" name='tanggal_akhir' value="<?php echo set_value('tanggal_akhir', isset($detail_riwayat->bln_selesai) && isset($detail_riwayat->thn_selesai) ? $detail_riwayat->thn_selesai.'-'.$detail_riwayat->bln_selesai : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('tanggal_akhir'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('nama_jabatan') ? ' error' : ''; ?> col-sm-8">
                <?php echo form_label("Jabatan", 'nama_jabatan', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='nama_jabatan' type='text' class="form-control" name='nama_jabatan' value="<?php echo set_value('nama_jabatan', isset($detail_riwayat->nama_jabatan) ? trim($detail_riwayat->nama_jabatan) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('nama_jabatan'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('rwt_jabatan_id') ? ' error' : ''; ?> col-sm-8">
                <?php echo form_label("Riwayat Jabatan", 'rwt_jabatan_id', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <select class="form-control select2" id="rwt_jabatan_id" name='rwt_jabatan_id'>
                        <?php if ($detail_riwayat->rwt_jabatan_id){ ?>
                            <option value="<?=$detail_riwayat->rwt_jabatan_id?>" selected><?=$detail_riwayat->nama_jabatan?></option>
                        <?php } ?>
                    </select>
                    <span class='help-inline'><?php echo form_error('rwt_jabatan_id'); ?></span>
                </div>
            </div>
            </fieldset>
            <fieldset style="margin-top: 20px;">
            <legend>ANGKA KREDIT LAMA</legend>
            <div class="control-group<?php echo form_error('ak_utama_lama') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Utama", 'ak_utama_lama', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_utama_lama' type='number' step="0.001" class="form-control" name='ak_utama_lama' value="<?php echo set_value('ak_utama_lama', isset($detail_riwayat->ak_utama_lama) ? trim($detail_riwayat->ak_utama_lama) : $last_ak->total_ak_utama); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_utama_lama'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('jabatan') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Penunjang", 'ak_penunjang_lama', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_penunjang_lama' type='number' step="0.001" class="form-control" name='ak_penunjang_lama' value="<?php echo set_value('ak_penunjang_lama', isset($detail_riwayat->ak_penunjang_lama) ? trim($detail_riwayat->ak_penunjang_lama) : $last_ak->total_ak_penunjang); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_penunjang_lama'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ak_lama_total') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Total", 'ak_lama_total', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_lama_total' type='number' step="0.001" class="form-control" name='ak_lama_total' readonly value="<?php echo set_value('ak_lama_total', isset($detail_riwayat->ak_lama_total) ? trim($detail_riwayat->ak_lama_total) : $last_ak->ak_akhir_total); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_lama_total'); ?></span>
                </div>
            </div>
            </fieldset>
            <fieldset style="margin-top: 20px;">
            <legend>ANGKA KREDIT BARU</legend>
            <div class="control-group<?php echo form_error('ak_utama_baru') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Utama", 'ak_utama_baru', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_utama_baru' type='number' step="0.001" class="form-control" name='ak_utama_baru' value="<?php echo set_value('ak_utama_baru', isset($detail_riwayat->ak_utama_baru) ? trim($detail_riwayat->ak_utama_baru) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_utama_baru'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('jabatan') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Penunjang", 'ak_penunjang_baru', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_penunjang_baru' type='number' step="0.001" class="form-control" name='ak_penunjang_baru' value="<?php echo set_value('ak_penunjang_baru', isset($detail_riwayat->ak_penunjang_baru) ? trim($detail_riwayat->ak_penunjang_baru) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_penunjang_baru'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ak_baru_total') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Total", 'ak_baru_total', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_baru_total' type='number' step="0.001" class="form-control" name='ak_baru_total' readonly value="<?php echo set_value('ak_baru_total', isset($detail_riwayat->ak_baru_total) ? trim($detail_riwayat->ak_baru_total) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_baru_total'); ?></span>
                </div>
            </div>
            </fieldset>
            <fieldset style="margin-top: 20px;">
            <legend>ANGKA KREDIT AKHIR</legend>
            <div class="control-group<?php echo form_error('total_ak_utama') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Utama", 'total_ak_utama', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='total_ak_utama' type='number' step="0.001" class="form-control" readonly name='total_ak_utama' value="<?php echo set_value('total_ak_utama', isset($detail_riwayat->total_ak_utama) ? trim($detail_riwayat->total_ak_utama) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('total_ak_utama'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('jabatan') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Penunjang", 'total_ak_penunjang', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='total_ak_penunjang' type='number' step="0.001" class="form-control" readonly name='total_ak_penunjang' value="<?php echo set_value('total_ak_penunjang', isset($detail_riwayat->total_ak_penunjang) ? trim($detail_riwayat->total_ak_penunjang) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('total_ak_penunjang'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ak_akhir_total') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Total", 'ak_akhir_total', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_akhir_total' type='number' step="0.001" class="form-control" name='ak_akhir_total' readonly value="<?php echo set_value('ak_akhir_total', isset($detail_riwayat->ak_akhir_total) ? trim($detail_riwayat->ak_akhir_total) : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('ak_akhir_total'); ?></span>
                </div>
            </div>
            <div class="control-group<?php echo form_error('ak_pertama') ? ' error' : ''; ?> col-sm-6">
                <?php echo form_label("Angka Kredit Pertama", 'ak_pertama', array('class' => 'control-label')); ?>
                <div class='controls'>
                    <input id='ak_baru_total' type='checkbox' name='ak_pertama' value="1" <?php echo isset($detail_riwayat->ak_pertama) && $detail_riwayat->ak_pertama == 1 ? 'checked' : ''; ?> />
                    <span class='help-inline'><?php echo form_error('ak_pertama'); ?></span>
                </div>
            </div>
            
            <?php if ($detail_riwayat->id_bkn){ ?>
            <div class="control-group col-sm-9">
                <label for="inputNAMA" class="control-label">ID BKN</label>
                <div class='controls'>
                    <input type='text' class="form-control pull-right" name='id_bkn' readonly value="<?php echo set_value('id_bkn', isset($detail_riwayat->id_bkn) ? $detail_riwayat->id_bkn : ''); ?>" />
                    <span class='help-inline'><?php echo form_error('id_bknv'); ?></span>
                </div>
            </div>
            <?php } ?>
            </fieldset>
            

            <fieldset style="margin-top: 20px;">
            <legend>BERKAS PAK</legend>
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
        <?php if (!$read_only){ ?>
        <div class="box-footer">
            <div class="messages">
            </div>
            <a href="javascript:;" id="btnsave_riwayatangkakredit" class="btn green btn-primary button-submit"> Simpan Data
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
        var form = $("#form-riwayat-angka-kredit-add");
        //Date picker
        $('.datepicker').datepicker({
        autoclose: true,format: 'yyyy-mm'
        }).on("input change", function (e) {
            var date = $(this).datepicker('getDate');
        });
        $('.datepicker2').datepicker({
        autoclose: true,format: 'yyyy-mm-dd'
        }).on("input change", function (e) {
            var date = $(this).datepicker('getDate');
        });
    </script>
    <script>
        hitung_ak();
        $("#btnsave_riwayatangkakredit").click(function() {
            $('#btnsave_riwayatangkakredit').text('Sedang Mengirim...');
            $('#btnsave_riwayatangkakredit').prop('disabled',true);
            submitdatafrmriwayatangkakredit();
            return false;
        });
        $("#frmA").submit(function() {
            submitdatafrmriwayatangkakredit();
            return false;
        });

        function submitdatafrmriwayatangkakredit() {
            var the_data = new FormData(document.getElementById("frmriwayatangkakredit"));
            var json_url = "<?php echo base_url() ?>pegawai/riwayat_angka_kredit/save";
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
                        $("#modal-custom-global").trigger("sukses-tambah-riwayat-angka-kredit");
                        $("#modal-custom-global").modal("hide");
                        $("#modal-global").modal("hide");
                        $('#btnsave_riwayatangkakredit').prop('disabled',false);
                        $('#btnsave_riwayatangkakredit').text('Kirimkan Data');
                    } else {
                        $('#btnsave_riwayatangkakredit').prop('disabled',false);
                        $('#btnsave_riwayatangkakredit').text('Kirimkan Data');
                        $("#form-riwayat-angka-kredit-add .messages").empty().append(data.msg);
                    }
                }
            });
            return false;
        }
        $('#ak_utama_baru').change(function(){
            hitung_ak();
        });
        $('#ak_penunjang_baru').change(function(){
            hitung_ak();
        });
        $('#ak_utama_lama').change(function(){
            hitung_ak();
        });
        $('#ak_penunjang_lama').change(function(){
            hitung_ak();
        });
        function hitung_ak(){
            var ak_utama_baru = $('#ak_utama_baru').val()?$('#ak_utama_baru').val():0;
            var ak_penunjang_baru = $('#ak_penunjang_baru').val()?$('#ak_penunjang_baru').val():0;
            var ak_utama_lama = $('#ak_utama_lama').val()?$('#ak_utama_lama').val():0;
            var ak_penunjang_lama = $('#ak_penunjang_lama').val()?$('#ak_penunjang_lama').val():0;
            var ak_lama = parseFloat(ak_utama_lama)+parseFloat(ak_penunjang_lama);
            var ak_baru = parseFloat(ak_utama_baru)+parseFloat(ak_penunjang_baru);
            var ak_utama = parseFloat(ak_utama_lama)+parseFloat(ak_utama_baru);
            var ak_penunjang = parseFloat(ak_penunjang_lama)+parseFloat(ak_penunjang_baru);
            var total_ak = ak_utama + ak_penunjang;
            $('#ak_baru_total').val(ak_baru);
            $('#ak_lama_total').val(ak_lama);
            $('#total_ak_utama').val(ak_utama.toFixed(3));
            $('#total_ak_penunjang').val(ak_penunjang);
            $('#ak_akhir_total').val(total_ak.toFixed(3));
        }

        $("#rwt_jabatan_id").select2({
            placeholder: 'Cari Riwayat Jabatan...',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            ajax: {
                url: '<?php echo site_url("pegawai/riwayatjabatan/get_riwayat_sync");?>',
                dataType: 'json',
                method:'POST',
                data : {
						NIP_BARU:'<?php echo $PNS_NIP;?>'
					},
                cache: true
            }
        });
    </script>