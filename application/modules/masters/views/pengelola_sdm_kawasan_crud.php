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

$id = isset($pengelolasdm->id) ? $pengelolasdm->id : '';

?>
<div class='admin-box box box-danger'>
    <div class="box-header">
        <!-- <h3>Edit Kelompok Jabatan</h3> -->
    </div>
    <?php echo form_open($this->uri->uri_string(), ''); ?>
    <input id='id' type='hidden' class="form-control" required='required' name='id' maxlength='100' value="<?php echo set_value('id', isset($pengelolasdm->id) ? $pengelolasdm->id : ''); ?>" />
    <div class="box-body">
        <div class="control-group<?php echo form_error('nama') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("Nama", 'nama', array('class' => 'control-label')); ?>
            <div class='controls'>
                <input id='nama' type='text' class="form-control" required='required' name='nama' maxlength='200' value="<?php echo set_value('nama', isset($pengelolasdm->nama) ? $pengelolasdm->nama : ''); ?>" />
                <span class='help-inline'><?php echo form_error('nama'); ?></span>
            </div>
        </div>
        <div class="control-group<?php echo form_error('rule') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("Rule", 'rule', array('class' => 'control-label')); ?>
            <div class='controls'>
                <select class="form-control" name="rule" id="rule">
                    <?php foreach ($rules as $rulex) { 
                        if ($isNew){
                            $selected = $rulex == $rule? 'selected':'disabled';
                        } else {
                            $selected = $rulex == $rule? 'selected':'';
                        }
                        ?>
                        <option value="<?=$rulex?>" <?=$selected?>><?=$rulex?></option>
                    <?php } ?>
                </select>
                <span class='help-inline'><?php echo form_error('rule'); ?></span>
            </div>
        </div>
        <div class="control-group<?php echo form_error('nama') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("Kawasan", 'nama', array('class' => 'control-label')); ?>
            <div class='controls'>
            <select name="kawasans[]" id="kawasans" multiple="multiple" class="select2 col-md-10 form-control">
                    <?php
                        if (isset($kawasans) && is_array($kawasans) && count($kawasans)) :
                            foreach ($kawasans as $kawasan) :
                                $selected = '';
                                $bool =  in_array( $kawasan->id, $akawasans );
                                if($bool){
                                    $selected = "selected";
                                }
                                echo "<option $selected value='$kawasan->id'>" . strtoupper($kawasan->name) . "</option>";
                            endforeach;
                        endif;
                        ?>                     
                    </select>
                <span class='help-inline'><?php echo form_error('satuan_kerja'); ?></span>
            </div>
        </div>
        <div class="control-group<?php echo form_error('nama') ? ' error' : ''; ?> col-sm-12">
            <?php echo form_label("Satuan Kerja", 'nama', array('class' => 'control-label')); ?>
            <div class='controls'>
            <select name="satkers[]" id="satkers" multiple="multiple" class="select2 col-md-10 form-control">
                    <?php
                        if (isset($satkers) && is_array($satkers) && count($satkers)) :
                            foreach ($satkers as $satker) :
                                $selected = '';
                                $bool =  in_array( $satker->ID, $asatkers );
                                if($bool){
                                    $selected = "selected";
                                }
                                echo "<option $selected value='$satker->ID'>" . strtoupper($satker->NAMA_UNOR) . "</option>";
                            endforeach;
                        endif;
                        ?>                     
                    </select>
                <span class='help-inline'><?php echo form_error('satuan_kerja'); ?></span>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <fieldset class='form-actions'>
            <input type='submit' name='save' class='btn btn-primary' value="Simpan" />
            <?php echo lang('bf_or'); ?>
            <?php echo anchor('/masters/pengelolasdmkawasan', "Batal", 'class="btn btn-warning"'); ?>

        </fieldset>
    </div>
    <?php echo form_close(); ?>

</div>
<script>
$('.select2').select2();
$('#rule').change(function(){
    var varvalue = $("#rule").val();
    if(varvalue != ""){
        window.location = "<?php echo base_url(); ?>masters/pengelolasdmkawasan/edit/<?php echo $id; ?>/"+varvalue;  
    }
});
</script>