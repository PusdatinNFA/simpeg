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
                <td width="200px"><label for="example-text-input" class="col-form-label">Pengelola Kawasan SDM</label></td>
                <td colspan=2>
                    <select class="form-control select2" name="pengelolasdm">
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
                <td width="150px"><label for="example-text-input" class="col-form-label">Tahun Mulai</label></td>
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
                <td width="200px"><label for="example-text-input" class="col-form-label">Tahun Selesai</label></td>
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
            <td colspan=4>
                <div class="pull-right">
                    <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Cari</button>
                    <button type="button" id="download" class="btn btn-info" target="_blank"><i class="fa fa-download"></i> Download </button>
                    <a class="btn btn-social btn-google" id="btn_kirim">
                        <i class="fa fa-upload"></i> <span id="texttandatangan">Kirim Ke BKN </span>
                    </a>
                </div>
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
        Integrasi Angka Kredit
    </div>
    <div class="box-body">
        <?php echo form_open($this->uri->uri_string(), 'id="frm-data"'); ?>
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
                <tr>
                    <th style="width:10px" align="center"><input type="checkbox" class="group-checkable">
                        <div id="divjumlahak"></div>
                    </th>
                    <th style="width:10px">No</th>
                    <th>Nama <br> NIP</th>
                    <th>No SK <br> Tanggal</th>
                    <th>Jabatan</th>
                    <th>Periode</th>
                    <th>Jumlah AK</th>
                    <th>Satuan Kerja</th>
                    <th width="70px" align="center">#</th>
                </tr>
            </thead>
        </table>
        </form>
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
                    "targets": [0, 1, 5, 6, 8]
                },
                {
                    "targets": 0,
                    "orderable": false
                }
            ],
            ajax: {
                url: "angkaKredit/ajax_list",
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
    $('.group-checkable').change(function() {
        var set = $('body').find('.table tbody > tr > td:nth-child(1) input[type="checkbox"]');
        var checked = $(this).prop("checked");
        $(set).each(function() {
            $(this).prop("checked", checked);
        });
        cekJumlah();
    });
    $('body').on('click', '.checkbox-ak', function() {
        cekJumlah();
    });

    function cekJumlah() {
        var jumlahselected = $('.checkbox-ak').filter(':checked').length;
        if (jumlahselected > 0) {
            $('#divjumlah').html("(" + jumlahselected + ")");
            $('#btn_kirim').show();
        } else {
            $('#divjumlah').html("");
            $('#btn_kirim').hide();
        }
    }
    $("#btn_kirim").click(function() {
        var jumlahselected = $('.checkbox-ak').filter(':checked').length;
        if (jumlahselected <= 0) {
            swal("Perhatian", "Silahkan checklist data yang akan diintegrasi ke BKN", "error");
            return false;
        }
        swal({
                title: "Anda Yakin?",
                text: "Kirim " + jumlahselected + " data AK ke BKN?",
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
                    var the_data = $("#frm-data").serialize();
                    $.ajax({
                        url: "<?php echo base_url() ?>pegawai/bkn/uploadRwAngkaKreditMultiple",
                        type: "POST",
                        data: the_data,
                        dataType: "json",
                        timeout: 180000,
                        success: function(result) {
                            if (result.success) {
                                swal("Perhatian!", result.msg, "success");
                            } else {
                                swal("Perhatian!", result.msg, "error");
                            }
                            $table.ajax.reload(null,true);
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
    $('#btn_kirim').hide();

    $("#download").click(function() {
        var xyz = $("#form_search_pegawai").serialize();
        window.open("<?= base_url() ?>integrasi/angkaKredit/download?" + xyz);
    });
</script>