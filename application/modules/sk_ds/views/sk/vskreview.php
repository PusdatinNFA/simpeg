
<?php
$this->load->library('convert');
    $convert = new convert();
?>
<div class="row">
        
        
        <div class="col-md-9">     
             <embed id="div_preview_file" src="<?php echo $url_sk; ?>" width="100%" height="1000" alt="pdf">
              <!-- <embed src="data:application/pdf;base64,<?php echo $b64; ?>" width="100%" height="700" alt="pdf"> -->
        </div>
        <!-- /.col -->
        <div class="col-md-3">

          <!-- About Me Box -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Keterangan SK</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <strong><i class="fa fa-user margin-r-5"></i> Penandatangan </strong>
              <p class="text-muted">
                  <?php echo isset($pejabat->GELAR_DEPAN) ? $pejabat->GELAR_DEPAN : "-"; ?>
                  <?php echo isset($pejabat->NAMA) ? $pejabat->NAMA : "-"; ?>
                  <?php echo isset($pejabat->GELAR_BELAKANG) ? $pejabat->GELAR_BELAKANG : "-"; ?>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Paraf Ke </strong>
              <p class="text-muted">
                  <?php echo $data[0]->korektor_ke; ?>
                  
              </p>
               <hr>
               <strong><i class="fa fa-user margin-r-5"></i> Status Paraf </strong>
              <p class="text-muted">
                  <?php echo $data[0]->status_koreksi == "1" ? "<span class='label label-success'>Sudah Paraf</span>" : ""; ?>
                  <?php echo $data[0]->status_koreksi == "2" ? "<span class='label label-warning'>Sedang Paraf</span>" : ""; ?>
                  <?php echo ($data[0]->status_koreksi == "" or $data[0]->status_koreksi == "0") ? "<span class='label label-danger'>Belum Koreksi</span>" : ""; ?>
                  
              </p>
              <hr>
              <?php if($data[0]->status_koreksi != "1"){ ?>
                <br>
                <?php echo form_open($this->uri->uri_string(), 'id="frm"'); ?>
                      <textarea name="catatan" id="catatan" class="form-control" width="100%" placeholder="Masukan Catatan Koreksi"><?php echo $data[0]->catatan_koreksi; ?></textarea>
                      <h5><b>Catatan Sebelumnya</b></h5>
                      <?php
                      if(isset($catatans) && is_array($catatans) && count($catatans)):
                        $no = 1;
                        foreach ($catatans as $record_catatan) {
                          echo $no.". ".$record_catatan->catatan_tindakan."<br>";
                          $no++;
                        }
                      else:
                        echo "-";
                      endif;
                      ?>
                      <br>
                      
                    <!-- <a class="btn btn-block btn-social btn-dropbox" id="btn_koreksi_ok">
                      <i class="fa fa-edit"></i> Teruskan
                     </a>-->
                    <a class="btn btn-block btn-social btn-flickr" id="btn_paraf">
                      <i class="fa fa-edit"></i> Paraf <?php echo $jml_dokumen; ?> Dokumen
                    </a>
                    <a class="btn btn-block btn-social btn-google" id="btn_koreksi_batal">
                      <i class="fa fa-arrow-left"></i> Koreksi/Kembalikan Ke Pemroses
                    </a>
              </form>
            <?php }else{ ?>
                <button class="btn btn-block btn-social btn-google" id="btn_notsign">
                  <i class="fa fa-key"></i> SK Sudak dikoreksi
                </button>
            <?php } ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
</div>
<div class="row">
  <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-data-review table-hover">
      <thead>
        <tr>
          <th width="2%">No</th>
          <th width="15%">Pemilik SK</th>
          <th width="10%">Kategori SK</th>
          <th>No SK / Tgl SK</th>
          <th>Unit Kerja</th>
          <th width="70px" align="center">#</th>
        </tr>
      </thead>
    </table>
</div>
<script>
$( "#btn_paraf" ).click(function() {
      swal({
      title: "Paraf SK",
      //text: "Silahkan Masukan Pasphrase anda dengan NIK <?php echo isset($pegawai_login->NIK) ? $pegawai_login->NIK : ""; ?>",
      text: "Silahkan Masukan Pasphrase anda",
      inputType: "password",
      type: "input",
      showCancelButton: true,
      closeOnConfirm: false,
      animation: "slide-from-top",
      inputPlaceholder: "Pasphrase"
    },
    function(inputValue){
      if (inputValue === false) return false;

      if (inputValue === "") {
        swal.showInputError("Silahkan masukan Pasphrase!");
        return false
      }
      swal("Perhatian", "Tunggu sebentar, tandatangan digital sedang proses....", "info");
      submitParaf(inputValue,1);
    });

});
function submitParaf(pass,status){
  var datas = 'passphrase='+pass+'&username=<?php echo isset($pegawai_login->NIK) ? trim($pegawai_login->NIK) : ""; ?>&NIP=<?php echo isset($pegawai_login->NIP_BARU) ? trim($pegawai_login->NIP_BARU) : ""; ?>&aid_file=<?php echo $aid_file; ?>';
  var json_url = "<?php echo base_url() ?>admin/sk/sk_ds/prosesvalidasi/"+status;
   $.ajax({    
    type: "POST",
    url: json_url,
    data: $("#frm").serialize()+"&"+datas,
    dataType: "json",
    success: function(data){ 
        if(data.success){
            swal("Pemberitahuan!", data.msg, "success");
            window.location = "<?php echo base_url(); ?>admin/sk/sk_ds/validasi";
        }
        else {
          swal("Pemberitahuan!", data.msg, "error");    
        }
    }});
  return false; 
}
$('body').on('click','#btn_koreksi_ok',function () { 
  swal({
    title: "Anda Yakin?",
    text: "Lanjutkan Proses ke paraf selanjutnya/Ke Penandatangan!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      submitdata(1);
    } else {
      swal("Batal", "", "error");
    }
  });
});
$('body').on('click','#btn_koreksi_batal',function () { 
  swal({
    title: "Anda Yakin?",
    text: "Koreksi dokumen!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      var catatan = $("#catatan").val();
      if(catatan == ""){
        swal("Peringatan", "Silahkan masukan catatan koreksi", "error");
        $("#catatan").focus();
      }else{
        submitParaf("",3);
      }
    } else {
      swal("Batal", "", "error");
    }
  });
});
$('body').on('click','.preview_file',function () { 
    var url = $(this).attr("url");
    document.getElementById("div_preview_file").setAttribute("src",url);
});
$table = $(".table-data-review").DataTable({
  
  dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
  "<'row'<'col-sm-12'tr>>" +
  "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
  processing: true,
  serverSide: true,
  "columnDefs": [
          {"className": "text-center", "targets": [0,4]},
          { "targets": [0,4], "orderable": false }
        ],
  ajax: {
    url: "<?php echo base_url() ?>admin/sk/sk_ds/getdata_preview",
    type:'POST',
    "data": function ( d ) {
      d.search = '<?php echo $aid_file; ?>';
    },
    "complete": function(response) {
      
    }
  }
});

</script>