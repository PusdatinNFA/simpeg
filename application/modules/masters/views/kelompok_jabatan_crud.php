<?php

if (validation_errors()) :
?>
    <div class='alert alert-block alert-error fade in'>
        <a class='close' data-dismiss='alert'>&times;</a>
        <h4 class='alert-heading'>
            <?php echo lang('jabatan_errors_message'); ?>
        </h4>
        <?php echo validation_errors(); ?>
    </div>
<?php
endif;

$id = isset($kelompok_jabatan->id) ? $kelompok_jabatan->id : '';

?>
<div class='admin-box box box-danger'>
    <div class="box-header">
        <!-- <h3>Edit Kelompok Jabatan</h3> -->
    </div>
    <?php echo form_open($this->uri->uri_string(), ''); ?>
    <input id='id' type='hidden' class="form-control" required='required' name='id' maxlength='100' value="<?php echo set_value('id', isset($kelompok_jabatan->id) ? $kelompok_jabatan->id : ''); ?>" />
    <div class="box-body">
        <div class="control-group<?php echo form_error('kel_jabatan') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("KELOMPOK JABATAN", 'kel_jabatan', array('class' => 'control-label')); ?>
            <div class='controls'>
                <input id='kel_jabatan' type='text' class="form-control" required='required' name='kel_jabatan' maxlength='200' value="<?php echo set_value('kel_jabatan', isset($kelompok_jabatan->kel_jabatan) ? $kelompok_jabatan->kel_jabatan : ''); ?>" />
                <span class='help-inline'><?php echo form_error('kel_jabatan'); ?></span>
            </div>
        </div>
        <div class="control-group<?php echo form_error('bkn_id') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("KODE BKN", 'bkn_id', array('class' => 'control-label')); ?>
            <div class='controls'>
                <input id='bkn_id' type='text' class="form-control" name='bkn_id' maxlength='100' value="<?php echo set_value('bkn_id', isset($kelompok_jabatan->bkn_id) ? $kelompok_jabatan->bkn_id : ''); ?>" />
                <span class='help-inline'><?php echo form_error('bkn_id'); ?></span>
            </div>
        </div>
        <div class="control-group<?php echo form_error('pembina_id') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("PEMBINA ID", 'bkn_id', array('class' => 'control-label')); ?>
            <div class='controls'>
                <input id='pembina_id' type='text' class="form-control" name='pembina_id' maxlength='100' value="<?php echo set_value('pembina_id', isset($kelompok_jabatan->pembina_id) ? $kelompok_jabatan->pembina_id : ''); ?>" />
                <span class='help-inline'><?php echo form_error('pembina_id'); ?></span>
            </div>
        </div>
    </div>
        <div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor('/masters/kelompokjabatan', "Batal", 'class="btn btn-warning"'); ?>

            </fieldset>
        </div>
        <?php echo form_close(); ?>

    </div>