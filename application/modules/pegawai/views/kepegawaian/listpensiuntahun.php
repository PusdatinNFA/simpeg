
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
						 <th>Jabatan <br>(BUP)</th>
						 <th>Tgl Lahir</th><th>Umur</th><th>Unit Kerja</th>
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
$(".table-data").DataTable({
	ordering: false,
	processing: true,
	serverSide: true,
	ajax: {
	  url: "<?php echo base_url() ?>admin/kepegawaian/pegawai/getdatapensiuntahun/<?php echo $tingkat ?>",
	  type:'POST',
	}
});
</script>