<div class="admin-box box box-danger expanded-box">
    <div class="box-header">
    <?php echo form_open($this->uri->uri_string(),"id=form_search_pegawai","form"); ?>
            <style>
                table.filter_pegawai tr td {
                    padding-top: 2px;
                }
            </style>
            <div class='alert alert-block alert-info fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Perhatian
                </h4>
                <p>Halaman ini untuk melihat seluruh daftar SK pengalihan Pegawai</p>
            </div>
            <table class="filter_pegawai" sborder=0 width='100%' cellpadding="10">
                 
                <tr>
                    <td width="200px"><label for="example-text-input" class="col-form-label">NAMA</label></td>
                    <td colspan=2><input class="form-control" type="text" name="nama_key" value="" ></td>
                </tr>
                <tr>
                    <td width="200px"><label for="example-text-input" class="col-form-label">NIP</label></td>
                    <td colspan=2><input class="form-control" type="text" name="nip_key" value="" ></td>
                </tr>

                <tr>
                    <td width="200px"><label for="example-text-input" class="col-form-label">INSTANSI</label></td>
                    <td colspan=2><input class="form-control" type="text" name="instansi_key" value="" ></td>
                </tr>
                <tr>
                    <td width="200px"><label for="example-text-input" class="col-form-label">Jabatan</label></td>
                    <td colspan=2>
                        <select name="is_jf_key" class="form-control">
                            <option value="">Silahkan Pilih</option>
                            <option value="1">JFT</option>
                            <option value="2">JFU</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td width="200px"><label for="example-text-input" class="col-form-label">Agama</label></td>
                    <td colspan=2>
                        <select name="agama_key" class="form-control">
                            <option value="">Silahkan Pilih</option>
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katholik">Katholik</option>
                            <option value="Hindu">Hindu</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan=3>
                        &nbsp;&nbsp;&nbsp;
                        <button type="submit" class="btn btn-success pull-right "><i class="fa fa-search"></i> Cari</button>
                    </td>
                </tr>
            </table>
        <?php
        echo form_close();    
        ?>
     </div>
    <div class="box-body">
        <table class="slug-table table table-bordered table-striped table-responsive dt-responsive table-data table-hover">
            <thead>
            <tr>
                <th style="width:10px">No</th>
                <th>Pegawai</th>
                <th width="200px">SK</th>
                <th width="200px">Draft SK</th>
                <th>#</th>
            </tr>
            </thead>
        </table>
    </div>
</div>     
<script type="text/javascript">
$table = $(".table-data").DataTable({
    
    dom : "<'row'<'col-sm-6'><'col-sm-6'>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-2'l><'col-sm-3'i><'col-sm-7'p>>",
    processing: true,
    serverSide: true,
    "columnDefs": [
                    {"className": "text-center", "targets": [0,2,3,4]},
                    { "targets": [0], "orderable": false }
                ],
    ajax: {
      url: "<?php echo base_url() ?>tte/sk_pengalihan/ajax_data",
      type:'POST',
      "data": function ( d ) {
            d.search['advanced_search_filters']=  $("#form_search_pegawai").serializeArray();
        }
    }
});
$("#form_search_pegawai").submit(function(){
    $table.ajax.reload(null,true);
    return false;
});
$('body').on('click','.popupurl',function () { 
    var url1 =$(this).attr("url");
    var title =$(this).attr("data-original-title");
    popitup1(url1,title);
});
     
function popitup1(url,title,w = 700,h=600) {
    var left = (screen.width/2)-(w/2);
    var top = (screen.height/2)-(h/2);
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

}
$('body').on('click','.generateSk',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Generate SK",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var post_data = "kode="+kode;
            $.ajax({
                    url: "<?php echo base_url() ?>tte/sk_pengalihan/generate_sk",
                    type:"GET",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                            $table.ajax.reload(null,true);
                            swal("Perhatian!", result.msg, "success");
                        }else{
                            $table.ajax.reload(null,true);
                            swal("Perhatian!", result.msg, "error");
                        }
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
$('body').on('click','.sendDs',function () { 
    var kode =$(this).attr("kode");
    swal({
        title: "Anda Yakin?",
        text: "Kirim dokumen ke penandatangan untuk di tandatangan secara elektronik",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: 'btn-success',
        confirmButtonText: 'Ya!',
        cancelButtonText: "Tidak, Batalkan!",
        closeOnConfirm: false,
        showLoaderOnConfirm: true,
        closeOnCancel: false
    },
    function (isConfirm) {
        if (isConfirm) {
            var post_data = "kode="+kode;
            $.ajax({
                    url: "<?php echo base_url() ?>tte/sk_pengalihan/kirimds",
                    type:"GET",
                    data: post_data,
                    dataType: "json",
                    timeout:180000,
                    success: function (result) {
                        if(result.success){
                            $table.ajax.reload(null,true);
                            swal("Perhatian!", result.msg, "success");
                        }else{
                            $table.ajax.reload(null,true);
                            swal("Perhatian!", result.msg, "error");
                        }
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
</script>
