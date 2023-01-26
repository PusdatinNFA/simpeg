
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
<div class="admin-box box box-danger">
	<div class="box-body">
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
					 <thead>
					 <tr>
					 	<th style="width:10px">No</th>
						 <th>#</th>
						 <th>NIP</th>
						 <th>NAMA Pegawai</th>
						 <th>Tanggal Lahir</th>
						 <th>Umur</th>
						 <th>Unit Kerja</th>
						 <th width="10px">#</th>
						</tr>
					 </thead>
					 </table>
		<?php
    echo form_close();
    
    ?>
</div>
</div>


<script type="text/javascript">
$table = $(".table-data").DataTable({
	
	dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
	"<'row'<'col-sm-12'tr>>" +
	"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
	processing: true,
	serverSide: true,
	"columnDefs": [
					{"className": "text-center", "targets": [1,7]},
					{ "targets": [0,1,5,6], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getdataultah",
	  type:'POST',
	  "data": function ( d ) {
			d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
		}
	}
});
$('body').on('click','.sweet-5',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Delete data Pegawai!",
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
					url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/deletedata",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Deleted!", result, "success");
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

</script>