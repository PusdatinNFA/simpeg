<div id="container-sk-slks" class="admin-box box box-danger expanded-box">
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
					<td width="150px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="filter_unit_kerja" name="filter_unit_kerja" width="100%" class=" col-md-10 format-control"></select></td>
				</tr>
				
				<tr>
					<td ><label for="example-text-input" class="col-form-label">Periode</label></td>
					<td style="padding-right:10px;" width="200px" >
						<select class="form-control select2" name="filter_bulan">
                            <?php 
                                $bulans = array(
                                    '01'=>'Januari',
                                    '02'=>'Februari',
                                    '03'=>'Maret',
                                    '04'=>'April',
                                    '05'=>'Mei',
                                    '06'=>'Juni',
                                    '07'=>'Juli',
                                    '08'=>'Agustus',
                                    '09'=>'September',
                                    '10'=>'Oktober',
                                    '11'=>'Nopember',
                                    '12'=>'Desember'
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
					<td width="200px"><label for="example-text-input" class="col-form-label">Pengelola Kawasan SDM</label></td>
					<td colspan=2>
						<select class="form-control select2" name="pengelolasdm">
						<?php
							if (isset($pengelolasdms) && is_array($pengelolasdms) && count($pengelolasdms)) :
								echo '<option value="">-- Silahkan Pilih --</option>';
								foreach ($pengelolasdms as $pengelolasdm) :
									$selected = '';
									echo "<option $selected value='$pengelolasdm->id'>".strtoupper($pengelolasdm->nama)."</option>";
								endforeach;
							endif;
							?>
						</select>
					</td>
				</tr>
					<td colspan=4>
                        <div class="pull-right">
						<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Cari</button>
						<button type="button" id="download" class="btn btn-info" target="_blank"><i class="fa fa-download"></i> Download Nominatif</button>
                        </div>
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
        DAFTAR NOMINATIF PEGAWAI PENSIUN
    </div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Foto</th>
                <th>Nama NIP</th>
                <th>Jabatan (BUP)</th>
                <th>Tangal Lahir</th>
                <th>Usia</th> 
				<th>TMT Pensiun</th>
                <th>Satuan Kerja</th>                   
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
	</div>
</div>
<script>
    $(document).ready(function(){
        
        $("#filter_unit_kerja").select2({
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
        $table = $(".table-data").DataTable({
	
            dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            processing: true,
            "columnDefs": [
                            {"className": "text-center", "targets": [0,8]}
                        ],
            ajax: {
            url: "pensiun/ajax_list",
            type:'POST',
            "data": function ( d ) {
                    d.advanced_search_filters =  $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function(){
            $table.ajax.reload(null,true);
            return false;
        });
    });
$("#download").click(function(){
	var xyz = $("#form_search_pegawai").serialize();
	window.open("<?=base_url()?>data_nominatif/pensiun/download_nominatif?"+xyz);
});
</script>