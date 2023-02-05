<!--tab-pane-->
<div class="tab-pane" id="<?php echo $TAB_ID; ?>">
    <div class="form-group">
        <div class="row">
            <?php if ($this->auth->has_permission('Riwayatpidana.Kepegawaian.Create')) : ?>
                <a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatpidana/add/<?php echo $PNS_ID ?>" tooltip="Tambah">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Riwayatpidana.Kepegawaian.UpdateMandiri')) : ?>
                <a type="button" class="show-modal-custom btn btn-default btn-info margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatpidana/addmandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Mandiri">
                    <i class="fa fa-plus"></i> Tambah Mandiri
                </a>
            <?php endif; ?>
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
                <thead>
                    <tr>
                        <th width='20px'>No</th>
                        <th>Nomor/<br> Tanggal Putusan</th>
                        <th>Vonis</th>
                        <th>Periode</th>
                        <th>Kasus</th>
                        <th>Status</th>
                        <th width='100px' align="center">Aksi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>

                    </tr>
                </tfoot>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!--tab-pane-->


<script type="text/javascript">
    (function($) {
        var $container = $("#<?php echo $TAB_ID; ?>");
        $grid_daftar_pidana = $(".table-datatable", $container).DataTable({
            ordering: false,
            processing: true,
            "bFilter": false,
            "bLengthChange": false,
            serverSide: true,
            "columnDefs": [{
                "className": "text-center",
                "targets": [0, 2, 3, 4, 5]
            }],
            ajax: {
                url: "<?php echo base_url() ?>pegawai/riwayatpidana/ajax_list",
                type: 'POST',
                data: {
                    pns_id: '<?php echo $PNS_ID; ?>'
                }
            }
        });
        $container.on('click', '.show-modal-custom', function(event) {
            showModalX.call(this, 'sukses-tambah-riwayat-pidana', function() {
                $grid_daftar_pidana.ajax.reload();
            }, this);
            event.preventDefault();
        });
        //$container.on('click','.show-modal',showModalX);

        $container.on('click', '.btn-hapus-pidana', function(event) {
            event.preventDefault();
            var kode = $(this).attr("kode");
            swal({
                    title: "Anda Yakin?",
                    text: "Hapus data Riwayat Pidana!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: "Tidak, Batalkan!",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {
                    if (isConfirm) {
                        var post_data = "kode=" + kode;
                        $.ajax({
                            url: "<?php echo base_url() ?>pegawai/riwayatpidana/delete/" + kode,
                            dataType: "html",
                            timeout: 180000,
                            success: function(result) {
                                swal("Data berhasil di hapus!", result, "success");
                                $grid_daftar_pidana.ajax.reload();
                            },
                            error: function(error) {
                                alert(error);
                            }
                        });

                    } else {
                        swal("Batal", "", "error");
                    }
                });
        });

    })(jQuery);
</script>