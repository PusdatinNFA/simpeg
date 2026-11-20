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
            <!-- <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Periode Awal</label></td>
                <td colspan=2><input type="text" id="from" name="from" width="100%" class="col-md-10 datepicker form-control" /></td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Periode Akhir</label></td>
                <td colspan=2><input type="text" id="to" name="to" width="100%" class="col-md-10 datepicker form-control" /></td>
            </tr> -->
            <!-- <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
                <td colspan=2><input type="text" id="nama" name="nama" width="100%" class="col-md-10 form-control" /></td>
            </tr> -->
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">NIP</label></td>
                <td colspan=2><input type="text" id="nip" name="nip" width="100%" class="col-md-10 form-control" /></td>
            </tr>
            <tr>
                <td><label for="example-text-input" class="col-form-label">Tahun</label></td>
                <td colspan="2"><input class="form-control" type="number" name="filter_tahun" value="<?php echo date('Y'); ?>"></td>
            </tr>
    </div>
    <td colspan=4>
        <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
        <!-- <button type="button" id="download" class="btn btn-info pull-right " target="_blank"><i class="fa fa-download"></i> Download </button> -->
        <a class="btn pull-right btn-warning" id="btn_sync">
            <i class="fa fa-refresh"></i> <span id="texttandatangan">Sync Data Pegawai</span>
        </a>

        <!-- <a type="button" id="get_last_kpo" class="btn btn-danger pull-right "><i class="fa fa-refresh"></i> Sinkron KPO Terakhir</a> -->
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
        Sinkronisasi Pengadaan
    </div>
    <?php $PNS_ID = '' ?>
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'id="frm-data"'); ?>
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px" align="center"><input type="checkbox" class="group-checkable">
                        <div id="divjumlah"></div>
                    </th>
                    <th style="width:10px">No</th>
                    <th>Nama<br>NIP</th>
                    <th>No Peserta</th>
                    <th>Periode</th>
                    <th>Nomor <br> Tanggal SK</th>
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
        $('[name=statusUsulan]').select2();
        $grid_daftar = $(".table-data").DataTable({

            dom: "<'row'<'col-sm-6'><'col-sm-6'>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            "columnDefs": [{
                    "className": "text-center",
                    "targets": [0, 1, 3, 4, 5]
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            ajax: {
                url: "pengadaan/ajax_list",
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
        $("#btn_sync").click(function() {
            var jumlahselected = $('.checkbox-sk').filter(':checked').length;
            if (jumlahselected <= 0) {
                swal("Perhatian", "Silahkan checklist data yang akan sinkronisasi dari BKN", "error");
                return false;
            } else {

                $('#id_files')
            }
            swal({
                    title: "Anda Yakin?",
                    text: "Sinkronisasi " + jumlahselected + " data dari BKN?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-success',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: "Tidak, Batalkan!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var the_data = $("#frm-data").serializeArray();
                        var datas = []
                        the_data.forEach(element => {
                            if (element.name == 'id_data[]')
                                datas.push(element.value)
                        });

                        var post_data = "nip_bkn=" + datas.join(",");
                        $.ajax({
                            url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpegawaibknnew",
                            type: "POST",
                            data: post_data,
                            dataType: "json",
                            timeout: 180000,
                            success: function(result) {
                                if (result.success) {
                                    swal("Perhatian!", result.msg, "success");
                                } else {
                                    swal("Perhatian!", result.msg, "error");
                                }
                                $grid_daftar.ajax.reload(null, false);
                                $('.group-checkable').prop("checked", false);
                            },
                            error: function(error) {
                                alert(error);
                            }
                        });

                    } else {
                        swal("Batal", "Transaksi dibatalkan", "error");
                    }
                });
        });
        $container_list.on('click', '.btn-sync', function(e) {
            // $('.btn-sync').click(function() {
            e.preventDefault();
            var nip = $(this).attr('kode')
            swal({
                    title: "Anda Yakin?",
                    text: "Sinkronisasi data Pegawai berdasarkan data BKN!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-success',
                    confirmButtonText: 'Ya!',
                    cancelButtonText: "Tidak, Batalkan!",
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var post_data = "kode=" + nip;
                        $.ajax({
                            url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getpegawaibkn",
                            type: "POST",
                            data: post_data,
                            dataType: "html",
                            timeout: 180000,
                            success: function(result) {
                                swal("Perhatian!", result, "success");
                            },
                            error: function(error) {
                                swal("Perhatian", "Ada masalah koneksi", "error");
                            }
                        });

                    } else {
                        swal("Batal", "", "error");
                    }
                });
        })
    });
    $('.group-checkable').change(function() {
        var set = $('body').find('.table tbody > tr > td:nth-child(1) input[type="checkbox"]');
        var checked = $(this).prop("checked");
        $(set).each(function() {
            $(this).prop("checked", checked);
        });
        cekJumlah();
    });
    $('body').on('click', '.checkbox-sk', function() {
        cekJumlah();
    });

    function cekJumlah() {
        var jumlahselected = $('.checkbox-sk').filter(':checked').length;
        if (jumlahselected > 0) {
            $('#divjumlah').html("(" + jumlahselected + ")");
            $('#btn_sync').show();
        } else {
            $('#divjumlah').html("");
            $('#btn_sync').hide();
        }
    }
    $('#btn_sync').hide();

    $("#download").click(function() {
        var xyz = $("#form_search_pegawai").serialize();
        window.open("<?= base_url() ?>integrasi/kpo/download?" + xyz);
    });
    $(document).on('click', '.show-modal-custom', function(event) {
        var data = $(this).attr("data");
        custom_pns_id = data
        showModalX.call(this, 'sukses-tambah-riwayat-kepangkatan', function() {
            // $grid_daftar.ajax.reload(null,false);
        }, this);
        event.preventDefault();
    });
</script>