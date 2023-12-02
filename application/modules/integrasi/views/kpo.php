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
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
                <td colspan=2><input type="text" id="nama" name="nama" width="100%" class="col-md-10 form-control" /></td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">NIP</label></td>
                <td colspan=2><input type="text" id="nip" name="nip" width="100%" class="col-md-10 form-control" /></td>
            </tr>
            <tr>
                <td><label for="example-text-input" class="col-form-label">Periode</label></td>
                <td style="padding-right:10px;" width="200px">
                    <select class="form-control select2" name="filter_bulan">
                        <?php
                        $bulans = array(
                            '02'=>'Februari',
                            '04'=>'April',
                            '06'=>'Juni',
                            '10'=>'Oktober',
                            '12'=>'Desember'
                        );
                        foreach ($bulans as  $key => $value) {
                            $bulan = date('m');
                            if ($bulan == $key)
                                echo "<option value='" . $key . "' selected>$value</option>";
                            else
                                echo "<option value='" . $key . "'>$value</option>";
                        }
                        ?>
                    </select>
                </td>
                <td><input class="form-control" type="text" name="filter_tahun" value="<?php echo date('Y'); ?>"></td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">STATUS USULAN</label></td>
                <td colspan=2>
                    <select class="select2" name="statusUsulan">
                        <option value="">Pilih Status Usulan</option>
                        <option value="1">Input Berkas</option>
                        <option value="2">Berkas Disimpan (Terverifikasi)</option>
                        <option value="3">Surat Usulan</option>
                        <option value="4">Approval Surat Usulan</option>
                        <option value="5">Perbaikan Dokumen</option>
                        <option value="6">Tidak Memenuhi Syarat</option>
                        <option value="7">Menunggu Cetak SK – Menyetujui</option>
                        <option value="8">Menunggu Cetak SK – Perbaikan Pertek</option>
                        <option value="9">Menunggu Cetak SK – Pembatalan Pertek</option>
                        <option value="10">Cetak SK</option>
                        <option value="11">Profil PNS telah diperbaharui</option>
                        <option value="12">Terima Usulan</option>
                        <option value="13">Validasi Usulan – Tidak Memenuhi Syarat</option>
                        <option value="14">Validasi Usulan – Perbaikan Dokumen</option>
                        <option value="15">Validasi Usulan – Disetujui</option>
                        <option value="16">Berkas Disetujui</option>
                        <option value="17">Menunggu Paraf – Paraf Pertek</option>
                        <option value="18">Menunggu Paraf – Gagal Paraf Pertek</option>
                        <option value="19">Sdh di paraf - Pertek</option>
                        <option value="20">Menunggu Tanda tangan- TTD Pertek</option>
                        <option value="21">Berkas Ditolak - TTD Pertek</option>
                        <option value="22">Sdh di TTD - Pertek</option>
                        <option value="23">Surat Keluar</option>
                        <option value="24">Perbaikan Pertek (Menunggu Approval Instansi)</option>
                        <option value="25">Terima Usulan Penetapan – Pembatalan</option>
                        <option value="26">Pembatalan Pertek (Menunggu Approval Instansi)</option>
                        <option value="27">Menunggu TTE SK </option>
                        <option value="28">Setuju Paraf SK</option>
                        <option value="29">Tolak TTD SK</option>
                        <option value="30">Setuju TTD SK</option>
                        <option value="31">Telah Update di Profile PNS</option>
                        <option value="32">Pembuatan SK Berhasil</option>
                        <option value="33">Menunggu Layanan</option>
                        <option value="34">Perbaikan Dokumen - Menunggu Approval</option>
                        <option value="35">Tolak Paraf SK</option>
                        <option value="36">Menunggu TTD - SK</option>
                        <option value="37">Approval Perbaikan Pertek</option>
                        <option value="38">Approval Pembatalan Pertek</option>
                        <option value="39">Perbaikan SK</option>
                        <option value="40">Berkas Disimpan (Terverifikasi) - Perbaikan SK</option>
                        <option value="41">Validasi Usulan - Perbaikan SK</option>
                        <option value="42">Validasi Usulan - Perbaikan SK (Disetujui)</option>
                        <option value="43">Menunggu Paraf - Perbaikan SK</option>
                        <option value="44">Menunggu TTD - Perbaikan SK</option>
                        <option value="45">Sudah TTD - Perbaikan SK</option>
                        <option value="46">Menunggu TTD SK - Instansi</option>
                        <option value="47">Tolak TTD SK - Instansi</option>
                        <option value="48">Setuju TTD SK - Instansi</option>
                        <option value="49">Sudah TTD - SK</option>
                        <option value="50">Perbaikan Dokumen - MYSAPK</option>
                        <option value="52">Perbaikan Dokumen - Approval</option>
                        <option value="53">Setuju TTD Pertek</option>
                        <option value="55">Approval Tingkat Provinsi</option>
                        <option value="56">Perbaikan Approval</option>
                        <option value="57">Perbaikan Pertek</option>
                        <option value="59">Menunggu Buat Sk</option>
                        <option value="60">Proses Persidangan</option>
                    </select>
                </td>
            </tr>
    </div>
    <td colspan=4>
        <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
        <!-- <button type="button" id="download" class="btn btn-info pull-right " target="_blank"><i class="fa fa-download"></i> Download </button>
                <a class="btn pull-right btn-warning" id="btn_sync">
                    <i class="fa fa-refresh"></i> <span id="texttandatangan">Sync Data Riwayat</span>
                </a>

                <a type="button" id="get_last_kpo" class="btn btn-danger pull-right "><i class="fa fa-refresh"></i> Sinkron KPO Terakhir</a> -->
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
        Sinkronisasi Hasil KPO
    </div>
    <?php $PNS_ID = '' ?>
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'id="frm-data"'); ?>
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>TMT</th>
                    <th>Nama</th>
                    <th>NIP</th>
                    <th>Golongan</th>
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
                    "targets": [0, 1, 4, 5]
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            ajax: {
                url: "kpo/ajax_list",
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
        $("#get_last_kpo").click(function() {
            $.ajax({
                url: "<?php echo base_url() ?>integrasi/kpo/get_last_kpo",
                type: "GET",
                dataType: "json",
                timeout: 180000,
                success: function(result) {
                    if (result.success) {
                        swal({
                            title: "Selamat!",
                            text: result.msg,
                            type: "success",
                            timer: 4000,
                            showConfirmButton: true
                        }, function() {
                            $("#modal-body").html(result.konten);
                            $("#myModalLabel").html("Riwayat KPO BKN");
                            $("#modal-global").modal('show');
                        });
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
                        var params = $("#form_search_pegawai").serializeArray();
                        params.push({
                            name: 'ids',
                            value: datas
                        })
                        $.ajax({
                            url: "<?php echo base_url() ?>integrasi/kpo/sync_hist_kpo",
                            type: "POST",
                            data: params,
                            dataType: "json",
                            timeout: 180000,
                            success: function(result) {
                                if (result.success) {
                                    swal("Perhatian!", result.msg, "success");
                                } else {
                                    swal("Perhatian!", result.msg, "error");
                                }
                                $table.ajax.reload(null, false);
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
            var PNS_NIP = $(this).attr('kode')
            var GOL_ID = $(this).attr('next')
            swal({
                    title: "Anda Yakin?",
                    text: "Lihat data riwayat kepangkatan berdasarkan data BKN!",
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
                        var post_data = "kode=" + PNS_NIP + "&golongan_id=" + GOL_ID;
                        $.ajax({
                            url: "<?php echo base_url() ?>pegawai/bkn/sinkron_panggol/" + PNS_NIP + "/" + GOL_ID,
                            type: "GET",
                            // data: post_data,
                            dataType: "json",
                            timeout: 180000,
                            success: function(result) {
                                swal("Perhatian", "Berhasil", "success");
                                $grid_daftar.ajax.reload(null, false);
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