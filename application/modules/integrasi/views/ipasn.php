<div class="admin-box box box-danger expanded-box">
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
                    </select>
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Tahun</label></td>
                <td colspan=2><input type="number" id="tahun" name="tahun" width="100%" class="col-md-10 form-control" value="2022" /></td>
            </tr>
            <td colspan=4>
                <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                <button type="button" id="download" class="btn btn-info pull-right " target="_blank"><i class="fa fa-download"></i> Download </button>
            </td>
            </tr>
        </table>
        <?php
        echo form_close();
        ?>
    </div>
</div>

<div id="container-sk-slks" class="admin-box box box-danger">
    <div class="box-header">
        Lihat Data IPASN
    </div>
    <?php $PNS_ID = '' ?>
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'id="frm-data"'); ?>
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Jabatan</th>
                    <th>Kualifikasi</th>
                    <th>Kompetensi</th>
                    <th>Kinerja</th>
                    <th>Disiplin</th>
                    <th>Total</th>
                    <th width="70px" align="center">#</th>
                </tr>
            </thead>
        </table>
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        var $container_list = $("#container-sk-slks");
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            maxDate: 0
        });
        $grid_daftar = $(".table-data").DataTable({

            dom: "<'row'<'col-sm-6'><'col-sm-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 4, 5, 6, 7, 8, 9]
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            ajax: {
                url: "ipasn/ajax_list",
                type: 'POST',
                "data": function(d) {
                    d.advanced_search_filters = $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function() {
            $grid_daftar.ajax.reload(null, true);
            return false;
        });
        $("#download").click(function() {
            var xyz = $("#form_search_pegawai").serialize();
            window.open("<?= base_url() ?>integrasi/ipasn/download?" + xyz);
        });
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
    });
</script>