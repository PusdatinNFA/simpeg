<?php
$areaUrl = '/masters/PengelolaSdmKawasan';
$num_columns	= 2;
$can_delete	= $this->auth->has_permission('PengelolaSdmKawasan.Masters.Delete');
$can_edit		= $this->auth->has_permission('PengelolaSdmKawasan.Masters.Edit');
$has_records	= isset($records) && is_array($records) && count($records);

if ($can_delete) {
    $num_columns++;
}
?>
<div class="admin-box box box-danger">
	<div class="box-header">
		<h3>
			 <!-- <?php if ($this->auth->has_permission('PengelolaSdmKawasan.Masters.Create')) : ?>
              <a href="<?php echo site_url($areaUrl . '/create'); ?>">
              	<button type="button" class="btn btn-default btn-warning margin pull-right "><i class="fa fa-plus"></i> Tambah</button>
			</a>
              <?php endif; ?>
			  <?php if ($this->auth->has_permission('PengelolaSdmKawasan.Masters.Download')) : ?>
              <a href="<?php echo site_url($areaUrl . '/download'); ?>">
              	<button type="button" class="btn btn-success margin pull-right " target="_BLANK" ><i class="fa fa-download"></i> Download</button>
			</a>
              <?php endif; ?> -->
		</h3>
	</div>
	<div class="box-body">	
		<?php echo form_open($this->uri->uri_string()); ?>
			<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
				<thead>
					<tr>
						<tr><th style="width:10px">No</th>
						<th>NAMA</th>
						<th width="70px">#</th>
					</tr>
				</thead>
			</table>
		<?php
   		echo form_close();
    ?>
	</div>
</div>


<script type="text/javascript">
var grid_daftar = $(".table-datatable").DataTable({
	ordering: true,
	processing: true,
	serverSide: true,
    "columnDefs": [{
            "className": "text-center",
            "targets": [0,2]
        }
    ],
	ajax: {
	  url: "<?php echo base_url() ?>masters/PengelolaSdmKawasan/ajax_list",
	  type:'POST',
	}
});

$('body').on('click','.btn-hapus',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Hapus data Pengelola Kawasan SDM!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-danger',
		confirmButtonText: 'Ya, Hapus!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>masters/PengelolaSdmKawasan/delete",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Data berhasil di hapus!", result, "success");
						 grid_daftar.ajax.reload();
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
$('body').on('click','.btn-generate',function () { 
	var kode =$(this).attr("kode");
	swal({
		title: "Anda Yakin?",
		text: "Generate data Pengelola Kawasan SDM!",
		type: "warning",
		showCancelButton: true,
		confirmButtonClass: 'btn-success',
		confirmButtonText: 'Ya, Generate!',
		cancelButtonText: "Tidak, Batalkan!",
		closeOnConfirm: false,
		closeOnCancel: false
	},
	function (isConfirm) {
		if (isConfirm) {
			var post_data = "kode="+kode;
			$.ajax({
					url: "<?php echo base_url() ?>masters/PengelolaSdmKawasan/generate",
					type:"POST",
					data: post_data,
					dataType: "html",
					timeout:180000,
					success: function (result) {
						 swal("Data berhasil di generate!", result, "success");
						 grid_daftar.ajax.reload();
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
