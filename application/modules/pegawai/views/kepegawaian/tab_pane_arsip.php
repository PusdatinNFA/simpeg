<?php 
	$this->load->library('convert');
 	$convert = new convert();
    $tab_pane_arsi_digital = "tab_pane_arsip_digital";
    $tab_pane_sk_elektronik = "tab_pane_sk_elektronik";
?>

<?php
$id = isset($pegawai->ID) ? $pegawai->ID : '';
$PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : '';
$NIP_BARU = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : '';
?>
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="nav-tabs-custom">
        <ul id="tab-insides-arsip" class="nav nav-tabs">
			<?php if($this->auth->has_permission("Arsip_digital.Arsip.View")){ ?>
			<li class="active">
                <a href="#<?php echo $tab_pane_arsi_digital;?>" data-toggle="tab" aria-expanded="false"> Arsip Digital <label id="arsip_digital_<?=$PNS_ID?>"></a>
            </li>
            <?php } ?>
            
			<?php if($this->auth->has_permission("Sk_ds.Arsip.Pegawai")){ ?>
            <li class="">
                <a href="#<?php echo $tab_pane_sk_elektronik;?>" data-toggle="tab" aria-expanded="false"> SK Elektronik </a>
            </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php 
				if($this->auth->has_permission("Sk_ds.Arsip.Pegawai")){
                    $this->load->view('kepegawaian/tab_pane_sk_elektronik',array('TAB_ID'=>$tab_pane_sk_elektronik,'NIP_BARU'=>$NIP_BARU));
				}

				if($this->auth->has_permission("Arsip_digital.Arsip.View")){
                    $this->load->view('kepegawaian/tab_pane_arsip_digital',array('TAB_ID'=>$tab_pane_arsi_digital,'NIP_BARU'=>$NIP_BARU));
				}
            ?>
            
        </div>
    </div>
</div>