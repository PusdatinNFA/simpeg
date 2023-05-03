<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div id="container-sk-kgb" class="admin-box box box-danger expanded-box">
        <div class="box-header">
                  <h3 class="box-title">Pencarian Lanjut</h3>
                   <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
                            <i class="fa fa-plus"></i> Tampilkan
                        </button>
                        
                  </div>
        </div>

        <div class="box-body">
            <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
                <style>
                    table.filter_pegawai tr td {
                        padding-top: 2px;
                    }
                </style>
                <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                    <tr>
                        <td width="150px"><label for="example-text-input" class="col-form-label">Pegawai</label></td>
                        <td colspan=2>
                            <select id="nip_sk" name="nip_sk" width="100%" class="form-control select2 class_pegawai">
                            <?php
                            if($pegawai){
                                echo "<option selected value='".$pegawai->PNS_ID."'>".$pegawai->NAMA."</option>";
                            }
                            ?>
                        </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="150px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
                        <td colspan=2><select name="filter_unit_kerja" width="100%" class="filter_unit_kerja col-md-10 format-control"></select></td>
                    </tr>

                    <tr>
                        <td ><label for="example-text-input" class="col-form-label">Periode</label></td>
                        <td style="padding-right:10px;" width="200px" >
                            <select class="form-control select2" name="filter_bulan">
                                <?php 
                                    $bulans = array(
                                        '04'=>'April',
                                        '10'=>'Oktober'
                                    );
                                    foreach($bulans as  $key=>$value){
                                        $bulan = date('m');
                                        if ($bulan == $key)
                                        echo "<option value='".$key."' selected>$value</option>";
                                        else 
                                        echo "<option value='".$key."'>$value</option>";
                                    }
                                ?>
                            </select>
                        </td>
                        <td ><input class="form-control" type="text" name="filter_tahun" value="<?php echo date('Y');?>" ></td>
                    </tr>
                    
                    <tr>
                        <td colspan=4>
                            <div class=" pull-right">
                                <button type="submit" class="btn btn-warning"><i class="fa fa-search"></i> Cari</button>
                                <button type="button" id="download" class="btn btn-success" target="_blank"><i class="fa fa-download"></i> Download Nominatif</button>
                            </div>
                        </td>
                    </tr>
                </table>
            <?php
            echo form_close();    
            ?>
        </div>
    </div>

    <div id="container-list" class="admin-box box box-danger">
        <div class="box-header">
            DAFTAR PEGAWAI YANG MENDAPATKAN KPO
        </div>
        <div class="box-body">
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
                <thead>
                <tr>
                    <th style="width:10px">No</th>
                    <th>Pegawai</th>
                    <th>Pangkat Lama</th>
                    <th>Pangkat Baru</th>
                    <th>Masa Kerja</th>
                    <th>Satuan Kerja</th> 
                    <th>Keterangan</th>                   
                    <th width="70px" align="center">#</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#btn-cetak-surat-pengantar").click(function(event){
            window.location  = 'kgb_instansi/cetak_surat_pengantar/'+$("[name=filter_tahun]").val()+"/"+$("[name=filter_bulan]").val();
        });
        var $container = $("#container-sk-kgb");
        var $container_list = $("#container-list");
        $container.on('click','.show-modal-custom',function(event){
            
            showModalX.call(this,'sukses-proses-kgb',function(){
                $table.ajax.reload();
            },this);
            event.preventDefault();
        });
        
        $("#filter_kawasan").select2({
            placeholder: 'Filter Kawasan...',
            width: '100%',
            minimumInputLength: 0,
            allowClear: true,
            ajax: {
                url: '<?php echo base_url('pegawai/kepegawaian/ajax_kawasan');?>',
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
        $table = $(".table-data").DataTable({
    
            dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            serverSide: true,
            // dom: 'Btip',
            // buttons: [
            //     {
            //         "extend": 'excel',
            //         "text": '<button class="btn btn-success pull-right"><i class="fa fa-file-excel-o" style="color: white;"></i> Download Excel</button>',
            //         "titleAttr": 'Excel',                
            //         "action": newexportaction
            //     }
            // ],
            processing: true,
            "columnDefs": [
                            {"className": "text-center", "targets": [0,6,7]},
                            {"className": "text-right", "targets": [4]}
                        ],
            ajax: {
            url: "kpo_instansi/ajax_list_kp",
            type:'POST',
            stateSave: true,
            "data": function ( d ) {
                    d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function(){
            $table.ajax.reload(null,true);
            return false;
        });
    $container_list.on('click','.btn-sync',function(e){
        e.preventDefault();
        var PNS_ID = $(this).attr('kode')
        var GOL_ID = $(this).attr('next')
        swal({
            title: "Anda Yakin?",
            text: "Lihat data riwayat kepangkatan berdasarkan data BKN!",
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
                var post_data = "kode="+PNS_ID+"&golongan_id="+GOL_ID;
                $.ajax({
                        url: "<?php echo base_url() ?>pegawai/bkn/viewkepangkatan",
                        type:"POST",
                        data: post_data,
                        dataType: "json",
                        timeout:180000,
                        success: function (result) {
                            if(result.success){
                                swal({
                                    title: "Selamat!",
                                    text: result.msg,
                                    type: "success",
                                    timer: 4000,
                                    showConfirmButton: true
                                }, function () {
                                    $("#modal-body").html(result.konten);
                                    $("#myModalLabel").html("Riwayat Kepangkatan BKN");
                                    $("#modal-global").modal('show');
                                });
                                $table.ajax.reload(null,true);
                            }else{
                                swal("Perhatian", result.msg, "error");
                            }
                            
                    },
                    error : function(error) {
                        swal("Perhatian", "Ada masalah koneksi", "error");
                    } 
                });        
                
            } else {
                swal("Batal", "", "error");
            }
        });
    })
    });
$(".class_pegawai").select2({
        placeholder: 'Cari Pegawai.....',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajaxnip");?>',
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
$("#download").click(function(){
    var xyz = $("#form_search_pegawai").serialize();
    window.open("<?=base_url()?>kpo/kpo_instansi/download_nominatif?"+xyz);
});
</script>
