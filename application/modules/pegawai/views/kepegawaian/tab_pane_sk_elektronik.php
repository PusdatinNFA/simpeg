<style>
	.dt-center {
		text-align:center;
	}
</style>
<!--tab-pane-->
 
<div class="tab-pane" id="<?php echo $TAB_ID;?>">
    <div class="form-group">
            <div class="box-header">
            <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
                    <style>
                        table.filter_pegawai tr td {
                            padding-top: 2px;
                        }
                    </style>
                    <!-- <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                        <tr>
                            <td width="150px"><label for="example-text-input" class="col-form-label">Kategori SK</label></td>
                            <td colspan=2>
                                <select name="kategori_sk" id="kategori_sk" class="form-control">
                                    <option value="">-- Silahkan Pilih --</option>
                                    <?php 
                                        foreach ($reckategori_ds as $record) {
                                    ?>
                                            <option value='<?php echo $record->kategori_ds; ?>'><?php echo $record->kategori_ds; ?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=4>
                                &nbsp;&nbsp;&nbsp;
                                <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                            </td>
                        </tr>
                    </table> -->
                <?php
                echo form_close();    
                ?>
            </div>
            <div class="box-body">
                <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
                    <thead>
                    <tr>
                        <th style="width:10px">No</th>
                        <th width="15%">Pegawai</th>
                        <th width="10%">Kategori</th>
                        <th>No SK / Tgl</th>
                        <th>Unit Kerja</th>
                        <th>TTD</th>
                        <th width="50px" align="center">#</th></tr>
                    </thead>
                </table>
            </div>
             
 
    </div>
     
    <script type="text/javascript">
     
    $("#unit_id_key").select2({
        placeholder: 'Cari Unit Kerja...',
        width: '100%',
        minimumInputLength: 0,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_unit_list");?>',
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
        
        dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
        "<'row'<'col-sm-12'tr>>" +
        "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
        processing: true,
        serverSide: true,
        "language": {
          "emptyTable": "Data tidak ditemukan"
        },
        "columnDefs": [
                        {"className": "dt-center", "targets": [4]},
                        { "targets": [0], "orderable": false }
                    ],
        ajax: {
          url: "<?php echo base_url() ?>admin/arsip/sk_ds/ajax_list",
          type:'POST',
          "data": {
                PNS_ID:'<?php echo $PNS_ID;?>'
            }
        }
    });
    $("#form_search_pegawai").submit(function(){
        $table.ajax.reload(null,true);
        return false;
    });
    $("#linkqr").click(function(){
        $( ".chkqr" ).prop( "checked", true );
    });
     
    $( "#textqrcode" ).focus(function() {
      $( ".chkqr" ).prop( "checked", true );
      $( "#textqrcode" ).val("");
    });
    </script>
</div>
