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
                <td width="150px"><label for="example-text-input" class="col-form-label">Jenis Penghargaan</label></td>
                <td colspan=2>
                    <div class='controls'>
                        <select name="ID_JENIS_PENGHARGAAN" id="ID_JENIS_PENGHARGAAN" class="form-control">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($jenis_penghargaans) && is_array($jenis_penghargaans) && count($jenis_penghargaans)) : ?>
                                <?php foreach ($jenis_penghargaans as $record) : ?>
                                    <option value="<?php echo $record->ID ?>"><?php echo $record->ID; ?> - <?php echo $record->NAMA; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        </select>
                        <span class='help-inline'><?php echo form_error('ID_JENIS_PENGHARGAAN'); ?></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Nomor SK</label></td>
                <td colspan=2><input class="form-control" name="SK_NOMOR"/></td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Tanggal SK</label></td>
                <td style="padding-right:10px;" width="200px">
                    <select class="form-control" name="operator_tahun">
                        <option value="=">Sama dengan</option>
                        <option value=">">Lebih dari</option>
                        <option value=">=">Lebih dari atau sama dengan</option>
                        <option value="<">Kurang dari</option>
                        <option value="<=">Kurang dari atau sama dengan</option>
                        <option value="!=">Tidak sama dengan</option>
                    </select>
                </td>
                <td>
                    <select name="filter_tahun" id="filter_tahun" class="form-control select2">
                        <option value=""> -- Semua --</option>
                        <?php for ($i = date('Y'); $i > date('Y') - 20; $i--) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="200px"><label for="example-text-input" class="col-form-label">s.d.</label></td>
                <td style="padding-right:10px;" width="200px">
                    <select class="form-control" name="operator_tahun_selesai">
                        <option value="=">Sama dengan</option>
                        <option value=">">Lebih dari</option>
                        <option value=">=">Lebih dari atau sama dengan</option>
                        <option value="<">Kurang dari</option>
                        <option value="<=">Kurang dari atau sama dengan</option>
                        <option value="!=">Tidak sama dengan</option>
                    </select>
                </td>
                <td>
                    <select name="filter_tahun_selesai" id="filter_tahun_selesai" class="form-control select2">
                        <option value=""> -- Semua --</option>
                        <?php for ($i = date('Y'); $i > date('Y') - 20; $i--) { ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="200px"><label for="example-text-input" class="col-form-label">Pengelola Kawasan SDM</label></td>
                <td colspan=2>
                    <select class="form-control select2" name="pengelolasdm" id="pengelolasdm">
                        <?php
                        if (isset($pengelolasdms) && is_array($pengelolasdms) && count($pengelolasdms)) :
                            echo '<option value="">-- Silahkan Pilih --</option>';
                            foreach ($pengelolasdms as $pengelolasdm) :
                                $selected = '';
                                echo "<option $selected value='$pengelolasdm->id'>" . strtoupper($pengelolasdm->nama) . "</option>";
                            endforeach;
                        endif;
                        ?>
                    </select>
                </td>
            </tr>            
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">PEGAWAI AKTIF</label></td>
                <td>
                    <input type="checkbox" name="active" value="1" checked>
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
        REKAP PENGHARGAAN PEGAWAI
    </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover col-lg-12 col-md-12">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Nama <br> NIP</th>
                    <th>Nomor <br>Tanggal</th>
                    <th>Jenis Penghargaan</th>
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
        $grid_daftar_penghargaan = $(".table-data").DataTable({

            dom: "<'row'<'col-sm-6'><'col-sm-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            "columnDefs": [{
                "className": "text-center",
                "targets": [0, 5]
            }],
            ajax: {
                url: "penghargaan/ajax_list",
                type: 'POST',
                "data": function(d) {
                    d.advanced_search_filters = $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function() {
            $grid_daftar_penghargaan.ajax.reload(null, true);
            return false;
        });
    });
    $("#filter_tahun").select2();
    $("#filter_tahun_selesai").select2();
    $("#pengelolasdm").select2();
    $('#ID_JENIS_PENGHARGAAN').select2();
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
        window.open("<?= base_url() ?>rekap/penghargaan/download?" + xyz);
    });
</script>