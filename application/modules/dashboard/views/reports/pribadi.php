<?php
	$this->load->library('convert');
	$convert = new convert();
	$mainmenu = $this->uri->segment(2);
	$menu = $this->uri->segment(3);
	$submenu = $this->uri->segment(4);
?>

<div class="row">
		<div class="col-md-4 col-sm-6 col-xs-12">
	        <div class="info-box bg-aqua">
	          <span class="info-box-icon"><i class="fa fa-files-o"></i></span>
	          <div class="info-box-content">
	            <span class="info-box-text">SK Sedang Proses</span>
	            <span class="info-box-number"><?php echo isset($jumlah_sk_proses) ? number_format($jumlah_sk_proses,0,"",".") : "0"; ?> Dokumen</span>
	            <div class="progress">
	              <div class="progress-bar" style="width: 100%"></div>
	            </div>
	            <span class="progress-description">
	              Jumlah Pegawai
	            </span>
	          </div><!-- /.info-box-content -->
	        </div><!-- /.info-box -->
	      </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-green">
              <span class="info-box-icon"><i class="fa fa-file-o"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">SK Sudah Terbit</span>
                <span class="info-box-number white"><a href="<?php echo base_url(); ?>admin/kepegawaian/pegawai/listultah" class="show-modal" tooltip="Pegawai Ulang tahun hari ini"><a href="<?php echo base_url(); ?>admin/arsip/sk_ds/viewallpegawai"><?php echo isset($jumlah_sk_selesai) ? $jumlah_sk_selesai : "0"; ?> <small> Dokumen	</small></a></span>
                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Hari ini <?php echo date("d M Y"); ?>
                </span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div>
        <!-- /.col -->
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box bg-red">
              <span class="info-box-icon"><i class="fa fa-folder"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Jumlah SK</span>
                <span class="info-box-number"><?php echo isset($jumlah_all) ? number_format($jumlah_all,0,"",".") : ""; ?>
               <small> Dokumen</small></span>
                <div class="progress">
                  <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description">
                  Jumlah Unit Satuan Kerja
                </span>
              </div><!-- /.info-box-content -->
            </div><!-- /.info-box -->
          </div>
        <!-- /.col -->
</div>

<div class="callout callout-warning">
   <h4>Perubahan data anda</h4>
 </div>
<div class="admin-box box box-danger">
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
			<tr>
				<th style="width:10px">No</th>
				<th>Perubahan</th>
				<th width="70px">Status</th>
			</thead>
		</table>
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
					{"className": "text-center", "targets": [2]},
					{ "targets": [0,1], "orderable": false }
				],
	ajax: {
	  url: "<?php echo base_url() ?>admin/reports/dashboard/getdataupdatemandiri_pribadi",
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

</script>

