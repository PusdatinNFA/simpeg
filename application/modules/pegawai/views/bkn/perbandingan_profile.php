
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<div class="row">
  <div class="col-md-12">
    <!-- About Me Box -->
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Perbandingan Data Profile Pegawai</h3>
        <form id="frm-data">
        <table class="table table-border">
          <tr>
            <thead>
              <th style="width: 10%;">DATA</th>
              <th style="width: 40%;">BKN</th>
              <th style="width: 40%;">SIMPEG</th>
            </thead>
          </tr>
          <input type="hidden" name="PNS_ID" value="<?=$pegawai->PNS_ID?>">
          <tr>
            <td>Agama</td>
            <td><?php foreach ($agamas as $record):
              echo ($pegawai_bkn->agamaId == $record->ID) ? $record->NAMA : "";
            endforeach; ?></td>
            <td>
              <select class="form-control" name="AGAMA_ID">
                <option value="">-</option>
                <?php foreach ($agamas as $record): ?>
                        <option value="<?= $record->ID ?>" <?= $record->ID == $pegawai->AGAMA_ID ? 'selected' : '' ?>><?= $record->NAMA ?></option>
                  <?php endforeach; ?>
                </select>
            </td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td><?= $pegawai_bkn->alamat ?></td>
            <td><textarea type="text" class="form-control" name="ALAMAT"><?= $pegawai->ALAMAT ?></textarea></td>
          </tr>
          <tr>
            <td>Email</td>
            <td><?= $pegawai_bkn->email ?></td>
            <td><input type="email" class="form-control" name="EMAIL_PRIVATE" value="<?= $pegawai->EMAIL_PRIVATE ?>"></td>
          </tr>
          <tr>
            <td>Email Instansi</td>
            <td><?= $pegawai_bkn->emailGov ?></td>
            <td><input type="email" class="form-control" name="EMAIL" value="<?= $pegawai->EMAIL ?>"></td>
          </tr>
          <tr>
            <td>Karis/Karsu</td>
            <td><?= $pegawai_bkn->karis_karsu ?></td>
            <td><input type="text" class="form-control" name="karis_karsu" value="<?= $pegawai->karis_karsu ?>"></td>
          </tr>
          <!-- <tr>
            <td>Kelas Jabatan</td>
            <td><?= $pegawai_bkn->kelas_jabatan ?></td>
            <td><?= $pegawai->kelas_jabatan ?></td>
          </tr> -->
          <tr>
            <td>Lokasi Kerja</td>
            <td><?= $pegawai_bkn->lokasiKerja ?></td>
            <td>
              <select class="form-control select2" id="kota" name="LOKASI_KERJA_ID"><option value="">-</option>
                <?php foreach ($lokasis as $record): ?>
                        <option value="<?= $record->ID ?>"     <?= $record->ID == $pegawai->LOKASI_KERJA_ID ? 'selected' : '' ?>><?= $record->NAMA ?></option>
                  <?php endforeach; ?>
                </select>
              </td>
          </tr>
          <tr>
            <td>Nomor BPJS</td>
            <td><?= $pegawai_bkn->bpjs ?></td>
            <td><input type="text" class="form-control" name="BPJS" value="<?= $pegawai->BPJS ?>"></td>
          </tr>
          <tr>
            <td>Nomor HP</td>
            <td><?= $pegawai_bkn->noHp ?></td>
            <td><input type="text" class="form-control" name="NOMOR_HP" value="<?= $pegawai->NOMOR_HP ?>"></td>
          </tr>
          <tr>
            <td>Nomor NPWP</td>
            <td><?= $pegawai_bkn->noNpwp ?></td>
            <td><input type="text" class="form-control" name="NPWP" value="<?= $pegawai->NPWP ?>"></td>
          </tr>
          <tr>
            <td>Tanggal NPWP</td>
            <td><?= $pegawai_bkn->tglNpwp ?></td>
            <td><input type="date" class="form-control" name="TGL_NPWP" value="<?= $pegawai->TGL_NPWP ?>"></td>
          </tr>
          <tr>
            <td>Nomor Taspen</td>
            <td><?= $pegawai_bkn->noTaspen ?></td>
            <td><input type="text" class="form-control" name="NO_TASPEN" value="<?= $pegawai->NO_TASPEN ?>"></td>
          </tr>
          <tr>
            <td>Tanggal Taspen</td>
            <td><?= $pegawai->tanggal_taspen ? date('Y-m-d', strtotime($pegawai->tanggal_taspen)) : "" ?></td>
            <td><input type="date" class="form-control" name="TGL_TASPEN" value="<?= $pegawai->TGL_TASPEN ?>"></td>
          </tr>
          <tr>
            <td>Nomor Tapera</td>
            <td><?= $pegawai_bkn->tabrum2 ?></td>
            <td><input type="text" class="form-control" name="NO_TAPERA" value="<?= $pegawai->NO_TAPERA ?>"></td>
          </tr>
          
        </table>
        <button id="btnsave" type="button" class="btn btn-success pull-right">Simpan dan Sinkron</button>
        </form>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script>
  // $(document).ready(function(){
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    })
    $('#kota').select2({
      width : '100%'
    });
    $('#btnsave').click(function(){
      submitdata()
    })
    function submitdata(){
        $('#btnsave').text('Sedang Mengirim...');
        $('#btnsave').prop('disabled', true);
        var the_data = new FormData(document.getElementById("frm-data"));
        var json_url = "<?php echo base_url() ?>pegawai/kepegawaian/save_profile_integrasi";
        $.ajax({    
          type: "POST",
          url: json_url,
          data: the_data,
                dataType: "json",
                processData: false, // tell jQuery not to process the data
                contentType: false, // tell jQuery not to set contentType
                success: function(data){ 
                  $('#btnsave').prop('disabled',false);
                  $('#btnsave').text('Simpan dan Sinkron');
                  if(data.success){
                      swal("Pemberitahuan!", data.message, "success");
                      $("#modal-global").trigger("sukses-tambah-riwayat-aktivitas-ilmiah");
                      $("#modal-global").modal("hide");
                  }
                  else {                              
                    swal("Pemberitahuan!", data.message, "error");
                  }
        }});
        return false; 
  }
  // });
</script>
