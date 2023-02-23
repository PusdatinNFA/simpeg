<div class="tab-pane" id="<?php echo $TAB_ID;?>">
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
		<?php echo form_open($this->uri->uri_string(),"id=form_search_jft","form"); ?>
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
						<button type="submit" class="btn btn-warning pull-right "><i class="fa fa-search"></i> Cari</button>
						<button type="button" id="download" class="btn btn-success pull-right " target="_blank"><i class="fa fa-download"></i> Download Nominatif</button>
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>

<div class="admin-box box box-danger">
    <div class="box-header">
        DAFTAR PEGAWAI JFT YANG MENDAPATKAN KPO
    </div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data-jft table-hover">
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
    
	var $container = $("#container-sk-kgb");
	$container.on('click','.show-modal-custom',function(event){
		
		showModalX.call(this,'sukses-proses-kgb',function(){
			$table.ajax.reload();
		},this);
		event.preventDefault();
	});
    
    $(".filter_kawasan").select2({
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
    $tablefjt = $(".table-data-jft").DataTable({

        dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
        serverSide: true,
        processing: true,
        "columnDefs": [
            {"className": "text-center", "targets": [0,6,7]},
            {"className": "text-right", "targets": [4]}
        ],
        ajax: {
        url: "kpo_instansi/ajax_list_kp_jft",
        type:'POST',
        stateSave: true,
        "data": function ( d ) {
                d.search['advanced_search_filters']=  $("#form_search_jft").serializeArray();
            }
        }
    });
    $("#form_search_jft").submit(function(){
        $tablefjt.ajax.reload(null,true);
        return false;
    });
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
	var xyz = $("#form_search_jft").serialize();
	window.open("<?=base_url()?>kpo/kpo_instansi/download_nominatif_jft?"+xyz);
});
</script>
