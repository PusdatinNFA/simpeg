<div class="callout callout-success">
    <h4>Perhatian</h4>
    <P>dibawah ini merupakan data riwayat dari BKN</p>
    <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_ortu" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data"><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi Simpeg</p>
</div>
<div class="box box-info">
    <fieldset>
        <?php
        // echo "<pre>";
        // echo print_r($data_jabatan);
        // echo "</pre>";
        if ($data_ortu == null) {
            echo "data orang tua tidak ditemukan";
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
                        <th>Hubungan</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    // foreach ($data_ortu as $row) {
                    if ($data_ortu->ayah->id) {
                        $row = $data_ortu->ayah;
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td>Ayah</td>
                            <td><?php echo $row->nama; ?></td>
                            <td><?php echo $row->tglLahir; ?></td>
                        </tr>
                    <?php
                    $no++;
                    }
                    if ($data_ortu->ibu->id) {
                        $row = $data_ortu->ibu;
                    ?>
                        <tr>
                            <td><?php echo $no; ?></td>
                            <td>Ibu</td>
                            <td><?php echo $row->nama; ?></td>
                            <td><?php echo $row->tglLahir; ?></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php } ?>
    </fieldset>


</div>
<script type="text/javascript">
    $('.sinkrondata_ortu').click(function(e) {
        var kode = $(this).attr("kode");
        swal({
                title: "Anda Yakin?",
                text: "Sinkron orang tua dari data BKN!",
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
                        url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_ortu",
                        type: "POST",
                        data: post_data,
                        dataType: "json",
                        timeout: 180000,
                        success: function(result) {
                            if (result.success) {
                                $("#modal-global").modal('hide');
                                swal("Perhatian!", result.msg, "success");
                                grid_data_ortu.ajax.reload();
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