<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_cltn" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi BRIN</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              if($data_cltn == null){
               ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data CLTN</h4>
                 </div>
              <?php  
              }else{ ?>

                <br><br>
            <table class="table table-datatable table-bordered">
                <thead>
                    <tr>
                        <th width='15px' >No</th>
                        <th>ID</th>
                        <th>CLTN ID</th>
                        <th>Nomor</th>
                        <th>Tgl SK</th>
                        <th>Mulai</th>
                        <th>Sampai</th>
                        <th>Tgl Aktif</th>
                        <th>Letter BKN</th>
                        <th>Tgl Letter BKN</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $no = 1; 
                    foreach ($data_cltn as $row) {
                    ?>
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo $row->id; ?></td>
                      <td><?php echo $row->cltnId; ?></td>
                      <td><?php echo $row->skNomor; ?></td>
                      <td><?php echo $row->skTanggal; ?></td>
                      <td><?php echo $row->tanggalAwal; ?></td>
                      <td><?php echo $row->tanggalAkhir; ?></td>
                      <td><?php echo $row->tanggalAktif; ?></td>
                      <td><?php echo $row->nomorLetterBkn; ?></td>
                      <td><?php echo $row->tanggalLetterBkn; ?></td>
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
    $('.sinkrondata_cltn').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat CLTN dari data BKN!",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var post_data = "kode="+kode;
            $.ajax({
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_cltn",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_cltn.ajax.reload();
                        }else{
                         swal("Perhatian!", result.msg, "error");
                        }
                },
                error : function(error) {
                    alert(error);
                } 
            });        
            
        } else {
            swal("Batal", "Transaksi dibatalkan", "error");
        }
    });
  });
  </script>