<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pendidikan = "tab_pendidikan";//uniqid("tab_pane_pendidikan");
    $tab_pane_personal_id = "tab_pane_personal";//uniqid("tab_pane_personal");
    $tab_pane_penilaian = "tab_pane_penilaian";//uniqid("tab_pane_kepangkatan");
    $tab_pane_riwayat = "tab_pane_riwayat";//uniqid("tab_pane_pindah_unit_kerja");
    $tab_pane_keluarga = "tab_pane_keluarga";//uniqid("tab_pane_pindah_unit_kerja");
    $tab_pane_ilmiah = "tab_pane_ilmiah";
    $tab_pane_arsip = "tab_pane_arsip";
    $tab_pane_karir = "tab_pane_karir";
    
    
?>

<?php

if (validation_errors()) :
?>
<div class='alert alert-block alert-error fade in'>
    <a class='close' data-dismiss='alert'>&times;</a>
    <h4 class='alert-heading'>
        <?php echo lang('pegawai_errors_message'); ?>
    </h4>
    <?php echo validation_errors(); ?>
</div>
<?php
endif;
    $id = isset($pegawai->ID) ? $pegawai->ID : '';
    $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
?>
<div class="row">
        <div class="col-md-9">
           <div class="nav-tabs-custom">
                <ul id="tab-insides-here" class="nav nav-tabs">
                    <li class="active">
                        <a href="#<?php echo $tab_pane_personal_id; ?>" data-toggle="tab" aria-expanded="true"> Data Personal </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_keluarga; ?>" data-toggle="tab" aria-expanded="false"> Keluarga </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pendidikan; ?>" data-toggle="tab" aria-expanded="false"> Pendidikan </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_penilaian; ?>" data-toggle="tab" aria-expanded="false"> Penilaian </a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_riwayat; ?>" data-toggle="tab" aria-expanded="false"> Riwayat </a>
                    </li>
                    <?php if ($this->auth->has_permission('RiwayatAktivitasIlmiah.Kepegawaian.View') || $this->auth->has_permission('RiwayatKaryatulisIlmiah.Kepegawaian.View')) : ?>
                    <li class="">
                        <a href="#<?php echo $tab_pane_ilmiah; ?>" data-toggle="tab" aria-expanded="false"> Aktivitas Ilmiah </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($this->auth->has_permission('Arsip_digital.Arsip.View')) : ?>
                    <li class="">
                        <a href="#<?php echo $tab_pane_arsip; ?>" data-toggle="tab" aria-expanded="false"> Arsip </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($this->auth->has_permission('Karir.Kepegawaian.View')) : ?>
                    <li class="">
                        <a href="#<?php echo $tab_pane_karir; ?>" data-toggle="tab" aria-expanded="false"> Karir </a>
                    </li>
                    <?php endif; ?>
                </ul>
                <div class="tab-content">
                    <?php 
                        if($model=="update"){
                          $this->load->view('kepegawaian/tab_pane_personaledit',array('TAB_ID'=>$tab_pane_personal_id));
                        }else{
                          $this->load->view('kepegawaian/tab_pane_personal',array('TAB_ID'=>$tab_pane_personal_id));  
                        }                       
                        $this->load->view('kepegawaian/tabkeluarga',array('TAB_ID'=>$tab_pane_keluarga));
                        $this->load->view('kepegawaian/tabpendidikan',array('TAB_ID'=>$tab_pendidikan));
                        $this->load->view('kepegawaian/tab_pane_penilaian',array('TAB_ID'=>$tab_pane_penilaian));
                        $this->load->view('kepegawaian/tab_pane_riwayat',array('TAB_ID'=>$tab_pane_riwayat));
                        if ($this->auth->has_permission('RiwayatAktivitasIlmiah.Kepegawaian.View') || $this->auth->has_permission('RiwayatKaryatulisIlmiah.Kepegawaian.View')){
                            $this->load->view('kepegawaian/tab_pane_ilmiah',array('TAB_ID'=>$tab_pane_ilmiah));
                        }
                    
                        if ($this->auth->has_permission('Arsip_digital.Arsip.View')) :
                          $this->load->view('kepegawaian/tab_pane_arsip',array('TAB_ID'=>$tab_pane_arsip));
                        endif;

                       if ($this->auth->has_permission('Karir.Kepegawaian.View')) :
                        $this->load->view('kepegawaian/tab_pane_karir',array('TAB_ID'=>$tab_pane_karir));
                      endif;
                    ?>
                </div>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-danger">
            <div class="box-body box-profile">
              <!-- <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/uploadfoto/<?php echo $PNS_ID; ?>" tooltip="Upload Foto" class="show-modal"> -->
              <img src="<?php  echo END_POINT_PHOTO.$pegawai->username_intra;  ?>" class="img-responsive pic-bordered img-rounded" id="photopegawai" alt="Photo" width="100%">

              <!-- </a> -->
                <h3 class="profile-username text-center">
                    <?php echo isset($pegawai->GELAR_DEPAN) ? $pegawai->GELAR_DEPAN.' ' : ''; ?><?php echo isset($pegawai->NAMA) ? trim($pegawai->NAMA) : ''; ?><?php echo isset($pegawai->GELAR_BELAKANG) ? ', '.$pegawai->GELAR_BELAKANG : ''; ?>
                </h3>
              <p class="text-muted text-center"><strong>
                        <?php
                        $jabatan = $JABATAN_PROFIL;
                        if($pegawai->JENIS_JABATAN_ID == "1"){
                            echo !empty($parent_path_array_unor[0]->NAMA_JABATAN)?$parent_path_array_unor[0]->NAMA_JABATAN:$pegawai->JABATAN_NAMA." ".$parent_path_array_unor[0]->NAMA_UNOR;      
                        }else{
                          echo $pegawai->JABATAN_NAMA;        
                        }
                          echo "<br>";
                          echo "TMT :".$convert->fmtDate($pegawai->TMT_JABATAN,"dd month yyyy");
                        ?>
              </strong>
              </p>

                <?php if($model!="update"){ ?>
                <?php if ($this->auth->has_permission('Pegawai.UpdateSimple.View')) : ?>
                    <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/profilen/<?php echo $pegawai->ref; ?>/update" tooltip="Upload Foto" class="btn btn-danger btn-block"><i class="fa fa-edit"></i> Edit Profile </a>
                <?PHP endif; ?>
                <?php if ($this->auth->has_permission('Pegawai.UpdateMandiri.View')) : ?>
                  <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/edit_mandiri/<?php echo $pegawai->ref; ?>" tooltip="Update Mandiri" class="btn btn-danger btn-block"><i class="fa fa-edit"></i> Edit Mandiri </a>
                <?PHP endif; ?>
                <?php if ($this->auth->has_permission('Pegawai.UpdateDetil.View')) : ?>
                    <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/edit/<?php echo $pegawai->ID; ?>" tooltip="Update Detil" class="btn btn-warning btn-block"><i class="fa fa-edit"></i> Edit Detil </a>
                <?PHP endif; ?>
                <?PHP }else{
                ?>
                    <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/profilen/<?php echo $pegawai->ref; ?>" class="btn btn-danger btn-block"><i class="fa fa-user"></i> Kembali Ke Profile </a>
                <?php
                } ?>
                <?php if ($this->auth->has_permission('Pegawai.Downloaddrhsingkat.View')) : ?>
                  <a href="<?php echo base_url();?>data_nominatif/slks/cetakdrh/<?php echo $pegawai->PNS_ID; ?>" class="btn btn-info btn-block" target="_BLANK"><i class="fa fa-download"></i> Download DRH Singkat</a>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Pegawai.Downloaddrh.View')) : ?>
                <a href="<?php echo base_url();?>admin/kepegawaian/pegawai/download_drh/<?php echo $pegawai->PNS_ID; ?>" class="btn btn-success btn-block" target="_BLANK"><i class="fa fa-download"></i> Download DRH Lengkap</a>
                <?php endif; ?>
                <?php if ($this->auth->has_permission('Pegawai.SinkronBkn.View')){ ?>
                <button tooltip="Singkron data Pribadi" class="btn btn-success btn-block generatedatabkn" kode="<?=$pegawai->NIP_BARU?>"><i class="fa fa-gear"></i> Sinkron data Pribadi</button>
                <?php } ?>
                <br>
                <?php if ($this->auth->has_permission('Pegawai.Pindahprofile.View')) : ?>
                <p>Ke Profile Pegawai Lain :</p>
                <select id="pindah_pegawai" name="pindah_pegawai" width="100%" class="form-control pindah_pegawai"></select>
                <?PHP endif; ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

          <!-- About Me Box -->
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title">Profile Pegawai</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <strong><i class="fa fa-user margin-r-5"></i> Kedudukan Hukum</strong>
              <p class="text-muted">
                <?php echo $pegawai->KEDUDUKAN_HUKUM ? $pegawai->KEDUDUKAN_HUKUM : "-"; ?>
              </p>
            <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Jabatan Aktif</strong>
              <p class="text-muted">
                  <ul>
                  <?php 
                      foreach($jabatan_aktifs as $jabatan){
                          if($jabatan->JENIS_JABATAN == "Struktural"){
                            $status_jabatan = $jabatan->status_jabatan == "Plt." ? "Plt. " : "";
                            $nama_unit = $jabatan->UNOR == $jabatan->NAMA_UNOR? $jabatan->UNOR : $jabatan->UNOR." - ".$jabatan->NAMA_UNOR;
                            echo "<li><strong>".$status_jabatan.$jabatan->NAMA_JABATAN." ".$nama_unit."</strong></li>";        
                          }else{
                            echo "<li><strong>".$jabatan->NAMA_JABATAN."</strong></li>";        
                          }
                      }
                  ?>
                </ul>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Bidang Kepakaran</strong>
              <p class="text-muted">
                  <ul>
                  <?php 
                      foreach($kepakarans as $kepakaran){
                        if ($kepakaran->kode){
                          echo "<li><strong>".$kepakaran->namaid.' (<i>'.$kepakaran->namaen.'</i>)</strong></li>';
                        }
                      }
                  ?>
                </ul>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Bidang Penelitian</strong>
              <p class="text-muted">
                  <ul>
                  <?php 
                      foreach($penelitians as $penelitian){
                        if ($penelitian->kode){
                          echo "<li><strong>".$penelitian->namaid.' (<i>'.$penelitian->namaen.'</i></strong></li>)';
                        }
                      }
                  ?>
                </ul>
              </p>
              <hr>
              <strong><i class="fa fa-user margin-r-5"></i> Tusi</strong>
              <p class="text-muted">
                  <?php echo $tusi_name ? $tusi_name : "-"; ?>
              </p>
              <hr>
              <strong><i class="fa fa-book margin-r-5"></i> Pendidikan</strong>

              <p class="text-muted">
                <?php 
                    if($selectedPendidikanID){
                        echo $selectedPendidikanID->NAMA;
                    }
                ?>
              </p>

              <hr>

              <strong><i class="fa fa-map-marker margin-r-5"></i> Alamat</strong>

              <p class="text-muted"><?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : ''); ?></p>

              <hr>
              <strong><i class="fa fa-users margin-r-5"></i> Masa Kerja berdasarkan KP Terakhir</strong>
              <p class="text-muted">
                  <?php 
                    echo isset($masa_kerja_kp->mk_tahun_baru) ? $masa_kerja_kp->mk_tahun_baru  : ""; ?> 
                    Tahun 
                    <?php echo isset($masa_kerja_kp->mk_bulan_baru) ? $masa_kerja_kp->mk_bulan_baru  : ""; ?> Bulan
              </p>
              <hr>
              <strong><i class="fa fa-home margin-r-5"></i> Unit Organisasi</strong>
              <p> 
                <ul>
                  <?php 
                      foreach($parent_path_array_unor as $node){
                          echo "<li><strong>".$node->NAMA_UNOR."</strong></li>";        
                      }
                  ?>
                </ul>
                </p>
              <hr>
              <strong><i class="fa fa-home margin-r-5"></i> Satker Penempatan</strong>
              <p> 
                  <?php echo isset($satker_penempatan_ids->NAMA_UNOR) ? $satker_penempatan_ids->NAMA_UNOR : ""; ?>
                </p>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <script>
        $(document).ready(function(){
        reset_pindah_profile();

        function reset_pindah_profile(){
          jQuery('span.select2-container').each(function (i, obj) {
            if (jQuery(obj).hasClass("select2-hidden-accessible")) {                
                jQuery(obj).remove();
            }
            if (jQuery(obj).hasClass("select2-container--default")) {                
                jQuery(obj).remove();
            }
          });
          $(".pindah_pegawai").html('').select2({
            placeholder: 'Cari Pegawai.....',
            width: '100%',
            minimumInputLength: 3,
            allowClear: true,
            ajax: {
                url: '<?php echo site_url("pegawai/kepegawaian/ajaxref");?>',
                dataType: 'json',
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: false
              }
          });          
        }
      $("#pindah_pegawai").change(function(){
        var ref = $("#pindah_pegawai").val();
        if (ref)
          window.location.replace("<?=base_url()?>admin/kepegawaian/pegawai/profilen/"+ref);
      });

      $('#modal-global').on('hidden.bs.modal', function () {
        reset_pindah_profile();
      });
      $('#modal-custom-global').on('hidden.bs.modal', function () {
        reset_pindah_profile();
      });
      $('.show-modal-custom').on('hidden.bs.modal', function () {
        reset_pindah_profile();
      });
      $('.modal-open').on('hidden.bs.modal', function () {
        reset_pindah_profile();
      });
      // $('body').on('click','.show-modal-custom',function(event){
      //   reset_pindah_profile();
      //   event.preventDefault();
      // });
    });
$('body').on('click','.generatedatabkn',function () { 
  var kode =$(this).attr("kode");
  swal({
    title: "Anda Yakin?",
    text: "Sinkronisasi data pribadi!",
    type: "warning",
    showCancelButton: true,
    confirmButtonClass: 'btn-danger',
    confirmButtonText: 'Ya!',
    cancelButtonText: "Tidak, Batalkan!",
    showLoaderOnConfirm: true,
    closeOnConfirm: false,
    closeOnCancel: false
  },
  function (isConfirm) {
    if (isConfirm) {
      var post_data = "nip_bkn="+kode;
      $.ajax({
          url: "<?php echo base_url() ?>pegawai/bkn/getpegawaibknnew",
          type:"POST",
          data: post_data,
          dataType: "json",
          timeout:180000,
          success: function (result) {
            if(result.success){
              swal("Pemberitahuan!", result.msg, "success");
            }
            else {
              swal("Pemberitahuan!", result.msg, "error");
            }
        },
        error : function(error) {
          alert(error);
        } 
      });        
      
    } else {
      swal("Batal", "", "error");
    }
  });
});
</script>
