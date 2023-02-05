<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<?php
	$this->load->library('convert');
 	$convert = new convert();
?>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
<?php if ($mandiri){ ?>
    <div class="callout callout-success">
       <h4>Perhatian</h4>
       <ul>
        <li>Silahkan lakukan perubahan data, jika ada kesalahan atau ada perubahan. Perubahan data yang anda lakukan akan diverifikasi oleh kepegawaian Kawasan</li>
        <li>
            Jika ada kesalahan Nama/NIP silahkan mengajukan perubahan melalui e-lawas.
        </li>
       </ul>
     </div>
<?php } ?>
	<form role="form" action="#" id="frmprofile">
	<input id='ID' type='hidden' class="form-control" name='ID' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
	<input id='mandiri' type='hidden' class="form-control" name='mandiri' value="<?php echo set_value('mandiri', isset($mandiri) ? $mandiri : ''); ?>" />
	<div class="box box-danger">
            <div class="box-header no-border">
              <h3 class="box-title">Data Pribadi</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <fieldset>
                <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             NIP
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : ''; ?></b>
                         </div>
                     </div>
                 </div>
			  <div class="form-group col-sm-12">
				  <div class="row">
					  <div class="col-sm-4">
						  Tempat/Tanggal Lahir
					  </div>
					  <div class="control-group col-sm-4">
                        <select name="TEMPAT_LAHIR_ID1" disabled id="TEMPAT_LAHIR_ID" required="" class="form-control select2">
                            <?php 
                                if($selectedTempatLahirPegawai){
                                    echo "<option selected value='".$selectedTempatLahirPegawai->ID."'>".$selectedTempatLahirPegawai->NAMA."</option>";
                                }
                            ?>
                        </select>
					  </div>
                      <div class="control-group col-sm-4">
                        <div class="input-group date">
                          <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                          </div>
                            <input id='TGL_LAHIRUP' type='text' class="form-control pull-right" disabled="" name='TGL_LAHIRUP' value="<?php echo isset($pegawai->TGL_LAHIR) ? $convert->fmtDate($pegawai->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?>" />
                            <span class='help-inline'><?php echo form_error('TGL_LAHIR'); ?></span>
                        </div>
                    </div> 
                      
				  </div>
			  </div>
        
        <?php if ($this->auth->has_permission('Privacy.Kepegawaian.View') || $this->auth->username() == $pegawai->NIP_BARU){ ?>
        <div class="control-group col-sm-12">
         <div class="row">
           <div class="col-sm-4">
             NIK
           </div>
           <div class="form-group col-sm-8">
             <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input id='NIK' type='text' class="form-control" <?=$this->auth->has_permission('Privacy.NIK.Edit')?'': 'readonly'?> name='NIK' maxlength='200' value="<?php echo set_value('NIK', isset($pegawai->NIK) ? $pegawai->NIK : ''); ?>" />
             </div>
          </div>
         </div>
      </div>
        <?php } ?>
      <div class="control-group col-sm-12">
         <div class="row">
           <div class="col-sm-4">
             Email Pribadi
           </div>
           <div class="form-group col-sm-8">
             <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input id='EMAIL_PRIVATE' type='text' class="form-control" name='EMAIL_PRIVATE' maxlength='200' value="<?php echo set_value('EMAIL_PRIVATE', isset($pegawai->EMAIL_PRIVATE) ? $pegawai->EMAIL_PRIVATE : ''); ?>" />
             </div>
          </div>
         </div>
      </div>
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 EMAIL
					 </div>
					 <div class="form-group col-sm-8">
					   <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input id='EMAIL' type='text' class="form-control" readonly name='EMAIL' maxlength='200' value="<?php echo set_value('EMAIL', isset($pegawai->EMAIL) ? $pegawai->EMAIL : ''); ?>" />
					   </div>
          </div>
				 </div>
			 </div>
       
            
             <?php if ($this->auth->has_permission('Privacy.Kepegawaian.View') || $this->auth->username() == $pegawai->NIP_BARU){ ?>
             <div class="form-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         ALAMAT DOMISILI
                     </div>
                     <div class="col-sm-8">
                        <textarea name="ALAMAT" cols="40" rows="3" id="ALAMAT" style="width:100%"><?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : ''); ?></textarea>
                     </div>
                 </div>
             </div>
             <div class="form-group col-sm-12">
                 <div class="row">
                     <div class="col-sm-4">
                         ALAMAT SESUAI KTP
                     </div>
                     <div class="col-sm-8">
                        <textarea name="ALAMAT_KTP" cols="40" rows="3" id="ALAMAT_KTP" style="width:100%"><?php echo set_value('ALAMAT_KTP', isset($pegawai->ALAMAT_KTP) ? $pegawai->ALAMAT_KTP : ''); ?></textarea>
                     </div>
                 </div>
             </div>
			 
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 NO HP
					 </div>
           <div class="form-group col-sm-8">
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-phone"></i>
                </div>
                <input id='NOMOR_HP' type='text' class="form-control" name='NOMOR_HP' maxlength='32' value="<?php echo set_value('NOMOR_HP', isset($pegawai->NOMOR_HP) ? $pegawai->NOMOR_HP : ''); ?>" />
              </div>
              <!-- /.input group -->
            </div>
					 
				 </div>
			 </div>
        
             <?php } ?>
       <div class="control-group col-sm-12">
           <div class="row">
               <div class="form-group col-sm-4">
                   Agama
               </div>
               <div class="form-group col-sm-8">
                  <select name="AGAMA_ID" id="AGAMA_ID" required class="form-control">
                      <option value="">-- Silahkan Pilih --</option>
                      <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                      <?php foreach($agamas as $record):?>
                          <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->AGAMA_ID))  echo  ($pegawai->AGAMA_ID==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                          <?php endforeach;?>
                      <?php endif;?>
                  </select>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-12">
            <div class="row">
               <div class="form-group col-sm-4">
                   Tingkat Pendidikan
               </div>
               <div class="form-group col-sm-2">
                  <select name="TK_PENDIDIKAN1" id="TK_PENDIDIKAN1" disabled class="form-control select2">
                            <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                            <?php foreach($tkpendidikans as $record):?>
                                <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->TK_PENDIDIKAN))  echo  (TRIM($pegawai->TK_PENDIDIKAN)==$record->ID) ? "selected" : ""; ?>><?php echo $record->NAMA; ?></option>
                                <?php endforeach;?>
                            <?php endif;?>
                        </select>
               </div>
               <div class="form-group col-sm-2">
                   Pendidikan
               </div>
               <div class="form-group col-sm-4">
                   <select name="PENDIDIKAN_ID1" id="PENDIDIKAN_ID1" disabled class="form-control select2">
                        <?php 
                            if($selectedPendidikanID){
                                echo "<option selected value='".$selectedPendidikanID->ID."'>".$selectedPendidikanID->NAMA."</option>";
                            }
                        ?>
                         
                    </select>
                </div>
            </div>
       </div>
              <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         Status PNS/CPNS
                     </div>
                     <div class="form-group col-sm-2">
                        <select class="validate[required] text-input form-control" name="STATUS_CPNS_PNS" id="STATUS_CPNS_PNS" class="chosen-select-deselect">
                          <option value="">-- Pilih  --</option>
                          <option value="P" <?php if(isset($pegawai->STATUS_CPNS_PNS))  echo  ("P"==$pegawai->STATUS_CPNS_PNS) ? "selected" : ""; ?>> PNS</option>
                          <option value="C" <?php if(isset($pegawai->STATUS_CPNS_PNS))  echo  ("C"==$pegawai->STATUS_CPNS_PNS) ? "selected" : ""; ?>> CPNS</option>
                        </select>
                     </div>
                     <div class="form-group col-sm-2">
                         TMT PNS
                     </div>
                     <div class="form-group col-sm-4">
                     <div class="input-group date col-sm-12">
                        <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                        </div>
                          <input id='TMT_PNS' disabled type='text' class="form-control pull-right datepicker" name='TMT_PNS1'  value="<?php echo set_value('TMT_PNS', isset($pegawai->TMT_PNS) ? $pegawai->TMT_PNS : ''); ?>" />
                        <span class='help-inline'><?php echo form_error('TMT_PNS'); ?></span>
                      </div>
                     </div>
                 </div>
            </div>
            <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         Golongan
                     </div>
                     <div class="form-group col-sm-4">
                        <select name="GOL_ID1" disabled id="GOL_ID" class="form-control select2">
                            <option value="">-- Silahkan Pilih --</option>
                            <?php if (isset($golongans) && is_array($golongans) && count($golongans)):?>
                            <?php foreach($golongans as $record):?>
                              <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->GOL_ID))  echo  ($pegawai->GOL_ID==$record->ID) ? "selected" : "disabled"; ?>><?php echo $record->NAMA; ?> | <?php echo $record->NAMA_PANGKAT; ?></option>
                              <?php endforeach;?>
                            <?php endif;?>
                          </select>
                     </div>
                     
                 </div>
            </div>
            <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         Kartu Pegawai
                     </div>
                     <div class="form-group col-sm-2">
                         <input id='KARTU_PEGAWAI' type='text' class="form-control" name='KARTU_PEGAWAI' maxlength='20' value="<?php echo set_value('KARTU_PEGAWAI', isset($pegawai->KARTU_PEGAWAI) ? $pegawai->KARTU_PEGAWAI : ''); ?>" />
                      </div>
                      <div class="form-group col-sm-2">
                         Kartu ASN
                     </div>
                     <div class="form-group col-sm-4">
                         <input id='KARTU_ASN' type='text' class="form-control" name='KARTU_ASN' maxlength='20' value="<?php echo set_value('KARTU_ASN', isset($pegawai->KARTU_ASN) ? $pegawai->KARTU_ASN : ''); ?>" />
                      </div>
                 </div>
            </div>
            <div class="control-group col-sm-12">
                 <div class="row">
                      <div class="form-group col-sm-4">
                         Nomor Induk JF
                     </div>
                     <div class="form-group col-sm-8">
                         <input id='no_induk_jf' type='text' class="form-control" name='no_induk_jf' maxlength='32' value="<?php echo set_value('no_induk_jf', isset($pegawai->no_induk_jf) ? $pegawai->no_induk_jf : ''); ?>" />
                        <span><b>*bagi yang memiliki</b></span> 
                      </div>
                 </div>
            </div>
            <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         Lokasi Kerja
                     </div>
                     <div class="form-group col-sm-8">
                        <select name="LOKASI_KERJA_ID1" disabled id="LOKASI_KERJA_ID" class="form-control select2">
                                <?php 
                                    if($selectedLokasiPegawai){
                                        echo "<option selected value='".$selectedLokasiPegawai->ID."'>".$selectedLokasiPegawai->NAMA."</option>";
                                    }
                                ?>
                          </select>
                     </div>
                 </div>
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="form-group col-sm-4">
                        Satker
                    </div>
                    <div class="form-group col-sm-8">
                        <select name="UNOR_INDUK_ID1" disabled id="UNOR_INDUK_ID" class="form-control select2">
                                <?php 
                                    if($selectedUnorindukid){
                                        echo "<option selected value='".$selectedUnorindukid->ID."'>".$selectedUnorindukid->NAMA_UNOR."</option>";
                                    }
                                ?>
                            </select>
                    </div>                
                </div>
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="form-group col-sm-4">
                        Unit
                    </div>
                    <div class="form-group col-sm-8">
                        <select name="UNOR_ID1" disabled id="Unor_ID" class="form-control select2">
                                <?php 
                                    if($selectedUnorid){
                                        echo "<option selected value='".$selectedUnorid->ID."'>".$selectedUnorid->NAMA_UNOR."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                </div>
            </div>
            <!-- <div class="control-group col-sm-12">
                <div class="row">
                    <div class="form-group col-sm-4">
                        Kepakaran
                    </div>
                    <div class="form-group col-sm-8">
                        <select name="kepakaran[]" id="kepakaran" multiple="multiple" class="form-control select2">
                                <?php 
                                    if (isset($kepakarans) && is_array($kepakarans) && count($kepakarans)) :
                                        foreach ($kepakarans as $kepakaran) :
                                            if ($kepakaran->kode){
                                                $selected = 'selected';
                                                echo "<option $selected value='$kepakaran->kode'>" . $kepakaran->namaid.' ('.$kepakaran->namaen.')' . "</option>";
                                            }
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        <span><b>*khusus Peneliti</b></span>   
                        </div>
                </div>
            </div> -->
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="form-group col-sm-4">
                        Bidang Kepakaran
                    </div>
                    <div class="form-group col-sm-8">
                        <select name="penelitian[]" id="bidangpenelitian" multiple="multiple" class="form-control select2">
                                <?php 
                                    if (isset($penelitians) && is_array($penelitians) && count($penelitians)) :
                                        foreach ($penelitians as $penelitian) :
                                            if ($penelitian->kode){
                                                $selected = 'selected';
                                                echo "<option $selected value='$penelitian->kode'>" . $penelitian->namaid.' ('.$penelitian->namaen.')' . "</option>";
                                            }
                                        endforeach;
                                    endif;
                                ?>
                            </select>
                        <span><b>*Khusus SDM IPTEK</b></span> 
                        </div>
                </div>
            </div>
            <div class="control-group col-sm-12">
                 <div class="row">
                     <div class="form-group col-sm-4">
                         Detail Kepakaran
                     </div>
                     <div class="form-group col-sm-8">
                         <textarea id='detail_kepakaran' rows="15" type='text' class="form-control" name='detail_kepakaran' placeholder="Tuliskan detail kepakaran secara rinci, jelas dan lengkap terkait keahlian atau kepakaran Anda. Detail kepakaran ini nantinya akan digunakan sebagai media informasi terkait bidang keahlian SDM IPTEK BRIN secara perorangan/individu." style="width:100%"><?php echo set_value('detail_kepakaran', isset($pegawai->detail_kepakaran) ? trim($pegawai->detail_kepakaran) : ''); ?></textarea>
                      </div>                     
                 </div>
            </div>
            </fieldset>
            <fieldset>
                <legend>Lainnya</legend>
                    <div class="control-group<?php echo form_error('JENIS_KAWIN_ID') ? ' error' : ''; ?> col-sm-12">
                       <?php echo form_label("STATUS PERKAWINAN", 'JENIS_KAWIN_ID', array('class' => 'control-label')); ?><br>
                       <span>(Untuk update status perkawinan dapat dilakukan dengan melengkapi data pasangan pada data keluarga.)</span>
                       <div class='controls'>
                           <select name="JENIS_KAWIN_ID1" disabled id="JENIS_KAWIN_ID" class="form-control select2">
                               <option value="">-- Silahkan Pilih --</option>
                               <?php if (isset($jenis_kawins) && is_array($jenis_kawins) && count($jenis_kawins)):?>
                               <?php foreach($jenis_kawins as $record):?>
                                   <option value="<?php echo $record->ID?>" <?php if(isset($pegawai->JENIS_KAWIN_ID))  echo  ($pegawai->JENIS_KAWIN_ID==$record->ID) ? "selected" : "disabled"; ?>><?php echo $record->NAMA; ?></option>
                                   <?php endforeach;?>
                               <?php endif;?>
                           </select>
                           <span class='help-inline'><?php echo form_error('JENIS_KAWIN_ID'); ?></span>
                       </div>
                    </div>
                   <div class="control-group<?php echo form_error('AKTE_KELAHIRAN') ? ' error' : ''; ?> col-sm-12">
                       <?php echo form_label("AKTE KELAHIRAN", 'AKTE_KELAHIRAN', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='AKTE_KELAHIRAN' type='text' class="form-control" name='AKTE_KELAHIRAN' value="<?php echo set_value('AKTE_KELAHIRAN', isset($pegawai->AKTE_KELAHIRAN) ? trim($pegawai->AKTE_KELAHIRAN) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('AKTE_KELAHIRAN'); ?></span>
                       </div>
                   </div>
                    
                    <div class="control-group col-sm-6">
                       <?php echo form_label("NO BPJS", 'BPJS', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='BPJS' type='text' class="form-control" name='BPJS' value="<?php echo set_value('BPJS', isset($pegawai->BPJS) ? trim($pegawai->BPJS) : ''); ?>" />
                       </div>
                   </div>
                   <div class="control-group<?php echo form_error('NO_TASPEN') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO TASPEN", 'NO_TASPEN', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_TASPEN' type='text' class="form-control" name='NO_TASPEN' maxlength='50' value="<?php echo set_value('NO_TASPEN', isset($pegawai->NO_TASPEN) ? trim($pegawai->NO_TASPEN) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NO_TASPEN'); ?></span>
                       </div>
                   </div>
                   <div class="control-group<?php echo form_error('NPWP') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label(lang('pegawai_field_NPWP'), 'NPWP', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NPWP' type='text' class="form-control" name='NPWP' value="<?php echo set_value('NPWP', isset($pegawai->NPWP) ? $pegawai->NPWP : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NPWP'); ?></span>
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TGL NPWP</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_NPWP' type='text' class="form-control pull-right datepicker" name='TGL_NPWP' value="<?php echo set_value('TGL_NPWP', isset($pegawai->TGL_NPWP) ? $pegawai->TGL_NPWP : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_NPWP'); ?></span>
                       </div>
                   </div> 
                   <div class="control-group<?php echo form_error('NPWP') ? ' error' : ''; ?> col-sm-9">
                       <?php echo form_label("NOMOR DARURAT", 'NOMOR_DARURAT', array('class' => 'control-label')); ?>
                        <span class='help-inline'>(No telepon keluarga satu rumah)</span>
                       <div class='controls'>
                           <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-phone"></i>
                            </div>
                            
                            <input id='NOMOR_DARURAT' type='text' class="form-control" name='NOMOR_DARURAT' maxlength='32' value="<?php echo set_value('NOMOR_DARURAT', isset($pegawai->NOMOR_DARURAT) ? $pegawai->NOMOR_DARURAT : ''); ?>" />
                            
                          </div>
                       </div>
                   </div>  
                   <?php if ($this->auth->has_permission('Privacy.Kepegawaian.View') || $this->auth->username() == $pegawai->NIP_BARU){ ?>                      
                    <div class="control-group<?php echo form_error('no_kk') ? ' error' : ''; ?> col-sm-6">
                        <label for="inputNAMA" class="control-label">Nomor KK</label>
                            <div class='controls'>
                                <input id='no_kk' type='text' required class="form-control" name='no_kk' maxlength='32' value="<?php echo set_value('no_kk', isset($pegawai->no_kk) ? $pegawai->no_kk : ''); ?>" />
                                <span class='help-inline'><?php echo form_error('no_kk'); ?></span>
                            </div>
                        </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">Kode Pos (Sesuai KK) </label>
                        <input type="text" required class="form-control" name="kode_pos" value="<?=$pegawai->kode_pos?>" />
                    </div>
                    <?php } ?>
                   <div class="control-group col-sm-6">
                        <label class="control-label">Situs Pribadi </label>
                        <input type="text" class="form-control" name="situs_pribadi" value="<?=$pegawai->situs_pribadi?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">Facebook </label>
                        <input type="text" class="form-control" name="facebook" value="<?=$pegawai->facebook?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">Twitter </label>
                        <input type="text" class="form-control" name="twitter" value="<?=$pegawai->twitter?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">Google Plus </label>
                        <input type="text" class="form-control" name="google_plus" value="<?=$pegawai->google_plus?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">Google Scholar </label>
                        <input type="text" class="form-control" name="google_scholar" value="<?=$pegawai->google_scholar?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">LinkedIn </label>
                        <input type="text" class="form-control" name="linkedin" value="<?=$pegawai->linkedin?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">ORCID </label>
                        <input type="text" class="form-control" name="orcid" value="<?=$pegawai->orcid?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">ResearchGate </label>
                        <input type="text" class="form-control" name="research_gate" value="<?=$pegawai->research_gate?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">ResearcherID </label>
                        <input type="text" class="form-control" name="researcher_id" value="<?=$pegawai->researcher_id?>" />
                    </div>
                    <div class="control-group col-sm-6">
                        <label class="control-label">Scopus </label>
                        <input type="text" class="form-control" name="scopus" value="<?=$pegawai->scopus?>" />
                    </div>
            </fieldset>
            <fieldset>
            <legend>Surat Keterangan CPNS</legend>
            <div class="control-group<?php echo form_error('NO_SURAT_DOKTER') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO SURAT KETERANGAN SEHAT DOKTER", 'NO_SURAT_DOKTER', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_SURAT_DOKTER' type='text' class="form-control" name='NO_SURAT_DOKTER' maxlength='100' value="<?php echo set_value('NO_SURAT_DOKTER', isset($pegawai->NO_SURAT_DOKTER) ? trim($pegawai->NO_SURAT_DOKTER) : ''); ?>" />
                           <!-- <span class='help-inline'> (CPNS)</span> -->
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TANGGAL</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_SURAT_DOKTER' type='text' class="form-control pull-right datepicker" name='TGL_SURAT_DOKTER' value="<?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai->TGL_SURAT_DOKTER) ? trim($pegawai->TGL_SURAT_DOKTER) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_SURAT_DOKTER'); ?></span>
                       </div>
                   </div> 
                   <div class="control-group <?php echo form_error('NO_BEBAS_NARKOBA') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO SURAT BEBAS NARKOBA", 'NO_BEBAS_NARKOBA', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_BEBAS_NARKOBA' type='text' class="form-control" name='NO_BEBAS_NARKOBA' value="<?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai->NO_BEBAS_NARKOBA) ? trim($pegawai->NO_BEBAS_NARKOBA) : ''); ?>" />
                           <!-- <span class='help-inline'> (CPNS)</span> -->
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TANGGAL</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_BEBAS_NARKOBA' type='text' class="form-control pull-right datepicker" name='TGL_BEBAS_NARKOBA' value="<?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai->TGL_BEBAS_NARKOBA) ? $pegawai->TGL_BEBAS_NARKOBA : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_BEBAS_NARKOBA'); ?></span>
                       </div>
                   </div> 
                   <div class="control-group<?php echo form_error('NO_CATATAN_POLISI') ? ' error' : ''; ?> col-sm-6">
                       <?php echo form_label("NO CATATAN KEPOLISIAN", 'NO_CATATAN_POLISI', array('class' => 'control-label')); ?>
                       <div class='controls'>
                           <input id='NO_CATATAN_POLISI' type='text' class="form-control" name='NO_CATATAN_POLISI' maxlength='100' value="<?php echo set_value('NO_CATATAN_POLISI', isset($pegawai->NO_CATATAN_POLISI) ? trim($pegawai->NO_CATATAN_POLISI) : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('NO_CATATAN_POLISI'); ?></span>
                       </div>
                   </div>
                   <div class="control-group col-sm-6">
                       <label for="inputNAMA" class="control-label">TANGGAL</label>
                       <div class="input-group date">
                         <div class="input-group-addon">
                           <i class="fa fa-calendar"></i>
                         </div>
                           <input id='TGL_CATATAN_POLISI' type='text' class="form-control pull-right datepicker" name='TGL_CATATAN_POLISI' value="<?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai->TGL_CATATAN_POLISI) ? $pegawai->TGL_CATATAN_POLISI : ''); ?>" />
                           <span class='help-inline'><?php echo form_error('TGL_CATATAN_POLISI'); ?></span>
                       </div>
                   </div> 
            </fieldset>
              <!-- /.table-responsive -->
             
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
    </div>
	 
    <div class="box-footer">
        <?php if ($this->auth->has_permission('Pegawai.UpdateSimple.View')) : ?>
            <input type='submit' name='save' id="btnsaveprofile" class='btn btn-success' value="Simpan Perubahan" />
        <?php endif; ?>
        <?php echo anchor(SITE_AREA . '/kepegawaian/pegawai/profilen/'.$pegawai->ref, "Batal", 'class="btn btn-warning"'); ?>
      </div>
    </form>
