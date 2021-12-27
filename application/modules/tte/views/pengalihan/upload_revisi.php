<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/dropzone/dropzone.min.css">
<script src="<?php echo base_url(); ?>themes/admin/js/dropzone/dropzone.min.js"></script>
<!-- sweet alert -->
<script src="<?php echo base_url(); ?>themes/admin/js/sweetalert.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/css/sweetalert.css">
<div class="box box-danger">
  <div class="box-body">
	  	<div class="control-group col-sm-12">
		  <div class='controls'>
			 <div class="dropzone well well-sm">
			 </div>
			</div>
	  	</div>
      
	</div> 
</div>  
<script>
Dropzone.autoDiscover = true;
    var foto_upload= new Dropzone(".dropzone",{
    	 autoProcessQueue: true,
		 url: "<?php echo base_url() ?>tte/sk_pengalihan/save_draft",
		 maxFilesize: 1,
		 parallelUploads : 10,
		 method:"post",
		 acceptedFiles:".docx",
		 paramName:"userfile",
		 dictDefaultMessage:"<img src='<?php echo base_url(); ?>assets/images/dropico.png' width='50px'/><br>Silahkan klik/drop disini (.docx)",
		 dictInvalidFileType:"Type file ini tidak dizinkan",
		 addRemoveLinks:true,
		 init: function () {
			   	this.on("success", function (file,response) {
			   	var data_n = JSON.parse(response);
			   	if(data_n.success){
					swal("Pemberitahuan!", "Upload Selesai", "success");
					$("#modal-global").modal("hide");
				}else{
					swal("Pemberitahuan!", "Upload Selesai", "error");
				}
			});
		   }
		 });
		foto_upload.on("sending",function(a,b,c){
			 a.token=Math.random();
			 c.append('token_foto',a.token);
			 console.log('mengirim');           
		 });
	foto_upload.processQueue();
</script>


