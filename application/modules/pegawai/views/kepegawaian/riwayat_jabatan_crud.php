<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/select2/select2.min.css">
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/css/fileinput.css" media="all"
	rel="stylesheet" type="text/css" />
<link href="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.css"
	media="all" rel="stylesheet" type="text/css" />
<?php
$id = isset($detail_riwayat->ID) ? $detail_riwayat->ID : '';
$definitif = true;
if ($mandiri) { ?>
	<div class="callout callout-info">
		<h4>Perhatian</h4>
		<ul>
			<li>Data yang anda masukan tidak otomatis mengubah data simpeg dan akan diverifikasi terlebih dahulu oleh
				kepegawaian.<br>
				Anda dapat mengecek pengajuan perubahan pada menu <b><a
						href="<?= base_url() ?>admin/kepegawaian/pegawai/peremajaan" target="_BLANK">Peremajaan Data</a></b>
			</li>
			<li>Maksimal ukuran berkas yang diunggah sebesar 2MB.</li>
		</ul>
	</div>
	</div>
<?php } ?>
<div class='box box-danger' id="form-riwayat-jabatan-add">
	<div class="box-body">
		<div class="messages">
		</div>
		<?php echo form_open($this->uri->uri_string(), 'id="frmriwayatjabatan"'); ?>
		<fieldset>
			<input type='hidden' class="form-control" name='ID' maxlength='32'
				value="<?php echo set_value('ID', isset($detail_riwayat->ID) ? trim($detail_riwayat->ID) : ''); ?>" />
			<input id='PNS_ID' type='hidden' class="form-control" name='PNS_ID' maxlength='32'
				value="<?php echo set_value('PNS_ID', isset($PNS_ID) ? trim($PNS_ID) : ''); ?>" />
			<input id='NIP_BARU' type='hidden' class="form-control" name='NIP_BARU' maxlength='18'
				value="<?php echo set_value('NIP_BARU', isset($NIP_BARU) ? trim($NIP_BARU) : ''); ?>" />
			<?php if ($definitif): ?>
				<div class="control-group<?php echo form_error('jenis_mutasi') ? ' error' : ''; ?> col-sm-12">
					<?php echo form_label('JENIS MUTASI', 'jenis_mutasi', array('class' => 'control-label')); ?>
					<div class='controls'>
						<?php //echo $detail_riwayat->ID_SATUAN_KERJA; 
							?>
						<select name="jenis_mutasi" id="jenis_mutasi" class="form-control select2  col-sm-12" width="100%">
							<option value="MJ" <?= $detail_riwayat->jenis_mutasi == 'MJ' ? 'selected' : '' ?>>Mutasi Jabatan
							</option>
							<option value="MU" <?= $detail_riwayat->jenis_mutasi == 'MU' ? 'selected' : '' ?>>Mutasi Unor</option>
						</select>
						<span class='help-inline'><?php echo form_error('jenis_mutasi'); ?></span>
					</div>
				</div>
			<?php endif; ?>
			<div class="control-group<?php echo form_error('ID_SATUAN_KERJA') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('SATUAN KERJA', 'ID_SATUAN_KERJA', array('class' => 'control-label')); ?>
				<div class='controls'>
					<?php //echo $detail_riwayat->ID_SATUAN_KERJA; 
					?>
					<select name="ID_SATUAN_KERJA" id="ID_SATUAN_KERJA" class="form-control select2  col-sm-12"
						width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)): ?>
							<?php foreach ($recsatker as $record): ?>
								<option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_SATUAN_KERJA))
									   echo (trim($detail_riwayat->ID_SATUAN_KERJA) == trim($record->KODE_INTERNAL)) ? "selected" : ""; ?>>[<?php echo $record->ID ?>] <?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
						<option value="N">Tidak ada</option>
					</select>
					<span class='help-inline'><?php echo form_error('ID_SATUAN_KERJA'); ?></span>
				</div>
			</div>

			<div class="control-group col-sm-12">
				<label for="inputNAMA" class="control-label">UNOR (Unit Organisasi) Terkecil/Kelompok Fungsi</label>
				<div class='controls'>
					<select id="ID_UNOR" name="ID_UNOR" width="100%" class="select2 col-md-10 format-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recunor) && is_array($recunor) && count($recunor)): ?>
							<?php foreach ($recunor as $record): ?>
								<option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_UNOR))
									   echo (trim($detail_riwayat->ID_UNOR) == trim($record->ID)) ? "selected" : ""; ?> 		<?php if (isset($detail_riwayat->ID_JENIS_JABATAN))
															echo ($jenis_jabatan == $record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<span class='help-inline'>Contoh : Subkelompok Fungsi Pengembangan Interoperabilitas/Web Services
						(Backend)</span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('GOLONGAN') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('Jenis Jabatan', 'Jenis Jabatan', array('class' => 'control-label')); ?>
				<div class='controls'>
					<select name="ID_JENIS_JABATAN" id="ID_JENIS_JABATAN" class="form-control">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($jenis_jabatans) && is_array($jenis_jabatans) && count($jenis_jabatans)): ?>
							<?php foreach ($jenis_jabatans as $record): ?>
								<option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_JENIS_JABATAN))
									   echo (trim($detail_riwayat->ID_JENIS_JABATAN) == $record->ID) ? "selected" : ""; ?> 		<?php if (isset($jenis_jabatan))
															echo ($jenis_jabatan == $record->ID) ? "selected" : ""; ?>>
									<?php echo $record->NAMA; ?>
								</option>
							<?php endforeach; ?>
						<?php endif; ?>
					</select>
					<span class='help-inline'><?php echo form_error('ID_JENIS_JABATAN'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('ID_JABATAN') ? ' error' : ''; ?> col-sm-6">
				<?php echo form_label('JABATAN', 'ID_JABATAN', array('class' => 'control-label')); ?>
				<div class='controls'>
					<div class='slcjabatan'>
						<select name="ID_JABATAN" id="ID_JABATAN" class="form-control select2  col-sm-12 slcjabatan"
							width="100%">
							<option value="">-- Silahkan Pilih --</option>
							<?php if (isset($jabatans) && is_array($jabatans) && count($jabatans)): ?>
								<?php foreach ($jabatans as $record): ?>
									<option value="<?php echo $record->KODE_JABATAN ?>" <?php if (isset($detail_riwayat->ID_JABATAN))
										   echo (trim($detail_riwayat->ID_JABATAN) == trim($record->KODE_JABATAN)) ? "selected" : ""; ?>>
										[<?php echo $record->KODE_JABATAN ?>] <?php echo $record->NAMA_JABATAN; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						</select>
					</div>
					<span class='help-inline'><?php echo form_error('ID_JABATAN'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('status_jabatan') ? ' error' : ''; ?> col-sm-3">
				<?php echo form_label('Status Struktural', 'status_jabatan', array('class' => 'control-label')); ?>
				<div class='controls'>
					<select name="status_jabatan" id="status_jabatan" class="form-control select2  col-sm-12"
						width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<option value="Plt." <?php echo $detail_riwayat->status_jabatan == 'Plt.' ? "selected" : ''; ?>>
							Plt.</option>
						<option value="Definitif" <?php echo $detail_riwayat->status_jabatan == 'Definitif' ? "selected" : ''; ?>>Definitif</option>
					</select>
					<span class='help-inline'><?php echo form_error('status_jabatan'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('eselon') ? ' error' : ''; ?> col-sm-3">
				<?php echo form_label('Eselon', 'eselon', array('class' => 'control-label')); ?>
				<div class='controls'>
					<select name="ESELON" id="ESELON" class="form-control select2  col-sm-4" width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php foreach ($eselons as $eselon) { ?>
							<option value="<?= $eselon->kode ?>" <?= $detail_riwayat->ESELON == $eselon->kode ? 'selected' : '' ?>>
								<?= $eselon->kode ?> 	<?= $eselon->jabatan ? ' (' . $eselon->jabatan . ')' : '' ?>
							</option>
						<?php } ?>
					</select>
					<!-- <input type='text' class="form-control pull-right" name='ESELON' value="<?php echo set_value('ESELON', isset($detail_riwayat->ESELON) ? trim($detail_riwayat->ESELON) : ''); ?>" /> -->
					<span class='help-inline'><?php echo form_error('ESELON'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('ID_SATKER_PENUGASAN') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('SATUAN KERJA PENUGASAN', 'ID_SATKER_PENUGASAN', array('class' => 'control-label')); ?>
				<div class='controls'>
					<?php //echo $detail_riwayat->ID_SATUAN_KERJA; 
					?>
					<select name="ID_SATKER_PENUGASAN" id="ID_SATKER_PENUGASAN" class="form-control select2  col-sm-12"
						width="100%">
						<option value="">-- Silahkan Pilih --</option>
						<?php if (isset($recsatker) && is_array($recsatker) && count($recsatker)): ?>
							<?php foreach ($recsatker as $record): ?>
								<option value="<?php echo $record->ID ?>" <?php if (isset($detail_riwayat->ID_SATKER_PENUGASAN))
									   echo (trim($detail_riwayat->ID_SATKER_PENUGASAN) == trim($record->ID)) ? "selected" : ""; ?>>
									[<?php echo $record->ID ?>] <?php echo $record->NAMA_UNOR; ?></option>
							<?php endforeach; ?>
						<?php endif; ?>
						<option value="N">Tidak ada</option>
					</select>
					<span class='help-inline'>Silahkan masukan satuan kerja penugasan, jika ditugaskan diluar satker
						aslinya (Contoh jabatan : Sekretaris)</span>
				</div>
			</div>
			<?php if ($definitif) : ?>
			<div class="control-group col-sm-6" id="div_mutasi">
				<label for="inputNAMA" class="control-label">TMT MUTASI</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type='text' class="form-control pull-right datepicker" name='tmt_mutasi' value="<?php echo set_value('tmt_mutasi', isset($detail_riwayat->tmt_mutasi) ? $detail_riwayat->tmt_mutasi : ''); ?>" />
					<span class='help-inline'><?php echo form_error('tmt_mutasi'); ?></span>
				</div>
			</div>
			<?php endif; ?>
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">TMT JABATAN</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_JABATAN'
						value="<?php echo set_value('TMT_JABATAN', isset($detail_riwayat->TMT_JABATAN) ? $detail_riwayat->TMT_JABATAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_JABATAN'); ?></span>
				</div>
			</div>

			<div class="control-group<?php echo form_error('NOMOR_SK') ? ' error' : ''; ?> col-sm-9">
				<?php echo form_label("SK NOMOR", 'SK NOMOR', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input id='NOMOR_SK' type='text' class="form-control" name='NOMOR_SK' maxlength='32'
						value="<?php echo set_value('NOMOR_SK', isset($detail_riwayat->NOMOR_SK) ? trim($detail_riwayat->NOMOR_SK) : ''); ?>" />
					<span class='help-inline'><?php echo form_error('NOMOR_SK'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">SK TANGGAL</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type='text' class="form-control pull-right datepicker" name='TANGGAL_SK'
						value="<?php echo set_value('TANGGAL_SK', isset($detail_riwayat->TANGGAL_SK) ? $detail_riwayat->TANGGAL_SK : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TANGGAL_SK'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-9">
				<label for="inputNAMA" class="control-label">TMT PELANTIKAN</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type='text' class="form-control pull-right datepicker" name='TMT_PELANTIKAN'
						value="<?php echo set_value('TMT_PELANTIKAN', isset($detail_riwayat->TMT_PELANTIKAN) ? $detail_riwayat->TMT_PELANTIKAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TMT_PELANTIKAN'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-3">
				<label for="inputNAMA" class="control-label">TGL PEMBERHENTIAN</label>
				<div class="input-group date">
					<div class="input-group-addon">
						<i class="fa fa-calendar"></i>
					</div>
					<input type='text' class="form-control pull-right datepicker" name='TERMINATED_DATE'
						value="<?php echo set_value('TERMINATED_DATE', isset($detail_riwayat->TERMINATED_DATE) ? $detail_riwayat->TERMINATED_DATE : ''); ?>" />
					<span class='help-inline'><?php echo form_error('TERMINATED_DATE'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-9">
				<label for="inputNAMA" class="control-label">NO SK PEMBERHENTIAN</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right" name='SK_PEMBERHENTIAN_NOMOR'
						value="<?php echo set_value('SK_PEMBERHENTIAN_NOMOR', isset($detail_riwayat->SK_PEMBERHENTIAN_NOMOR) ? $detail_riwayat->SK_PEMBERHENTIAN_NOMOR : ''); ?>" />
					<span class='help-inline'><?php echo form_error('SK_PEMBERHENTIAN_NOMOR'); ?></span>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<legend>Berkas</legend>
			<div class="control-group col-sm-8">
				<label for="inputNAMA" class="control-label">Pengangkatan</label>
				<div class='controls'>
					<div id="form_upload">
						<input id="file_dokumen" name="file_dokumen" class="file" type="file" data-show-upload="false"
							data-preview-file-type="pdf">
					</div>

				</div>
			</div>
			<div class="control-group col-sm-4">
				<label for="inputNAMA" class="control-label"><br></label>
				<div class='controls'>
					<div class=" btn-group">
						<?php if (!$read_only) { ?>
							<?php $kode_arsip = uniqid(); ?>
							<a href="#" kode='<?= $kode_arsip ?>' class="btn btn-sm btn-success btn-daftar-arsip"
								data-toggle='tooltip' tooltip="Pilih dokumen" title="Pilih dokumen"><span
									class="fa fa-search"></span> pilih</a>
							<a href="#" id='view_<?= $kode_arsip ?>' class="btn btn-sm btn-info show-modal hide"
								data-toggle='tooltip' tooltip="Lihat dokumen baru" title="Lihat dokumen baru"><span
									class="fa fa-file-o"></span> lihat</a>
						<?php } ?>
						<input type="text" name='id_arsip' id='<?= $kode_arsip ?>' class="hide"
							value="<?= $detail_riwayat->id_arsip ?>">
						<?php if (isset($detail_riwayat->id_arsip)) { ?>
							<a href="<?php echo base_url(); ?>admin/arsip/arsip_digital/viewdoc2/<?php echo $detail_riwayat->id_arsip; ?>"
								data-toggle='tooltip' class="btn btn-sm btn-warning show-modal" tooltip="Lihat dokumen"
								title="Lihat dokumen"><span class="fa fa-file-o"></span> Lihat</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<div class="control-group col-sm-8">
				<label for="inputNAMA" class="control-label">Pemberhentian</label>
				<div class='controls'>
					<div id="form_upload">
						<input id="file_dokumen2" name="file_dokumen2" class="file" type="file" data-show-upload="false"
							data-preview-file-type="pdf">
					</div>

				</div>
			</div>
			<div class="control-group col-sm-4">
				<label for="inputNAMA" class="control-label"><br></label>
				<div class='controls'>
					<div class=" btn-group">
						<?php if (!$read_only) { ?>
							<?php $kode_arsip = uniqid(); ?>
							<a href="#" kode='<?= $kode_arsip ?>' class="btn btn-sm btn-success btn-daftar-arsip"
								data-toggle='tooltip' tooltip="Pilih dokumen" title="Pilih dokumen"><span
									class="fa fa-search"></span> pilih</a>
							<a href="#" id='view_<?= $kode_arsip ?>' class="btn btn-sm btn-info show-modal hide"
								data-toggle='tooltip' tooltip="Lihat dokumen baru" title="Lihat dokumen baru"><span
									class="fa fa-file-o"></span> lihat</a>
						<?php } ?>
						<input type="text" name='id_arsip_pemberhentian' id='<?= $kode_arsip ?>' class="hide"
							value="<?= $detail_riwayat->id_arsip_pemberhentian ?>">
						<?php if (isset($detail_riwayat->id_arsip_pemberhentian)) { ?>
							<a href="<?php echo base_url(); ?>admin/arsip/arsip_digital/viewdoc2/<?php echo $detail_riwayat->id_arsip_pemberhentian; ?>"
								data-toggle='tooltip' class="btn btn-sm btn-warning show-modal" tooltip="Lihat dokumen"
								title="Lihat dokumen"><span class="fa fa-file-o"></span> Lihat</a>
						<?php } ?>
					</div>
				</div>
			</div>
			<input type="text" class="hide" name='mandiri' value="<?= $mandiri ? $mandiri : '' ?>">
			<input type="text" class="hide" name='id_update_mandiri'
				value="<?= $id_update_mandiri ? $id_update_mandiri : '' ?>">
		</fieldset>
		<fieldset id="field_jft">
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">NO PAK</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right" name='nomor_pak'
						value="<?php echo set_value('nomor_pak', isset($detail_riwayat->nomor_pak) ? $detail_riwayat->nomor_pak : ''); ?>" />
					<span class='help-inline'><?php echo form_error('nomor_pak'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Tanggal PAK</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right datepicker" name='tanggal_pak'
						value="<?php echo set_value('tanggal_pak', isset($detail_riwayat->tanggal_pak) ? $detail_riwayat->tanggal_pak : ''); ?>" />
					<span class='help-inline'><?php echo form_error('tanggal_pak'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Nilai Angka Kredit</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right" name='ANGKA_KREDIT'
						value="<?php echo set_value('ANGKA_KREDIT', isset($detail_riwayat->ANGKA_KREDIT) ? $detail_riwayat->ANGKA_KREDIT : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ANGKA_KREDIT'); ?></span>
				</div>
			</div>
			<div class="control-group col-sm-6">
				<label for="inputNAMA" class="control-label">Bidang Kepakaran</label>
				<div class='controls'>
					<input type='text' class="form-control pull-right" name='kepakaran'
						value="<?php echo set_value('kepakaran', isset($detail_riwayat->kepakaran) ? $detail_riwayat->kepakaran : ''); ?>" />
					<span class='help-inline'><?php echo form_error('kepakaran'); ?></span>
				</div>
				<span class='help-inline'>* Khusus Peneliti</span>
			</div>
		</fieldset>
		<fieldset>
			<legend>Jika tidak ada</legend>
			<div class="control-group<?php echo form_error('INSTANSI') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('INSTANSI', 'INSTANSI', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input type='text' class="form-control" name='INSTANSI'
						value="<?php echo set_value('INSTANSI', isset($detail_riwayat->INSTANSI) ? $detail_riwayat->INSTANSI : ''); ?>" />
					<span class='help-inline'><?php echo form_error('INSTANSI'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('eselon1') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('ESELON 1', 'ESELON1', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input type='text' class="form-control" name='ESELON1'
						value="<?php echo set_value('ESELON1', isset($detail_riwayat->ESELON1) ? $detail_riwayat->ESELON1 : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('eselon2') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('ESELON 2', 'eselon2', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input type='text' class="form-control" name='ESELON2'
						value="<?php echo set_value('ESELON2', isset($detail_riwayat->ESELON2) ? $detail_riwayat->ESELON2 : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON1'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('ESELON3') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('ESELON 3', 'ESELON3', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input type='text' class="form-control" name='ESELON3'
						value="<?php echo set_value('ESELON3', isset($detail_riwayat->ESELON3) ? $detail_riwayat->ESELON3 : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON3'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('ESELON4') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('ESELON 4', 'ESELON4', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input type='text' class="form-control" name='ESELON4'
						value="<?php echo set_value('ESELON4', isset($detail_riwayat->ESELON4) ? $detail_riwayat->ESELON4 : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON4'); ?></span>
				</div>
			</div>
			<div class="control-group<?php echo form_error('NAMA_JABATAN') ? ' error' : ''; ?> col-sm-12">
				<?php echo form_label('NAMA JABATAN', 'NAMA_JABATAN', array('class' => 'control-label')); ?>
				<div class='controls'>
					<input type='text' class="form-control" id='NAMA_JABATAN' name='NAMA_JABATAN'
						value="<?php echo set_value('NAMA_JABATAN', isset($detail_riwayat->NAMA_JABATAN) ? $detail_riwayat->NAMA_JABATAN : ''); ?>" />
					<span class='help-inline'><?php echo form_error('ESELON4'); ?></span>
				</div>
			</div>
		</fieldset>
	</div>
	<?php if (!$read_only) { ?>
		<div class="box-footer">
			<div class="messages">
			</div>
			<a href="javascript:;" id="btnsave_riwayatjabatan" class="btn green btn-primary button-submit"> Simpan Data
				<i class="fa fa-save"></i>
			</a>
		</div>
	<?php } ?>
	<?php echo form_close(); ?>
</div>
<script src="<?php echo base_url(); ?>themes/admin/plugins/datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/themes/explorer/theme.js"
	type="text/javascript"></script>
<script src="<?php echo base_url(); ?>themes/admin/plugins/bootstrap-fileinput-master/js/fileinput.js"
	type="text/javascript"></script>
<script>
	var jenis_jabatan = '<?php echo isset($detail_riwayat->ID_JENIS_JABATAN) ? $detail_riwayat->ID_JENIS_JABATAN : 0; ?>'
	if (jenis_jabatan == 0) {
		jenis_jabatan = '<?php echo $jenis_jabatan ?>'
	}
	if (jenis_jabatan == 2) {
		$("#field_jft").show()
	} else {
		$("#field_jft").hide()
	}
	$(".select2").select2({
		width: '100%',
		dropdownParent: $("#modal-custom-global")
	});
</script>
<script>
	$('#ID_SATUAN_KERJA').change(function () {
		var valuesatker = $('#ID_SATUAN_KERJA').val();
		$("#ID_UNOR").empty().append("<option>loading...</option>"); //show loading...

		var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getbysatker?satker=" + encodeURIComponent(valuesatker);
		$.getJSON(json_url, function (data) {
			$("#ID_UNOR").empty();
			if (data == "") {
				$("#ID_UNOR").append("<option value=\"\">Silahkan Pilih </option>");
			} else {
				$("#ID_UNOR").append("<option value=\"\">Silahkan Pilih</option>");
				for (i = 0; i < data.id.length; i++) {
					$("#ID_UNOR").append("<option value=\"" + data.id[i] + "\">" + data.nama[i] + "</option>");
				}
			}

		});
		// $("#ID_UNOR").select2("updateResults");
		return false;
	});
	$('#ID_UNOR').change(function () {
		var val = $('#ID_UNOR').val();

		// var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getnamajabatan_byid?unor=" + encodeURIComponent(val);
		var json_url = "<?php echo base_url(); ?>pegawai/manage_unitkerja/getsatkerpenugasan?unor=" + encodeURIComponent(val);
		$.ajax({
			type: "POST",
			url: json_url,
			data: "",
			dataType: "html",
			success: function (data) {
				//alert(data);
				var valuejenisjabatan = $('#ID_JENIS_JABATAN').val();
				// if(valuejenisjabatan == "1"){
				// 	$('.slcjabatan').hide();
				// 	$('.divjabatan').html(data);
				// 	$('#NAMA_JABATAN').val(data) ;
				// }else{
				// 	$('.slcjabatan').show();
				// 	$('.divjabatan').html("");
				// }
				if (data) {
					$('#ID_SATKER_PENUGASAN').val(data).trigger('change');
				}
			}
		});

	});
	/*
		$("#ID_UNOR").select2({
				placeholder: 'Cari Unit Kerja...',
				width: '100%',
				minimumInputLength: 3,
				allowClear: true,
				ajax: {
					url: '<?php echo site_url("pegawai/duk/ajax_unit_list"); ?>',
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
		*/
	$('#ID_JENIS_JABATAN').change(function () {
		var valuejenisjabatan = $('#ID_JENIS_JABATAN').val();
		if (valuejenisjabatan == 2) {
			$("#field_jft").show()
		} else {
			$("#field_jft").hide()
		}
		$("#ID_JABATAN").empty().append("<option>loading...</option>"); //show loading...

		var json_url = "<?php echo base_url(); ?>admin/masters/jabatan/getbyjenis?jenis=" + encodeURIComponent(valuejenisjabatan);
		//alert(json_url);
		$.getJSON(json_url, function (data) {
			$("#ID_JABATAN").empty();
			if (data == "") {
				$("#ID_JABATAN").append("<option value=\"\">Silahkan Pilih </option>");
			} else {
				$("#ID_JABATAN").append("<option value=\"\">Silahkan Pilih</option>");
				for (i = 0; i < data.id.length; i++) {
					$("#ID_JABATAN").append("<option value=\"" + data.id[i] + "\">" + data.nama[i] + "</option>");
				}
			}

		});
		$("#ID_JABATAN").select2("updateResults");
		return false;
	});
	var form = $("#form-riwayat-jabatan-add");
	//Date picker
	$('.datepicker').datepicker({
		autoclose: true,
		format: 'yyyy-mm-dd'
	}).on("input change", function (e) {
		var date = $(this).datepicker('getDate');
		if (date) $("[name=TAHUN]", form).val(date.getFullYear());
	});
</script>
<script>
	$("#btnsave_riwayatjabatan").click(function () {
		$('#btnsave_riwayatjabatan').text('Sedang Mengirim...');
		$('#btnsave_riwayatjabatan').prop('disabled', true);
		submitdatafrmriwayatjabatan();
		return false;
	});
	$("#frmA").submit(function () {
		submitdatafrmriwayatjabatan();
		return false;
	});

	function submitdatafrmriwayatjabatan() {
		var the_data = new FormData(document.getElementById("frmriwayatjabatan"));
		var json_url = "<?php echo base_url() ?>pegawai/riwayatjabatan/save";
		$.ajax({
			type: "POST",
			url: json_url,
			data: the_data,
			dataType: "json",
			processData: false, // tell jQuery not to process the data
			contentType: false, // tell jQuery not to set contentType
			success: function (data) {
				if (data.success) {
					swal("Pemberitahuan!", data.msg, "success");
					$("#modal-custom-global").trigger("sukses-tambah-riwayat-jabatan");
					$("#modal-custom-global").trigger("sukses-tambah-riwayat-pindah_unit_kerja");
					$("#modal-custom-global").modal("hide");
					$("#form-riwayat-jabatan-add .messages").empty();
					$("#modal-global").modal("hide");
					$('#btnsave_riwayatjabatan').prop('disabled', false);
					$('#btnsave_riwayatjabatan').text('Kirimkan Data');
					$grid_daftar_pindahunit.ajax.reload();
				} else {
					$('#btnsave_riwayatjabatan').prop('disabled', false);
					$('#btnsave_riwayatjabatan').text('Kirimkan Data');
					$("#form-riwayat-jabatan-add .messages").empty().append(data.msg);
				}
			}
		});
		return false;
	}
</script>