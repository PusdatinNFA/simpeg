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
                    <select id="nip_sk" name="nip_sk" width="100%" class="form-control select2 class_pegawai">
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
                <td width="150px"><label for="example-text-input" class="col-form-label">Kawasan</label></td>
                <td colspan=2>
                    <select name="kawasans[]" id="kawasans" multiple="multiple" class="select2 col-md-10 form-control">
                        <?php
                        if (isset($kawasans) && is_array($kawasans) && count($kawasans)) :
                            foreach ($kawasans as $kawasan) :
                                $selected = '';
                                echo "<option $selected value='$kawasan->id'>" . strtoupper($kawasan->name) . "</option>";
                            endforeach;
                        endif;
                        ?>                     
                    </select>
                </td>
            </tr>

            <tr>
                <td><label for="example-text-input" class="col-form-label">Bulan Cutoff</label></td>
                <td style="padding-right:10px;" width="200px">
                    <select class="form-control select2" name="filter_bulan">
                        <?php
                        $bulans = array(
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '09' => 'September',
                            '10' => 'Oktober',
                            '11' => 'Nopember',
                            '12' => 'Desember'
                        );
                        foreach ($bulans as  $key => $value) {
                            echo "<option value='" . $key . "'>$value</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input class="form-control" type="text" name="filter_tahun" value="<?php echo date('Y'); ?>"></td>
            </tr>
            <tr>
                <td><label for="example-text-input" class="col-form-label">Jenis Penghargaan</label></td>
                <td style="padding-right:10px;" width="200px">
                    <select class="form-control select2" name="filter_jenis">
                        <option value="10">Satyalanacana X Tahun</option>
                        <option value="20">Satyalanacana XX Tahun</option>
                        <option value="30">Satyalanacana XXX Tahun</option>
                    </select>
                </td>
            <tr>
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
                <tr>
                <td colspan=4>
                    <button type="submit" class="btn btn-warning pull-right margin"><i class="fa fa-search"></i> Cari</button>
                    <button type="button" id="download" class="btn btn-success margin pull-right " target="_blank"><i class="fa fa-download"></i> Download Nominatif</button>
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
        DAFTAR NOMINATIF PENERIMA PENGHARGAAN SATYALANCANA KARYA SATYA
        <!-- <div class="box-tools pull-right">
            <button type="button" id="btn-cetak-surat-pengantar" class="btn btn-box-tool btn-default " >
                <i class="fa fa-print"></i> Cetak Surat Pengantar SK 
            </button>
        </div>                    -->
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Pegawai</th>
                    <th>Pangkat|Golongan</th>
                    <th>Jabatan</th>
                    <th>Masa Kerja</th>
                    <th>Penghargaan</th>
                    <th>Satuan Kerja</th>
                    <!-- <th>Keterangan</th>                    -->
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
            serverSide: true,
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [5, 7, 0]
                },
                {
                    "targets": [0, 5],
                    "orderable": false
                }
            ],
            ajax: {
                url: "<?php echo base_url('data_nominatif/slks/ajax_list')?>",
                type: 'POST',
                "data": function(d) {
                    // d.advanced_search_filters =  $("#form_search_pegawai").serializeArray();
                    d.search['advanced_search_filters'] = $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function() {
            $table.ajax.reload(null, true);
            return false;
        });
    });
    $('.select2').select2();
    $("#nip_sk").select2({
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
        window.open("<?= base_url() ?>/data_nominatif/slks/download_nominatif?" + xyz);
    });
</script>