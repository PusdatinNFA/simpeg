<div id="container-sk-slks" class="admin-box box box-danger expanded-box">
    <div class="box-header">
        <h3 class="box-title">Pencarian Lanjut</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
                <i class="fa fa-plus"></i> Tampilkan
            </button>

        </div>
    </div>

    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), "id=form_search_pegawai", "form"); ?>
        <style>
            table.filter_pegawai tr td {
                padding-top: 2px;
            }
        </style>
        <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Pegawai</label></td>
                <td colspan=2>
                    <select id="nip" name="nip" width="100%" class="form-control select2 class_pegawai">
                        <?php
                        if ($pegawai) {
                            echo "<option selected value='" . $pegawai->PNS_ID . "'>" . $pegawai->NAMA . "</option>";
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
                <td colspan=2><select id="filter_unit_kerja" name="filter_unit_kerja" width="100%" class=" col-md-10 format-control"></select></td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Kategori</label></td>
                <td colspan=2>
                    <select id="filter_kategoei" name="filter_kategori" width="100%" class=" col-md-10 format-control select2">
                        <option value=""> -- Semua --</option>
                        <option value="R">Ringan</option>
                        <option value="S">Sedang</option>
                        <option value="B">Berat</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">JENIS HUKUMAN</label></td>
                <td>
                    <div class='controls'>
                        <select name="ID_JENIS_HUKUMAN" id="ID_JENIS_HUKUMAN" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($jenis_hukumans) && is_array($jenis_hukumans) && count($jenis_hukumans)) : ?>
                                <?php foreach ($jenis_hukumans as $record) : ?>
                                    <option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_JENIS_HUKUMAN))  echo ($detail_riwayat->ID_JENIS_HUKUMAN == $record->ID) ? "selected" : ""; ?> <?php if (isset($detail_riwayat->ID_JENIS_HUKUMAN))  echo ($detail_riwayat->ID_JENIS_HUKUMAN == $record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <span class='help-inline'><?php echo form_error('ID_JENIS_HUKUMAN'); ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Tahun</label></td>
                <td>
                    <select name="filter_tahun" id="filter_tahun" class="form-control select2">
                        <option value=""> -- Semua --</option>
                        <?php for ($i=date('Y'); $i > date('Y')-20; $i--) { ?>
                            <option value="<?=$i?>"><?=$i?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">Pengelola Kawasan SDM</label></td>
					<td colspan=2>
						<select class="form-control select2" name="pengelolasdm">
						<?php
							if (isset($pengelolasdms) && is_array($pengelolasdms) && count($pengelolasdms)) :
								echo '<option value="">-- Silahkan Pilih --</option>';
								foreach ($pengelolasdms as $pengelolasdm) :
									$selected = '';
									echo "<option $selected value='$pengelolasdm->id'>".strtoupper($pengelolasdm->nama)."</option>";
								endforeach;
							endif;
							?>
						</select>
					</td>
				</tr>
            <td colspan=4>
                <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                <button type="button" id="download" class="btn btn-info pull-right " target="_blank"><i class="fa fa-download"></i> Download Nominatif</button>
            </td>
            </tr>
        </table>
        <?php
        echo form_close();
        ?>
    </div>
</div>

<div class="admin-box box box-danger">
    <div class="box-header">
        REKAP HUKUMAN DISIPLIN
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Foto</th>
                    <th>Nama <br> NIP</th>
                    <th>Nomor SK</th>
                    <th>Kategori <br> Jenis Hukuman</th>
                    <th>Tanggal SK <br> Tanggal Mulai</th>
                    <th>Satuan Kerja</th>
                    <th width="70px" align="center">#</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {

        $("#filter_unit_kerja").select2({
            placeholder: 'Filter Unit Kerja...',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            ajax: {
                url: '<?php echo base_url('pegawai/kepegawaian/ajax_unit_list'); ?>',
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
        $table = $(".table-data").DataTable({

            dom: "<'row'<'col-sm-6'><'col-sm-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            processing: true,
            "columnDefs": [{
                "className": "text-center",
                "targets": [0, 7]
            }],
            ajax: {
                url: "hukdis/ajax_list",
                type: 'POST',
                "data": function(d) {
                    d.advanced_search_filters = $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function() {
            $table.ajax.reload(null, true);
            return false;
        });
    });
    $(".select2").select2();
    $(".class_pegawai").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip"); ?>',
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
    $("#download").click(function() {
        var xyz = $("#form_search_pegawai").serialize();
        window.open("<?= base_url() ?>data_nominatif/hukdis/download?" + xyz);
    });
</script>