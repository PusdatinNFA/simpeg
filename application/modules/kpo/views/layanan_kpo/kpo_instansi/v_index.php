<?php
$tab_jfu = "tab_jfu";
$tab_jft = "tab_jft";
?>
<div class="row">
    <div class="col-md-12">
		<div class="nav-tabs-custom">
            <ul id="tab-insides-here" class="nav nav-tabs">
                <li class="active">
                    <a href="#<?php echo $tab_jfu; ?>" data-toggle="tab" aria-expanded="true"> JFU </a>
                </li>
                <li class="">
                    <a href="#<?php echo $tab_jft; ?>" data-toggle="tab" aria-expanded="false"> JFT </a>
                </li>
            </ul>
            <div class="tab-content">
                <?php 
                    $this->load->view('layanan_kpo/kpo_instansi/kp_jfu',array('TAB_ID'=>$tab_jfu));
                ?>
                <?php 
                    $this->load->view('layanan_kpo/kpo_instansi/kp_jft',array('TAB_ID'=>$tab_jft));
                ?>
            </div>
 <script type="text/javascript">
    $(".filter_unit_kerja").select2({
            placeholder: 'Filter Unit Kerja...',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            ajax: {
                url: '<?php echo base_url('pegawai/kepegawaian/ajax_unit_list');?>',
                dataType: 'json',
                data: function(params) {
                    return {
                        term: params.term || '',
                        page: params.page || 1
                    }
                },
                cache: true
            }
        });
</script>
