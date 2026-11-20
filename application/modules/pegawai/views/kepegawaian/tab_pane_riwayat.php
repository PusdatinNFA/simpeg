
<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pane_kepangkatan_id = "tab_pane_kepangkatan";
    $tab_pane_pindah_unit_kerja_id = "tab_pane_pindah_unit_kerja";
    $tab_pane_rwt_jabatan = "tab_pane_rwt_jabatan";
    $tab_pane_rwt_hukdis = "tab_pane_rwt_hukdis";
    $tab_pane_rwt_pekerjaan = "tab_pane_rwt_pekerjaan";
    $tab_pane_rwt_kgb = "tab_pane_rwt_kgb";
    $tab_pane_rwt_tb = "tab_pane_rwt_tb";
    $tab_pane_rwt_pns = "tab_pane_rwt_pns";
    $tab_pane_cltn = "tab_pane_cltn";
    $tab_pane_rwt_penghargaan = "tab_pane_rwt_penghargaan";
    $tab_pane_rwt_angka_kredit = "tab_pane_rwt_angka_kredit";
    $tab_pane_rwt_organisasi = "tab_pane_rwt_organisasi";
    $tab_pane_rwt_profesor_riset = "tab_pane_rwt_profesor_riset";
    $tab_pane_rwt_pidana = "tab_pane_rwt_pidana";
?>
<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
$NIP_BARU = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : '';
?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="nav-tabs-custom">
        <ul id="tab-insides-riwayat" class="nav nav-tabs">
            <?php if($this->auth->has_permission("RiwayatKepangkatan.Kepegawaian.View")){ ?>
            <li class="active">
                <a href="#<?php echo $tab_pane_kepangkatan_id;?>" data-toggle="tab" aria-expanded="false"> Kepangkatan <label id="riwayat_kepangkatan_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("RiwayatPindahUnitKerja.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_pindah_unit_kerja_id;?>" data-toggle="tab" aria-expanded="false"> Unit Kerja </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("riwayatjabatan.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_jabatan;?>" data-toggle="tab" aria-expanded="false"> Jabatan <label id="riwayat_jabatan_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatangkakredit.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_angka_kredit;?>" data-toggle="tab" aria-expanded="false"> Angka Kredit <label id="riwayat_ak_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("riwayatpekerjaan.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_pekerjaan;?>" data-toggle="tab" aria-expanded="false"> Pekerjaan </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("RiwayatOrganisasi.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_organisasi;?>" data-toggle="tab" aria-expanded="false"> Organisasi <label id="riwayat_organisasi_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatpenghargaan.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_penghargaan;?>" data-toggle="tab" aria-expanded="false"> Penghargaan <label id="riwayat_penghargaan_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayathukdis.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_hukdis;?>" data-toggle="tab" aria-expanded="false"> Hukuman Disiplin </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("riwayatkgb.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_kgb;?>" data-toggle="tab" aria-expanded="false"> Kenaikan Gaji Berkala <label id="riwayat_kgb_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayattb.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_tb;?>" data-toggle="tab" aria-expanded="false"> Tugas Belajar <label id="riwayat_tubel_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatpns.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_pns;?>" data-toggle="tab" aria-expanded="false"> CPNS/PNS </a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatcltn.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_cltn;?>" data-toggle="tab" aria-expanded="false"> Cuti <label id="riwayat_cuti_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatprofesorriset.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_profesor_riset;?>" data-toggle="tab" aria-expanded="false"> Profesor Riset <label id="profesor_riset_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            <?php if($this->auth->has_permission("Riwayatpidana.Kepegawaian.View")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_rwt_pidana;?>" data-toggle="tab" aria-expanded="false"> Pidana <label id="pidana_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php 
                if($this->auth->has_permission("RiwayatKepangkatan.Kepegawaian.View")){
                    if ($pegawai->status_pegawai == 2)
                    $this->load->view('kepegawaian/tab_pane_riwayat_kepangkatan_pppk',array('TAB_ID'=>$tab_pane_kepangkatan_id,'NIP_BARU'=>$NIP_BARU));
                    else
                    $this->load->view('kepegawaian/tab_pane_riwayat_kepangkatan',array('TAB_ID'=>$tab_pane_kepangkatan_id,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("RiwayatPindahUnitKerja.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_pindah_unit_kerja',array('TAB_ID'=>$tab_pane_pindah_unit_kerja_id,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("riwayatjabatan.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_jabatan',array('TAB_ID'=>$tab_pane_rwt_jabatan,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatangkakredit.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_angka_kredit',array('TAB_ID'=>$tab_pane_rwt_angka_kredit,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("riwayatpekerjaan.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_pekerjaan',array('TAB_ID'=>$tab_pane_rwt_pekerjaan,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("RiwayatOrganisasi.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_organisasi',array('TAB_ID'=>$tab_pane_rwt_organisasi,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("riwayatkgb.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_kgb',array('TAB_ID'=>$tab_pane_rwt_kgb,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatpenghargaan.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_penghargaan',array('TAB_ID'=>$tab_pane_rwt_penghargaan,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayathukdis.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_huk_dis',array('TAB_ID'=>$tab_pane_rwt_hukdis,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayattb.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_tugasbelajar',array('TAB_ID'=>$tab_pane_rwt_tb,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatpns.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_pnscpns',array('TAB_ID'=>$tab_pane_rwt_pns,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatcltn.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_cltn',array('TAB_ID'=>$tab_pane_cltn,'NIP_BARU'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatprofesorriset.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_profesor_riset',array('TAB_ID'=>$tab_pane_rwt_profesor_riset,'nip'=>$NIP_BARU));
                }
                if($this->auth->has_permission("Riwayatpidana.Kepegawaian.View")){
                    $this->load->view('kepegawaian/tab_pane_riwayat_pidana',array('TAB_ID'=>$tab_pane_rwt_pidana,'nip'=>$NIP_BARU));
                }
            ?>
            
        </div>
    </div>
</div>
