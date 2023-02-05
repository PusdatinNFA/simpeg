
<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
			<?php if ($this->auth->has_permission('Data_keluarga.Kepegawaian.Sinkron')) : ?>
            	<a class="btn btn-default btn-success margin pull-right" id="lihat_data_anak" kode="<?php echo $PNS_ID ?>" tooltip="Sinkron dengan BKN">
				<i class="fa fa-refresh"></i> Sinkron BKN
            	</a>
            <?php endif; ?>
            <?php if ($this->auth->has_permission('Data_keluarga.Kepegawaian.Create')) : ?>
            <a type="button" class="modal-custom-global btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/data_keluarga/addanak/<?php echo $PNS_ID ?>" tooltip="Tambah Data anak" title="Tambah  Data anak">
				<i class="fa fa-plus"></i> Tambah
            </a>
            <?php endif; ?>
			<?php if ($this->auth->has_permission('Data_keluarga.Kepegawaian.CreateMandiri')) : ?>
            <a type="button" class="modal-custom-global btn btn-default btn-info margin pull-right " href="<?php echo base_url(); ?>pegawai/data_keluarga/addanakmandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Data Anak" title="Tambah  Data Anak">
				<i class="fa fa-plus"></i> Tambah Mandiri
            </a>
            <?php endif; ?>
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
            <thead>
                <tr>
                    <th width='20px' >No</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tgl Lahir</th>
                    <th>Tempat Lahir</th>
                    <th>Status Anak</th>
                    <th width='10%' align="center">AKSI</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                        
                </tr>
            </tfoot>
            <tbody>
               
            </tbody>
        </table>  
        </div>
        </div>
    </div>
</div>
<!--tab-pane-->


<script type="text/javascript">

	(function($){
		var $container = $("#<?php echo $TAB_ID;?>");
		grid_data_anak = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					//{"className": "dt-center", "targets": "_all"}
					{"className": "dt-center", "targets": [0,2]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/data_keluarga/ajax_list_anak",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal',function(event){
			showmodalnew.call(this,'sukses-tambah-anak',function(){
				grid_data_anak.ajax.reload();
			},this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus-anak',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data anak!",
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
								url: "<?php echo base_url() ?>pegawai/data_keluarga/deleteanak/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di hapus!", result, "success");
									grid_data_anak.ajax.reload();
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

		$container.on('click','#lihat_data_anak',function(event){
		var kode =$(this).attr("kode");
		swal({
			title: "Anda Yakin?",
			text: "Lihat data anak berdasarkan data BKN!",
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
				var post_data = "kode="+kode;
				$.ajax({
						url: "<?php echo base_url() ?>pegawai/bkn/viewanak",
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
		  							$("#myModalLabel").html("Data Keluarga BKN");
		                            $("#modal-global").modal('show');
		                        });
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
	});	
				
	})(jQuery);
</script>
