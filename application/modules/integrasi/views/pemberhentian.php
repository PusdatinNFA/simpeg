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
                <td width="150px"><label for="example-text-input" class="col-form-label">Tanggal Awal</label></td>
                <td colspan=2>
                    <input id="tgl_awal" name="tgl_awal" width="50%" class="form-control datepicker" value="<?= date('01-m-Y') ?>" />
                </td>
            </tr>
            <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Tanggal Akhir</label></td>
                <td colspan=2>
                    <input id="tgl_akhir" name="tgl_akhir" width="50%" class="form-control datepicker" value="<?= date('d-m-Y') ?>"/>
                </td>
            </tr>
            <!-- <tr>
                <td width="150px"><label for="example-text-input" class="col-form-label">Status Usulan</label></td>
                <td colspan=2>
                    <select name="statusUsulan" id="statusUsulan" class="form-control form-control">
                        <option value="">--- Semua Status Usulan ---</option>
                        <option value="1">1 - Input Berkas (Instansi)</option>
                        <option value="2">2 - Berkas Terverifikasi (Instansi)</option>
                        <option value="4">4 - Perbaikan Setelah BTS Approval (Instansi)</option>
                        <option value="5">5 - Berkas Tidak Sesuai (Instansi)</option>
                        <option value="6">6 - Validasi Approval - TMS (Instansi / End Status)</option>
                        <option value="11">11 - Perbaikan Setelah BTS BKN (BKN)</option>
                        <option value="12">12 - Terima Usulan (BKN)</option>
                        <option value="13">13 - Validasi BKN - TMS (Instansi / End Status)</option>
                        <option value="14">14 - Validasi BKN - BTS (Instansi)</option>
                        <option value="15">15 - Usulan Tervalidasi BKN (BKN)</option>
                        <option value="20">20 - Menunggu TTE Pertek (BKN)</option>
                        <option value="22">22 - Sudah TTE Pertek (Instansi)</option>
                        <option value="30">30 - Menunggu TTE SK (Instansi)</option>
                        <option value="31">31 - Perbaikan Pertek (BKN)</option>
                        <option value="32">32 - Sudah TTE SK (Instansi / Proses Selesai)</option>
                        <option value="39">39 - Perbaikan SK (Instansi)</option>
                        <option value="59">59 - Menunggu Buat SK (Instansi)</option>
                        <option value="99">99 - Usulan Dihapus (Instansi / End Status)</option>
                    </select>
                </td>
            </tr> -->
            <tr>
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
        Lihat Data Pemberhentian
    </div>
    <?php $PNS_ID = '' ?>
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'id="frm-data"'); ?>
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th style="width:25%">Nama<br>NIP</th>
                    <th style="width:15%">Layanan</th>
                    <th style="width:15%">TMT</th>
                    <th style="width:15%">Nomor <br> Tanggal SK</th>
                    <th width="300px" align="center">#</th>
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
                    "targets": [0, 2, 3, 4, 5]
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            ajax: {
                url: "pemberhentian/ajax_list",
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
            window.open("<?= base_url() ?>integrasi/pemberhentian/download?" + xyz);
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
                        var post_data = "nip=" + nip;
                        $.ajax({
                            url: "<?php echo base_url() ?>integrasi/pemberhentian/sinkron",
                            type: "POST",
                            data: post_data,
                            dataType: "json",
                            timeout: 180000,
                            success: function(result) {
                                swal({
                                    title: "Selamat!",
                                    text: result.msg,
                                    type: "success"
                                });
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
</script>