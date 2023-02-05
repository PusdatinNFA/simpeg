<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pane_rwt_aktivitas_ilmiah = "tab_pane_rwt_aktivitas_ilmiah";
    $tab_pane_rwt_karyatulis_ilmiah = "tab_pane_rwt_karyatulis_ilmiah";
?>
<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
$NIP_BARU = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : '';
?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="nav-tabs-custom">
        <ul id="tab-insides-ilmiah" class="nav nav-tabs">
            <?php if($this->auth->has_permission("RiwayatAktivitasIlmiah.Kepegawaian.View")){ ?>
            <li class="active">
                <a href="#<?php echo $tab_pane_rwt_aktivitas_ilmiah;?>" data-toggle="tab" aria-expanded="false"> Aktivitas Ilmiah </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("RiwayatKaryatulisIlmiah.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_karyatulis_ilmiah;?>" data-toggle="tab" aria-expanded="false"> Karya Tulis Ilmiah</a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php 
                if($this->auth->has_permission("RiwayatAktivitasIlmiah.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_aktivitas_ilmiah',array('TAB_ID'=>$tab_pane_rwt_aktivitas_ilmiah,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("RiwayatKaryatulisIlmiah.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_karyatulis_ilmiah',array('TAB_ID'=>$tab_pane_rwt_karyatulis_ilmiah,'NIP_BARU'=>$NIP_BARU));
                }
            ?>
        </div>
    </div>
</div>
