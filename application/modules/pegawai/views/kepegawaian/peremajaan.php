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
		<?php echo form_open($this->uri->uri_string(), "id=form_search_pegawai", "form"); ?>
		<style>
			table.filter_pegawai tr td {
				padding-top: 2px;
			}
		</style>
		<table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
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
					<select name="status" class="form-control">
						<option value="">-- Semua --</option>
						<option value="3">Menunggu Verifikasi</option>
						<option value="1">Sudah diverifikasi</option>
						<option value="4">Dibatalkan Pegawai</option>
					</select>
				</td>
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
	<div class="box-body">
		<table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
			<thead>
				<tr>
					<th style="width:10px">No</th>
					<th>Tanggal</th>
					<th>Data</th>
					<th>Status</th>
					<th>Keterangan</th>
					<th width="70px" align="center">#</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script type="text/javascript">
	$("#data").select2({
		placeholder: 'Cari Jenis Data...',
		width: '100%',
		minimumInputLength: 3,
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
	$table = $(".table-data").DataTable({

		dom: "<'row'<'col-sm-6'><'col-sm-6'>>" +
			"<'row'<'col-sm-12'tr>>" +
			"<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
		processing: true,
		serverSide: true,
		"columnDefs": [{
				"className": "text-center",
				"targets": [3,5]
			},
			{
				"targets": [0, 1, 2, 3, 4, 5],
				"orderable": false
			}
		],
		ajax: {
			url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getdataperemajaan",
			type: 'POST',
			"data": function(d) {
				d.search['advanced_search_filters'] = $("#form_search_pegawai").serializeArray();
			}
		}
	});
	$("#form_search_pegawai").submit(function() {
		$table.ajax.reload(null, true);
		return false;
	});
	$('body').on('click', '.btn-delete', function(event) {
		event.preventDefault();
		var kode = $(this).attr("kode");
		swal({
				title: "Anda Yakin?",
				text: "Membatalkan pengajuan perubahan data!",
				type: "warning",
				showCancelButton: true,
				confirmButtonClass: 'btn-danger',
				confirmButtonText: 'Ya, Batalkan!',
				cancelButtonText: "Tidak!",
				closeOnConfirm: false,
				closeOnCancel: false
			},
			function(isConfirm) {
				if (isConfirm) {
					var post_data = "kode=" + kode;
					$.ajax({
						url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/batalupdatemandiri",
						type:"POST",
						data: post_data,
						dataType: "json",
						success: function(result) {
							if (result.success) {
								swal("Pemberitahuan!", "Berhasil dibatalkan", "success");
							} else swal("Pemberitahuan!", result.msg, "error");
							$table.ajax.reload();
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
</script>