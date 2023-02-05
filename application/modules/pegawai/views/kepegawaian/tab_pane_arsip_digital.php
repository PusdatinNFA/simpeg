<style>
	.dt-center {
		text-align: center;
	}
</style>
<!--tab-pane-->
<div class="tab-pane active" id="<?php echo $TAB_ID; ?>">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
			<?php if ($this->auth->has_permission('Arsip_digital.Arsip.Create')) : ?>
				<a type="button" class="show-modal-custom btn btn-default btn-warning margin pull-right " href="<?php echo base_url(); ?>admin/arsip/arsip_digital/create/<?php echo $PNS_ID ?>" tooltip="Tambah Arsip Digital">
					<i class="fa fa-plus"></i> Tambah
				</a>
			<?php endif; ?>
			<?php if ($this->auth->has_permission('Arsip_digital.Arsip.CreateMandiri')) : ?>
				<a type="button" class="show-modal-custom btn btn-default btn-info margin pull-right " href="<?php echo base_url(); ?>admin/arsip/arsip_digital/create_mandiri/<?php echo $PNS_ID ?>" tooltip="Tambah Arsip Digital">
					<i class="fa fa-plus"></i> Tambah Mandiri
				</a>
				<?php endif; ?>
		</div>
	</div>

	<div class="form-group">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-21 col-xs-12">
				<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover table-datatablearsip">
					<thead>
						<tr>
							<th width="5px">No</th>
							<th width="40%">Jenis Arsip</th>
							<th>Keterangan</th>
							<th width="30%" align="center">#</th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
</div>
<!--tab-pane-->

<script type="text/javascript">
	(function($) {
		var $container = $("#<?php echo $TAB_ID; ?>");
		var grid_daftararsip = $(".table-datatablearsip", $container).DataTable({
			processing: true,
			"bFilter": true,
			"bLengthChange": false,
			serverSide: true,
			"columnDefs": [{
					"className": "dt-center",
					"targets": [0, 3]
				},
				{
					"targets": [0, 2, 3],
					"orderable": false
				}
			],
			ajax: {
				url: "<?php echo base_url() ?>admin/arsip/arsip_digital/ajax_list",
				type: 'POST',
				data: {
					PNS_ID: '<?php echo $PNS_ID; ?>'
				}
			},
			"fnInitComplete": function(oSettings, json) {
				count_pdm();
			}
		});
		$container.on('click', '.show-modal-custom', function(event) {
			showModalX.call(this, 'sukses-tambah-arsip', function() {
				alert("masuk");
				grid_daftararsip.ajax.reload();
			}, this);
			event.preventDefault();
		});
		//$container.on('click','.show-modal',showModalX);

		$container.on('click', '.btn-hapus_arsip', function(event) {
			event.preventDefault();
			var kode = $(this).attr("kode");
			swal({
					title: "Anda Yakin?",
					text: "Hapus berkas digital!",
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
						var urlarsipdel = "<?php echo base_url() ?>admin/arsip/arsip_digital/deletedata";
						$.ajax({
							url: urlarsipdel,
							data: post_data,
							type: "POST",
							dataType: "html",
							timeout: 180000,
							success: function(result) {
								grid_daftararsip.ajax.reload(null, true);
								swal("Deleted!", result, "success");
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
		$('#btncancelarsip').click(function() {
			$("#modal-custom-global").modal("hide");
		});
		$('body').on('click', '#btnsavearsip', function(event) {
			if (!event.detail || event.detail == 1) { //activate on first click only to avoid hiding again on multiple clicks
				// code here. // It will execute only once on multiple clicks
				submitdatadokumen();
			}
		});
		$('#btnsavevalidasi').click(function(event) {
			if (!event.detail || event.detail == 1) { //activate on first click only to avoid hiding again on multiple clicks
				// code here. // It will execute only once on multiple clicks
				submitdatadokumen();
			}
		});

		function submitdatadokumen() {
			$('#btnsavearsip').prop('disabled', true);
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

				beforeSend: function(xhr) {
					//$("#loading-all").show();
				},
				success: function(response) {
					if (response.success) {
						swal("Sukses", response.msg, "success");
						$("#modal-custom-global").modal("hide");
						$("#modal-global").modal("hide");
						grid_daftararsip.ajax.reload(null, true);
						$('#btnsavearsip').prop('disabled', false);
					} else {
						$(".messagearsip").html(response.msg);
						$('#btnsavearsip').prop('disabled', false);
					}
					$('#btnsavearsip').prop("disabled", false);
				}
			});

			return false;
		}

		function submitvalidasi() {
			$('#btnsavevalidasi').prop('disabled', true);
			var the_data = new FormData(document.getElementById("formarsip"));
			$.ajax({
				url: "<?php echo base_url('admin/arsip/arsip_digital/act_save_validasi'); ?>",
				type: "POST",
				data: the_data,
				enctype: 'multipart/form-data',
				processData: false, // tell jQuery not to process the data
				contentType: false, // tell jQuery not to set contentType
				dataType: 'JSON',

				beforeSend: function(xhr) {
					//$("#loading-all").show();
				},
				success: function(response) {
					if (response.success) {
						swal("Sukses", response.msg, "success");
						$("#modal-custom-global").modal("hide");
						grid_daftararsip.ajax.reload(null, true);
						$('#btnsavevalidasi').prop('disabled', false);
					} else {
						$(".messagearsip").html(response.msg);
						$('#btnsavevalidasi').prop('disabled', false);
					}
					$('#btnsavevalidasi').prop('disabled', false);
				}
			});

			return false;
		}

	})(jQuery);
</script>