<div class="callout callout-success">
    <h4>Perhatian</h4>
    <P>dibawah ini merupakan data riwayat dari BKN</p>
    <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_anak" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data"><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi Simpeg</p>
</div>
<div class="box box-info">
    <fieldset>
        <?php
        if ($data_anak == null) {
            echo "data anak tidak ditemukan";
        ?>
            <div class="callout callout-danger">
                <h4>Tidak ada data PNS Unor</h4>
            </div>
        <?php
        } else {
        ?>
            <table class="table table-datatable table-bordered">
                <thead>
                    <tr>
                        <th width='15px'>No</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Lahir</th>
                        <th>Tempat Lahir</th>
                        <th>Status Anak</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($data_anak->listAnak as $row) {
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td>
                            <?php echo $row->nama; ?>
                            <td>
                            <?php echo $row->jenisKelamin; ?></td>
                            </td>
                            <td><?php echo $row->tglLahir; ?></td>
                            <td><?php echo $row->tempatLahir; ?></td>
                            <td><?php echo $row->jenisAnak; ?></td>
                        </tr>
                    <?php
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>
    </fieldset>


</div>
<script type="text/javascript">
    $('.sinkrondata_anak').click(function(e) {
        var kode = $(this).attr("kode");
        swal({
                title: "Anda Yakin?",
                text: "Sinkron anak dari data BKN!",
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
                    var post_data = "kode=" + kode;
                    $.ajax({
                        url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_anak",
                        type: "POST",
                        data: post_data,
                        dataType: "json",
                        timeout: 180000,
                        success: function(result) {
                            if (result.success) {
                                $("#modal-global").modal('hide');
                                swal("Perhatian!", result.msg, "success");
                                grid_data_anak.ajax.reload();
                            } else {
                                swal("Perhatian!", result.msg, "error");
                            }
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
</script>