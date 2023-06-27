<script src="<?php echo base_url(); ?>themes/admin/js/jqueryvalidation/jquery.validate.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>themes/admin/plugins/datepicker/datepicker3.css">
<?php
	$this->load->library('convert');
 	$convert = new convert();
?>
<div class="tab-pane active" id="<?php echo $TAB_ID;?>">
    <div class="callout callout-info">
       <h4>Perhatian</h4>
       <p><?=$attention;?></p>
     </div>
	<form role="form" action="#" id="frmprofile">
	<input id='ID' type='hidden' class="form-control" name='ID' maxlength='25' value="<?php echo set_value('ID', isset($pegawai->ID) ? $pegawai->ID : ''); ?>" />
	<div class="box box-danger">
            <div class="box-header no-border">
              <h3 class="box-title">Data Pribadi</h3>

              <div class="box-tools pull-right">
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
                 <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             Username Intra
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->username_intra) ? $pegawai->username_intra : '-'; ?></b>
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
                            <b><?php echo isset($pegawai->NIK) ? $pegawai->NIK : ''; ?></b>
                         </div>
                     </div>
                 </div>
                 <div class="control-group col-sm-12">
                     <div class="row">
                         <div class="col-sm-4">
                             NO KK
                         </div>
                         <div class="form-group col-sm-8">
                            <b><?php echo isset($pegawai->no_kk) ? $pegawai->no_kk : '-'; ?></b>
                         </div>
                     </div>
                 </div>
                 <?php } ?>
        			  <div class="form-group col-sm-12">
        				  <div class="row">
        					  <div class="col-sm-4">
        						  Tempat/Tanggal Lahir
        					  </div>
        					  <div class="control-group col-sm-8">
                      <?php echo isset($selectedTempatLahirPegawai->NAMA) ? $selectedTempatLahirPegawai->NAMA : ""; ?> / 
                          <?php echo isset($pegawai->TGL_LAHIR) ? $convert->fmtDate($pegawai->TGL_LAHIR,"dd month yyyy") : 'TGL_LAHIR'; ?>
                  </div> 
                              
        				  </div>
        			  </div>
        <div class="control-group col-sm-12">
         <div class="row">
           <div class="col-sm-4">
             Email Pribadi
           </div>
           <div class="form-group col-sm-8">
                <?php echo set_value('EMAIL', isset($pegawai->EMAIL_PRIVATE) ? $pegawai->EMAIL_PRIVATE : '-'); ?>
         </div>
       </div>
      </div>
			 <div class="control-group col-sm-12">
				 <div class="row">
					 <div class="col-sm-4">
						 Email Coporate 
					 </div>
					 <div class="form-group col-sm-8">
                <?php echo set_value('EMAIL', isset($pegawai->EMAIL) ? $pegawai->EMAIL : '-'); ?>
				 </div>
			 </div>
      </div>
      
        <div class="control-group col-sm-12">
           <div class="row">
               <div class="col-sm-4">
                   Alamat Domisili
               </div>
               <div class="form-group col-sm-8">
                  <?php echo set_value('ALAMAT', isset($pegawai->ALAMAT) ? $pegawai->ALAMAT : '-'); ?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-12">
           <div class="row">
               <div class="col-sm-4">
                   Alamat Sesuai KTP
               </div>
               <div class="form-group col-sm-8">
                  <?php echo set_value('ALAMAT_KTP', isset($pegawai->ALAMAT_KTP) ? $pegawai->ALAMAT_KTP : '-'); ?>
               </div>
           </div>
       </div>
			 
        <div class="control-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    No HP
                </div>
            <div class="form-group col-sm-8">
            <?php echo set_value('NOMOR_HP', (isset($pegawai->NOMOR_HP) and $pegawai->NOMOR_HP != "") ? $pegawai->NOMOR_HP : '-'); ?>
            </div>                            
            </div>
        </div>
        
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   Agama
               </div>
               <div class="form-group col-sm-4">
                      <?php if (isset($agamas) && is_array($agamas) && count($agamas)):?>
                      <?php foreach($agamas as $record):?>
                      <?php if(isset($pegawai->AGAMA_ID))  echo  ($pegawai->AGAMA_ID==$record->ID) ? $record->NAMA : ""; ?>
                      <?php endforeach;?>
                      <?php endif;?>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Jenis Kelamin
               </div>
               <div class="form-group  col-sm-8">
                      <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("M"==$pegawai->JENIS_KELAMIN) ? "Laki-Laki" : ""; ?>
                      <?php if(isset($pegawai->JENIS_KELAMIN))  echo  ("F"==$pegawai->JENIS_KELAMIN) ? "Perempuan" : ""; ?>
               </div>
           </div>
       </div>

			 <div class="control-group col-sm-6">
				 <div class="row">
					 <div class="control-group col-sm-8">
						 Tingkat Pendidikan
					 </div>
					 <div class="form-group col-sm-4">
                  <?php if (isset($tkpendidikans) && is_array($tkpendidikans) && count($tkpendidikans)):?>
                  <?php foreach($tkpendidikans as $record):?>
                      <?php echo  (TRIM($pegawai->TK_PENDIDIKAN)==$record->ID) ? $record->NAMA : ""; ?>
                      <?php endforeach;?>
                  <?php endif;?>
					 </div>
				 </div>
			 </div>
             <div class="control-group col-sm-6">
                <div class="row">
                    <div class="col-sm-4">
                        Pendidikan
                    </div>
                    <div class="form-group col-sm-8">
                        <?php echo $selectedPendidikanID->NAMA; ?>
                    </div>
                </div>
            </div>
            <div class="control-group col-sm-12">
                  <div class="row">
                      <div class="col-sm-4">
                          Masa Kerja berdasarkan TMT CPNS (Tahun/Bulan)
                      </div>
                      <div class="form-group col-sm-4">
                          <b><?php echo isset($recpns_aktif->masa_kerja_th) ? $recpns_aktif->masa_kerja_th  : ""; ?>/<?php echo isset($recpns_aktif->masa_kerja_bl) ? $recpns_aktif->masa_kerja_bl  : ""; ?></b>
                      </div>
                  </div>
            </div>

            <div class="control-group col-sm-12">
                <div class="row">
                      <div class="form-group col-sm-4">
                          Lokasi Kerja
                      </div>
                      <div class="form-group col-sm-8">
                          <b><?php echo isset($pegawai->LOKASI_KERJA) ? $pegawai->LOKASI_KERJA  : ""; ?></b>
                      </div>
                </div>
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        Jenis Pegawai
                    </div>
                    <div class="form-group col-sm-8">
                        <b><?php echo isset($pegawai->JENIS_PEGAWAI) ? $pegawai->JENIS_PEGAWAI : ''; ?></b>
                    </div>
                </div>
                   
            </div>
            <div class="control-group col-sm-12">
                <div class="row">
                    <div class="col-sm-4">
                        Pangkat Golongan Aktif
                    </div>
                    <div class="form-group col-sm-8">
                        <b><?php echo isset($NAMA_PANGKAT) ? $NAMA_PANGKAT  : "-"; ?></b> <b><?php echo isset($GOLONGAN_AKHIR) ? $GOLONGAN_AKHIR  : "-"; ?></b>
                    </div>
                </div>
                   
            </div>
         
         <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   Gol Ruang Awal
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($GOLONGAN_AWAL) ? $GOLONGAN_AWAL  : "-"; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Gol Ruang Akhir
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($GOLONGAN_AKHIR) ? $GOLONGAN_AKHIR  : "-"; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   TMT Golongan
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai->TMT_GOLONGAN) ? $convert->fmtDate($pegawai->TMT_GOLONGAN ,"dd month yyyy") : ""; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Gaji Pokok
               </div>
               <div class="form-group  col-sm-8">
                      <b>-</b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-8">
                   TMT CPNS
               </div>
               <div class="form-group col-sm-4">
                      <b><?php echo isset($pegawai->TMT_CPNS) ? $convert->fmtDate($pegawai->TMT_CPNS ,"dd month yyyy") : ""; ?></b>
               </div>
           </div>
       </div>
       <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   SK CPNS
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($pegawai->NOMOR_SK_CPNS) ? $pegawai->NOMOR_SK_CPNS != "" ? $pegawai->NOMOR_SK_CPNS : "" : ''; ?></b>
               </div>
           </div>
       </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Status CPNS/PNS
                </div>
                <div class="col-sm-8">
                    <b><?php echo isset($pegawai->STATUS_CPNS_PNS) ? $pegawai->STATUS_CPNS_PNS == "P" ? "PNS" : $pegawai->STATUS_CPNS_PNS : ''; ?>   (<?php echo $this->convert->fmtDate($pegawai->TMT_PNS,"dd month yyyy"); ?>)</b>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Status Pegawai
                </div>
                <div class="col-sm-8">
                    <b><?php echo isset($pegawai->status_pegawai) ? $pegawai->status_pegawai == 1 ? "PNS" : ($pegawai->status_pegawai == 2 ? "PPPK" : "NON PNS"):""; ?> </b>
                </div>
            </div>
        </div>
        <div class="form-group col-sm-6">
            <div class="row">
                <div class="col-sm-8">
                    Kartu Pegawai
                </div>
                <div class="col-sm-4">
                    <b><?php echo isset($pegawai->KARTU_PEGAWAI) ? $pegawai->KARTU_PEGAWAI != "" ? $pegawai->KARTU_PEGAWAI : "" : ''; ?></b>
                </div>
            </div>
        </div>
        <div class="control-group col-sm-6">
           <div class="row">
               <div class="form-group col-sm-4">
                   Kartu ASN
               </div>
               <div class="form-group  col-sm-8">
                      <b><?php echo isset($pegawai->KARTU_ASN) ? $pegawai->KARTU_ASN != "" ? $pegawai->KARTU_ASN : "" : ''; ?></b>
               </div>
           </div>
       </div>
        <div class="form-group col-sm-12">
            <div class="row">
                <div class="col-sm-4">
                    Nomor Induk Jabatan Fungsional
                </div>
                <div class="col-sm-8">
                    <b><?php echo isset($pegawai->no_induk_jf) ? $pegawai->no_induk_jf != "" ? $pegawai->no_induk_jf : "" : ''; ?></b>
                </div>
            </div>
        </div>
        <div class="control-group col-sm-12">
                      <div class="row">
                         <div class="col-sm-4">
                           Status Perkawinan
                         </div>
                          <div class="form-group col-sm-8">
                              <?php if (isset($jenis_kawins) && is_array($jenis_kawins) && count($jenis_kawins)):?>
                               <?php foreach($jenis_kawins as $record):?>
                                   <?php echo  ($pegawai->JENIS_KAWIN_ID==$record->ID) ? $record->NAMA : ""; ?>
                                   <?php endforeach;?>
                               <?php endif;?>
                          </div>

                       </div>

                    </div>


        </fieldset>
            <fieldset>
                <legend>Data Lainnya</legend>
                    <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Surat Keterangan Dokter (CPNS)
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_SURAT_DOKTER', isset($pegawai->NO_SURAT_DOKTER) ? trim($pegawai->NO_SURAT_DOKTER) : '-'); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b><?php echo set_value('TGL_SURAT_DOKTER', isset($pegawai->TGL_SURAT_DOKTER) ? trim($pegawai->TGL_SURAT_DOKTER) : '-'); ?></b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Surat Bebas Narkoba (CPNS)
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_BEBAS_NARKOBA', isset($pegawai->NO_BEBAS_NARKOBA) ? trim($pegawai->NO_BEBAS_NARKOBA) : ''); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('TGL_BEBAS_NARKOBA', isset($pegawai->TGL_BEBAS_NARKOBA) ? $pegawai->TGL_BEBAS_NARKOBA : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 No Catatan Kepolisian
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('NO_CATATAN_POLISI', isset($pegawai->NO_CATATAN_POLISI) ? trim($pegawai->NO_CATATAN_POLISI) : ''); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('TGL_CATATAN_POLISI', isset($pegawai->TGL_CATATAN_POLISI) ? $pegawai->TGL_CATATAN_POLISI : ''); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 Akte Kelahiran
                             </div>
                             <div class="form-group col-sm-4">
                                    <b><?php echo set_value('AKTE_KELAHIRAN', isset($pegawai->AKTE_KELAHIRAN) ? trim($pegawai->AKTE_KELAHIRAN) : ''); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 NO BPJS
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('BPJS', isset($pegawai->BPJS) ? $pegawai->BPJS : ''); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-12">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Taspen
                             </div>
                             <div class="form-group col-sm-8">
                                    <b><?php echo set_value('NO_TASPEN', isset($pegawai->NO_TASPEN) ? trim($pegawai->NO_TASPEN) : '-'); ?></b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 NPWP
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('NPWP', isset($pegawai->NPWP) ? $pegawai->NPWP : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>

                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                 Tanggal NPWP
                             </div>
                             <div class="form-group  col-sm-8">
                                    <b>
                                    <?php echo set_value('TGL_NPWP', isset($pegawai->TGL_NPWP) ? $pegawai->TGL_NPWP : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 NO DARURAT
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('NOMOR_DARURAT', isset($pegawai->NOMOR_DARURAT) ? $pegawai->NOMOR_DARURAT : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                    <div class="control-group col-sm-6">
                        <div class="row">
                            <div class="form-group col-sm-4">
                                Kode Pos (Sesuai KK)
                            </div>
                            <div class="col-sm-8">
                                <b><?php echo isset($pegawai->kode_pos) ? $pegawai->kode_pos : '-'; ?></b>
                            </div>
                        </div>
                    </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                 Situs Pribadi
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('situs_pribadi', isset($pegawai->situs_pribadi) ? $pegawai->situs_pribadi : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                Facebook
                             </div>
                             <div class="col-sm-8">
                                    <b>
                                    <?php echo set_value('facebook', isset($pegawai->facebook) ? $pegawai->facebook : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
                     <div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                Twitter
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('twitter', isset($pegawai->twitter) ? $pegawai->twitter : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                Google Plus
                             </div>
                             <div class="col-sm-8">
                                    <b>
                                    <?php echo set_value('google_plus', isset($pegawai->google_plus) ? $pegawai->google_plus : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                                Google Scholar
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('google_scholar', isset($pegawai->google_scholar) ? $pegawai->google_scholar : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                                LinkedIn
                             </div>
                             <div class="col-sm-8">
                                    <b>
                                    <?php echo set_value('linkedin', isset($pegawai->linkedin) ? $pegawai->linkedin : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                             ORCID
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('orcid', isset($pegawai->orcid) ? $pegawai->orcid : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                             ResearchGate
                             </div>
                             <div class="col-sm-8">
                                    <b>
                                    <?php echo set_value('research_gate', isset($pegawai->research_gate) ? $pegawai->research_gate : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-8">
                             ResearcherID
                             </div>
                             <div class="form-group  col-sm-4">
                                    <b>
                                    <?php echo set_value('researcher_id', isset($pegawai->researcher_id) ? $pegawai->researcher_id : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div><div class="control-group col-sm-6">
                         <div class="row">
                             <div class="form-group col-sm-4">
                             Scopus
                             </div>
                             <div class="col-sm-8">
                                    <b>
                                    <?php echo set_value('scopus', isset($pegawai->scopus) ? $pegawai->scopus : '-'); ?>
                                      
                                    </b>
                             </div>
                         </div>
                     </div>
            </fieldset>
              <!-- /.table-responsive -->
             
            <!-- /.box-body -->
            
            <!-- /.box-footer -->
          </div>
    </div>
 
    
    </form>
</div>
  
