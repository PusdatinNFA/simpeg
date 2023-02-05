
<?php
$checkSegment = $this->uri->segment(4);
$areaUrl = SITE_AREA . '/kepegawaian/pegawai';
$num_columns	= 44;
$can_delete	= $this->auth->has_permission('Pegawai.Kepegawaian.Delete');
$can_edit		= $this->auth->has_permission('Pegawai.Kepegawaian.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>


<div class="admin-box box box-danger expanded-box">
	<div class="box-header">
              <h3 class="box-title">Pencarian Lanjut</h3>
			   <div class="box-tools pull-right">
                	<button type="button" class="btn btn-box-tool btn-default btn-advanced-search" data-widget="collapse">
						<i class="fa fa-minus"></i> Tampilkan
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
					<td width="200px"><label for="example-text-input" class="col-form-label">Satuan Kerja</label></td>
					<td colspan=2><select id="unit_id_key" name="unit_id_key" width="100%" class=" col-md-10 format-control"></select></td>
				</tr>
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">Pegawai</label></td>
					<td colspan=2>
						<select id="nip" name="nip" width="100%" class="form-control select2 class_pegawai">
						</select>
					</td>
				</tr>
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">Data</label></td>
					<td colspan=2>
						<select name="data" class="form-control select2" id='data'>

						</select>
					</td>
				</tr>
				<tr>
					<td width="200px"><label for="example-text-input" class="col-form-label">Status</label></td>
					<td colspan=2>
						<select class="form-control" name="status_cb">
							<option value="-1">Belum diverifikasi</option>
							<option value="0">Ditolak</option>
							<option value="1">Diterima</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan=4>
						<button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
						<button class='btn btn-info pull-right downloadzip' href="<?php echo site_url('admin/kepegawaian/pegawai/download_updatemandiri1'); ?>" data-toggle="tooltip" data-original-title="Download excell" tooltip="Download excell"><i class="fa fa-download" aria-hidden="true"></i> Download xls</button>&nbsp;&nbsp;&nbsp;
					</td>
				</tr>
			</table>
		<?php
		echo form_close();    
		?>
	</div>
</div>
<div class="admin-box box box-danger">
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>NIP</th>
				<th>NAMA Pegawai</th>
				<th>Unit Kerja</th>
				<th>Perubahan</th>
				<th>Keterangan</th>
				<th width="70px" align="center">#</th></tr>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
$("#data").select2({
		placeholder: 'Cari Jenis Data...',
		width: '100%',
		minimumInputLength: 0,
		allowClear: true,
		ajax: {
			url: '<?php echo site_url("pegawai/kepegawaian/ajax_jenis_data_update"); ?>',
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
$("#unit_id_key").select2({
	placeholder: 'Cari Unit Kerja...',
	width: '100%',
	minimumInputLength: 0,
	allowClear: true,
	ajax: {
		url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
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
	serverSide: true,
	stateSave: true,
	"columnDefs": [
					{"className": "text-center", "targets": [1, 4,5]},
					{ "targets": [0,1,2,3,4,5], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getdataupdatemandirisatker",
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

$('#modal-global').on('hidden.bs.modal', function () {
	$table.ajax.reload(null,true);
});
$('#modal-custom-global').on('hidden.bs.modal', function () {
	$table.ajax.reload(null,true);
});
$('.show-modal-custom').on('hidden.bs.modal', function () {
	$table.ajax.reload(null,true);
});
$('.modal-open').on('hidden.bs.modal', function () {
	$table.ajax.reload(null,true);
});
$(document).on('hidden.bs.modal', function(event) {
	$table.ajax.reload(null,true);
});
$(".class_pegawai").html('').select2({
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
		cache: false
		}
	});  
$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Delete data pengajuan perubahan!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya, Delete!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/deletedataupdatemandiri",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
						 $table.ajax.reload(null,true);
				},
				error : function(error) {
					alert(error);
				} 
			});        
			
		} else {
			swal("Batal", "", "error");
		}
	});
});
$('body').on('click','.btn-tolak',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda yakin tolak data pengajuan perubahan?",
		text: "Masukan keterangan penolakan",
		type: "input",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya, Tolak!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (inputValue) {
		if (inputValue) {
			var post_data = "kode="+kode+"&keterangan="+inputValue;
			$.ajax({
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/tolakupdatemandiri",
					type:"POST",
					data: post_data,
					dataType: "json",
					timeout:180000,
					success: function (result) {
						 swal("Berhasil!", result.msg, "success");
						 $table.ajax.reload(null,true);
				},
				error : function(error) {
					alert(error);
				} 
			});        
			
		} 
		else {
			swal("Batal", "", "error");
		}
	});
});
$(".downloadzip").click(function(){
    var xyz = $("#form_search_pegawai").serialize();
    window.open("<?php echo base_url('admin/kepegawaian/pegawai/download_updatemandiri1');?>?"+xyz);
});
$('body').on('click','#btnsavearsip',function () { 
    if(!event.detail || event.detail == 1){//activate on first click only to avoid hiding again on multiple clicks
        // code here. // It will execute only once on multiple clicks
        submitdatadokumen();
    }
});
function submitdatadokumen(){
	    $('#btnsavearsip').prop('disabled',true);
        $('#btnsavearsip').prop("disabled", true);
	    var the_data = new FormData(document.getElementById("formarsip"));
	    $.ajax({
	        url: "<?php echo base_url('admin/arsip/arsip_digital/act_save'); ?>",
	        type: "POST",
	        data: the_data,
	        enctype: 'multipart/form-data',
	        processData: false, // tell jQuery not to process the data
	        contentType: false, // tell jQuery not to set contentType
	        dataType: 'JSON',

	        beforeSend: function (xhr) {
	            //$("#loading-all").show();
	        },
	        success: function (response) {
	            if(response.success){
	                swal("Sukses",response.msg,"success");
	                $("#modal-custom-global").modal("hide");
	                $("#modal-global").modal("hide");
	                $('#btnsavearsip').prop('disabled',false);
	            }else{
	                $(".messagearsip").html(response.msg);
	                $('#btnsavearsip').prop('disabled',false);
	            }
                $('#btnsavearsip').prop("disabled", false);
	        }
	    });
	    
	    return false; 
	  }
</script>