</div>
<script>
    //Date picker
    $('.datepicker').datepicker({
      autoclose: true,format: 'yyyy-mm-dd'
    });
</script>

<script>
    $("#frmprofile").validate({
        submitHandler: function(form) {
        $("#btnsaveprofile").val('Menyimpan Perubahan......').attr('disabled', true).addClass('bt-hud').unbind('click');
        submitdata();
      },
      rules: {
        NOMOR_HP: {
          required: false,
          number: true
        },
        EMAIL: {
            required: false,
            email: true
        },
        BPJS: {
            required: false
        },
        

      },errorElement: "span",
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "help-block" );

            // Add `has-feedback` class to the parent div.form-group
            // in order to add icons to inputs

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !element.next( "span" )[ 0 ] ) {
            }
        },
        success: function ( label, element ) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if ( !$( element ).next( "span" )[ 0 ] ) {
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".control-group" ).addClass( "has-error" ).removeClass( "has-success" );
        },
        unhighlight: function ( element, errorClass, validClass ) {
            $( element ).parents( ".col-sm-5" ).addClass( "has-success" ).removeClass( "has-error" );
        }


    });
    // $("#TEMPAT_LAHIR_ID").select2({
    //     placeholder: 'Cari Tempat Lahir...',
    //     width: '100%',
    //     minimumInputLength: 3,
    //     allowClear: true,
    //     ajax: {
    //         url: '<?php echo site_url("admin/lokasi/pegawai/ajax");?>',
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 term: params.term || '',
    //                 page: params.page || 1
    //             }
    //         },
    //         cache: true
    //     }
    // }); 
    $('#TK_PENDIDIKAN').change(function() {
        /* 
        var valuetingkat = $('#TK_PENDIDIKAN').val();
            $("#PENDIDIKAN_ID").empty().append("<option>loading...</option>"); //show loading...
            
            var json_url = "<?php echo base_url(); ?>pegawai/pendidikan/getbytingkat?tingkat=" + encodeURIComponent(valuetingkat);
            //alert(json_url);
            $.getJSON(json_url,function(data){
                $("#PENDIDIKAN_ID").empty(); 
                if(data==""){
                    $("#PENDIDIKAN_ID").append("<option value=\"\">Silahkan Pilih </option>");
                }
                else{
                    $("#PENDIDIKAN_ID").append("<option value=\"\">Silahkan Pilih</option>");
                    for(i=0; i<data.id.length; i++){
                        $("#PENDIDIKAN_ID").append("<option value=\"" + data.id[i]  + "\">" + data.nama[i] +"</option>");
                    }
                }
                
            });
            */
            //$("#PENDIDIKAN_ID").select2("updateResults");
            return false;
    });
    // $("#PENDIDIKAN_ID").select2({
    //     placeholder: 'Cari Jabatan...',
    //     width: '100%',
    //     minimumInputLength: 3,
    //     allowClear: true,
    //     ajax: {
    //         url: '<?php echo site_url("pegawai/masterpendidikan/ajax");?>',
    //         dataType: 'json',
    //         data: function(params) {
    //             return {
    //                 term: params.term || '',
    //                 page: params.page || 1
    //             }
    //         },
    //         cache: true
    //     }
    // });
	function submitdata(){
		
		var json_url = "<?php echo base_url() ?>admin/kepegawaian/pegawai/updatemandiri";
		 $.ajax({    
		 	type: "post",
			url: json_url,
			data: $("#frmprofile").serialize(),
            dataType: "json",
			      success: function(data){ 
                if(data.success){
                    swal("Pemberitahuan!", data.msg, "success");
                    $("#btnsaveprofile").val('Simpan Perubahan').attr('disabled', false).addClass('bt-hud').unbind('click');
                    window.location.replace("<?=base_url()?>admin/kepegawaian/pegawai/profilen/"+'<?=$pegawai->ref?>');
                }
                else {
                    $(".messages").empty().append(data.msg);
                }
			}});
		return false; 
	}
