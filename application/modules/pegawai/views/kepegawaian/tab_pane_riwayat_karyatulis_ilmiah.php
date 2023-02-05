<style>
	.dt-center {
		text-align: center;
	}
</style>
<!--tab-pane-->
<div class="tab-pane" id="<?php echo $TAB_ID; ?>">
	<div class="form-group">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
				<div class="col-lg-12 col-md-12 col-sm-21 col-xs-12 pull-right">
				<?php if ($this->auth->has_permission('RiwayatKaryatulisIlmiah.Kepegawaian.Create')) : ?>
					<a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatkaryatulisilmiah/add/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Karya Tulis Ilmiah">
						<i class="fa fa-plus"></i> Tambah
					</a>
				<?php endif; ?>
				<?php if ($this->auth->has_permission('RiwayatKaryatulisIlmiah.Kepegawaian.UpdateMandiri')) : ?>
					<a type="button" class="show-modal-custom btn btn-default btn-info margin pull-right " href="<?php echo base_url(); ?>pegawai/riwayatkaryatulisilmiah/addmandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Riwayat Karya Tulis Ilmiah">
						<i class="fa fa-plus"></i> Tambah Mandiri
					</a>
				<?php endif; ?>
				</div>
				<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-datatable table-hover" style="width: 100%;">
					<thead>
						<tr>
							<th width='15px'>No</th>
							<th width='300px'>Judul</th>
							<th>Tahun</th>
							<th>Skala</th>
							<th width="30%">Keterangan</th>
							<th width='50px' align="center">Aksi</th>
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
	(function($) {
		var $container = $("#<?php echo $TAB_ID; ?>");
		var grid_daftar = $(".table-datatable", $container).DataTable({
			ordering: false,
			processing: true,
			"bFilter": false,
			"bLengthChange": false,
			serverSide: true,
			"columnDefs": [
				//{"className": "dt-center", "targets": "_all"}
				{
					"className": "text-center",
					"targets": [0, 2, 3, 4, 5]
				}
			],
			ajax: {
				url: "<?php echo base_url() ?>pegawai/riwayatkaryatulisilmiah/ajax_list",
				type: 'POST',
				data: {
					PNS_ID: '<?php echo $PNS_ID; ?>'
				}
			}
		});
		$container.on('click', '.show-modal-custom', function(event) {
			showModalX.call(this, 'sukses-tambah-riwayat-karyatulis-ilmiah', function() {
				grid_daftar.ajax.reload();
			}, this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click', '.btn-hapus-karyatulis', function(event) {
			event.preventDefault();
			var kode = $(this).attr("kode");
			swal({
					title: "Anda Yakin?",
					text: "Hapus data Riwayat Karya Tulis Ilmiah!",
					type: "warning",
					showCancelButton: true,
					confirmButtonClass: 'btn-danger',
					confirmButtonText: 'Ya, Hapus!',
					cancelButtonText: "Tidak, Batalkan!",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						var post_data = "kode=" + kode;
						$.ajax({
							url: "<?php echo base_url() ?>pegawai/riwayatkaryatulisilmiah/delete/" + kode,
							dataType: "html",
							timeout: 180000,
							success: function(result) {
								swal("Data berhasil di hapus!", result, "success");
								grid_daftar.ajax.reload();
							},
							error: function(error) {
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