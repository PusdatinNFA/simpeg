<div class="nav-tabs-custom">
        <ul id="tab-insides-here" class="nav nav-tabs">
            <li class="active">
                <a href="#belumttd" data-toggle="tab" aria-expanded="true"> Belum Tanda Tangan </a>
            </li>
            <li class="">
                <a href="#sudahttd" data-toggle="tab" aria-expanded="false"> Sudah Tanda Tangan </a>
            </li> 
            <li class="">
                <a href="#antrianttd" data-toggle="tab" aria-expanded="false"> Antrian Tanda Tangan </a>
            </li> 
        </ul>
        <div class="tab-content">
            <?php 
                                                   
                $this->load->view('sk/belumttd',array('TAB_ID'=>"belumttd"));
                $this->load->view('sk/sudahttd',array('TAB_ID'=>"sudahttd"));
                $this->load->view('sk/antrianttd',array('TAB_ID'=>"antrianttd"));
            ?>
        </div>
    </div>
