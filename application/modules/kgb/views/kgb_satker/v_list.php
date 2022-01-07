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

                                    echo "<option value='".$key."'>$value</option>";
                                }
                            ?>
						</select>
					</td>
					<td ><input class="form-control" type="text" name="filter_tahun" value="<?php echo date('Y');?>" ></td>
				</tr>
				
				<tr>
					<td colspan=4>
						<button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
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
        DAFTAR PEGAWAI YANG MENDAPATKAN KENAIKAN GAJI BERKALA    
        <!-- <div class="box-tools pull-right">
            <button type="button" id="btn-cetak-surat-pengantar" class="btn btn-box-tool btn-default " >
                <i class="fa fa-print"></i> Cetak Surat Pengantar SK 
            </button>
        </div>                    -->
    </div>
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Pegawai</th>
				<th>Pangkat|Golongan</th>
                <th>Masa Kerja</th>
                <th>Gaji Pokok</th>
                <th>Satuan Kerja</th> 
                <th>Keterangan</th>                   
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
	</div>
</div>
<script>
    $(document).ready(function(){
        $("#btn-cetak-surat-pengantar").click(function(event){
            window.location  = 'kgb_instansi/cetak_surat_pengantar/'+$("[name=filter_tahun]").val()+"/"+$("[name=filter_bulan]").val();
        });
		var $container = $("#container-sk-kgb");
		$container.on('click','.show-modal-custom',function(event){
			
			showModalX.call(this,'sukses-proses-kgb',function(){
				$table.ajax.reload();
			},this);
			event.preventDefault();
		});
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
	
            // dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
            // "<'row'<'col-sm-12'tr>>" +
            // "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
            processing: true,
            serverSide: true,
            dom: 'Btip',
            buttons: [
                {
                    "extend": 'excel',
                    "text": '<button class="btn btn-success pull-right"><i class="fa fa-file-excel-o" style="color: white;"></i> Download Excel</button>',
                    "titleAttr": 'Excel',                
                    "action": newexportaction
                }
            ],
            "columnDefs": [
                            {"className": "text-center", "targets": [0,7]},
                            {"className": "text-right", "targets": [4]}
                        ],
            ajax: {
            url: "kgb_satker/ajax_list",
            type:'POST',
            "data": function ( d ) {
                    d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
                }
            }
        });
        $("#form_search_pegawai").submit(function(){
            $table.ajax.reload(null,true);
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

/* For Export Buttons available inside jquery-datatable "server side processing" - Start
- due to "server side processing" jquery datatble doesn't support all data to be exported
- below function makes the datatable to export all records when "server side processing" is on */

function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
        
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
};
//For Export Buttons available inside jquery-datatable "server side processing" - End
</script>