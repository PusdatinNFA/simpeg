<?php 
    $this->load->library('convert');
 	  $convert = new convert();
    $tab_pane_keluargaall = "tab_pane_keluargaall";//uniqid("tab_pane_pendidikan");
    $tab_pane_orangtua = "tab_pane_orangtua";//uniqid("tab_pane_diklat_struktural");
    $tab_pane_istri = "tab_pane_istri";//uniqid("tab_pane_diklat_fungsional");
    $tab_pane_anak = "tab_pane_anak";
?>

 
<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
$jk = ISSET($pegawai->JENIS_KELAMIN) ? $pegawai->JENIS_KELAMIN : "";
$hubungan = $jk == "M" ? "Istri" : "Suami";
?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
           <div class="nav-tabs-custom">
                <ul id="tab-insides-here" class="nav nav-tabs">
                     
                    <!-- <li class="active">
                        <a href="#<?php echo $tab_pane_keluargaall; ?>" data-toggle="tab" aria-expanded="false"> Keluarga </a>
                    </li> -->

                 <?php $active = ''; if ($this->auth->has_permission('Privacy.Kepegawaian.View') || $this->auth->username() == $pegawai->NIP_BARU){ ?>
                     <li class="active inline">
                        <a href="#<?php echo $tab_pane_orangtua;?>" data-toggle="tab" aria-expanded="false"> Orangtua <label id="data_orang_tua_<?=$PNS_ID?>"></label></a>
                    </li>
                    <?php } else { $active = 'active';} ?>
                    <li class="<?=$active?>">
                        <a href="#<?php echo $tab_pane_istri;?>" data-toggle="tab" aria-expanded="false"> <?php echo $hubungan; ?> <label id="data_pasangan_<?=$PNS_ID?>"></label></a>
                    </li>
                    <li class="">
                        <a href="#<?php echo $tab_pane_anak;?>" data-toggle="tab" aria-expanded="false"> Anak <label id="data_anak_<?=$PNS_ID?>"></label></a>
                    </li>
                </ul>
                <div class="tab-content">
                    <?php 
                        // $this->load->view('data_keluarga/tab_pane_keluargaall',array('TAB_ID'=>$tab_pane_keluargaall));

                        if ($this->auth->has_permission('Privacy.Kepegawaian.View') || $this->auth->username() == $pegawai->NIP_BARU){
                            $this->load->view('data_keluarga/tab_pane_orangtua',array('TAB_ID'=>$tab_pane_orangtua));
                        }
                        $this->load->view('data_keluarga/tab_pane_istri',array('TAB_ID'=>$tab_pane_istri));
                        $this->load->view('data_keluarga/tab_pane_anak',array('TAB_ID'=>$tab_pane_anak));
                    ?>
                </div>
            </div>
</div>