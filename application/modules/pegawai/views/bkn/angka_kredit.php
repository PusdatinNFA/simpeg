<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data riwayat dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_angka_kredit" kode="<?php echo $nipBaru; ?>" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi BRIN</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              if($data_angka_kredit == null){
               ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data Angka Kredit</h4>
                 </div>
              <?php  
              }else{ ?>

                <br><br>
            <table class="table table-datatable table-bordered">
                <thead>
                    <tr>
                        <th width='15px' >No</th>
                        <th>Nomor</th>
                        <th>Tgl SK</th>
                        <th>Periode</th>
                        <th>Jumlah Angka Kredit</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $this->load->helper('aplikasi');
                    $no = 1; 
                    foreach ($data_angka_kredit as $row) {
                    ?>
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo $row->nomorSk; ?></td>
                      <td><?php echo $row->tanggalSk; ?></td>
                      <td><?php echo getBulanIndonesia($row->bulanMulaiPenailan)." ".$row->tahunMulaiPenailan." s.d. ".getBulanIndonesia($row->bulanSelesaiPenailan)." ".$row->tahunSelesaiPenailan;; ?></td>
                      <td><?php echo $row->kreditBaruTotal; ?></td>
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
    $('.sinkrondata_angka_kredit').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron riwayat Angka Kredit dari data BKN!",
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
                    url: "<?php echo base_url() ?>pegawai/bkn/sinkron_cache_angka_kredit",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_angka_kredit.ajax.reload();
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