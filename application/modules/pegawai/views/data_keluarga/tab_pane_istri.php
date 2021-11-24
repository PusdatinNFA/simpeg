
<style>
	.dt-center {
		text-align:center;
	}
</style>
<?php
$jk = ISSET($pegawai->JENIS_KELAMIN) ? trim($pegawai->JENIS_KELAMIN) : "";
$hubungan = $jk == "M" ? "Istri" : "Suami";
?>
<!--tab-pane-->
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
            <?php if ($this->auth->has_permission('Data_keluarga.Kepegawaian.Create')) : ?>
            <a type="button" class="show-modal btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/data_keluarga/addistri/<?php echo $PNS_ID ?>" tooltip="Tambah Data istri" title="Tambah  Data istri">
				<i class="fa fa-plus"></i> Tambah
            </a>
            <?php endif; ?>
            <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover">
            <thead>
                <tr>
                    <th width='5%' ><?php echo $hubungan; ?> Ke</th>
                    <th>Nama <?php echo $hubungan; ?> </th>
                    <th>Status</th>
                    <th width='100px' >Pns</th>
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
		var grid_daftaristri = $(".table-datatable",$container).DataTable({
				ordering: false,
				processing: true,
				"bFilter": false,
				"bLengthChange": false,
				serverSide: true,
				"columnDefs": [
					{"className": "dt-center", "targets": [0,3,4]}
				],
				ajax: {
					url: "<?php echo base_url() ?>pegawai/data_keluarga/ajax_list_istri",
					type:'POST',
					data : {
						PNS_ID:'<?php echo $PNS_ID;?>'
					}
				}
		});
		$container.on('click','.show-modal',function(event){
			showmodalnew.call(this,'sukses-tambah-istri',function(){
				grid_daftaristri.ajax.reload();
			},this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click','.btn-hapus-istri',function(event){
			event.preventDefault();
			var kode =$(this).attr("kode");
				swal({
					title: "Anda Yakin?",
					text: "Hapus data <?php echo $hubungan ?>!",
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
								url: "<?php echo base_url() ?>pegawai/data_keluarga/deleteistri/"+kode,
								dataType: "html",
								timeout:180000,
								success: function (result) {
									swal("Data berhasil di hapus!", result, "success");
									grid_daftaristri.ajax.reload();
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
				
	})(jQuery);
</script>