//   $("#LOKASI_KERJA_ID").select2({
//         placeholder: 'Cari Lokasi Kerja...',
//         width: '100%',
//         minimumInputLength: 3,
//         allowClear: true,
//         ajax: {
//             url: '<?php echo site_url("admin/lokasi/pegawai/ajax");?>',
//             dataType: 'json',
//             data: function(params) {
//                 return {
//                     term: params.term || '',
//                     page: params.page || 1
//                 }
//             },
//             cache: true
//         }
//     });
//   $("#Unor_ID").select2({
//         placeholder: 'Cari Unit Kerja...',
//         width: '100%',
//         minimumInputLength: 3,
//         allowClear: true,
//         ajax: {
//             url: '<?php echo site_url("pegawai/manage_unitkerja/ajax");?>',
//             dataType: 'json',
//             data: function(params) {
//                 return {
//                     term: params.term || '',
//                     page: params.page || 1
//                 }
//             },
//             cache: true
//         }
//     });
//     $("#UNOR_INDUK_ID").select2({
//         placeholder: 'Cari Unit Kerja...',
//         width: '100%',
//         minimumInputLength: 3,
//         allowClear: true,
//         ajax: {
//             url: '<?php echo site_url("pegawai/manage_unitkerja/ajaxall");?>',
//             dataType: 'json',
//             data: function(params) {
//                 return {
//                     term: params.term || '',
//                     page: params.page || 1
//                 }
//             },
//             cache: true
//         }
//     });
$("#kepakaran").select2({
        placeholder: '  Cari Kepakaran...',
        width: '100%',
        minimumInputLength: 3,
        allowClear: true,
        ajax: {
            url: '<?php echo site_url("pegawai/kepegawaian/ajax_bidang_kepakaran");?>',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1
                }
            },
            cache: true
        },
        templateResult: formatOutput
    });
$("#bidangpenelitian").select2({
    placeholder: '  Cari Bidang Kepakaran...',
    width: '100%',
    minimumInputLength: 3,
    allowClear: true,
    ajax: {
        url: '<?php echo site_url("pegawai/kepegawaian/ajax_bidang_penelitian");?>',
        dataType: 'json',
        data: function(params) {
            return {
                term: params.term || '',
                page: params.page || 1
            }
        },
        cache: true
    },
    templateResult: formatOutput
});
function formatOutput (optionElement) {
  if (!optionElement.id) { return optionElement.text; }
  var $state = $('<span><strong>' + optionElement.long_text + '</span>');
  return $state;
};
</script>
