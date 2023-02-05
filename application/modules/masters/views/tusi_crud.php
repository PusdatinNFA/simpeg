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

$id = isset($tusi->id) ? $tusi->id : '';

?>
<div class='admin-box box box-danger'>
    <div class="box-header">
        <!-- <h3>Edit Kelompok Jabatan</h3> -->
    </div>
    <?php echo form_open($this->uri->uri_string(), ''); ?>
    <input id='id' type='hidden' class="form-control" required='required' name='id' maxlength='100' value="<?php echo set_value('id', isset($tusi->id) ? $tusi->id : ''); ?>" />
    <div class="box-body">
        <div class="control-group<?php echo form_error('name') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("Tusi", 'name', array('class' => 'control-label')); ?>
            <div class='controls'>
                <input id='name' type='text' class="form-control" required='required' name='name' maxlength='200' value="<?php echo set_value('name', isset($tusi->name) ? $tusi->name : ''); ?>" />
                <span class='help-inline'><?php echo form_error('kel_jabatan'); ?></span>
            </div>
        </div>
    </div>
        <div class="box-footer">
            <fieldset class='form-actions'>
                <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
                <?php echo lang('bf_or'); ?>
                <?php echo anchor('/masters/tusi', "Batal", 'class="btn btn-warning"'); ?>

            </fieldset>
        </div>
        <?php echo form_close(); ?>

    </div>