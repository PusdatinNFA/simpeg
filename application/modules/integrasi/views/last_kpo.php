<div class="callout callout-success">
       <h4>Perhatian</h4>
       <P>dibawah ini merupakan data KPO dari BKN</p>
        <p>Silahkan klik tombol <button class="btn btn-warning sinkrondata_kpo" tooltip="Sinkron data" ><i class="fa fa-refresh" aria-hidden="true"></i> Sinkron</button> untuk sinkronisasi data dengan aplikasi BKN</p>
     </div>
    <div class="box box-info">
        <fieldset>
             <?php
              if($data_kpo == null){
               ?>
                <div class="callout callout-danger">
                   <h4>Tidak ada data KPO terbaru</h4>
                 </div>
              <?php  
              }else{ ?>
                <br><br>
            <table class="table table-datatable table-bordered">
                <thead>
                    <tr>
                        <th width='15px' >No</th>
                        <th>NIP</th>
                        <th>Tgl SK / Nomor SK</th>
                        <th>Golongan</th>
                        <th>TMT</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $this->load->helper('aplikasi');
                    $no = 1; 
                    foreach ($data_kpo as $row) {
                    ?>
                    <tr>
                      <td><?php echo $no; ?></td>
                      <td><?php echo $row->nipBaru; ?></td>
                      <td><?php echo $row->noSk."<br>".$row->skTanggal; ?></td>
                      <td><?php echo $row->golongan->nama; ?></td>
                      <td><?php echo $row->tmtGolongan; ?></td>
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
    $('.sinkrondata_kpo').click(function(e) {  
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Sinkron Data KPO dari data BKN!",
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
                    url: "<?php echo base_url() ?>integrasi/kpo/sinkron_cache_hukdis",
                    type:"POST",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                          $("#modal-global").modal('hide');
                          swal("Perhatian!", result.msg, "success");
                          $grid_daftar_hukdis.ajax.reload();
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