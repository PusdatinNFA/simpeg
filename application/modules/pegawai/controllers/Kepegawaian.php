<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Kepegawaian extends Admin_Controller
{
    protected $permissionCreate 			= 'Pegawai.Kepegawaian.Create';
    protected $permissionViewPpnpn 			= 'Pegawai.Kepegawaian.CreatePpnpn';
    protected $permissionDelete 			= 'Pegawai.Kepegawaian.Delete';
    protected $permissionEdit   			= 'Pegawai.Kepegawaian.Edit';
    protected $permissionView   			= 'Pegawai.Kepegawaian.View';
    protected $permissionViewKawasan   		= 'Pegawai.Kawasan.View';
    protected $permissionFiltersatker   	= 'Pegawai.Kepegawaian.Filtersatker';
    protected $permissionAddpendidikan   	= 'Pegawai.Kepegawaian.Addpendidikan';
    protected $permissionUpdateMandiri   	= 'Pegawai.Kepegawaian.Updatemandiri';
	protected $permissionUpdateSimple   	= 'Pegawai.UpdateSimple.View';
    protected $permissionViewProfileOther   = 'Pegawai.Kepegawaian.LihatProfileLain';
    protected $permissionEselon1   			= 'Pegawai.Kepegawaian.permissionEselon1';
    protected $permissionUbahfoto   		= 'Pegawai.Kepegawaian.Ubahfoto';
    protected $VerifikasiUpdate   			= 'Pegawai.Kepegawaian.VerifikasiUpdate';
    protected $VerifikasiUpdate3   			= 'Pegawai.Kepegawaian.VerifikasiUpdate3';
    protected $UnitkerjaTerbatas   			= 'Unitkerja.Kepegawaian.Terbatas';
    protected $permissionCreateUser   		= 'Pegawai.CreateUser.View';
    protected $permissionViewDataBkn   		= 'Pegawai.ViewDataBkn.View';
    protected $permissionSinktonDataBkn 	= 'Pegawai.SinkronBkn.View';
    protected $permissionKarirView 			= 'Karir.Kepegawaian.View';
	protected $permissionPeremajaanView 	= 'Peremajaan.Reports.View';
	protected $viewDetailPeremajaan			= 'Pegawai.UpdateMandiri.DetailPeremajaan';
    protected $permissionViewKawasanSdm   	= 'Pegawai.KawasanSdm.View';

	public $UNOR_ID = null;
	public $UkerTerbatas = false;
	
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        

		set_time_limit(0);
		parent::__construct(); 
        $this->load->model('pegawai/pegawai_model');
		$this->load->model('pegawai/riwayat_pendidikan_model');
		$this->load->model('pegawai/riwayat_kepangkatan_model');
        $this->lang->load('pegawai');
        
        Assets::add_css('flick/jquery-ui-1.8.13.custom.css');
        Assets::add_js('jquery-ui-1.8.13.min.js');
        $this->form_validation->set_error_delimiters("<span class='has-error'>", "</span>");
        
        Template::set_block('sub_nav', 'kepegawaian/_sub_nav');

        Assets::add_module_js('pegawai', 'pegawai.js');
        
        //load referensi
        $this->load->model('pegawai/JENIS_PEGAWAI_model');
        $JENIS_PEGAWAIs = $this->JENIS_PEGAWAI_model->find_all();
		Template::set('JENIS_PEGAWAIs', $JENIS_PEGAWAIs);
		
		$this->load->model('pegawai/KEDUDUKAN_HUKUM_model');
        $KEDUDUKAN_HUKUMs = $this->KEDUDUKAN_HUKUM_model->find_all();
		Template::set('KEDUDUKAN_HUKUMs', $KEDUDUKAN_HUKUMs);
		$this->load->model('pegawai/golongan_model');
        $golongans = $this->golongan_model->find_all();
		Template::set('golongans', $golongans);

		$this->load->model('pegawai/golongan_pppk_model');
        $golongans_pppk = $this->golongan_pppk_model->find_all();
		Template::set('golongans_pppk', $golongans_pppk);	
	
		$this->load->model('pegawai/agama_model');
		
        $agamas = $this->agama_model->find_all();
		Template::set('agamas', $agamas);
		
		$this->load->model('pegawai/tingkatpendidikan_model');
        $tkpendidikans = $this->tingkatpendidikan_model->find_all();
		Template::set('tkpendidikans', $tkpendidikans);
		
		$this->load->model('pegawai/kpkn_model');
        $kpkns = $this->kpkn_model->find_all();
		Template::set('kpkns', $kpkns);
		$this->load->model('ref_jabatan/jabatan_model');
        $jabatans = $this->jabatan_model->find_all();
		Template::set('jabatans', $jabatans);
		/*
        $jabataninstansis = $this->jabatan_model->find_all();
		Template::set('jabataninstansis', $jabataninstansis);
		*/
        $this->load->model('pegawai/jenis_jabatan_model');
        $jenis_jabatans = $this->jenis_jabatan_model->find_all();
		Template::set('jenis_jabatans', $jenis_jabatans);
		$this->load->model('pegawai/jenis_kawin_model');
        $jenis_kawins = $this->jenis_kawin_model->find_all();
		Template::set('jenis_kawins', $jenis_kawins);
		$this->load->model('pegawai/kawasan_model');
		$kawasans = $this->kawasan_model->find_all();
		Template::set('kawasans', $kawasans);
		$this->load->model('pegawai/tusi_model');
		$tusis = $this->tusi_model->find_all();
		Template::set('tusis', $tusis);

		$this->load->model('masters/pengelola_sdm_kawasan_model');
		$pengelolasdms = $this->pengelola_sdm_kawasan_model->find_all();
		Template::set('pengelolasdms', $pengelolasdms);

		$this->load->model('pegawai/pendidikan_model');
		
		$this->load->model('pegawai/lokasi_model');
		$this->load->model('pegawai/unitkerja_model');
		$this->load->model('pegawai/jenis_pengadaan_model');
		
		$this->load->model('pegawai/jenis_pemberhentian_model');
		$pemberhentians = $this->jenis_pemberhentian_model->find_all();
		Template::set('jenis_pemberhentians', $pemberhentians);

		// filter untuk role yang filtersatkernya aktif
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$this->UNOR_ID = $this->pegawai_model->getunor_induk(trim($this->auth->username()));
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$this->UNOR_ID = $this->pegawai_model->getunor_eselon1(trim($this->auth->username()));
		}
    }

    /**
     * Display a list of pegawai data.
     *
     * @return void
     */
    public function index()
    {	
    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title', lang('pegawai_manage'));
		
        Template::render();
    }
    public function kawasan()
    {	
    	$this->auth->restrict($this->permissionViewKawasan);
		$akawasan = json_decode($this->auth->kawasans());
		Template::set('akawasans',$akawasan);
        Template::set('toolbar_title', "Pegawai Unitkerja");
        Template::render();
    }
	public function kawasan_sdm()
    {	
    	$this->auth->restrict($this->permissionViewKawasan);
		$kawasan_sdms = json_decode($this->auth->kawasan_sdms());
		Template::set('kawasan_sdms',$kawasan_sdms);
        Template::set('toolbar_title', "Pegawai Per Kawasan SDM");
        Template::render();
    }
    public function ppnpn()
    {	
    	$this->auth->restrict($this->permissionViewPpnpn);
        Template::set('toolbar_title', "Pegawai Non ASN");		
        Template::render();
    }
    public function pensiun()
    {	
    	$this->auth->restrict($this->permissionView);
        Template::set('toolbar_title',"Pegawai Pensiun");
		
        Template::render();
    }
    public function uploadfoto()
	{
		$PNS_ID = $this->uri->segment(5);
		$pegawaiData = $this->pegawai_model->find_by("PNS_ID",$PNS_ID);
		$PNS_NIP = ISSET($pegawaiData->NIP_BARU) ? trim($pegawaiData->NIP_BARU) : "";
		Template::set('PNS_ID', trim($PNS_ID));
		Template::set('PNS_NIP', $PNS_NIP);
		//echo $PNS_ID." PNS_ID";

		Template::render();
	}
	function savefoto(){
		$this->auth->restrict($this->permissionUbahfoto);
    	$this->load->helper('handle_upload');
		$uploadData = array();
		$upload = true;
		$id_log = $this->input->post('PNS_ID');
		$NIP = $this->input->post('PNS_NIP');
		$id = "";
		$namafile = "";
		if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
		 {
		 	$pegawaiData = $this->pegawai_model->find_by("PNS_ID",$id_log);
			$PHOTO = ISSET($pegawaiData->PHOTO) ? trim($pegawaiData->PHOTO) : "";
			//die($this->settings_lib->item('site.urlphoto').$PHOTO);
			if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$PHOTO) && $PHOTO != ""){
				deletefile($PHOTO,trim($this->settings_lib->item('site.urlphoto')));
			}

			$tmp_name 	= pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
			$uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.urlphoto')),$NIP);
			//print_r($uploadData);
			 if (isset($uploadData['error']) && !empty($uploadData['error']))
			 {
			 	$tipefile=$_FILES['userfile']['type'];
			 	//$tipefile = $_FILES['userfile']['name'];
				 $upload = false;
				//$uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.urlphoto')));
				 log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].trim($this->settings_lib->item('site.urlphoto')).$tipefile.$this->input->ip_address(), 'pegawai');
			 }else{
			 	$namafile = $uploadData['data']['file_name'];
                log_activity($this->auth->user_id(), 'upload foto : ' . $id_log . ' : ' . $this->input->ip_address(), 'pegawai');
			 }
		 }else{
		 	die("File tidak ditemukan");
		 	log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
		 } 	
		if ($namafile != "")
		{
			$dataupdate = array(
			'PHOTO'	=> $namafile
			);
			$this->pegawai_model->update_where("PNS_ID",$id_log, $dataupdate);
		}
       echo '{"namafile":"'.$namafile.'"}';
       exit();
	}
	public function importdatapegawai()
	{
		Template::set('toolbar_title', "Import data");
		Template::render();
	}
	public function importhcdp()
	{
		Template::set('toolbar_title', "Import HCDP");
		Template::render();
	}
	public function importdatabkn()
	{
		Template::set('toolbar_title', "Import data dari BKN");
		Template::render();
	}
	
	function runimport(){
    	 //$this->auth->restrict($this->permissionCreate);
    	 $this->load->helper('handle_upload');
		 $uploadData = array();
		 $upload = true;
		 $id = $this->input->post('id');
		 $message = "";
		 $index = 0;
		 $gagal = 0;
		 if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
		 {
			$tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
			$uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.pathuploaded')),$tmp_name);
			 if (isset($uploadData['error']) && !empty($uploadData['error']))
			 {
			 	$tipefile=$_FILES['userfile']['type'];
				 $upload = false;
				 $message = "Gagal upload data".$uploadData['error'];
				 log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].$this->pegawai_model->error.$tipefile.$this->input->ip_address(), 'pegawai');
			 }else{
			 	
				if(isset($uploadData['data']['file_name']))
					$file = trim($this->settings_lib->item('site.pathuploaded')).$uploadData['data']['file_name'];
				else
					$file ="";
				$this->load->library('Excel');
				$objPHPExcel = PHPExcel_IOFactory::load($file);
				 //  Get worksheet dimensions
				$sheet = $objPHPExcel->getSheet(0); 
				$highestRow = $sheet->getHighestRow(); 
				$highestColumn = $sheet->getHighestColumn();
				$itemfield = $this->db->list_fields('pegawai');
   				for ($row = 2; $row <= $highestRow; $row++)
				{ 
					//  Read a row of data into an array
					$rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row,
													  NULL,
													  TRUE,
													  FALSE);
					$data = array();
					$col = 0;
					foreach($itemfield as $field)
					{
						if(trim($rowData[0][$col]) != ''){
							$val = "";
							if($field == "TMT_CPNS" or $field == "TMT_PNS" or $field == "TMT_GOLONGAN" or $field == "TMT_JABATAN" or $field == "TGL_SK_CPNS" or $field == "TGL_SURAT_DOKTER" or $field == "TGL_BEBAS_NARKOBA" or $field == "TGL_CATATAN_POLISI" or $field == "TGL_CATATAN_POLISI" or $field == "TGL_NPWP" or $field == "TMT_PENSIUN"){
								if(trim($rowData[0][$col]) != ""){
									$valtanggal = (double)trim($rowData[0][$col]);
									$unix_date = ($valtanggal - 25569) * 86400;
									$excel_date = 25569 + ($unix_date / 86400);
									$unix_date = ($excel_date - 25569) * 86400;
									$val =  gmdate("Y-m-d", $unix_date);	
								}
								
							}
							 
							if($val != ""){
						   		$data[$field] 	= $val; 
							}
						   	else{
						   		$data[$field] 	= trim($rowData[0][$col]); 
						   	}
						}
						$col++;
					}
					if($rowData[0][1] != ""){
						$data['status_pegawai'] 	= 1; 
						if($insert_id = $this->pegawai_model->insert($data)){
							$index++;
						}else{
							$gagal++;
						}
					}
				}
				$message = "Upload Berhasil :".$index." data, gagal :".$gagal.$this->pegawai_model->error;
			 	log_activity($this->auth->user_id(), 'Berhasil  : '.$this->pegawai_model->error.$this->input->ip_address(), 'pegawai');
			 	
			 }
		 }else{
		 	log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
		 } 
		 echo '{"message":"'.$message.'"}';
		 exit();
	}
	
    /**
     * Create a pegawai object.
     *
     * @return void
     */
    public function create()
    {
        $this->auth->restrict($this->permissionCreate);
        
        if (isset($_POST['save'])) {
            if ($insert_id = $this->save_pegawai()) {
                log_activity($this->auth->user_id(), lang('pegawai_act_create_record') . ': ' . $insert_id . ' : ' . $this->input->ip_address(), 'pegawai');
                Template::set_message(lang('pegawai_create_success'), 'success');

                redirect(SITE_AREA . '/kepegawaian/pegawai');
            }

            // Not validation error
            if ( ! empty($this->pegawai_model->error)) {
                Template::set_message(lang('pegawai_create_failure') . $this->pegawai_model->error, 'error');
            }
        }

        Template::set('toolbar_title', lang('pegawai_action_create'));

        Template::render();
    }
    public function create_ppnpn()
    {
        $this->auth->restrict($this->permissionCreatePpnpn);
        Template::set('toolbar_title', "Tambah Pegawai PPNPN");

        Template::render();
    }
	public function peremajaan()
    {	
    	$this->auth->restrict($this->permissionPeremajaanView);
        Template::set('toolbar_title', "Riwayat Peremajaan Data Mandiri");
        Template::render();
    }
    public function viewupdatemandiri()
    {	
    	$this->auth->restrict($this->VerifikasiUpdate);
        Template::set('toolbar_title', "Verifikasi Update Mandiri");
        Template::set('kawasan', json_decode($this->auth->kawasans()));
        Template::render();
    }
    public function viewupdatemandiri1()
    {	
    	$this->auth->restrict($this->VerifikasiUpdate);
        Template::set('toolbar_title', "Verifikasi Update Mandiri Level Satker/Kawasan");
        Template::render();
    }
    // riwayat pendidikan
    public function addpendidikan($PNS_BKN_ID,$riwayat_pendidikan_id=NULL)
    {
        $this->auth->restrict($this->permissionAddpendidikan);
		Template::set('rwt_pendidikan', $this->riwayat_pendidikan_model->find($riwayat_pendidikan_id));
		
		Template::set('PNS_ID', $PNS_BKN_ID);
        Template::set('toolbar_title', "Tambah Riwayat Pendiikan");

        Template::render();
    }
    public function deletependidikan()
	{
		$this->auth->restrict($this->permissionAddpendidikan);
		$id 	= $this->input->post('kode');
		if ($this->riwayat_pendidikan_model->delete($id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Pendidikan : ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
	public function createdata($type = "insert"){
        $this->auth->restrict($this->permissionCreate);
        $tahun = date("Y");
        $response = array(
            'success'=>false,
            'shortmsg'=>"",
            'msg'=>'Unknown error'
        );
        $nomor = "";
        $id = $this->input->post('id');
        $this->form_validation->set_rules($this->pegawai_model->get_validation_rules());
        $extra_unique_rule = '';
		if ($id != '')
		{
			$_POST['id'] = $id;
			$extra_unique_rule = ',pegawai.ID';
		}else{
			$this->form_validation->set_rules('PNS_ID','KODE','required|max_length[36]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
			$this->form_validation->set_rules('username_intra','Username Intra','max_length[8]');
		}
        //$this->form_validation->set_rules('JENIS_KELAMIN','JENIS_KELAMIN','required|max_length[30]');
        if ($this->form_validation->run() === FALSE)
        {

            $response['msg'] = "
            <div class='alert alert-danger alert-dismissable alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error :
                </h4>
                ".validation_errors()."
            </div>
            ";
            $response['shortmsg'] = "
            <div class='alert alert-danger alert-dismissable alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                Terdapat kesalahan pada formulir, silahkan cek kembali kelengkapan data.
            </div>
            ";
        	echo json_encode($response);
            exit();
         }
            $data = $this->pegawai_model->prep_data($this->input->post());
        
	        $reclokasikerja = $this->lokasi_model->find($this->input->post('LOKASI_KERJA_ID'));
			$data['LOKASI_KERJA']	= $reclokasikerja->NAMA;
			$recpendidikan = $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
			$data['PENDIDIKAN']	= $recpendidikan->NAMA;
			
			$data['PNS_ID']	= $this->input->post('PNS_ID');
			$data['NIP_BARU']	= $this->input->post('PNS_ID');
	        $data['AGAMA_ID']	= $this->input->post('AGAMA_ID') ? $this->input->post('AGAMA_ID') : null;
			$data['TGL_LAHIR']	= $this->input->post('TGL_LAHIR') ? $this->input->post('TGL_LAHIR') : null;
			$data['TGL_NPWP']	= $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;
			$data['terminated_date']	= $this->input->post('terminated_date') ? $this->input->post('terminated_date') : null;
			
	        $return = false;
	        //die($id." ini");
	        if ($id == '') {
	            $id = $this->pegawai_model->insert($data);

	            if (is_numeric($id)) {
	                $return = $id;
	                $response ['success']= true;
                    $response ['msg']= "Berhasil Simpan data";
	            }
	        } elseif ($id != '') {
	            $return = $this->pegawai_model->update($id, $data);
	            $response ['success']= true;
                $response ['msg']= "Berhasil Update data";
	        }
        
        echo json_encode($response);
        exit();  

    }
    public function savependidikan()
    {
    	$insert_id = 0;
        $this->auth->restrict($this->permissionAddpendidikan);
		$id = $this->input->post('id_data');
		if($id != ""){
			if($this->save_pendidikan("update",$id))
			{
				 echo "Sukses Update data";
			}else{
				 echo "Gagal simpan data";
			}
        }else{
        	
        	if($id = $this->save_pendidikan()){
        		log_activity($this->auth->user_id(), 'Save riwayat Pendidikan : ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
        		echo "Sukses simpan data";
        	}else{
        		log_activity($this->auth->user_id(), 'Save riwayat Pendidikan gagal, dari' .$this->riwayat_pendidikan_model->error. $this->input->ip_address(), 'pegawai');
        		echo "Gagal ".$this->riwayat_pendidikan_model->error;
        	}
        }
       
        exit();
    }
    private function save_pendidikan($type = 'insert', $id = 0)
    {
        if ($type == 'update') {
            $_POST['id'] = $id;
        }
       
        // Make sure we only pass in the fields we want
        
        $data = $this->riwayat_pendidikan_model->prep_data($this->input->post());
		$data['PNS_ID'] 	= trim($this->input->post('PNS_ID'));
		$data['TINGKAT_PENDIDIKAN_ID'] 	= $this->input->post('TINGKAT_PENDIDIKAN_ID');
		$data['PENDIDIKAN_ID'] 	= $this->input->post('PENDIDIKAN_ID');
		$data['TANGGAL_LULUS'] 	= $this->input->post('TANGGAL_LULUS') ? $this->input->post('TANGGAL_LULUS') : null;
		$data['TAHUN_LULUS'] 	= $this->input->post('TAHUN_LULUS');
		$data['NOMOR_IJASAH'] 	= $this->input->post('NOMOR_IJASAH');
		$data['NAMA_SEKOLAH'] 	= $this->input->post('NAMA_SEKOLAH');
		$data['GELAR_DEPAN'] 	= $this->input->post('GELAR_DEPAN');
		$data['GELAR_BELAKANG'] 	= $this->input->post('GELAR_BELAKANG');
		$data['PENDIDIKAN_PERTAMA'] 	= $this->input->post('PENDIDIKAN_PERTAMA');
		$data['NEGARA_SEKOLAH'] 	= $this->input->post('NEGARA_SEKOLAH');
        // Additional handling for default values should be added below,
        

        $return = false;
        if ($type == 'insert') {
            $id = $this->riwayat_pendidikan_model->insert($data);

            if (is_numeric($id)) {
                $return = $id;
            }
        } elseif ($type == 'update') {
            $return = $this->riwayat_pendidikan_model->update($id, $data);
        }

        return $return;
    }
    /**
     * Allows editing of pegawai data.
     *
     * @return void
     */
    public function edit()
    {
    	$this->auth->restrict($this->permissionEdit);
        $id = $this->uri->segment(5);
        $pegawaiData = $this->pegawai_model->find($id);
        if (!$this->auth->has_permission($this->permissionViewProfileOther) && $this->auth->username() != $pegawaiData->NIP_BARU){
        	$pegawaiData = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
        	$id = isset($pegawaiData->ID) ? $pegawaiData->ID : "";
            Template::set_message("Anda tidak ada akses", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/profilen');
		}
        if (empty($id)) {
            Template::set_message(lang('pegawai_invalid_id'), 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/profilen');
        }
         
        Template::set('pegawai', $pegawaiData);
        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawaiData->LOKASI_KERJA_ID));
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawaiData->TEMPAT_LAHIR_ID));
        //Template::set('selectedPendidikan',$this->pendidikan_model->find($pegawaiData->PENDIDIKAN_ID));
        if(TRIM($pegawaiData->UNOR_ID) == TRIM($pegawaiData->UNOR_INDUK_ID)){
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	Template::set('selectedUnorindukid',$unor);
        }else{
        	Template::set('selectedUnorid',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID)));
        	Template::set('selectedUnorindukid',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_INDUK_ID)));
        }
        Template::set('satker_penempatan_ids',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->satker_penempatan_id)));
        //die($pegawaiData->JABATAN_INSTANSI_ID);
        Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawaiData->PENDIDIKAN_ID)));
        $datajabatan = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_ID));
        Template::set('selectedJabatanInstansiID',$datajabatan);

        $datajabatan_real = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_REAL_ID));
        Template::set('selectedJabatanInstansiRealID',$datajabatan_real);

        if($pegawaiData->JENIS_JABATAN_ID != ""){
        	//$jabataninstansis = $this->jabatan_model->find_select(trim($pegawaiData->JENIS_JABATAN_ID));
			//Template::set('jabataninstansis', $jabataninstansis);
        }
        if($pegawaiData->TK_PENDIDIKAN != ""){
        	//$pendidikans = $this->pendidikan_model->find_all(trim($pegawaiData->TK_PENDIDIKAN));
			//Template::set('pendidikans', $pendidikans);
		}
		// kepakaran dan bidang penelitian
		$this->load->model('pegawai/kepakaran_pegawai_model');
		$kepakarans = $this->kepakaran_pegawai_model->find_bidang_kepakaran($pegawaiData->NIP_BARU);
		$penelitians = $this->kepakaran_pegawai_model->find_bidang_penelitian($pegawaiData->NIP_BARU);
		Template::set('kepakarans',$kepakarans);
        Template::set('penelitians',$penelitians);

    	Template::set('recsatker', $this->unitkerja_model->find_satker());
        
        Template::set('toolbar_title', lang('pegawai_edit_heading'));
        Template::render();
    }
	/**
     * Allows editing of pegawai data.
     *
     * @return void
     */
    public function edit_mandiri()
    {
        $ref = $this->uri->segment(5);
        if (empty($ref)) {
            Template::set_message(lang('pegawai_invalid_id'), 'error');

            redirect(SITE_AREA . '/kepegawaian/pegawai');
        }
                
        $pegawaiData = $this->pegawai_model->find_by(array('ref' => $ref));
		// cek akses edit
		if (!$this->auth->has_permission("Pegawai.Kepegawaian.Edit") && $this->auth->username() != $pegawaiData->NIP_BARU){
            Template::set_message("Anda tidak ada akses", 'error');

            redirect(SITE_AREA . '/kepegawaian/pegawai/profilen');
		}
		Template::set_view('pegawai/kepegawaian/tab_pane_personaledit');
        Template::set('pegawai', $pegawaiData);
		Template::set('mandiri', 1);


        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawaiData->LOKASI_KERJA_ID));
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawaiData->TEMPAT_LAHIR_ID));
        //Template::set('selectedPendidikan',$this->pendidikan_model->find($pegawaiData->PENDIDIKAN_ID));
        if(TRIM($pegawaiData->UNOR_ID) == TRIM($pegawaiData->UNOR_INDUK_ID)){
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	Template::set('selectedUnorindukid',$unor);
        }else{
        	Template::set('selectedUnorid',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID)));
        	Template::set('selectedUnorindukid',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_INDUK_ID)));
        }
        Template::set('satker_penempatan_ids',$this->unitkerja_model->find_by("ID",TRIM($pegawaiData->satker_penempatan_id)));
        //die($pegawaiData->JABATAN_INSTANSI_ID);
        Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawaiData->PENDIDIKAN_ID)));
        $datajabatan = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_ID));
        Template::set('selectedJabatanInstansiID',$datajabatan);

        $datajabatan_real = $this->jabatan_model->find_by("KODE_JABATAN",trim($pegawaiData->JABATAN_INSTANSI_REAL_ID));
        Template::set('selectedJabatanInstansiRealID',$datajabatan_real);

        if($pegawaiData->JENIS_JABATAN_ID != ""){
        	//$jabataninstansis = $this->jabatan_model->find_select(trim($pegawaiData->JENIS_JABATAN_ID));
			//Template::set('jabataninstansis', $jabataninstansis);
        }
        if($pegawaiData->TK_PENDIDIKAN != ""){
        	//$pendidikans = $this->pendidikan_model->find_all(trim($pegawaiData->TK_PENDIDIKAN));
			//Template::set('pendidikans', $pendidikans);
		}
		$this->load->model('pegawai/kepakaran_pegawai_model');
        $kepakarans = $this->kepakaran_pegawai_model->find_bidang_kepakaran($pegawaiData->NIP_BARU);
		$penelitians = $this->kepakaran_pegawai_model->find_bidang_penelitian($pegawaiData->NIP_BARU);
		Template::set('kepakarans',$kepakarans);
        Template::set('penelitians',$penelitians);

        Template::set('toolbar_title', lang('pegawai_edit_heading'));
        Template::render();
    }
    public function edit_ppnpn()
    {
    	$this->auth->restrict($this->permissionEdit);
        $id = $this->uri->segment(5);
        if (empty($id)) {
            Template::set_message(lang('pegawai_invalid_id'), 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai_ppnpn');
        }
        
         
        $pegawaiData = $this->pegawai_model->find($id);
        Template::set('pegawai', $pegawaiData);

        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawaiData->LOKASI_KERJA_ID));
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawaiData->TEMPAT_LAHIR_ID));
        //Template::set('selectedPendidikan',$this->pendidikan_model->find($pegawaiData->PENDIDIKAN_ID));
        if(TRIM($pegawaiData->UNOR_ID) == TRIM($pegawaiData->UNOR_INDUK_ID)){
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	Template::set('selectedUnorindukid',$unor);
        }else{
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	$unor_induk = $this->unitkerja_model->find_by("ID",TRIM($pegawaiData->UNOR_INDUK_ID));
        	Template::set('selectedUnorindukid',$unor_induk);
        }
        //die($pegawaiData->JABATAN_INSTANSI_ID);
        Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawaiData->PENDIDIKAN_ID)));

        if($pegawaiData->JENIS_JABATAN_ID != ""){
        	//$jabataninstansis = $this->jabatan_model->find_select(trim($pegawaiData->JENIS_JABATAN_ID));
			//Template::set('jabataninstansis', $jabataninstansis);
        }
        if($pegawaiData->TK_PENDIDIKAN != ""){
        	//$pendidikans = $this->pendidikan_model->find_all(trim($pegawaiData->TK_PENDIDIKAN));
			//Template::set('pendidikans', $pendidikans);
		}
        Template::set('toolbar_title', "Edit Data Pegawai PPNPN");
        Template::render();
    }
    public function updatemandiri(){
         // Validate the data
		$this->load->helper('aplikasi');
    	date_default_timezone_set("Asia/Bangkok");
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $id_data = $this->input->post('ID');
        // GET DATA PEGAWAI 
        $datasekarang = $this->pegawai_model->find($id_data);
        $PNS_ID = $datasekarang->PNS_ID;
        
		$data = $this->pegawai_model->prep_data($this->input->post()); 

		$data['UPDATED_DATE']	= date("Y-m-d");
        $data['UPDATED_BY']		= $this->auth->user_id();

		foreach ($data as $key => $value) {
			if ($data[$key] == ''){
				$data[$key] = null;
			}
		}
		$response ['success']= false;
		$response ['msg']= "Update gagal";

		$kepakaran = $this->input->post('kepakaran');
		$penelitian = $this->input->post('penelitian');		
		$this->simpanKepakaran($datasekarang->NIP_BARU,$kepakaran);		
		$this->simpanPenelitian($datasekarang->NIP_BARU,$penelitian);
        // status tiga adalah level satker
		$mandiri = $this->input->post('mandiri');
		if ($mandiri){
			$this->auth->restrict($this->permissionUpdateMandiri);
			$differences = arrDiff($datasekarang,$_POST,true);

			if ($differences[1] != '[]'){
				$data_update = array();
				$data_update['PNS_ID']		= $PNS_ID;
				$data_update['KOLOM']		= 'DATA_PERSONAL';
				$data_update['DARI']		= $differences[0];
				$data_update['PERUBAHAN']	= $differences[1];
				$data_update['NAMA_KOLOM']	= 'DATA_PERSONAL';
				$data_update['LEVEL_UPDATE']= 3;
				$data_update['STATUS']		= 1;
				$data_update['UPDATE_TGL']	= date("Y-m-d");
				$data_update['UPDATED_BY']	= $this->auth->user_id();
				$id_update = $this->update_mandiri_model->insert($data_update);
			}
			$response ['success']= true;
			$response ['msg']= "Update berhasil";
		} 
		
		// else {
			// $this->auth->restrict($this->permissionUpdateSimple);
			$result = $this->pegawai_model->update($id_data,$data);
			$perubahan = arrDiff($datasekarang,$data);
			if($result){
				$response ['success']= true;
				$response ['msg']= "Update berhasil";
				
				log_activity($this->auth->user_id(),"Perubahan data pegawai : ".$datasekarang->NIP_BARU.', data perubahan :'.json_encode($perubahan), 'pegawai');
			}
			// update riwayat cpns_pns
			if ($data['status_pegawai'] == 1){
				$this->load->model('pegawai/riwayat_pns_cpns_model');
				$rwt_pns_cpns = $this->riwayat_pns_cpns_model->find_by("PNS_NIP",$data['NIP_BARU']);
				$data_pns = array();
				$data_pns['STATUS_KEPEGAWAIAN'] = $data['STATUS_CPNS_PNS'] == 'P'? "PNS" : "CPNS";
				$data_pns['TMT_CPNS'] = $data['TMT_CPNS'];
				$data_pns['TGL_SK_CPNS'] = $data['TGL_SK_CPNS'];
				$data_pns['NO_SK_CPNS'] = $data['NOMOR_SK_CPNS'];
				$data_pns['NO_SK_CPNS'] = $data['NOMOR_SK_CPNS'];
				$data_pns['TMT_PNS'] = $data['TMT_PNS'];
				$data_pns['KARPEG'] = $data['KARTU_PEGAWAI']?$data['KARTU_PEGAWAI']: null;
				$data_pns['NO_SURAT_DOKTER'] = $data['NO_SURAT_DOKTER']?$data['NO_SURAT_DOKTER']: null;
				$data_pns['KARIS_KARSU'] = $data['kartu_istri']?$data['kartu_istri']: null;

				if (empty($data_pns['TMT_CPNS'])){
					unset($data_pns['TMT_CPNS']);
				}
				if (empty($data_pns['TMT_PNS'])){
					unset($data_pns['TMT_PNS']);
				}
				if (empty($data_pns['TGL_SK_CPNS'])){
					unset($data_pns['TGL_SK_CPNS']);
				}
				if ($rwt_pns_cpns){
					if($this->riwayat_pns_cpns_model->update_where("PNS_NIP",$data['NIP_BARU'], $data_pns)){
						log_activity($this->auth->user_id(), 'Update Rwt CPNS PNS : ' . $data['NIP_BARU'] . ' : ' . $this->input->ip_address(), 'pegawai');
					}  
				} else {
					if($this->riwayat_pns_cpns_model->insert($data_pns)){
						log_activity($this->auth->user_id(), 'Insert Rwt CPNS PNS : ' . $data['NIP_BARU'] . ' : ' . $this->input->ip_address(), 'pegawai');
					} 
				}
			}
		// }
        echo json_encode($response);    
		
    }
    public function verifikasiupdatemandiri(){
         // Validate the data
    	date_default_timezone_set("Asia/Bangkok");
    	// $this->auth->restrict($this->permissionUpdateMandiri);
		if (!($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) ){
            die();
        }
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        $id_data = $this->input->post('kode');
        $status_update 	= $this->input->post('valstatus_update');
        $dataupdate 	= $this->update_mandiri_model->find($id_data);
        $PERUBAHAN 		= isset($dataupdate->PERUBAHAN) ? TRIM($dataupdate->PERUBAHAN) : "";
        $PNS_ID 		= isset($dataupdate->PNS_ID) ? TRIM($dataupdate->PNS_ID) : "";
        // GET DATA PEGAWAI 
        $data_sekarang = $this->pegawai_model->find_by('PNS_ID',$PNS_ID);
        $kolomupdate = isset($dataupdate->KOLOM) ? trim($dataupdate->KOLOM) : "";
        if($kolomupdate == "GOL_AWAL_ID")
        	$kolomupdate = "GOL_ID";
        
    	$data_update = array();
    	$data_update['VERIFIKASI_BY']	= $this->auth->user_id();
    	$data_update['VERIFIKASI_TGL']	= date("Y-m-d");
    	$data_update['STATUS']			= $status_update;
		$result = $this->update_mandiri_model->update($id_data,$data_update);
		if($status_update == "1"){
			$data_pegawai = array();
			$data_pegawai['UPDATED_DATE']	= date("Y-m-d");
        	$data_pegawai['UPDATED_BY']		= $this->auth->user_id();
			$json_perubahan = json_decode(TRIM($PERUBAHAN));
				
			if (isset($json_perubahan) && ($json_perubahan instanceof stdClass)){
				$json_perubahan = (array)$json_perubahan;

			if ($kolomupdate == 'DATA_PERSONAL'){
				
				if ($json_perubahan){
					$data_pegawai = array_merge($json_perubahan,$data_pegawai);
				} else {
					$data_pegawai[$kolomupdate]		= $PERUBAHAN ? TRIM($PERUBAHAN) : null;
				}
				$result = $this->pegawai_model->update_where("PNS_ID",$PNS_ID,$data_pegawai);
				log_activity($this->auth->user_id(),"Perubahan data pegawai : ".$data_sekarang->NIP_BARU.', data perubahan :'.$PERUBAHAN, 'pegawai');
			} else if ($kolomupdate == 'RIWAYAT_PNS'){
				$this->load->model('pegawai/Riwayat_pns_cpns_model');
				$id_rw_pns = $dataupdate->ID_TABEL;
				if (isset($id_rw_pns)){
					$this->Riwayat_pns_cpns_model->update($dataupdate->ID_TABEL, $json_perubahan);
				} else {
					$id_rw_pns = $this->Riwayat_pns_cpns_model->insert($json_perubahan);
				}
				log_activity($this->auth->user_id(),"Perubahan riwayat pns pegawai : ".$data_sekarang->NIP_BARU.', data perubahan :'.$PERUBAHAN, 'pegawai');
				//todo update data cpns pns
				$this->load->helper('aplikasi');
				$data = $this->Riwayat_pns_cpns_model->find($id_rw_pns);
				$data_pegawai = $this->pegawai_model->find_by('NIP_BARU',$data->PNS_NIP);
				$data_pegawai_update       = array();
				if($data->KARPEG != ""){
					$data_pegawai_update["KARTU_PEGAWAI"] =   $data->KARPEG;
				}
				if($data->KARIS_KARSU != ""){
					$data_pegawai_update["kartu_istri"] =   $data->KARIS_KARSU; 
				}
				if($data->NO_SK_CPNS != ""){
					$data_pegawai_update["NOMOR_SK_CPNS"] =   $data->NO_SK_CPNS;
				}
				if($data->NO_SK_PNS != ""){
					$data_pegawai_update["NO_SK_PNS"] =   $data->NO_SK_PNS;
				}
				if($data->TMT_CPNS != ""){
					$data_pegawai_update["TMT_CPNS"] =   $data->TMT_CPNS;
				}
				if($data->TMT_PNS != ""){
					$data_pegawai_update["TMT_PNS"] =   $data->TMT_PNS;
				}
				if($data->TGL_SK_CPNS != ""){
					$data_pegawai_update["TGL_SK_CPNS"] =   $data->TGL_SK_CPNS;
				}

				$perubahan = arrDiff($data_sekarang,$data_pegawai_update);
				if (sizeof($perubahan)>0){
					$this->pegawai_model->update_where("PNS_ID",$PNS_ID, $data_pegawai_update);
					log_activity($this->auth->user_id(),"Perubahan data pegawai : ".$data_sekarang->NIP_BARU.', data perubahan :'.json_encode($perubahan), 'pegawai');
				}
			}
		}
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Update berhasil";
    	}
        echo json_encode($response); 

		}
	}
    public function profile($id='')
    {
    	
    	Template::set('collapse', true);
    	if($this->auth->role_id() == "2"){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		//die($id." masuk");
    	}
    	 
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
        
        $this->load->library('convert');
 		
        $pegawai = $this->pegawai_model->find_detil($id);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
		// lokasi kerja
		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		if ($pegawai->status_pegawai == 2){
			$this->load->model('pegawai/riwayat_kepangkatan_pppk_model');
			$this->riwayat_kepangkatan_pppk_model->order_by("TMT_GOLONGAN","desc");
			$this->riwayat_kepangkatan_pppk_model->where("PNS_ID",$pegawai->PNS_ID);  
			$recordrwtpangakats = $this->riwayat_kepangkatan_pppk_model->limit(1)->find_all();
		} else {
			$this->load->model('pegawai/riwayat_kepangkatan_model');
			$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        		$this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        		$recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
		}
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		
		if($gol_id != ""){
			if ($pegawai->status_pegawai == 2){
				$recgolongan = $this->golongan_pppk_model->find(trim($gol_id));
			} else {
				$recgolongan = $this->golongan_model->find(trim($gol_id));
			}
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);

			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			if ($pegawai->status_pegawai == 2){
				$recgolongan = $this->golongan_pppk_model->find(trim($gol_awal_id));
			} else {
				$recgolongan = $this->golongan_model->find(trim($gol_awal_id));
			}
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}

		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
		Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
		Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
		 
		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Lihat Profile");
		//Template::set('view_back_button',true);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }

    public function profile2($id='')
    {
    	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if($this->auth->role_id() == "2"){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		//die($id." masuk");
    	}
    	 
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
        

       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);
		print_r($pegawai);
		die();
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);

        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));

		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
		}

		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Lihat Profile");
		//Template::set('view_back_button',true);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }
    public function profilen($id='')
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->model('pegawai/Riwayat_pns_cpns_model');
    	$model = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);
		$attention = $this->settings_lib->item('site.personal_disclaimer');
		Template::set('attention', $attention);
    	if(!$this->auth->has_permission($this->permissionViewProfileOther)){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ref) ? $pegawai->ref : "";
    	}
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }else{
        	$pegawai = $this->pegawai_model->find_by("ref",trim($id));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
        }
       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);
        $pegawai = $this->pegawai_model->find_detil($id);
        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
        Template::set('NIP_BARU', $pegawai->NIP_BARU);
        Template::set('NO_KARPEG', $pegawai->KARTU_PEGAWAI);

        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));
        Template::set('selectedLokasiPegawai',$this->lokasi_model->find($pegawai->LOKASI_KERJA_ID));
		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;

        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	if($gol_id == ""){
        		$gol_id = $golonganriwayat;
        	}
        }
        // end riwayat golongan terakhir
		if($gol_id != ""){
			if ($pegawai->status_pegawai == 2){
				$recgolongan = $this->golongan_pppk_model->find(trim($gol_id));
			} else {
				$recgolongan = $this->golongan_model->find(trim($gol_id));
			}
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);

			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			if ($pegawai->status_pegawai == 2){
				$recgolongan = $this->golongan_pppk_model->find(trim($gol_awal_id));
			} else {
				$recgolongan = $this->golongan_model->find(trim($gol_awal_id));
			}
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		// kewasan dan tusi
		if($pegawai->kawasan_id != ""){
			$kawasans = $this->kawasan_model->find($pegawai->kawasan_id);
			Template::set('kawasan_name', $kawasans->name);
		}
		if($pegawai->tusi_id != ""){
			$tusis = $this->tusi_model->find($pegawai->tusi_id);
			Template::set('tusi_name', $tusis->name);
		}
		
		$JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
		$recjabatan = null;
		$jenis_jabatan = null;

		$this->load->model('pegawai/riwayat_jabatan_model');
		if($JABATAN_INSTANSI_REAL_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
			$this->riwayat_jabatan_model->where('ID_JABATAN',$JABATAN_INSTANSI_REAL_ID);
			$this->riwayat_jabatan_model->where('TMT_JABATAN',$pegawai->TMT_JABATAN);
			$jabatan_aktif = $this->riwayat_jabatan_model->find_aktif($pegawai->NIP_BARU);
			Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
			Template::set('JABATAN_PROFIL',$jabatan_aktif[0]);
			$jenis_jabatan = isset($recjabatan->JENIS_JABATAN) ? $recjabatan->JENIS_JABATAN : "";
			
		}
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$unor 				= $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		$pemimpin_pns_id 	= isset($unor->PEMIMPIN_PNS_ID) ? $unor->PEMIMPIN_PNS_ID : "";
		$nama_jabatan_struktural = isset($unor->NAMA_JABATAN) ? $unor->NAMA_JABATAN : "";
		if($pemimpin_pns_id == $pegawai->PNS_ID or $pegawai->JENIS_JABATAN_ID == "1")
		{
			if($nama_jabatan_struktural != "")
				Template::set('NAMA_JABATAN_REAL', $nama_jabatan_struktural);
			Template::set('JENIS_JABATAN', "Struktural");
			
		}
		// satker penempatan
		Template::set('satker_penempatan_ids',$this->unitkerja_model->find_by("ID",TRIM($pegawai->satker_penempatan_id)));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$this->Riwayat_pns_cpns_model->order_by("ID","DESC");
		$riwayat_pns_cpns = $this->Riwayat_pns_cpns_model->find_by("PNS_NIP",$pegawai->NIP_BARU);
		Template::set('riwayat_pns_cpns',$riwayat_pns_cpns);

		$pengadaans = $this->jenis_pengadaan_model->find_all();
		Template::set('pengadaans',$pengadaans);

		$pengelolasdm = $this->pengelola_sdm_kawasan_model->find($pegawai->pengelola_sdm_kawasan_id);
		Template::set('pengelolasdm', $pengelolasdm);
		// jabatan aktif
		$jabatan_aktifs = $this->riwayat_jabatan_model->find_aktif($pegawai->NIP_BARU);
		Template::set('jabatan_aktifs',$jabatan_aktifs);
		// end jabatan aktif
		if(TRIM($pegawai->UNOR_ID) == TRIM($pegawai->UNOR_INDUK_ID)){
        	$unor = $this->unitkerja_model->find_by("ID",TRIM($pegawai->UNOR_ID));
        	Template::set('selectedUnorid',$unor);
        	Template::set('selectedUnorindukid',$unor);
        }else{
        	Template::set('selectedUnorid',$this->unitkerja_model->find_by("ID",TRIM($pegawai->UNOR_ID)));
        	Template::set('selectedUnorindukid',$this->unitkerja_model->find_by("ID",TRIM($pegawai->UNOR_INDUK_ID)));
        }
		// kepakaran dan bidang penelitian
		$this->load->model('pegawai/kepakaran_pegawai_model');
		$kepakarans = $this->kepakaran_pegawai_model->find_bidang_kepakaran($pegawai->NIP_BARU);
		$penelitians = $this->kepakaran_pegawai_model->find_bidang_penelitian($pegawai->NIP_BARU);
		Template::set('kepakarans',$kepakarans);
        Template::set('penelitians',$penelitians);

		if($this->auth->has_permission($this->permissionKarirView)){
			$this->load->model('kgb/vw_kgb_model');
			$PNS_ID = $pegawai->PNS_ID;
			$kgb = $this->vw_kgb_model->getEstimasi($pegawai->NIP_BARU);
			$this->pegawai_model->where('pegawai."PNS_ID"', $PNS_ID);
			$pensiun = $this->pegawai_model->find_estimasi_pensiun();

			$last_kgb = $this->vw_kgb_model->last_kgb($pegawai->NIP_BARU);
			$last_kp = $this->vw_kgb_model->last_kp($pegawai->NIP_BARU);

			$last_ak = $this->pegawai_model->getAKTerakhir($pegawai->NIP_BARU);

			Template::set('last_ak',$last_ak);
			Template::set('last_kgb',$last_kgb);
			Template::set('last_kp',$last_kp);
			Template::set('kgb',$kgb[0]);
			Template::set('pensiun',$pensiun[0]);
		}
    	// $this->load->model('pegawai/update_mandiri_model');
		// $belum_diverifikasi = $this->update_mandiri_model->find_belum_diverifikasi($pegawai->PNS_ID);
        Template::set('show_count_pdm', SHOW_COUNT_PDM);
		$masa_kerja_kp = $this->pegawai_model->get_masa_kerja_kp($pegawai->NIP_BARU);
		// Template::set('belum_diverifikasi',$belum_diverifikasi);
		Template::set('masa_kerja_kp',$masa_kerja_kp);
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Profile Pegawai");
		Template::set('model',$model);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }
    public function profileppnpn($id='')
    {
    	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if($this->auth->role_id() == "2"){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		//die($id." masuk");
    	}
    	 
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            Template::set_message(lang('pegawai_invalid_id'), 'error');
            	redirect(SITE_AREA . '/kepegawaian/pegawai');
            }
        }
        

       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);

        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);

        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));

		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}
		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		$JABATAN_INSTANSI_REAL_ID = $pegawai->JABATAN_INSTANSI_REAL_ID;
		if($JABATAN_INSTANSI_REAL_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_INSTANSI_REAL_ID));
			Template::set('NAMA_JABATAN_REAL', $recjabatan->NAMA_JABATAN);
		}

		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));

			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
		}

		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Profile Pegawai");
		//Template::set('view_back_button',true);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }
    public function profile_bkn($pns_id='')
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$this->load->model('pegawai/Riwayat_pns_cpns_model');
    	$model = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);

        $pegawai = $this->pegawai_model->find_by("PNS_ID",$pns_id);
		$id = isset($pegawai->ID) ? $pegawai->ID : "";
		$nip_baru = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : "";
		
       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);

        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
		$data_utama_pegawai = $this->cache->get('data_utama_'.$nip_baru);
		$data_golongan = $this->cache->get('golongan_'.$nip_baru);
		$data_pasangan = $this->cache->get('data_pasangan_'.$nip_baru);
		$data_penghargaan = $this->cache->get('data_penghargaan_'.$nip_baru);
		$data_skp = $this->cache->get('data_rwt_skp_'.$nip_baru);
		$data_pendidikan = $this->cache->get('rwt_pendidikan_'.$nip_baru);
		$data_jabatan = $this->cache->get('rwt_jabatan_'.$nip_baru);
		$data_diklat = $this->cache->get('rwt_diklat_'.$nip_baru);
		$data_masa_kerja = $this->cache->get('rwt_masakerja_'.$nip_baru);
		$data_pwk = $this->cache->get('rwt_pwk_'.$nip_baru);
		$data_ppo_hist = $this->cache->get('ppo_sk_hist_'.$nip_baru);
		$data_kpo_sk_hist = $this->cache->get('data_kpo_sk_hist_'.$nip_baru);
		$data_hukdis = $this->cache->get('data_rwt_hukdis_'.$nip_baru);
		$data_pns_unor = $this->cache->get('data_rwt_pnsunor_'.$nip_baru);
		$data_anak = $this->cache->get('data_anak_'.$nip_baru);
		$data_ortu = $this->cache->get('data_ortu_'.$nip_baru);
		$data_dp3 = $this->cache->get('data_dp3_'.$nip_baru);

		Template::set('pegawai_bkn', $data_utama_pegawai);
		Template::set('data_golongan', $data_golongan);
		Template::set('data_penghargaan', $data_penghargaan);
		Template::set('data_skp', $data_skp);
		Template::set('data_pasangan', $data_pasangan);
		Template::set('data_pendidikan', $data_pendidikan);
		Template::set('data_jabatan', $data_jabatan);
		Template::set('data_diklat', $data_diklat);
		Template::set('data_masa_kerja', $data_masa_kerja);
		Template::set('data_pwk', $data_pwk);
		Template::set('data_ppo_hist', $data_ppo_hist);
		Template::set('data_kpo_sk_hist', $data_kpo_sk_hist);
		Template::set('data_hukdis', $data_hukdis);
		Template::set('data_pns_unor', $data_pns_unor);
		Template::set('data_anak', $data_anak);
		Template::set('data_dp3', $data_dp3);

		

		Template::set('foto_pegawai', $foto_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('PNS_ID', $pegawai->PNS_ID);
        Template::set_view('bkn/profile_bkn');

        Template::render();
    }
	public function perbandingan($ref)
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
		$lokasis = $this->lokasi_model->find_all();

        $pegawai = $this->pegawai_model->find_by("ref", $ref);
		$id = isset($pegawai->ID) ? $pegawai->ID : "";
		$nip_baru = isset($pegawai->NIP_BARU) ? $pegawai->NIP_BARU : "";

        $pegawai = $this->pegawai_model->find_detil($id);

		$data_utama_pegawai = Modules::run('pegawai/Bkn/getdata_utama_bkn', $nip_baru);
		// $data_utama_pegawai = $this->cache->get('data_utama_'.$nip_baru);

		Template::set('pegawai_bkn', $data_utama_pegawai);
        Template::set('pegawai', $pegawai);
        Template::set('lokasis', $lokasis);
        Template::set_view('bkn/perbandingan_profile');

        Template::render();
    }
    public function review($id='')
    {	
    	$this->load->model('pegawai/Pns_aktif_model');
    	$ID2 = $this->uri->segment(6);
    	$this->load->library('convert');
    	Template::set('collapse', true);
    	if(!$this->auth->has_permission($this->permissionViewProfileOther)){
    		$pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    	}
        if (empty($id)) {
            $pegawai = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
    		$id = isset($pegawai->ID) ? $pegawai->ID : "";
    		if($id == ""){
	            $id = "0";
            }
        }

       	$recpns_aktif = $this->Pns_aktif_model->find($id);
 		Template::set('recpns_aktif', $recpns_aktif);

        $pegawai = $this->pegawai_model->find_detil($id);
        Template::set('pegawai', $pegawai);
        $PNS_ID = isset($pegawai->PNS_ID) ? $pegawai->PNS_ID : "";
        $selectedpegawai = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID));
        Template::set('selectedpegawai',$selectedpegawai);
        // pns 2

        $recpns_aktif2 = $this->Pns_aktif_model->find($ID2);
 		Template::set('recpns_aktif2', $recpns_aktif2);

        $pegawai2 = $this->pegawai_model->find_detil($ID2);
        Template::set('pegawai2', $pegawai2);

        $PNS_ID2 = isset($pegawai2->PNS_ID) ? $pegawai2->PNS_ID : "";
        $selectedpegawai2 = $this->pegawai_model->find_by("PNS_ID",trim($PNS_ID2));
        Template::set('selectedpegawai2',$selectedpegawai2);


        $foto_pegawai =  END_POINT_PHOTO.$pegawai->username_intra;
		Template::set('foto_pegawai', $foto_pegawai);

		$foto_pegawai2 =  END_POINT_PHOTO.$pegawai2->username_intra;
		Template::set('foto_pegawai2', $foto_pegawai2);
        
        Template::set('PNS_ID', $pegawai->PNS_ID);
        //DIE($pegawai2->NAMA);
        Template::set('selectedTempatLahirPegawai',$this->lokasi_model->find($pegawai->TEMPAT_LAHIR_ID));
        Template::set('selectedTempatLahirPegawai2',$this->lokasi_model->find($pegawai2->TEMPAT_LAHIR_ID));

		// cek dari riwayat golongan terakhir
		// jika golongan riwayat lebih tinggi dengan golongan yang di pegawai maka pakai golongan yang di riwayat
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai->PNS_ID);  
        $recordrwtpangakats = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat = isset($recordrwtpangakats[0]->ID_GOLONGAN) ? trim($recordrwtpangakats[0]->ID_GOLONGAN) : "";
        $gol_id = $pegawai->GOL_ID;
        if((int)$golonganriwayat > (int)$pegawai->GOL_ID){
        	$gol_id = $golonganriwayat;
        }
        // end riwayat golongan terakhir
        
		if($gol_id != ""){
			$recgolongan = $this->golongan_model->find(trim($gol_id));
			Template::set('GOLONGAN_AKHIR', $recgolongan->NAMA);
			Template::set('NAMA_PANGKAT', $recgolongan->NAMA_PANGKAT);
			
		}


		$this->riwayat_kepangkatan_model->order_by("TMT_GOLONGAN","desc");
        $this->riwayat_kepangkatan_model->where("PNS_ID",$pegawai2->PNS_ID);  
        $recordrwtpangakats2 = $this->riwayat_kepangkatan_model->limit(1)->find_all();
        $golonganriwayat2 = isset($recordrwtpangakats2[0]->ID_GOLONGAN) ? trim($recordrwtpangakats2[0]->ID_GOLONGAN) : "";
		$gol_id2 = $pegawai2->GOL_ID;
        if((int)$golonganriwayat2 > (int)$pegawai2->GOL_ID){
        	$gol_id2 = $golonganriwayat2;
        }
        // end riwayat golongan terakhir
        
		if($gol_id2 != ""){
			$recgolongan2 = $this->golongan_model->find(trim($gol_id2));
			Template::set('GOLONGAN_AKHIR2', $recgolongan2->NAMA);
			Template::set('NAMA_PANGKAT2', $recgolongan2->NAMA_PANGKAT);
			
		}


		$gol_awal_id = $pegawai->GOL_AWAL_ID;
		if($gol_awal_id != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id);
			Template::set('GOLONGAN_AWAL', $recgolongan->NAMA);
		}
		$gol_awal_id2 = $pegawai2->GOL_AWAL_ID;
		if($gol_awal_id2 != ""){
			$recgolongan = $this->golongan_model->find($gol_awal_id2);
			Template::set('GOLONGAN_AWAL2', $recgolongan->NAMA);
		}

		$jenis_jabatan = $pegawai->JENIS_JABATAN_ID;
		if($jenis_jabatan != ""){
			$recjenis_jabatan = $this->jenis_jabatan_model->find($jenis_jabatan);
			Template::set('JENIS_JABATAN', $recjenis_jabatan->NAMA);
		}
		$jenis_jabatan2 = $pegawai2->JENIS_JABATAN_ID;
		if($jenis_jabatan2 != ""){
			$recjenis_jabatan2 = $this->jenis_jabatan_model->find($jenis_jabatan2);
			Template::set('JENIS_JABATAN2', $recjenis_jabatan2->NAMA);
		}

		$JABATAN_ID = $pegawai->JABATAN_INSTANSI_ID;
		if($JABATAN_ID != ""){
			$recjabatan = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID));
			Template::set('NAMA_JABATAN', $recjabatan->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN', $recjabatan->NAMA_JABATAN_BKN);
		}

		$JABATAN_ID2 = $pegawai2->JABATAN_INSTANSI_ID;
		if($JABATAN_ID2 != ""){
			$recjabatan2 = $this->jabatan_model->find_by("KODE_JABATAN",TRIM($JABATAN_ID2));
			Template::set('NAMA_JABATAN2', $recjabatan2->NAMA_JABATAN);
			Template::set('NAMA_JABATAN_BKN2', $recjabatan2->NAMA_JABATAN_BKN);
		}

		$unor = $this->unitkerja_model->find_by("ID",trim($pegawai->UNOR_ID));
		Template::set('nama_unor',$unor->NAMA_UNOR);
		$unor_induk = $this->unitkerja_model->find_by("ID",$unor->DIATASAN_ID);
		Template::set('unor_induk',$unor_induk->NAMA_UNOR);
		
		Template::set('selectedPendidikanID',$this->pendidikan_model->find(trim($pegawai->PENDIDIKAN_ID)));
		Template::set('selectedPendidikanID2',$this->pendidikan_model->find(trim($pegawai2->PENDIDIKAN_ID)));
		
		Template::set("parent_path_array_unor",$this->unitkerja_model->get_parent_path($unor->ID,true,true));
        Template::set('toolbar_title',"Review Pegawai");
		Template::set('model',$model);
		Template::set('back_button_label',"<< Kembali ke Daftar Pegawai");
		Template::set('back_button_url',base_url("admin/kepegawaian/pegawai"));
        Template::render();
    }
    //--------------------------------------------------------------------------
    // !PRIVATE METHODS
    //--------------------------------------------------------------------------

    /**
     * Save the data.
     *
     * @param string $type Either 'insert' or 'update'.
     * @param int    $id   The ID of the record to update, ignored on inserts.
     *
     * @return boolean|integer An ID for successful inserts, true for successful
     * updates, else false.
     */
    public function getdata(){
    	$this->auth->restrict($this->permissionView);
    	$this->load->helper('aplikasi');
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		$anama_jabatan = array();
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				// echo $filter['name'];
				if($filter['name'] == 'nama_jabatan[]'){
					array_push($anama_jabatan,$filter["value"]);
				}else{
					$filter['name'] = $filter["value"];
				}
			}
			
		}
		
		$kedudukan_hukum = "";
		$this->db->start_cache();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		$jabatan_nama  = $this->input->post("jabatan_nama");
		$fkawasans = array();
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				if ($filter['name'] == 'kawasan[]'){
					$fkawasans[] = $filter["value"];
				} else
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key'] != ""){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key'] != ""){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_key'] != ""){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
	
			if($filters['golongan_key'] != ""){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['username_intra'] != ""){
				$this->pegawai_model->where('lower("username_intra")',strtolower($filters['username_intra']));	
			}
			if($filters['tingkat_pendidikan'] != ""){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_jabatan'] != ""){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum'] != ""){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
			if(sizeof($anama_jabatan) > 0){
				$this->pegawai_model->where_in('"JABATAN_INSTANSI_REAL_ID"',$anama_jabatan);	
			}
			if (count($fkawasans) > 0){
				$this->pegawai_model->where_in('kawasan_id',$fkawasans);
			}
			if($filters['status_pegawai'] != ""){
				$this->pegawai_model->where_in('status_pegawai',$filters['status_pegawai']);	
			}
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
		}
				
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->satkers());
			$total= $this->pegawai_model->count_all($asatkers,false,$kedudukan_hukum);
		}else{
			$total= $this->pegawai_model->count_all($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("NAMA_JABATAN",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->satkers());
			$records=$this->pegawai_model->find_all($asatkers,false,$kedudukan_hukum);
		}else{
			$records=$this->pegawai_model->find_all($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU."<br><i><b>".$record->NAMA."</b></i>";
                $row []  = $record->NAMA_JABATAN;
                $row []  = $record->status_pegawai == 2 ? $record->NAMA_GOLONGAN : $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = reverseUnor($record->NAMA_UNOR_FULL);
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>";
				if ($this->auth->has_permission('Pegawai.Downloaddrh.View')) {
					$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/download_drh/".$record->PNS_ID."' class='btn btn-sm btn-success' target='_BLANK'><i class='glyphicon glyphicon-download'></i> </a>";
				}
                if($this->auth->has_permission("Pegawai.Kepegawaian.Edit")){
                	$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/edit/".$record->ID."' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionSinktonDataBkn)){
                	$btn_actions  [] = "<a href='#".$record->NIP_BARU."' class='btn btn-sm btn-success generatedatabkn' data-placement='top' data-original-title='Sinkron data BKN'  title='Sinkron data BKN' kode='$record->NIP_BARU' ><i class='glyphicon glyphicon-refresh'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionViewDataBkn)){
                	$btn_actions  [] = "<a href='#".$record->NIP_BARU."' kode='$record->NIP_BARU' class='btn btn-sm btn-warning viewdatabkn' data-toggle='tooltip' data-placement='top' data-original-title='Sinkron data BKN' title='Sinkron data BKN'><i class='glyphicon glyphicon-refresh'></i> </a>";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdata_kawasan(){
    	$this->auth->restrict($this->permissionViewKawasan);
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/kawasan');
		}
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
		}
		$kedudukan_hukum = "";
		$this->db->start_cache();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key'] != ""){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key'] != ""){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_key'] != ""){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
	
			if($filters['golongan_key'] != ""){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan'] != ""){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_jabatan'] != ""){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum'] != ""){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->satkers());
			$total= $this->pegawai_model->count_all($asatkers,false,$kedudukan_hukum);
		}else{
			$total= $this->pegawai_model->count_all($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->satkers());
			$records=$this->pegawai_model->find_all($asatkers,false,$kedudukan_hukum);
		}else{
			$records=$this->pegawai_model->find_all($this->UNOR_ID,false,$kedudukan_hukum);
		}
		
		
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_UNOR_FULL."<br>".$record->pengelola_sdm_kawasan_id.'</b>';
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>";
				if ($this->auth->has_permission('Pegawai.Downloaddrh.View')) {
					$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/download_drh/".$record->PNS_ID."' class='btn btn-sm btn-success'  target='_BLANK'><i class='glyphicon glyphicon-download'></i> </a>";
				}
				if($this->auth->has_permission("Pegawai.Kepegawaian.Edit")){
                	$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/edit/".$record->ID."' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionSinktonDataBkn)){
                	$btn_actions  [] = "<a href='#".$record->NIP_BARU."' class='btn btn-sm btn-success generatedatabkn' data-placement='top' data-original-title='Sinkron data BKN'  title='Sinkron data BKN' kode='$record->NIP_BARU' ><i class='glyphicon glyphicon-refresh'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionViewDataBkn)){
                	$btn_actions  [] = "<a href='#".$record->NIP_BARU."' kode='$record->NIP_BARU' class='btn btn-sm btn-warning viewdatabkn' data-toggle='tooltip' data-placement='top' data-original-title='Sinkron data BKN' title='Sinkron data BKN'><i class='glyphicon glyphicon-refresh'></i> </a>";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getpensiundata(){
    	$this->auth->restrict($this->permissionView);
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/pensiun');
		}
		$this->load->helper('aplikasi');
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
		}

		$this->db->start_cache();
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key'] != ""){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key'] != ""){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_key'] != ""){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
	
			if($filters['golongan_key'] != ""){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan'] != ""){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_jabatan'] != ""){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum'] != ""){
				$this->pegawai_model->where('"KEDUDUKAN_HUKUM_ID"',$filters['kedudukan_hukum']);	
			}
			if($filters['jenis_pemberhentian'] != ""){
				$this->pegawai_model->where('"JENIS_PEMBERHENTIAN"',$filters['jenis_pemberhentian']);	
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->pegawai_model->count_pensiun_all($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("pegawai.NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_PANGKAT",$order['dir']);
			}
			if($order['column']==4){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		$records=$this->pegawai_model->find_pensiun_all($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
				$tmt = $record->TMT_PENSIUN?$record->TMT_PENSIUN:$record->terminated_date;
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_PANGKAT."/".$record->NAMA_GOLONGAN;
                $row []  = $record->kedudukan_hukum."<br> <b>TMT ".getIndonesiaFormat($tmt).'</b>';
                $row []  = $record->jenis_pemberhentian;
                $row []  = $record->NAMA_UNOR_FULL;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>";
				if ($this->auth->has_permission('Pegawai.Downloaddrh.View')) {
					$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/download_drh/".$record->PNS_ID."' class='btn btn-sm btn-success'  target='_BLANK'><i class='glyphicon glyphicon-download'></i> </a>";
				}
				if($this->auth->has_permission("Pegawai.Kepegawaian.Edit")){
                	$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/edit/".$record->ID."' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionSinktonDataBkn)){
                	$btn_actions  [] = "<a href='#".$record->NIP_BARU."' class='btn btn-sm btn-success generatedatabkn' data-placement='top' data-original-title='Sinkron data BKN'  title='Sinkron data BKN' kode='$record->NIP_BARU' ><i class='glyphicon glyphicon-refresh'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionViewDataBkn)){
                	$btn_actions  [] = "<a href='#".$record->NIP_BARU."' kode='$record->NIP_BARU' class='btn btn-sm btn-warning viewdatabkn' data-toggle='tooltip' data-placement='top' data-original-title='Sinkron data BKN' title='Sinkron data BKN'><i class='glyphicon glyphicon-refresh'></i> </a>";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function downloadpensiun(){
    	$this->auth->restrict($this->permissionView);
		$this->load->helper('aplikasi');
		
		$filters  = $_GET;
		if ($filters) {
			$this->db->start_cache();
			if($filters['unit_id_key'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key'] != ""){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key'] != ""){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_key'] != ""){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}

			if($filters['golongan_key'] != ""){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan'] != ""){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_jabatan'] != ""){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum'] != ""){
				$this->pegawai_model->where('"KEDUDUKAN_HUKUM_ID"',$filters['kedudukan_hukum']);	
			}
			if($filters['jenis_pemberhentian'] != ""){
				$this->pegawai_model->where('"JENIS_PEMBERHENTIAN"',$filters['jenis_pemberhentian']);	
			}
			$this->db->stop_cache();
		}
		
		$records=$this->pegawai_model->find_pensiun_all();
		$this->db->flush_cache();
		$this->load->library('Excel');
		$objPHPExcel = new PHPExcel();

		$objPHPExcel->setActiveSheetIndex(0);

		// $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,1,"Template database ".$tte->nama_proses);

		$type = PHPExcel_Cell_DataType::TYPE_STRING;
		$row = 1;

		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $row)->setValueExplicit("No", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $row)->setValueExplicit("Nama", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit("NIP", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $row)->setValueExplicit("Pangkat", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $row)->setValueExplicit("Golongan", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $row)->setValueExplicit("TMT", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(6, $row)->setValueExplicit("Kedudukan Hukum", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(7, $row)->setValueExplicit("Jenis Pemberhentian", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->setValueExplicit("Satuan Kerja", $type);
		$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(8, $row)->setValueExplicit("Email", $type);
		$no = 1;
		// $keys = array_keys($records);
		foreach ($records as $record) {
			$row++;
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $no);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $record->NAMA);
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $row)->setValueExplicit($record->NIP_BARU);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $record->NAMA_PANGKAT);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $record->NAMA_GOLONGAN);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $record->TMT_PENSIUN?getIndonesiaFormat($record->TMT_PENSIUN):getIndonesiaFormat($record->terminated_date));
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $record->kedudukan_hukum);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $record->jenis_pemberhentian);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $record->NAMA_UNOR_FULL);
			$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $record->EMAIL);
			$no++;
		}

		$filename = "Pegawai Pensiun " . date('Ymdhi') . '.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
		//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
		ob_end_clean();
		$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
		exit; //done.. exiting!
		
	}
	public function getdataupdatemandiri(){
    	$this->auth->restrict($this->VerifikasiUpdate);
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
		}

		$this->db->start_cache();
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			$fkawasans = array();
			foreach($advanced_search_filters as  $filter){
				if ($filter['name'] == 'kawasans[]'){
					$kawasans[] = $filter["value"];
				} else
					$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip']),"BOTH");	
			}
			if($filters['status_cb'] != ''){
				if ($filters['status_cb'] == -1)
					$this->update_mandiri_model->where('STATUS is null');	
				else 
					$this->update_mandiri_model->where('STATUS',$filters['status_cb']);	
			}
			if($filters['data']){
				$this->update_mandiri_model->where('NAMA_KOLOM',$filters['data']);	
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nama_key']).'%\'');	
			}
			if($filters['golongan_key']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			
			if(count($fkawasans)>0){
				$this->update_mandiri_model->where_in("pegawai.kawasan_id",$fkawasans);	
			}
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
			
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif($this->UNOR_ID);
		$orders = $this->input->post('order');
		// foreach($orders as $order){
		// 	if($order['column']==1){
		// 		$this->update_mandiri_model->order_by("NIP_BARU",$order['dir']);
		// 	}
		// 	if($order['column']==2){
		// 		$this->update_mandiri_model->order_by("pegawai.NAMA",$order['dir']);
		// 	}
		// 	if($order['column']==3){
		// 		$this->update_mandiri_model->order_by("NAMA_PANGKAT",$order['dir']);
		// 	}
		// 	if($order['column']==4){
		// 		$this->update_mandiri_model->order_by("NAMA_UNOR",$order['dir']);
		// 	}
		// }
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);
		$records=$this->update_mandiri_model->find_notif($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$keterangan = $record->KETERANGAN?'<b>'.$record->KETERANGAN.'</b><br>':'';
				if ($record->STATUS != '' && $record->VERIFIKASI_BY){
					$keterangan .= 'Diverifikasi oleh : '.$record->verifikator.' pada '.$record->VERIFIKASI_TGL;
				}
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_KOLOM;
                $row []  = $keterangan;
                $btn_actions = array();
                if($this->auth->has_permission($this->VerifikasiUpdate) or $this->auth->has_permission($this->VerifikasiUpdate3)){
                	$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/viewupdate/".$record->ID."' class='btn btn-sm btn-warning modal-custom-global' data-toggle='tooltip' data-placement='top' data-original-title='Lihat perubahan data' title='Lihat Perubahan data' tooltip='Lihat'><i class='fa fa-eye'></i> </a>";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete") and $this->auth->has_permission($this->VerifikasiUpdate3)){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdataupdatemandirisatker(){
    	$this->auth->restrict($this->VerifikasiUpdate);
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			//Template::set_message("Hanya request ajax", 'error');
            //redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
		}

		$kawasan_sdm = $this->auth->kawasan_sdms();
		$this->db->start_cache();
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip']),"BOTH");	
			}
			if($filters['data']){
				$this->update_mandiri_model->where('NAMA_KOLOM',$filters['data']);	
			}
			if($filters['status_cb'] != ''){
				if ($filters['status_cb'] == -1)
					$this->update_mandiri_model->where('STATUS is null');	
				else 
					$this->update_mandiri_model->where('STATUS',$filters['status_cb']);	
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nama_key']).'%\'');	
			}
			if($filters['golongan_key']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			
		}

		// ubah pembagian kawasan
		if ($kawasan_sdm){
			if (in_array(1,json_decode($kawasan_sdm))){
				$this->db->group_start();
				$this->update_mandiri_model->where_in('pegawai.pengelola_sdm_kawasan_id',json_decode($kawasan_sdm));
				$this->update_mandiri_model->or_where('pegawai.pengelola_sdm_kawasan_id is null');
				$this->db->group_end();
			} else {
				$this->update_mandiri_model->where_in('pegawai.pengelola_sdm_kawasan_id',json_decode($kawasan_sdm));
			}
		} else {
			$this->update_mandiri_model->where_in('pegawai.kawasan_id',json_decode($this->auth->kawasans()));	
		}

		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif1($this->UNOR_ID);
		$orders = $this->input->post('order');
		// foreach($orders as $order){
		// 	if($order['column']==1){
		// 		$this->update_mandiri_model->order_by("NIP_BARU",$order['dir']);
		// 	}
		// 	if($order['column']==2){
		// 		$this->update_mandiri_model->order_by("pegawai.NAMA",$order['dir']);
		// 	}
		// 	if($order['column']==3){
		// 		$this->update_mandiri_model->order_by("NAMA_PANGKAT",$order['dir']);
		// 	}
		// 	if($order['column']==4){
		// 		$this->update_mandiri_model->order_by("NAMA_UNOR",$order['dir']);
		// 	}
		// }
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);
		$records=$this->update_mandiri_model->find_notif1($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
				$keterangan = $record->KETERANGAN?'<b>'.$record->KETERANGAN.'</b><br>':'';
				if ($record->STATUS != '' && $record->VERIFIKASI_BY){
					$keterangan .= 'Diverifikasi oleh : '.$record->verifikator.' pada '.$record->VERIFIKASI_TGL;
				}
                $row []  = $nomor_urut.".";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $row []  = $record->NAMA_KOLOM;
                $row []  = $keterangan;
                
                $btn_actions = array();

                if($this->auth->has_permission($this->VerifikasiUpdate) or $this->auth->has_permission($this->VerifikasiUpdate3)){
                	$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/viewupdate/".$record->ID."' class='btn btn-sm btn-warning modal-custom-global' data-toggle='tooltip' data-placement='top' data-original-title='Lihat perubahan data' title='Lihat Perubahan data' tooltip='Lihat'><i class='fa fa-eye'></i> </a>";
                }
                if($this->auth->has_permission("Pegawai.Kepegawaian.Delete")){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdataperemajaan(){
    	$this->auth->restrict($this->permissionPeremajaanView);
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
		}

		$this->db->start_cache();
		// $user = $this->pegawai_model->find_by("NIP_BARU",trim($this->auth->username()));
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['data']){
				$this->update_mandiri_model->where('NAMA_KOLOM',$filters['data']);	
			}
			if($filters['status']){
				if ($filters['status'] == 4 || $filters['status'] == 1)
					$this->update_mandiri_model->where('"STATUS"',$filters['status']);
				else if ($filters['status'] == 3) {
					// $this->update_mandiri_model->where('"LEVEL_UPDATE"',$filters['status']);
					$this->update_mandiri_model->where('"STATUS" is null');
				} 
			}
		}
		$this->update_mandiri_model->where('pegawai.NIP_BARU',$this->auth->username());
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif_pribadi();
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);
		$records=$this->update_mandiri_model->find_notif_pribadi();
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$status = '<label class="label label-info">Belum diverifikasi</label>';
				if ($record->STATUS == 1) {
					$status = '<label class="label label-success">Sudah diverifikasi</label>';
				} if ($record->STATUS == 4) {
					$status = '<label class="label label-warning">Dibatalkan Pegawai</label>';
				} if ($record->STATUS != '' && $record->STATUS == 0) {
					$status = '<label class="label label-danger">Ditolak Verifikator</label>';
				}
				$keterangan = $record->KETERANGAN?'<b>'.$record->KETERANGAN.'</b><br>':'';
				if ($record->STATUS != '' && $record->VERIFIKASI_BY){
					$keterangan .= 'Diverifikasi oleh : '.$record->verifikator.' pada '.$record->VERIFIKASI_TGL;
				}
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->UPDATE_TGL;
                $row []  = $record->NAMA_KOLOM;
                // $row []  = $record->DARI;
                $row []  = $status;
                $row []  = $keterangan;
                $btn_actions = array();
				$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/viewupdate/".$record->ID."/view' class='btn btn-sm btn-warning show-modal' data-toggle='tooltip' title='Lihat perubahan' tooltip='Lihat perubahan'><i class='fa fa-eye'></i> </a>";
				if ($record->STATUS == '')
					$btn_actions  [] = "<a href='#' kode='".$record->ID."' class='btn btn-sm btn-danger btn-delete' data-toggle='tooltip' title='Batalkan perubahan' tooltip='Batalkan perubahan' ><i class='fa fa-ban'></i> </a>";
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function getdatappnpn(){
		$this->auth->restrict($this->permissionViewPpnpn);
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/ppnpn');
		}
    	
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$searchKey = isset($_REQUEST['search']["key"]) ? $_REQUEST['search']["key"] : "";

		$selectedUnors = array();
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			
		}

		$this->db->start_cache();
		
		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		$advanced_search_filters  = $this->input->post("search[advanced_search_filters]");
		if($advanced_search_filters){
			$filters = array();
			foreach($advanced_search_filters as  $filter){
				$filters[$filter['name']] = $filter["value"];
			}
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['pns_id']){
				$this->pegawai_model->like('upper("PNS_ID")',strtoupper($filters['pns_id']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->pegawai_model->count_all_ppnpn($this->UNOR_ID);
		$orders = $this->input->post('order');
		foreach($orders as $order){
			if($order['column']==1){
				$this->pegawai_model->order_by("NIP_BARU",$order['dir']);
			}
			if($order['column']==2){
				$this->pegawai_model->order_by("NAMA",$order['dir']);
			}
			if($order['column']==3){
				$this->pegawai_model->order_by("NAMA_UNOR",$order['dir']);
			}
		}
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->pegawai_model->limit($length,$start);
		$records=$this->pegawai_model->find_all_ppnpn($this->UNOR_ID);
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = $record->username_intra;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_UNOR_FULL;
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>";

                if($this->auth->has_permission("Pegawai.Kepegawaian.Edit")){
                	$btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/edit/".$record->ID."' class='btn btn-sm btn-warning'><i class='glyphicon glyphicon-edit'></i> </a>";
                }
                
                if($this->auth->has_permission("Pegawai.Kepegawaian.Deleteppnpn")){
                	$btn_actions  [] = "<a kode='$record->ID' class='btn btn-sm btn-danger btn-hapus' data-toggle='tooltip' data-placement='top' data-original-title='Hapus data' title='Hapus data' tooltip='Hapus'><i class='glyphicon glyphicon-remove'></i> </a>";
                }
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		
	}
	public function download_drh($pns_id = null)
	{
		$this->load->model('pegawai/orang_tua_model');
		$this->load->model('pegawai/istri_model');
		$this->load->model('pegawai/anak_model');
		$this->load->model('pegawai/riwayat_jabatan_model');
		$this->load->model('pegawai/riwayat_kepangkatan_model');
		$this->load->model('pegawai/riwayat_pendidikan_model');
		$this->load->model('pegawai/riwayat_aktivitas_ilmiah_model');
		$this->load->model('pegawai/riwayat_karyatulis_ilmiah_model');
		$this->load->model('pegawai/riwayat_huk_dis_model');
		$this->load->model('pegawai/riwayat_penghargaan_model');
		$this->load->model('pegawai/diklat_fungsional_model');
		$this->load->model('pegawai/diklat_struktural_model');
		$this->load->model('pegawai/riwayat_kgb_model');
		$this->load->model('pegawai/riwayat_cltn_model');
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$this->load->model('pegawai/riwayat_pindah_unit_kerja_model');
		$this->load->model('pegawai/riwayat_pekerjaan_model');
		$this->load->model('pegawai/riwayat_tugasbelajar_model');
		$this->load->model('pegawai/riwayat_kursus_model');

		$pegawai = $this->pegawai_model->get_drh($pns_id);
		$this->riwayat_pendidikan_model->order_by("TAHUN_LULUS","asc");
        $this->riwayat_pendidikan_model->where("NIP",$pegawai->NIP_BARU);
        $this->riwayat_pendidikan_model->or_where("PNS_ID",$pns_id);     
		$rwt_pendidikan=$this->riwayat_pendidikan_model->find_all();

        $this->riwayat_kepangkatan_model->where("PNS_NIP",$pegawai->NIP_BARU);  
        $rwt_kepangkatan = $this->riwayat_kepangkatan_model->find_all();

		$this->diklat_fungsional_model->where("NIP_BARU",$pegawai->NIP_BARU);
        $this->diklat_fungsional_model->ORDER_BY("TAHUN","Desc");
        $rwt_diklat_fungsional = $this->diklat_fungsional_model->find_all();

		$this->diklat_struktural_model->where("PNS_NIP",$pegawai->NIP_BARU); 
		$this->diklat_struktural_model->or_where("PNS_ID",$pegawai->PNS_ID); 
        $this->diklat_struktural_model->ORDER_BY("ID","Desc");
        $rwt_diklat_struktural = $this->diklat_struktural_model->find_all();
		
		$orang_tua=$this->orang_tua_model->find_all($pegawai->NIP_BARU);

		$pasangan=$this->istri_model->find_all($pegawai->NIP_BARU);

		$anaks=$this->anak_model->find_all($pegawai->NIP_BARU);

		$this->riwayat_jabatan_model->where("ID_JENIS_JABATAN",'2'); 
		$this->db->group_start();
		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai->NIP_BARU); 
		$this->riwayat_jabatan_model->or_where("PNS_ID",$pegawai->PNS_ID); 
		$this->db->group_end();
		$rwt_jabatan_fungsional =$this->riwayat_jabatan_model->find_all();


		$this->riwayat_jabatan_model->where("rwt_jabatan.status_jabatan <> 'Plt.'"); 
		$this->riwayat_jabatan_model->where("ID_JENIS_JABATAN",'1'); 
		$this->db->group_start();
		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai->NIP_BARU);
		$this->riwayat_jabatan_model->or_where("PNS_ID",$pegawai->PNS_ID); 
		$this->db->group_end();
		$rwt_jabatan_struktural =$this->riwayat_jabatan_model->find_all();

		$this->riwayat_jabatan_model->where("ID_JENIS_JABATAN in ('3','4')"); 
		$this->db->group_start();
		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai->NIP_BARU); 
		$this->riwayat_jabatan_model->or_where("PNS_ID",$pegawai->PNS_ID); 
		$this->db->group_end();
		$rwt_jabatan_pelaksana =$this->riwayat_jabatan_model->find_all();

		// $this->riwayat_kgb_model->where("pegawai_nip",$pegawai->NIP_BARU);
        $rwt_kgb = $this->riwayat_kgb_model->find_all($pegawai->NIP_BARU);

		$this->riwayat_penghargaan_model->where("rwt_penghargaan.PNS_NIP",$pegawai->NIP_BARU); 
		$this->riwayat_penghargaan_model->or_where("rwt_penghargaan.PNS_ID",$pegawai->PNS_ID);
		$rwt_penghargaan =$this->riwayat_penghargaan_model->find_all();

		$this->riwayat_karyatulis_ilmiah_model->where("nip",$pegawai->NIP_BARU); 
		$rwt_karyatulis =$this->riwayat_karyatulis_ilmiah_model->find_all();

		$this->riwayat_aktivitas_ilmiah_model->where("nip",$pegawai->NIP_BARU); 
		$rwt_aktivitas_ilmiah =$this->riwayat_aktivitas_ilmiah_model->find_all();

		$this->riwayat_huk_dis_model->where("PNS_NIP",$pegawai->NIP_BARU); 
		$rwt_hukdis =$this->riwayat_huk_dis_model->find_all();

		$this->riwayat_cltn_model->where("pns_nip",$pegawai->NIP_BARU); 
        $rwt_cltn = $this->riwayat_cltn_model->find_all();

		$this->riwayat_pindah_unit_kerja_model->where("PNS_ID",$pns_id);  		
        $rwt_pindah_unit = $this->riwayat_pindah_unit_kerja_model->find_all();

		$this->riwayat_prestasi_kerja_model->where("PNS_NIP",$pegawai->NIP_BARU);
		$rwt_ppk = $this->riwayat_prestasi_kerja_model->find_all();

		$this->riwayat_pekerjaan_model->where("PNS_NIP",$pegawai->NIP_BARU); 
        $rwt_pekerjaan=$this->riwayat_pekerjaan_model->find_all();

		$this->riwayat_tugasbelajar_model->where("NIP",$pegawai->NIP_BARU); 
        $rwt_tubel=$this->riwayat_tugasbelajar_model->find_all();

		$this->riwayat_kursus_model->where("PNS_NIP",$pegawai->NIP_BARU); 
        $rwt_kursus = $this->riwayat_kursus_model->find_all();

		$this->riwayat_jabatan_model->where("(ID_JENIS_JABATAN in ('5','6','7') or (ID_JENIS_JABATAN = '1' and rwt_jabatan.status_jabatan = 'Plt.') )");
		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai->NIP_BARU); 
		$rwt_jabatan_penugasan =$this->riwayat_jabatan_model->find_all();		

		$masa_kerja_kp = $this->pegawai_model->get_masa_kerja_kp($pegawai->NIP_BARU);

		$data = array();
		$data['pegawai'] = $pegawai;
		$data['rwt_kepangkatan'] = $rwt_kepangkatan;
		$data['rwt_kgb'] = $rwt_kgb;
		$data['rwt_jabatan_fungsional'] = $rwt_jabatan_fungsional;
		$data['rwt_jabatan_struktural'] = $rwt_jabatan_struktural;
		$data['rwt_jabatan_pelaksana'] = $rwt_jabatan_pelaksana;
		$data['rwt_jabatan_penugasan'] = $rwt_jabatan_penugasan;
		$data['rwt_pendidikan'] = $rwt_pendidikan;
		$data['orang_tua'] = $orang_tua;
		$data['pasangan'] = $pasangan;
		$data['anaks'] = $anaks;
		$data['rwt_aktivitas_ilmiah'] = $rwt_aktivitas_ilmiah;
		$data['rwt_karyatulis_ilmiah'] = $rwt_karyatulis;
		$data['rwt_penghargaan'] = $rwt_penghargaan;
		$data['rwt_hukdis'] = $rwt_hukdis;
		$data['rwt_diklat_fungsional'] = $rwt_diklat_fungsional;
		$data['rwt_diklat_struktural'] = $rwt_diklat_struktural;
		$data['rwt_cltn'] = $rwt_cltn;
		$data['rwt_ppk'] = $rwt_ppk;
		$data['rwt_pindah_unit'] = $rwt_pindah_unit;
		$data['rwt_pekerjaan'] = $rwt_pekerjaan;
		$data['rwt_tubel'] = $rwt_tubel;
		$data['rwt_kursus'] = $rwt_kursus;
		$data['masa_kerja_kp'] = $masa_kerja_kp;
		$this->load->view('kepegawaian/drh',$data);
	}
	public function download_drh2($pns_id){
		$this->load->model('pegawai/orang_tua_model');
		$this->load->model('pegawai/istri_model');
		$this->load->model('pegawai/anak_model');
		$this->load->model('pegawai/riwayat_jabatan_model');
		$pegawai = $this->pegawai_model->get_drh($pns_id);
		$this->load->library('LibOpenTbs');
		$template_name = APPPATH."..".DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'template_drh.docx';
		$TBS = $this->libopentbs->TBS;
		
		$TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
		
		$foto_pegawai =  END_POINT_PHOTO.$pegawai->PHOTO;
		//die($foto_pegawai);
		$TBS->MergeField('a', array(
			'fullname'=>$pegawai->NAMA,
			'nip'=>$pegawai->NIP_BARU,
			'alamat'=>$pegawai->ALAMAT,
			'foto'=>$foto_pegawai,
			'tempat_lahir'=>$pegawai->TEMPAT_LAHIR,
			'tanggal_lahir'=>$pegawai->TGL_LAHIR,
			'pangkat'=>$pegawai->PANGKAT_TEXT,
			'gol_ruang'=>$pegawai->GOL_TEXT,
			'sex'=>$pegawai->JENIS_KELAMIN=='M'?"Pria":"Wanita",
			'agama'=>$pegawai->AGAMA_TEXT,
			'status_kawin'=>$pegawai->KAWIN_TEXT,
		));
		$this->riwayat_pendidikan_model->order_by("TAHUN_LULUS","asc");
        $this->riwayat_pendidikan_model->where("PNS_ID",$pns_id);    
		$records=$this->riwayat_pendidikan_model->find_all();
		if(isset($records) && is_array($records) && count($records)):
			$TBS->MergeBlock('pend',$records);	
		endif;
		//

        $this->riwayat_kepangkatan_model->where("PNS_ID",$pns_id);  
        $recordpangkats = $this->riwayat_kepangkatan_model->find_all();
		if(isset($recordpangkats) && is_array($recordpangkats) && count($recordpangkats)):
			$TBS->MergeBlock('pk',$recordpangkats);	
		endif;
		//$TBS->MergeBlock('pk',$records);
		$recordkeluargas=$this->orang_tua_model->find_all($pegawai->NIP_BARU);
		if(isset($recordkeluargas) && is_array($recordkeluargas) && count($recordkeluargas)):
			$TBS->MergeBlock('kel',$recordkeluargas);	
		endif;

		$recordistris=$this->istri_model->find_all($pegawai->NIP_BARU);
		if(isset($recordistris) && is_array($recordistris) && count($recordistris)):
			$TBS->MergeBlock('istri',$recordistris);	
		endif;

		$recordanaks=$this->anak_model->find_all($pegawai->NIP_BARU);
		if(isset($recordanaks) && is_array($recordanaks) && count($recordanaks)):
			$TBS->MergeBlock('anak',$recordanaks);	
		endif;

		$this->riwayat_jabatan_model->where("PNS_NIP",$pegawai->NIP_BARU); 

		$recordjabatans =$this->riwayat_jabatan_model->find_all();
		if(isset($recordjabatans) && is_array($recordjabatans) && count($recordjabatans)):
			$TBS->MergeBlock('jb',$recordjabatans);	
		endif;
		

		$output_file_name = 'DRH.docx';
		$output_file_name = str_replace('.', '_'.date('Y-m-d').$pegawai->NIP_BARU.'.', $output_file_name);
		$TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function download()
	{
	  
		$advanced_search_filters  = $_GET;
		
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if($filters['unit_id_key'] != ""){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key'] != ""){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_key'] != ""){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_key'] != ""){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
	
			if($filters['golongan_key'] != ""){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan'] != ""){
				$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_jabatan'] != ""){
				$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum'] != ""){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
			if($filters['nama_jabatan']){
				$this->pegawai_model->where_in('"JABATAN_INSTANSI_REAL_ID"',$filters['nama_jabatan']);	
			}
			if($filters['kawasan']){
				$this->pegawai_model->where_in('kawasan_id',$filters['kawasan']);	
			}
			if($filters['pengelolasdm']){
				$this->pegawai_model->where_in('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
		}
		
		$datapegwai=$this->pegawai_model->find_all_detil($this->UNOR_ID);
		
		$this->load->library('Excel');
		$objPHPExcel = new PHPExcel();
		$objPHPExcel = PHPExcel_IOFactory::load(trim($this->settings_lib->item('site.pathuploaded')).'template.xls');

		$objPHPExcel->setActiveSheetIndex(0);
		$col = 0;
		$itemfield = $this->db->list_fields('pegawai');
		foreach($itemfield as $field)
		{
			 
			   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,1,$field);
			   $col++;
		}
		$row = 2;
		if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
			foreach ($datapegwai as $record) :
				$col = 0;
				$type = PHPExcel_Cell_DataType::TYPE_STRING;
				foreach($itemfield as $field)
				{
					if($col == 3)
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->$field." ",PHPExcel_Cell_DataType::TYPE_STRING);
					else
						//$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$row,$record->$field,PHPExcel_Cell_DataType::TYPE_STRING);
						$type = PHPExcel_Cell_DataType::TYPE_STRING;
						$objPHPExcel->getActiveSheet()->getCellByColumnAndRow($col, $row)->setValueExplicit($record->$field, $type);
					$col++;
				}
			   
			$row++;
			endforeach;
		endif;
		  
		$filename = "pegawai".mt_rand(1,100000).'.xls'; //just some random filename
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');  //downloadable file is in Excel 2003 format (.xls)
		//$objWriter = PHPExcel_IOFactory::createWriter($objTpl, 'Excel2007'); 
        ob_end_clean();
		$objWriter->save('php://output');  //send it to user, of course you can save it to disk also!
		exit; //done.. exiting!
		
	}
	public function downloadnominatif(){
		$advanced_search_filters  = $_GET;
		$kedudukan_hukum = "";
		$filters = $advanced_search_filters;

		if($filters['unit_id_key'] != ""){
			$this->db->group_start();
			$this->db->where('vw."ID"',$filters['unit_id_key']);	
			$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
			$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
			$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
			$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
			$this->db->group_end();
		}
		if($filters['nama_key'] != ""){
			$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
		}
		if($filters['nip_key'] != ""){
			$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
		}
		if($filters['umur_key'] != ""){
			if($filters['umur_operator']=="="){
				$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
			}
			if($filters['umur_operator']==">="){
				$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
			}
			if($filters['umur_operator']==">"){
				$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
			}
			if($filters['umur_operator']=="<="){
				$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
			}
			if($filters['umur_operator']=="<"){
				$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
			}
			if($filters['umur_operator']=="!="){
				$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
			}
		}

		if($filters['golongan_key'] != ""){
			$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
		}
		if($filters['tingkat_pendidikan'] != ""){
			$this->pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
		}
		if($filters['kategori_jabatan'] != ""){
			$this->pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
		}
		if($filters['username_intra'] != ""){
			$this->pegawai_model->where('lower("username_intra")',strtolower($filters['username_intra']));	
		}
		if($filters['kedudukan_hukum']){
			$kedudukan_hukum = 	$filters['kedudukan_hukum'];
		}
		if($filters['nama_jabatan']){
			$this->pegawai_model->where_in('"JABATAN_INSTANSI_REAL_ID"',$filters['nama_jabatan']);	
		}
		if($filters['kawasan']){
			$this->pegawai_model->where_in('kawasan_id',$filters['kawasan']);	
		}
		if($filters['status_pegawai']){
			$this->pegawai_model->where('status_pegawai',$filters['status_pegawai']);	
		}
		if($filters['pengelolasdm']){
			$this->pegawai_model->where_in('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
		}
		$datapegwai=$this->pegawai_model->find_download($this->UNOR_ID,false, $kedudukan_hukum);
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'templatenominatif.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'bulan'=>'maret',
        )); 

        $output_file_name = 'Nominatif_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        ob_end_clean();
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function downloadnominatif_mv(){
		$this->load->model('pegawai/mv_nominatif_pegawai_model');
		$advanced_search_filters  = $_GET;
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if(isset($filters['unit_id_cb']) and $filters['unit_id_cb'] != ""){
				$this->db->group_start();
				$this->db->where('"ID_UNOR"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('"ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_cb']){
				$this->mv_nominatif_pegawai_model->like('upper("NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip_cb']){
				$this->mv_nominatif_pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->mv_nominatif_pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->mv_nominatif_pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->mv_nominatif_pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tkp_cb']){
				$this->mv_nominatif_pegawai_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['kategori_jabatan']){
				$this->mv_nominatif_pegawai_model->where('"KATEGORI_JABATAN"',$filters['kategori_jabatan']);	
			}
			if($filters['kedudukan_hukum']){
				$kedudukan_hukum = $filters['kedudukan_hukum'];
			}
			if($filters['pengelolasdm']){
				$this->pegawai_model->where_in('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
		}
		
		$datapegwai=$this->mv_nominatif_pegawai_model->find_download($this->UNOR_ID);
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'templatenominatif.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'bulan'=>'maret',
        )); 

        $output_file_name = 'Nominatif_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        ob_end_clean();
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function downloadphoto()
	{
	 
		$advanced_search_filters  = $_GET;
		$this->load->helper('download');
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			
			if($filters['nama_cb']){
				$this->pegawai_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
				
			}
			if($filters['unit_id_cb']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}


			if($filters['nip_cb']){
				$this->pegawai_model->like('upper("NIP_BARU")',strtoupper($filters['nip_key']),"BOTH");	
			}
			if($filters['umur_cb']){
				if($filters['umur_operator']=="="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']==">"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") >',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") <=',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="<"){
					$this->pegawai_model->where('calc_age("TGL_LAHIR")<',$filters['umur_key']*12);	
				}
				if($filters['umur_operator']=="!="){
					$this->pegawai_model->where('calc_age("TGL_LAHIR") !=',$filters['umur_key']*12);	
				}
			}
			if($filters['eselon_cb']){
				$this->pegawai_model->where('upper("NAMA1") LIKE \''.strtoupper($filters['nip_key']).'%\'');	
			}
			if($filters['golongan_cb']){
				$this->pegawai_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
		}
		
		$datapegwai=$this->pegawai_model->find_all($this->UNOR_ID);
		$this->load->helper('handle_upload');
		$zip = new ZipArchive();
		$DelFilePath = "zipphoto.zip";
		//if(file_exists(trim($this->settings_lib->item('site.urlphoto')."zip/".$DelFilePath))) {
			//die("hapus");
			deletefile($DelFilePath,trim($this->settings_lib->item('site.urlphoto'))."zip/");
		    //unlink (trim($this->settings_lib->item('site.urlphoto')."zip/".$DelFilePath)); 
		//}
			
		//die(trim($this->settings_lib->item('site.urlphoto'))."ga hapus");
		if ($zip->open(trim($this->settings_lib->item('site.urlphoto'))."zip/".$DelFilePath, ZIPARCHIVE::CREATE) != TRUE) {
		    die ("Could not open archive");
		}
		if (isset($datapegwai) && is_array($datapegwai) && count($datapegwai)) :
			foreach ($datapegwai as $record) :

				$PHOTO = ISSET($record->PHOTO) ? trim($record->PHOTO) : "";
				if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$PHOTO) && $PHOTO != ""){
					$zip->addFile(trim($this->settings_lib->item('site.urlphoto')).trim($PHOTO),TRIM($PHOTO));
				}
			endforeach;
		endif;
		// close and save archive
		$zip->close();
		force_download(trim($this->settings_lib->item('site.urlphoto'))."zip/".$DelFilePath,NULL);
		exit; //done.. exiting!
		
	}
	public function getdatapensiun(){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_estimasi_pensiun($this->UNOR_ID);

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->pegawai_model->count_estimasi_pensiun($this->UNOR_ID);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_estimasi_pensiun($this->UNOR_ID);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='70%'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                 
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function deletedata()
	{
		$this->auth->restrict($this->permissionDelete);
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$id 	= $this->input->post('kode');
		if ($this->pegawai_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Delete pegawai sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
	public function deletedataupdatemandiri()
	{
		$this->load->model('pegawai/update_mandiri_model');
		$this->auth->restrict($this->VerifikasiUpdate3);
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/viewupdatemandiri');
		}
		$id 	= $this->input->post('kode');
		if ($this->update_mandiri_model->delete($id)) {
			 log_activity($this->auth->user_id(),"Delete data" . ': ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Delete pengajuan perubahan sukses", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
	}
	public function batalupdatemandiri()
	{
		$this->load->model('pegawai/update_mandiri_model');
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/peremajaan');
		}
		$id 	= $this->input->post('kode');
		$data = $this->update_mandiri_model->find($id);
		if (!$this->auth->has_permission('Pegawai.Pdm.Cancel') && $this->auth->username() != $data->NIP_BARU) {
			Template::set_message("Hanya request ajax", 'error');
		 	redirect(SITE_AREA . '/kepegawaian/pegawai/peremajaan');
	 	}
		$response = array();
		$response ['success']= false;
    	$response ['msg']= "Gagal";
		if ($this->update_mandiri_model->update($id, array('STATUS' => 4))) {
			log_activity($this->auth->user_id(),"Batal update data mandiri, id : ". $id . ' : ' . $this->input->ip_address(), 'pegawai');
			$response ['success']= true;
    		$response ['msg']= "Berhasil";

			if ($data->ID_ARSIP && empty($data->DARI)){
				$this->load->model('arsip_digital/arsip_digital_model');
				$this->load->library('Api_s3_aws');
				$aws = new Api_s3_aws;
				$this->arsip_digital_model->where('ISVALID',0);
				$arsip = $this->arsip_digital_model->find($data->ID_ARSIP);
				if ($arsip){
					$aws->deleteObject("simpeg", $arsip->location . $arsip->name);
					$arsip = $this->arsip_digital_model->delete($data->ID_ARSIP);
				}
			}
    	}

        echo json_encode($response);
		exit();
	}
	public function batalmultipleupdatemandiri()
	{
		$this->load->model('pegawai/update_mandiri_model');
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/peremajaan');
		}
		$ids 	= $this->input->post('pdm_id');
		foreach ($ids as $id) {
			$data = $this->update_mandiri_model->find($id);
			if (!$this->auth->has_permission('Pegawai.Pdm.Cancel') && $this->auth->username() != $data->NIP_BARU) {
				Template::set_message("Hanya request ajax", 'error');
				redirect(SITE_AREA . '/kepegawaian/pegawai/peremajaan');
			}
			$response = array();
			$response ['success']= false;
			$response ['msg']= "Gagal";
			if ($this->update_mandiri_model->update($id, array('STATUS' => 4))) {
				log_activity($this->auth->user_id(),"Batal update data mandiri, id : ". $id . ' : ' . $this->input->ip_address(), 'pegawai');
				$response ['success']= true;
				$response ['msg']= "Berhasil";

				if ($data->ID_ARSIP && empty($data->DARI)){
					$this->load->model('arsip_digital/arsip_digital_model');
					$this->load->library('Api_s3_aws');
					$aws = new Api_s3_aws;
					$this->arsip_digital_model->where('ISVALID',0);
					$arsip = $this->arsip_digital_model->find($data->ID_ARSIP);
					if ($arsip){
						$aws->deleteObject("simpeg", $arsip->location . $arsip->name);
						$arsip = $this->arsip_digital_model->delete($data->ID_ARSIP);
					}
				}
			}
		}

        echo json_encode($response);
		exit();
	}
	
	public function tolakupdatemandiri()
	{
		$this->load->model('pegawai/update_mandiri_model');
		if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai/peremajaan');
		}
		if (!$this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') && !$this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')){
			redirect(SITE_AREA . '/kepegawaian/pegawai/peremajaan');
		}
		$id 	= $this->input->post('kode');
		$keterangan 	= $this->input->post('keterangan');
		$response = array();
		$response ['success']= false;
    	$response ['msg']= "Gagal";

		$data_update = array('STATUS' => 0, 'KETERANGAN' => $keterangan, 'VERIFIKASI_BY' => $this->auth->user_id(), 'VERIFIKASI_TGL' => date('Y-m-d'));
		
		if ($this->update_mandiri_model->update($id, $data_update)) {
			log_activity($this->auth->user_id(),"Tolak update data mandiri, id : ". $id . ' : ' . $this->input->ip_address(), 'pegawai');
			$response ['success']= true;
    		$response ['msg']= "Berhasil";
		}
        echo json_encode($response);
		exit();
	}
	function save_profile_integrasi(){
		try {			
			date_default_timezone_set("Asia/Bangkok");
			$this->load->helper('aplikasi');
			if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
			}
			$response = array(
				'success'=>false,
				'msg'=>'Unknown error'
			);
			// Make sure we only pass in the fields we want
			$data = $this->pegawai_model->prep_data($this->input->post());
			
			$PNS_ID = $this->input->post('PNS_ID');
			$data_lama = $this->pegawai_model->find_by('PNS_ID',$PNS_ID);
			$perubahan = array();
			foreach ($data as $key => $value) {
				if (empty($value)){
					$data[$key] = null;
				}
			}
			if ($data_lama){
				$perubahan = arrDiff($data_lama, $data);
				// die(json_encode($perubahan));
				$this->pegawai_model->update($data_lama->ID, $data);	
				log_activity($this->auth->user_id(),"Perubahan data pegawai : ".$data_lama->NIP_BARU.', data perubahan :'.json_encode($perubahan), 'pegawai');
				$response = Modules::run('pegawai/Bkn/uploadDataUtama', $data_lama->NIP_BARU);
				if ($response['success']){
					return $this->print_success_json('Berhasil');
				} else {
					return $this->print_error_json($response['msg']);
				}
			}
		} catch (Exception $e) {
			return $this->print_error_json($e->getMessage());
		}
	}
	public function save_pegawai()
    {
    	if (!($this->auth->has_permission($this->permissionCreate) 
        || $this->auth->has_permission($this->permissionEdit)) ){
            die("anda tidak ada akses");
        }
    	date_default_timezone_set("Asia/Bangkok");
		$this->load->helper('aplikasi');
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
		$id_data = $this->input->post('ID');
        $this->form_validation->set_rules($this->pegawai_model->get_validation_rules());
        $this->form_validation->set_rules('username_intra','Username Intra','max_length[8]');
        $extra_unique_rule = '';
		if ($id_data != '')
		{
			$_POST['id'] = $id_data;
			$extra_unique_rule = ',pegawai.ID';
		}else{
			$this->form_validation->set_rules('PNS_ID','KODE','required|max_length[36]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
		}
        $this->form_validation->set_rules('PNS_ID','PNS ID','required|max_length[36]|unique[pegawai.PNS_ID' . $extra_unique_rule . ']');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }

        // Make sure we only pass in the fields we want
        $data = $this->pegawai_model->prep_data($this->input->post());
        $rec_jabatan    = $this->jabatan_model->find_by("KODE_JABATAN", $this->input->post('JABATAN_INSTANSI_REAL_ID'));

        
        $NIP_BARU = $this->input->post('NIP_BARU');
		$data_lama = $this->pegawai_model->find_by('NIP_BARU',$NIP_BARU);
		$perubahan = array();
		if ($data_lama){
			$perubahan = arrDiff($data_lama,$data);
		}
        $reclokasikerja = $this->lokasi_model->find($this->input->post('LOKASI_KERJA_ID'));
		$data['LOKASI_KERJA']				= $reclokasikerja->NAMA;
		$recpendidikan 						= $this->pendidikan_model->find($this->input->post('PENDIDIKAN_ID'));
		$data['PENDIDIKAN']					= $recpendidikan->NAMA;
		if($this->input->post("JENIS_JABATAN_ID") != ""){
			$rec_jenis = $this->jenis_jabatan_model->find($this->input->post("JENIS_JABATAN_ID"));
			$data["JENIS_JABATAN_NAMA"] = $rec_jenis->NAMA;
		}
		$recjabatan 						= $this->jabatan_model->find_by("KODE_JABATAN",$this->input->post('JABATAN_INSTANSI_ID'));
		$data['JABATAN_INSTANSI_NAMA']		= $recjabatan->NAMA_JABATAN;
		$data['PNS_ID']						= $this->input->post('PNS_ID');
        $data['AGAMA_ID']					= $this->input->post('AGAMA_ID') ? $this->input->post('AGAMA_ID') : null;
        $data['GOL_ID']						= $this->input->post('GOL_ID') ? $this->input->post('GOL_ID') : null;
        $data['JENIS_JABATAN_ID']			= $this->input->post('JENIS_JABATAN_ID') ? $this->input->post('JENIS_JABATAN_ID') : null;
		$data['TGL_LAHIR']					= $this->input->post('TGL_LAHIR') ? $this->input->post('TGL_LAHIR') : null;
		$data['TGL_SK_CPNS']				= $this->input->post('TGL_SK_CPNS') ? $this->input->post('TGL_SK_CPNS') : null;
		$data['TMT_CPNS']					= $this->input->post('TMT_CPNS') ? $this->input->post('TMT_CPNS') : null;
		$data['TMT_PNS']					= $this->input->post('TMT_PNS') ? $this->input->post('TMT_PNS') : null;
		$data['TMT_GOLONGAN']				= $this->input->post('TMT_GOLONGAN') ? $this->input->post('TMT_GOLONGAN') : null;
		// jabatan
		$data['TMT_JABATAN']				= $this->input->post('TMT_JABATAN') ? $this->input->post('TMT_JABATAN') : null;
		$data['JABATAN_INSTANSI_ID']		= $this->input->post('JABATAN_INSTANSI_ID') ? $this->input->post('JABATAN_INSTANSI_ID') : null;
		$data['JABATAN_ID']					= $this->input->post('JABATAN_INSTANSI_ID') ? $this->input->post('JABATAN_INSTANSI_ID') : null;
		$data['JABATAN_INSTANSI_REAL_ID']	= $this->input->post('JABATAN_INSTANSI_REAL_ID') ? $this->input->post('JABATAN_INSTANSI_REAL_ID') : null;
		$data['JABATAN_NAMA']               = $rec_jabatan->NAMA_JABATAN ? $rec_jabatan->NAMA_JABATAN : null;
		$data['TMT_PENSIUN']				= $this->input->post('TMT_PENSIUN') ? $this->input->post('TMT_PENSIUN') : null;
		$data['TGL_SURAT_DOKTER']			= $this->input->post('TGL_SURAT_DOKTER') ? $this->input->post('TGL_SURAT_DOKTER') : null;
		$data['TGL_BEBAS_NARKOBA']			= $this->input->post('TGL_BEBAS_NARKOBA') ? $this->input->post('TGL_BEBAS_NARKOBA') : null;
		$data['TGL_CATATAN_POLISI']			= $this->input->post('TGL_CATATAN_POLISI') ? $this->input->post('TGL_CATATAN_POLISI') : null;
		$data['TGL_MENINGGAL']				= $this->input->post('TGL_MENINGGAL') ? $this->input->post('TGL_MENINGGAL') : null;
		$data['TGL_NPWP']					= $this->input->post('TGL_NPWP') ? $this->input->post('TGL_NPWP') : null;
		$data['terminated_date']			= $this->input->post('terminated_date') ? $this->input->post('terminated_date') : null;
		$data['status_pegawai']				= $this->input->post('status_pegawai') ? $this->input->post('status_pegawai') : 1;
		$data['MK_TAHUN_SWASTA']			= $this->input->post('MK_TAHUN_SWASTA') ? $this->input->post('MK_TAHUN_SWASTA') : 0;
		$data['MK_BULAN_SWASTA']			= $this->input->post('MK_BULAN_SWASTA') ? $this->input->post('MK_BULAN_SWASTA') : 0;
		// kawasan tusi
		$data['kawasan_id']					= $this->input->post('kawasan_id') ? $this->input->post('kawasan_id') : null;
		$data['tusi_id']					= $this->input->post('tusi_id') ? $this->input->post('tusi_id') : null;
		$data['satker_penempatan_id']		= $this->input->post('satker_penempatan_id') ? $this->input->post('satker_penempatan_id') : null;
		$data['username_intra']				= $this->input->post('username_intra') ? $this->input->post('username_intra') : null;
		$data['JENIS_PEMBERHENTIAN']		= $this->input->post('JENIS_PEMBERHENTIAN') ? $this->input->post('JENIS_PEMBERHENTIAN') : null;
		$data['pengelola_sdm_kawasan_id']	= $this->input->post('pengelola_sdm_kawasan_id') ? $this->input->post('pengelola_sdm_kawasan_id') : null;
        $result = false;
        $msg = "";
		$kepakaran = $this->input->post('kepakaran');
		$penelitian = $this->input->post('penelitian');		
		$this->simpanKepakaran($NIP_BARU,$kepakaran);		
		$this->simpanPenelitian($NIP_BARU,$penelitian);
        if ($id_data == "") {
        	$data['CREATED_DATE']	= date("Y-m-d");
        	$data['CREATED_BY']		= $this->auth->user_id();
            if($this->pegawai_model->insert($data)){
            	$result = true;
            	log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Tambah data pegawai", 'pegawai');
            	log_activity($this->auth->user_id(),"Tambah data pegawai ".json_encode($data), 'pegawai');
            	
            }else{
            	$msg = $this->pegawai_model->error;
            }
        } elseif ($id_data != "") {
        	$data['UPDATED_DATE']	= date("Y-m-d");
        	$data['UPDATED_BY']		= $this->auth->user_id();
            $result = $this->pegawai_model->update($id_data, $data);

            // update log
            log_activity_pegawai($NIP_BARU,$this->auth->user_id(),"Perubahan data pegawai", 'pegawai');
			log_activity($this->auth->user_id(),"Perubahan data pegawai : ".$NIP_BARU.', data perubahan :'.json_encode($perubahan), 'pegawai');
        }

		// update riwayat cpns_pns
		if ($data['status_pegawai'] == 1){
			$this->load->model('pegawai/riwayat_pns_cpns_model');
			$rwt_pns_cpns = $this->riwayat_pns_cpns_model->find_by("PNS_NIP",$data['NIP_BARU']);
			$data_pns = array();
			$data_pns['STATUS_KEPEGAWAIAN'] = $data['STATUS_CPNS_PNS'] == 'P'? "PNS" : "CPNS";
			$data_pns['TMT_CPNS'] = $data['TMT_CPNS'];
			$data_pns['TGL_SK_CPNS'] = $data['TGL_SK_CPNS'];
			$data_pns['NO_SK_CPNS'] = $data['NOMOR_SK_CPNS'];
			$data_pns['TMT_PNS'] = $data['TMT_PNS'];
			$data_pns['KARPEG'] = $data['KARTU_PEGAWAI']?$data['KARTU_PEGAWAI']: null;
			$data_pns['NO_SURAT_DOKTER'] = $data['NO_SURAT_DOKTER']?$data['NO_SURAT_DOKTER']: null;
			$data_pns['KARIS_KARSU'] = $data['kartu_istri']?$data['kartu_istri']: null;

			if (empty($data_pns['TMT_CPNS'])){
				unset($data_pns['TMT_CPNS']);
			}
			if (empty($data_pns['TMT_PNS'])){
				unset($data_pns['TMT_PNS']);
			}
			if (empty($data_pns['TGL_SK_CPNS'])){
				unset($data_pns['TGL_SK_CPNS']);
			}
			if ($rwt_pns_cpns){
				if($this->riwayat_pns_cpns_model->update_where("PNS_NIP",$data['NIP_BARU'], $data_pns)){
                	log_activity($this->auth->user_id(), 'Update Rwt CPNS PNS : ' . $data['NIP_BARU'] . ' : ' . $this->input->ip_address(), 'pegawai');
            	}  
			} else {
				if($this->riwayat_pns_cpns_model->insert($data_pns)){
                	log_activity($this->auth->user_id(), 'Insert Rwt CPNS PNS : ' . $data['NIP_BARU'] . ' : ' . $this->input->ip_address(), 'pegawai');
            	} 
			}
		}
		//
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Simpan Berhasil";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan : ".$msg;
    	}
        echo json_encode($response);    
    }
    public function listpensiun()
    {	
    	$this->auth->restrict($this->permissionView);
        $records = $this->pegawai_model->find_all_pensiun($this->UNOR_ID);
        Template::set('records', $records);
    	Template::set('toolbar_title', "Estimasi Pegawai Pensiun");
		
        Template::render();
    }
	public function ajax_unit_list(){
		if (!$this->input->is_ajax_request()) {
   			//Template::set_message("Hanya request ajax", 'error');
            //redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');
		$this->db->flush_cache();
		//Jika ada role executive ?
		$UNOR_ID = '';
		$CI = &get_instance();
		$asatkers = null;
		if($this->auth->has_permission($this->UnitkerjaTerbatas)){
			$asatkers = json_decode($this->auth->satkers());
		}
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
		$data = $this->unitkerja_model->find_unit($term,$UNOR_ID,$asatkers);

		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$active = "";
			if($row->EXPIRED_DATE != ""){
				$active = " (Non Aktif)";
			}
			$output['results'] [] = array(
				'id'=>$row->ID,
				'text'=>$row->NAMA_UNOR_FULL.$active
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}
	public function ajax_kawasan(){
		if (!$this->input->is_ajax_request()) {
			//Template::set_message("Hanya request ajax", 'error');
		}
		
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');
		$this->db->flush_cache();
		//Jika ada role executive ?
		$UNOR_ID = '';
		$CI = &get_instance();
		$asatkers = null;
		// if($this->auth->has_permission($this->UnitkerjaTerbatas)){
		// 	$asatkers = json_decode($this->auth->satkers());
		// }
		// if($this->auth->has_permission($this->permissionFiltersatker)){
		// 	$UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
		// }
		// if($this->auth->has_permission($this->permissionEselon1)){
		// 	$UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		// }
		if ($term){
			$this->kawasan_model->where("name ilike '%".$term."%'");
		}
		$data = $this->kawasan_model->find_all();

		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$output['results'] [] = array(
				'id'=>$row->id,
				'text'=>$row->name
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}

	public function ajax_bidang_penelitian(){
		if (!$this->input->is_ajax_request()) {
   			//Template::set_message("Hanya request ajax", 'error');
		}
		$this->load->model('masters/bidang_penelitian_model');
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');
		$this->db->flush_cache();
		//Jika ada role executive ?
		$UNOR_ID = '';
		$CI = &get_instance();
		$asatkers = null;

		$this->db->group_start();
		if ($term){
			$this->bidang_penelitian_model->or_where('upper(bidangpenelitian.namaid) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_penelitian_model->or_where('upper(bidangpenelitian.namaen) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_penelitian_model->or_where('upper(b.namaid) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_penelitian_model->or_where('upper(b.namaen) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_penelitian_model->or_where('upper(k.namaid) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_penelitian_model->or_where('upper(k.namaen) LIKE \'%' . strtoupper($term) . '%\'');
		}
		$this->db->group_end();
		$data = $this->bidang_penelitian_model->find_all();

		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$long_text = 'Rumpun : '.$row->kepakaranid.' (<i>'.$row->kepakaranen.'</i>) - Bidang Kepakaran: '.$row->bkepakaranid.' (<i>'.$row->bkepakaranen.'</i>) - Bidang Penelitian: '.$row->namaid.' (<i>'.$row->namaen.'</i>)';
			$text = $row->namaid.' ('.$row->namaen.')';
			$output['results'] [] = array(
				'id'=>$row->kode,
				'text'=>$text,
				'long_text' => $long_text
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}
	public function ajax_bidang_kepakaran(){
		if (!$this->input->is_ajax_request()) {
   			//Template::set_message("Hanya request ajax", 'error');
		}
		$this->load->model('masters/bidang_kepakaran_model');
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');
		$this->db->flush_cache();
		//Jika ada role executive ?
		$UNOR_ID = '';
		$CI = &get_instance();
		$asatkers = null;

		$this->db->group_start();
		if ($term){
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaid) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(bidangkepakaran.namaen) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(k.namaid) LIKE \'%' . strtoupper($term) . '%\'');
			$this->bidang_kepakaran_model->or_where('upper(k.namaen) LIKE \'%' . strtoupper($term) . '%\'');
		}
		$this->db->group_end();
		$data = $this->bidang_kepakaran_model->find_all();

		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$long_text = 'Rumpun : '.$row->kepakaranid.' (<i>'.$row->kepakaranen.'</i>) - Bidang Kepakaran: '.$row->namaid.' (<i>'.$row->namaen.'</i>)';
			$text = $row->namaid.' ('.$row->namaen.')';
			$output['results'] [] = array(
				'id'=>$row->kode,
				'text'=>$text,
				'long_text' => $long_text
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}

	public function resumenew($nip='')
	{
		$namafile = "5cc15eedb871c";//uniqid();

		$ch = curl_init(); 

		 
		
		$data = array(
			'nama_file'=>base_url().'assets/profile/'.$namafile.'.pdf',
			'nama_path'=>base_url().'assets/static/'
		);
		$this->load->view('kepegawaian/resume',$data);
	}
	/*end*/
	
	public function resume($nip='')
	{
		
	}

	public function ajax_satker_list(){
		$length = 10;
		$term = $this->input->get('term');
		$page = $this->input->get('page');

		$UNOR_ID = '';
		$CI = &get_instance();
		
		if($this->auth->has_permission($this->permissionFiltersatker)){
			$UNOR_ID = $this->pegawai_model->getunor_id($CI->auth->username());
		}
		if($this->auth->has_permission($this->permissionEselon1)){
			$UNOR_ID = $this->pegawai_model->getunor_eselon1($this->auth->username());
		}
		
		$this->db->flush_cache();
		$this->db->like("lower(\"NAMA_UNOR\")",strtolower($term),"BOTH");
		$data = $this->unitkerja_model->find_satker($UNOR_ID);
		
		$output = array();
		$output['results'] = array();
		foreach($data as $row){
			$nama_unor = array();
			
			$output['results'] [] = array(
				'id'=>$row->ID,
				'text'=>$row->NAMA_UNOR
			);
		}
		$output['pagination'] = array("more"=>false);
		
		echo json_encode($output);
	}

	public function ajax_nama_pejabat(){
        $json = array();
        $limit = 10;
        $page = $this->input->get('page') ? $this->input->get('page') : "1";
        $q= $this->input->get('term');
        $start = ($page-1)*$limit;
		
		if(!empty($q)){
            $json = $this->data_model_nama_pejabat($q,$start,$limit);
		}
        echo json_encode($json);
	}
	public function ajax_jenis_data_update(){
        $this->load->model('pegawai/update_mandiri_model');
        $json = array();
        $q= $this->input->get('term');
		if(!empty($q)){
            $json = $this->update_mandiri_model->get_jenis_data($q);
			$json = array(
				"results" => $json);
		}
        echo json_encode($json);
	}
	
	private function data_model_nama_pejabat($key,$start,$limit){
		// update
		// ASLI
		  // $this->db->start_cache();
		  
		  // $this->db->like('lower("NAMA_UNOR")', strtolower($key));
		  // $this->db->from("hris.unitkerja");
  

	  
		  
		  
		  // $this->db->stop_cache();
		  // $total = $this->db->get()->num_rows();
		  // $this->db->select('ID as id,NAMA_UNOR as text');

		  // $this->db->limit($limit,$start);
		  // $data = $this->db->get()->result();
		  //#ASLI
		  
		  //modified by bana

		  $sql = "SELECT \"PNS_ID\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
		  		WHERE lower(\"NAMA\") ILIKE ? LIMIT ? OFFSET ?";
	   
		$key1 = "%".$key."%";
		//var_dump($key1);
		$query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
		$data = $query->result();
		$total = $query->num_rows();
		  //var_dump($sql);

		  $endCount = $start + $limit;
		  $morePages = $endCount > $total;
		  $o = array(
		  "results" => $data,
			  "pagination" => array(
				  "more" =>$morePages,
			  )
		  );   
		  $this->db->flush_cache();
		  return $o;    
	}

	public function ajax(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
	    $json = array();
	    $limit = 10;
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_model($q,$start,$limit);
		}
	    echo json_encode($json);
	}

	private function data_model($key,$start,$limit){
			  $sql = "SELECT trim(\"PNS_ID\") as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
			  		WHERE lower(\"NAMA\") ILIKE ? LIMIT ? OFFSET ?";
		   
			$key1 = "%".$key."%";
			$query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
			$data = $query->result();
			$total = $query->num_rows();
			  //var_dump($sql);

			  $endCount = $start + $limit;
			  $morePages = $endCount > $total;
			  $o = array(
			  "results" => $data,
				  "pagination" => array(
					  "more" =>$morePages,
				  )
			  );   
			  $this->db->flush_cache();
			  return $o;    
	}
	public function ajaxid(){
		if (!$this->input->is_ajax_request()) {
			die("Only ajax request");
		}
	    $json = array();
	    $limit = 10;
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_modelid($q,$start,$limit);
		}
	    echo json_encode($json);
	}
	private function data_modelid($key,$start,$limit){
			  $sql = "SELECT \"ID\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
			  		WHERE lower(\"NAMA\") ILIKE ? LIMIT ? OFFSET ?";
		   
			$key1 = "%".$key."%";
			$query = $this->db->query($sql, array(strtolower($key1), $limit, $start));
			$data = $query->result();
			$total = $query->num_rows();
			  //var_dump($sql);

			  $endCount = $start + $limit;
			  $morePages = $endCount > $total;
			  $o = array(
			  "results" => $data,
				  "pagination" => array(
					  "more" =>$morePages,
				  )
			  );   
			  $this->db->flush_cache();
			  return $o;    
	}
	public function ajaxnip(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
	    $json = array();
	    $limit = $this->input->get('limit') ? $this->input->get('limit') : "10";
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_modelnip($q,$start,$limit);
		}
	    echo json_encode($json);
	}
	public function ajaxref(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
	    $json = array();
	    $limit = $this->input->get('limit') ? $this->input->get('limit') : "10";
	    $page = $this->input->get('page') ? $this->input->get('page') : "1";
	    $q= $this->input->get('term');
	    $start = ($page-1)*$limit;
		
		if(!empty($q)){
	        $json = $this->data_modelref($q,$start,$limit);
		}
	    echo json_encode($json);
	}
	private function data_modelnip($key,$start,$limit){
			  $sql = "SELECT \"NIP_BARU\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
			  		WHERE lower(\"NAMA\") ILIKE ? or \"NIP_BARU\" = ? order by \"NAMA\" LIMIT ? OFFSET ?";
		   
			$key1 = "%".$key."%";
			$query = $this->db->query($sql, array(strtolower($key1), $key, $limit, $start));
			$data = $query->result();
			$total = $query->num_rows();
			  //var_dump($sql);

			  $endCount = $start + $limit;
			  $morePages = $endCount > $total;
			  $o = array(
			  "results" => $data,
				  "pagination" => array(
					  "more" =>$morePages,
				  )
			  );   
			  $this->db->flush_cache();
			  return $o;    
	}
	private function data_modelref($key,$start,$limit){
		$sql = "SELECT \"ref\" as id, \"NIP_BARU\" || ' - ' || \"NAMA\" as text FROM hris.pegawai 
				WHERE lower(\"NAMA\") ILIKE ? or lower(\"username_intra\") ILIKE ?  or \"NIP_BARU\" = ? order by \"NAMA\" LIMIT ? OFFSET ?";
	 
	  $key1 = "%".$key."%";
	  $query = $this->db->query($sql, array(strtolower($key1),strtolower($key1), $key, $limit, $start));
	  $data = $query->result();
	  $total = $query->num_rows();
		//var_dump($sql);

		$endCount = $start + $limit;
		$morePages = $endCount > $total;
		$o = array(
		"results" => $data,
			"pagination" => array(
				"more" =>$morePages,
			)
		);   
		$this->db->flush_cache();
		return $o;    
}
	public function getinfounit(){
		if (!$this->input->is_ajax_request()) {
				die("Only ajax request");
		}
		$PNS_ID = $this->input->post("PNS_ID");
        $pegawai_data 	= $this->pegawai_model->find_detil_nip($PNS_ID);  
        $NAMA_UNOR_FULL = isset($pegawai_data[0]->NAMA_UNOR_FULL) ? $pegawai_data[0]->NAMA_UNOR_FULL : "";
        $NAMA_JABATAN = isset($pegawai_data[0]->JABATAN_INSTANSI_NAMA) ? $pegawai_data[0]->JABATAN_INSTANSI_NAMA : "";
        $UNIT_ASAL =  $pegawai_data[0]->UNOR_ID != "" ? $pegawai_data[0]->UNOR_ID : "";

        $output = "<div class='control-group col-sm-6'>
        			<label for='JABATAN_ID' class='control-label'>Dari</label>
                    <div class='controls'>
                        ".$NAMA_UNOR_FULL."
                    </div>
                </div>
                <div class='control-group col-sm-6'>
                	<label class='control-label'>Jabatan Awal</label>
                    <div class='controls'>
                        ".$NAMA_JABATAN."
                    </div>
                </div>
                ";
       echo $output;
        die();
	}
	public function viewupdate()
    {
        $id = $this->uri->segment(5);
		$key = $this->uri->segment(6);
        $this->load->model('pegawai/update_mandiri_model');
        $dataupdate = $this->update_mandiri_model->find($id);
		$pegawai = $this->pegawai_model->find_by('PNS_ID',trim($dataupdate->PNS_ID));
		if (!$dataupdate || !$pegawai){
			die();
		}
		if (!($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$pegawai->NIP_BARU){
            die();
        }
		$record = $dataupdate;
		if (empty($key)){
			if ($this->auth->has_permission($this->VerifikasiUpdate) or $this->auth->has_permission($this->VerifikasiUpdate3)) {
				if ($record->STATUS == ''){
					$btn_actions  [] = "<a style='margin:5px ;' kode='$record->ID' class='btn btn-sm btn-danger btn-tolak pull-right' data-toggle='tooltip' data-placement='top' data-original-title='Tolak data' title='Tolak update data' tooltip='Tolak'><i class='fa fa-ban'></i> Tolak</a>";
					if (trim($record->NAMA_KOLOM) == "RIWAYAT_PENDIDIKAN") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatpendidikan/verifikasi2/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KEPANGKATAN") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatkepangkatan/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_JABATAN") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatjabatan/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "DATA_PASANGAN") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/data_keluarga/verifikasi_pasangan/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "DATA_ORANG_TUA") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/data_keluarga/verifikasi_ortu/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "DATA_ANAK") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/data_keluarga/verifikasi_anak/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "DIKLAT_STRUKTURAL") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/diklatstruktural/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "DIKLAT_FUNGSIONAL") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/diklatfungsional/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KURSUS") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatkursus/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PPK") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatprestasikerja/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PPK") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatprestasikerja/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_AK") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_angka_kredit/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PENGHARGAAN") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatpenghargaan/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KGB") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_kgb/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_TUBEL") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_tugasbelajar/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_CUTI") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_cltn/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "KARYA_TULIS") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatkaryatulisilmiah/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "AKTIVITAS_ILMIAH") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayataktivitasilmiah/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "ARSIP_DIGITAL") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "arsip_digital/arsip/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_ORGANISASI") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatorganisasi/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "PROFESOR_RISET") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatprofesorriset/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PIDANA") {
						$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatpidana/verifikasi/" . $record->ID . "' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
					} else {								
						$btn_actions  [] = "<a style='margin:5px ;' kode='$record->ID' class='btn btn-sm btn-success btn-verif pull-right' data-toggle='tooltip' data-placement='top' data-original-title='Terima data' title='Terima update data' tooltip='Terima'><i class='fa fa-check'></i> Terima</a>";
					}
				}
			}
		}
		
		if ($this->auth->has_permission($this->viewDetailPeremajaan)) {
			if (trim($record->NAMA_KOLOM) == "RIWAYAT_PENDIDIKAN") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatpendidikan/verifikasi2/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KEPANGKATAN") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatkepangkatan/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_JABATAN") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatjabatan/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "DATA_PASANGAN") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/data_keluarga/verifikasi_pasangan/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "DATA_ORANG_TUA") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/data_keluarga/verifikasi_ortu/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "DATA_ANAK") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/data_keluarga/verifikasi_anak/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "DIKLAT_STRUKTURAL") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/diklatstruktural/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "DIKLAT_FUNGSIONAL") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/diklatfungsional/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KURSUS") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatkursus/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PPK") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatprestasikerja/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PPK") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatprestasikerja/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_AK") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_angka_kredit/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PENGHARGAAN") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatpenghargaan/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KGB") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_kgb/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_TUBEL") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_tugasbelajar/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_CUTI") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayat_cltn/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "KARYA_TULIS") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatkaryatulisilmiah/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "AKTIVITAS_ILMIAH") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayataktivitasilmiah/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_ORGANISASI") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatorganisasi/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info modal-custom-global pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "ARSIP_DIGITAL") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "arsip_digital/arsip/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
			} else if (trim($record->NAMA_KOLOM) == "PROFESOR_RISET") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatprofesorriset/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
			} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PIDANA") {
				$btn_actions[] = "<a style='margin:5px ;' href='" . base_url() . "pegawai/riwayatpidana/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-success modal-custom-global pull-right'><i class='fa fa-check'></i> Terima</a>";
			}
		}
		
		Template::set('button', $btn_actions);
		Template::set('readonly', false);
        Template::set('dataupdate', $dataupdate);
		if (isset($key) && $key == 'view'){
			Template::set('readonly', true);
		}
        Template::set('toolbar_title', "Data Perubahan");
        Template::render();
    }
    public function lihatupdate_pribadi()
    {
        $id = $this->uri->segment(5);
        $this->load->model('pegawai/update_mandiri_model');
        $dataupdate = $this->update_mandiri_model->find($id);
        Template::set('dataupdate', $dataupdate);
        Template::set('toolbar_title', "Lihat Permintaan Update");
        Template::render();
    }
    public function cekphoto(){
    	$this->auth->restrict($this->permissionView);
    	
		//$this->pegawai_model->limit(100);
		$records=$this->pegawai_model->find_photo();
		$nomor_urut = 1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$PHOTO = ISSET($record->PHOTO) ? trim($record->PHOTO) : "";
				//echo trim($this->settings_lib->item('site.pathphoto')).$PHOTO. " "."<br>";
				if(file_exists(trim($this->settings_lib->item('site.urlphoto')).$PHOTO) && $PHOTO != ""){
					//echo " ada ";
				}else{
					//echo trim($this->settings_lib->item('site.urlphoto')).$PHOTO. "|";
					echo $nomor_urut."|"; 
					echo $PHOTO."|"; 
					echo " Tdk ada |";
					echo $record->NAMA."|<br>";
					$nomor_urut++;
				}
                
				
			}
		endif;
		
	}
	// get data from BKN
	public function getpegawaibkn(){
		$this->load->model('pegawai/golongan_model');
		$nip_baru 	= $this->input->post('kode');
		$pegawai_lokal = $this->pegawai_model->find_by("NIP_BARU",$nip_baru);		

		$this->load->library('Api_bkn');
		$api_lipi = new Api_bkn;
        // get data utama
        $json_data = $api_lipi->data_utama($nip_baru);
        $decodejson = json_decode($json_data);
        $decodejson = json_decode($decodejson);
        $pegawai 	= json_decode($decodejson->data);
        $data 		= array();
		if(isset($pegawai->id) and $pegawai->id != "") 
			$data["PNS_ID"]	=	isset($pegawai->id)	?	$pegawai->id : 	"";
        if(isset($pegawai_lokal->AGAMA_ID)){
        	if($pegawai->agamaId != "")
        		$data["AGAMA_ID"]	=	isset($pegawai->agamaId)	? (int)$pegawai->agamaId : 	"";
        } 
        if(isset($pegawai_lokal->AKTE_KELAHIRAN) and $pegawai_lokal->AKTE_KELAHIRAN == "") 
			$data["AKTE_KELAHIRAN"]	=	isset($pegawai->akteKelahiran)	?	$pegawai->akteKelahiran : 	"";
		if(isset($pegawai_lokal->AKTE_MENINGGAL) and $pegawai_lokal->AKTE_MENINGGAL == "") 
			$data["AKTE_MENINGGAL"]	=	isset($pegawai->akteMeninggal)	?	$pegawai->akteMeninggal : 	"";
		if(isset($pegawai_lokal->ALAMAT) and $pegawai_lokal->ALAMAT == "") 
			$data["ALAMAT"]	=	isset($pegawai->alamat)	?	$pegawai->alamat : 	"";
		if(isset($pegawai_lokal->BPJS) and $pegawai_lokal->BPJS == "") 
			$data["BPJS"]	=	isset($pegawai->bpjs)	?	$pegawai->bpjs : 	"";
		if(isset($pegawai_lokal->EMAIL) and trim($pegawai_lokal->EMAIL) == "") 
			$data["EMAIL"]	=	isset($pegawai->email)	?	$pegawai->email : 	"";
		if(isset($pegawai_lokal->GELAR_BELAKANG) and $pegawai_lokal->GELAR_BELAKANG == "") 
			$data["GELAR_BELAKANG"]	=	isset($pegawai->gelarBelakang)	?	$pegawai->gelarBelakang : 	"";
		if(isset($pegawai_lokal->GELAR_DEPAN) and $pegawai_lokal->GELAR_DEPAN == "") 
			$data["GELAR_DEPAN"]	=	isset($pegawai->gelarDepan)	?	$pegawai->gelarDepan : 	"";
		if(isset($pegawai_lokal->GOL_AWAL_ID) and $pegawai_lokal->GOL_AWAL_ID == "") 
			$data["GOL_AWAL_ID"]	=	isset($pegawai->golRuangAwalId)	?	$pegawai->golRuangAwalId : 	"";
		if(trim($pegawai_lokal->GOL_ID) == "" or $pegawai_lokal->GOL_ID == "0" or $pegawai->golRuangAkhirId != "") 
			$data["GOL_ID"]	=	isset($pegawai->golRuangAkhirId)	?	$pegawai->golRuangAkhirId : 	"";
		if(isset($pegawai_lokal->INSTANSI_INDUK_ID) and $pegawai_lokal->INSTANSI_INDUK_ID == "") 
			$data["INSTANSI_INDUK_ID"]	=	isset($pegawai->instansiIndukId)	?	$pegawai->instansiIndukId : 	"";
		if(isset($pegawai_lokal->INSTANSI_INDUK_NAMA) and $pegawai_lokal->INSTANSI_INDUK_NAMA == "") 
			$data["INSTANSI_INDUK_NAMA"]	=	isset($pegawai->instansiIndukNama)	?	$pegawai->instansiIndukNama : 	"";
		if(isset($pegawai_lokal->INSTANSI_KERJA_ID) and $pegawai_lokal->INSTANSI_KERJA_ID == "") 
			$data["INSTANSI_KERJA_ID"]	=	isset($pegawai->instansiKerjaId)	?	$pegawai->instansiKerjaId : 	"";
		if(isset($pegawai_lokal->INSTANSI_KERJA_NAMA) and $pegawai_lokal->INSTANSI_KERJA_NAMA == "") 
			$data["INSTANSI_KERJA_NAMA"]	=	isset($pegawai->instansiKerjaNama)	?	$pegawai->instansiKerjaNama : 	"";
		if(isset($pegawai_lokal->JABATAN_ID) and $pegawai_lokal->JABATAN_ID == "") 
			$data["JABATAN_ID"]	=	isset($pegawai->jabatanStrukturalId)	?	$pegawai->jabatanStrukturalId : 	"";
		
		if(isset($pegawai_lokal->JENIS_JABATAN_ID) and $pegawai_lokal->JENIS_JABATAN_ID == "") 
			$data["JENIS_JABATAN_ID"]	=	isset($pegawai->jenisJabatanId)	?	$pegawai->jenisJabatanId : 	"";
		if(isset($pegawai_lokal->JENIS_JABATAN_NAMA) and $pegawai_lokal->JENIS_JABATAN_NAMA == "") 
			$data["JENIS_JABATAN_NAMA"]	=	isset($pegawai->jenisJabatan)	?	$pegawai->jenisJabatan : 	"";
		if(isset($pegawai_lokal->JENIS_KAWIN_ID) and $pegawai_lokal->JENIS_KAWIN_ID == "") 
			$data["JENIS_KAWIN_ID"]	=	isset($pegawai->jenisKawinId)	?	$pegawai->jenisKawinId : 	"";

		if(isset($pegawai_lokal->JENIS_KELAMIN) and $pegawai->jenisKelamin != ""){
			$jenis_kelamin = isset($pegawai->jenisKelamin)	?	TRIM($pegawai->jenisKelamin) : 	"";
				if($jenis_kelamin == "Pria")
					$jk = "M";
				if($jenis_kelamin == "Wanita")
					$jk = "F";

				$data["JENIS_KELAMIN"]	=	$jk;
				// $data["JENIS_KELAMIN"]	=	isset($pegawai->jenisKelamin)	?	$pegawai->jenisKelamin : 	"";
		} 
		if(isset($pegawai_lokal->JENIS_PEGAWAI_ID) and $pegawai_lokal->JENIS_PEGAWAI_ID == "") 
			$data["JENIS_PEGAWAI_ID"]	=	isset($pegawai->jenisPegawaiId)	?	$pegawai->jenisPegawaiId : 	"";
		if(isset($pegawai_lokal->JML_ANAK) and $pegawai_lokal->JML_ANAK == "") 
			$data["JML_ANAK"]	=	isset($pegawai->jumlahAnak)	?	$pegawai->jumlahAnak : 	"";
		if(isset($pegawai_lokal->JML_ISTRI) and $pegawai_lokal->JML_ISTRI == "") 
			$data["JML_ISTRI"]	=	isset($pegawai->jumlahIstriSuami)	?	$pegawai->jumlahIstriSuami : 	"";
		if(isset($pegawai_lokal->KARTU_PEGAWAI) and $pegawai_lokal->KARTU_PEGAWAI == "") 
			$data["KARTU_PEGAWAI"]	=	isset($pegawai->noSeriKarpeg)	?	$pegawai->noSeriKarpeg : 	"";
		if(isset($pegawai_lokal->KEDUDUKAN_HUKUM_ID) and $pegawai_lokal->KEDUDUKAN_HUKUM_ID == "") 
			$data["KEDUDUKAN_HUKUM_ID"]	=	isset($pegawai->kedudukanPnsId)	?	$pegawai->kedudukanPnsId : 	"";
		if(isset($pegawai_lokal->KODECEPAT) and $pegawai_lokal->KODECEPAT == "") 
			$data["KODECEPAT"]	=	isset($pegawai->instansiKerjaKodeCepat)	?	$pegawai->instansiKerjaKodeCepat : 	"";
		if(isset($pegawai_lokal->KPKN_ID) and $pegawai_lokal->KPKN_ID == "") 
			$data["KPKN_ID"]	=	isset($pegawai->kpknId)	?	$pegawai->kpknId : 	"";
		if(isset($pegawai_lokal->MK_BULAN) and $pegawai_lokal->MK_BULAN == "" or $pegawai->mkBulan != "") 
			$data["MK_BULAN"]	=	isset($pegawai->mkBulan)	?	$pegawai->mkBulan : 	"";
		if(isset($pegawai_lokal->MK_TAHUN) and $pegawai_lokal->MK_TAHUN == "" or $pegawai->mkTahun != "") 
			$data["MK_TAHUN"]	=	isset($pegawai->mkTahun)	?	$pegawai->mkTahun : 	"";
		if(isset($pegawai_lokal->NAMA) and $pegawai_lokal->NAMA == "") 
			$data["NAMA"]	=	isset($pegawai->nama)	?	$pegawai->nama : 	"";
		if(isset($pegawai_lokal->NIK) and $pegawai_lokal->NIK == "") 
			$data["NIK"]	=	isset($pegawai->nik)	?	$pegawai->nik : 	"";
		
		if($pegawai_lokal->NIP_LAMA == "") 
			$data["NIP_LAMA"]	=	isset($pegawai->nipLama)	?	trim($pegawai->nipLama) : 	"";
		if($pegawai_lokal->NO_ASKES == "") 
			$data["NO_ASKES"]	=	isset($pegawai->noAskes)	?	trim($pegawai->noAskes) : 	"";
		if(trim($pegawai_lokal->NO_BEBAS_NARKOBA) == "") 
			$data["NO_BEBAS_NARKOBA"]	=	isset($pegawai->noSuratKeteranganBebasNarkoba)	?	$pegawai->noSuratKeteranganBebasNarkoba : 	"";
		if($pegawai_lokal->NO_CATATAN_POLISI == "") 
			$data["NO_CATATAN_POLISI"]	=	isset($pegawai->skck)	?	$pegawai->skck : 	"";
		if($pegawai_lokal->NO_SURAT_DOKTER == "") 
			$data["NO_SURAT_DOKTER"]	=	isset($pegawai->noSuratKeteranganDokter)	?	$pegawai->noSuratKeteranganDokter : 	"";
		if(trim($pegawai_lokal->NO_TASPEN) == "") 
			$data["NO_TASPEN"]	=	isset($pegawai->noTaspen)	?	trim($pegawai->noTaspen) : 	"";
		if(trim($pegawai_lokal->NOMOR_HP) == "") 
			$data["NOMOR_HP"]	=	isset($pegawai->noHp)	?	$pegawai->noHp : 	"";
		if($pegawai_lokal->NOMOR_SK_CPNS == "") 
			$data["NOMOR_SK_CPNS"]	=	isset($pegawai->nomorSkCpns)	?	$pegawai->nomorSkCpns : 	"";
		if($pegawai_lokal->NOMOR_SK_CPNS == "") 
			$data["NOMOR_SK_CPNS"]	=	isset($pegawai->nomorSkCpns)	?	$pegawai->nomorSkCpns : 	"";
		if($pegawai_lokal->NPWP == "") 
			$data["NPWP"]	=	isset($pegawai->noNpwp)	?	$pegawai->noNpwp : 	"";
		if($pegawai_lokal->PENDIDIKAN == "" or $pegawai->pendidikanTerakhirId != "") 
			$data["PENDIDIKAN"]	=	isset($pegawai->pendidikanTerakhirId)	?	$pegawai->pendidikanTerakhirId : 	"";
		
		if($pegawai_lokal->SATUAN_KERJA_INDUK_ID == "") 
			$data["SATUAN_KERJA_INDUK_ID"]	=	isset($pegawai->satuanKerjaIndukId)	?	$pegawai->satuanKerjaIndukId : 	"";
		if($pegawai_lokal->SATUAN_KERJA_INDUK_NAMA == "") 
			$data["SATUAN_KERJA_INDUK_NAMA"]	=	isset($pegawai->satuanKerjaIndukNama)	?	$pegawai->satuanKerjaIndukNama : 	"";
		if($pegawai_lokal->SATUAN_KERJA_KERJA_ID == "") 
			$data["SATUAN_KERJA_KERJA_ID"]	=	isset($pegawai->satuanKerjaKerjaId)	?	trim($pegawai->satuanKerjaKerjaId) : 	"";
		if(trim($pegawai_lokal->SATUAN_KERJA_NAMA) == "") 
			$data["SATUAN_KERJA_NAMA"]	=	isset($pegawai->satuanKerjaKerjaNama)	?	trim($pegawai->satuanKerjaKerjaNama) : 	"";
		if($pegawai_lokal->STATUS_CPNS_PNS == "") 
			$data["STATUS_CPNS_PNS"]	=	isset($pegawai->statusPegawai)	?	$pegawai->statusPegawai : 	"";
		if(trim($pegawai_lokal->STATUS_HIDUP) == "") 
			$data["STATUS_HIDUP"]	=	isset($pegawai->statusHidup)	?	$pegawai->statusHidup : 	"";
		if(trim($pegawai_lokal->TEMPAT_LAHIR) == "") 
			$data["TEMPAT_LAHIR"]	=	isset($pegawai->tempatLahir)	?	$pegawai->tempatLahir : 	"";
		if(trim($pegawai_lokal->TEMPAT_LAHIR_ID) == "") 
			$data["TEMPAT_LAHIR_ID"]	=	isset($pegawai->tempatLahirId)	?	$pegawai->tempatLahirId : 	"";
		if($pegawai_lokal->TGL_BEBAS_NARKOBA == "") 
			$data["TGL_BEBAS_NARKOBA"]	=	isset($pegawai->tglSuratKeteranganBebasNarkoba)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganBebasNarkoba)) : 	"";
		if($pegawai_lokal->TGL_CATATAN_POLISI == "") 
			$data["TGL_CATATAN_POLISI"]	=	isset($pegawai->tglSkck)	?	date('Y-m-d', strtotime($pegawai->tglSkck)) : 	"";
		if(trim($pegawai_lokal->TGL_LAHIR) == "") 
			$data["TGL_LAHIR"]	=	isset($pegawai->tglLahir)	?	date('Y-m-d', strtotime($pegawai->tglLahir)) : 	"";
		if($pegawai_lokal->TGL_MENINGGAL == "") 
			$data["TGL_MENINGGAL"]	=	isset($pegawai->tglMeninggal)	?	date('Y-m-d', strtotime($pegawai->tglMeninggal)) : 	"";
		if($pegawai_lokal->TGL_NPWP == "") 
			$data["TGL_NPWP"]	=	isset($pegawai->tglNpwp)	?	date('Y-m-d', strtotime($pegawai->tglNpwp)) : 	"";
		if($pegawai_lokal->TGL_SURAT_DOKTER == "") 
			$data["TGL_SURAT_DOKTER"]	=	isset($pegawai->tglSuratKeteranganDokter)	?	date('Y-m-d', strtotime($pegawai->tglSuratKeteranganDokter)) : 	"";
		if($pegawai_lokal->TK_PENDIDIKAN == "" or $pegawai->tkPendidikanTerakhirId != "") 
			$data["TK_PENDIDIKAN"]	=	isset($pegawai->tkPendidikanTerakhirId)	?	$pegawai->tkPendidikanTerakhirId : 	"";
		if($pegawai_lokal->TMT_CPNS == "") 
			$data["TMT_CPNS"]	=	isset($pegawai->tmtCpns)	?	date('Y-m-d', strtotime($pegawai->tmtCpns)) : 	"";
		if($pegawai_lokal->TGL_SK_CPNS == "") 
			$data["TGL_SK_CPNS"]	=	isset($pegawai->tglSkCpns)	?	date('Y-m-d', strtotime($pegawai->tglSkCpns)) : 	"";
		if($pegawai_lokal->TMT_GOLONGAN == "" or $pegawai->tmtGolAkhir != "") 
			$data["TMT_GOLONGAN"]	=	isset($pegawai->tmtGolAkhir)	?	date('Y-m-d', strtotime($pegawai->tmtGolAkhir)) : 	"";
		if($pegawai_lokal->TMT_JABATAN == "" or $pegawai->tmtJabatan != "") 
			$data["TMT_JABATAN"]	=	isset($pegawai->tmtJabatan)	?	date('Y-m-d', strtotime($pegawai->tmtJabatan)) : 	"";
		if($pegawai_lokal->LOKASI_KERJA_ID == "") 
			$data["LOKASI_KERJA_ID"]	=	isset($pegawai->lokasiKerjaId)	?	$pegawai->lokasiKerjaId : 	"";
		if($pegawai_lokal->UNOR_ID == "") 
			$data["UNOR_ID"]	=	isset($pegawai->tmtPns)	?	$pegawai->tmtPns : 	"";
		if($pegawai_lokal->UNOR_ID == "") 
			$data["UNOR_ID"]	=	isset($pegawai->unorId)	?	$pegawai->unorId : 	"";
		if($pegawai_lokal->UNOR_INDUK_ID == "") 
			$data["UNOR_INDUK_ID"]	=	isset($pegawai->unorIndukId)	?	$pegawai->unorIndukId : 	"";

		if(isset($pegawai_lokal->NIP_BARU)){
			$this->load->helper('aplikasi');
			$perubahan = arrDiff($pegawai_lokal,$data);
			if(isset($pegawai->id) and $pegawai->id != "") {
				if($this->pegawai_model->update_where("NIP_BARU",$pegawai_lokal->NIP_BARU, $data))
				{
					echo "Berhasil update data";
					log_activity($this->auth->user_id(),"Update Data Pegawai sync BKN NIP ".$pegawai_lokal->NIP_BARU.', data update : '.json_encode($perubahan), 'pegawai');
				}	
			}else{
				echo "Data BKN tidak didapatkan..";
			}
			
		}else{
			if($this->pegawai_model->insert($data)){
				{
					echo "Berhasil Tambah data";
				}
			}
		}
	}
	private function get_data_skp($nip = "",$tahun = ""){
		$this->load->library('Api_skp');
		$api_skp = new Api_skp;
		$json_skp = $api_skp->getnilai_skp(trim($nip),$tahun);
		$data_skp = json_decode($json_skp);
		return $data_skp;
	}
	private function generateskp($nip = "",$tahun = ""){
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$record_skp = $this->cek_prestasi_kerja($nip,$tahun);
		$id_data = "";
		if(isset($record_skp->ID)){
			$id_data = $record_skp->ID;
		}
		$this->pegawai_model->where("NIP_BARU",$nip);
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $dataskp = $this->get_data_skp($nip,$tahun);
        if($pegawai_data->PNS_ID != ""){
        	$data = array();
	        $data["PNS_ID"] = $pegawai_data->PNS_ID;
	        $data["PNS_NAMA"] = $pegawai_data->NAMA;
	        $data["PNS_NIP"] = $pegawai_data->NIP_BARU;
	        $data['TAHUN'] 		= $tahun;
	        $data['NILAI_SKP'] = $dataskp->nilaiSKP;
	        $data['NILAI_PROSENTASE_SKP'] = $dataskp->nilaiBobotSKP;
	        $data['NILAI_SKP_AKHIR'] = $data['NILAI_PROSENTASE_SKP']/100*$data['NILAI_SKP'];

	        $data['NILAI_PROSENTASE_PERILAKU'] = $dataskp->nilaiBobotPerilaku;
	        $data['NILAI_PERILAKU'] = $dataskp->nilaiPerilaku;
	        $data['PERILAKU_KOMITMEN'] = $dataskp->nilaiDetailPerilaku->Komitmen;
	        $data['PERILAKU_INTEGRITAS'] = $dataskp->nilaiDetailPerilaku->Integritas;
	        $data['PERILAKU_DISIPLIN'] = $dataskp->nilaiDetailPerilaku->Disiplin;
	        $data['PERILAKU_KERJASAMA'] = $dataskp->nilaiDetailPerilaku->Kerjasama;
	        $data['PERILAKU_ORIENTASI_PELAYANAN'] = $dataskp->nilaiDetailPerilaku->{'Orientasi Pelayanan'};

	         
	        $data['NILAI_PERILAKU_AKHIR'] = $dataskp->nilaiAkhirPerilaku;
	        $data['NILAI_PPK'] = $dataskp->nilaiPrestasiKerja;
	        if(isset($id_data) && !empty($id_data)){
	            $this->riwayat_prestasi_kerja_model->update($id_data,$data);
				log_activity($this->auth->user_id(), 'Insert data Riwayat Prestasi Kerja: ' . $id_data . ' : ' . $this->input->ip_address(), 'pegawai');  
	        }
	        else {
				$id = $this->riwayat_prestasi_kerja_model->insert($data);	
				log_activity($this->auth->user_id(), 'Insert data Riwayat Prestasi Kerja: ' . $id . ' : ' . $this->input->ip_address(), 'pegawai');  
			}
        }
	}
	private function cek_prestasi_kerja($nip = "",$tahun = ""){
		$this->load->model('pegawai/riwayat_prestasi_kerja_model');
		$this->riwayat_prestasi_kerja_model->where("TAHUN",$tahun);
		$data_prestasi_kerja = $this->riwayat_prestasi_kerja_model->find_by("PNS_NIP",$nip);
		return $data_prestasi_kerja;
	}
	public function createakun()
    {
    	$this->auth->restrict($this->permissionCreateUser);
    	$nip 	= trim($this->input->post('kode'));
    	$this->load->model('users/user_model');   
    	$this->load->model('users/roles_user_model');   
    	$pegawai_data = $this->pegawai_model->find_by("NIP_BARU",$nip);
    	if($pegawai_data->PNS_ID != ""){

    	}
    	date_default_timezone_set("Asia/Bangkok");
    	if (!$this->input->is_ajax_request()) {
   			die("Only ajax request");
		}
		
        
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if($pegawai_data->EMAIL == ""){
        	$response['msg'] = "
                Silahkan lengkapi data email terlebih dahulu
            ";
            echo json_encode($response);
            exit();
        }
        
        $datauser = $this->user_model->find_by("username",$nip);
        $result = false;
        if(!isset($datauser->id)){
        	$password = "Data.12345";
            $result = $this->save_user($nip,$password,$nip,$pegawai_data->NAMA,$pegawai_data->EMAIL);
            if($result){
            	log_activity($this->auth->user_id(), 'Create User from list pegawai ID : '.$result." IP ".$this->input->ip_address(), 'users');
            	$id_roles_users = $this->save_user_role($result,"2");
            	log_activity($this->auth->user_id(), 'Create roles User from list pegawai : '.$result."_".$id_roles_users." IP ".$this->input->ip_address(), 'users');
            }
        }else{
        	$response['msg'] = "User telah terdaftar";
            echo json_encode($response);
            exit();
        }
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Buat user berhasil, Username ".$nip.", Password : Data.12345";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Ada kesalahan";
    	}
        echo json_encode($response);    
    }
    private function save_user($username = "",$password = "",$nip = "",$nama = "",$email = "")
    {
     	
        $data =  array();
        $data['timezone']       = "UP7";
        $data['username']       = trim($username);
        $data['password']       = trim($password); 
        $data['active']         = 1; 
        $data['nip']            = trim($nip);
        $data['display_name']   = trim($nama);
        $data['email']          = trim($email);
        $data['role_id']        = "2";
        $return = $this->user_model->insert($data);
        
        return $return;

    }
    public function reset_password()
    {
     	$nip 	= trim($this->input->post('kode'));
        $data =  array();
        $data['password']       = trim("Data.12345"); 
        $data['active']         = 1; 
        $this->user_model->skip_validation(true);
        $result = $this->user_model->update_where("username",$nip,$data);
        if($result){
 	       	$response ['success']= true;
    		$response ['msg']= "Password berhasil direset menjadi : Data.12345";
    	}else{
    		$response ['success']= false;
    		$response ['msg']= "Reset Password gagal";
    	}
    	echo json_encode($response);
    	exit();
    }
    private function save_user_role($user_id = "",$role_id = "")
    {
     	
        $data =  array();
        $data['user_id']          = $user_id;
        $data['role_id']       = $role_id; 
        $this->roles_user_model->skip_validation(true);
        $return = $this->roles_user_model->insert($data);
        return $return;

    }
    public function download_updatemandiri(){
		$advanced_search_filters  = $_GET;
        $this->load->model('pegawai/update_mandiri_model');
		$this->db->start_cache();
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip']),"BOTH");	
			}
			if($filters['status_cb'] != ''){
				if ($filters['status_cb'] == -1)
					$this->update_mandiri_model->where('STATUS is null');	
				else 
					$this->update_mandiri_model->where('STATUS',$filters['status_cb']);	
			}
			if($filters['data']){
				$this->update_mandiri_model->where('NAMA_KOLOM',$filters['data']);	
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nama_key']).'%\'');	
			}
			if($filters['golongan_key']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
			if($filters['kawasans']){
				if (is_array($filters['kawasans']) && sizeof($filters['kawasans']) > 0)
				$this->update_mandiri_model->where_in("pegawai.kawasan_id",$filters['kawasans']);	
				
				if (!is_array($filters['kawasans']))
				$this->update_mandiri_model->where("pegawai.kawasan_id",$filters['kawasans']);	
			}
			
			if($filters['pengelolasdm'] != ""){
				if ($filters['pengelolasdm'] == 1){
					$this->db->group_start();
					$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);
					$this->pegawai_model->or_where('pengelola_sdm_kawasan_id is null');
					$this->db->group_end();
				} else 
				$this->pegawai_model->where('pengelola_sdm_kawasan_id',$filters['pengelolasdm']);	
			}
		}
		
		$this->db->stop_cache();
		$output=array();
		$datapegwai = $this->update_mandiri_model->find_notif();
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'template_update_mandiri.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d M Y"),
        )); 

        $output_file_name = 'request_update_mandiri_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        ob_end_clean();
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function download_updatemandiri1(){
		$advanced_search_filters  = $_GET;
        $this->load->model('pegawai/update_mandiri_model');
		if($advanced_search_filters){
			$filters = $advanced_search_filters;
			if($filters['unit_id_key']){
				$this->db->group_start();
				$this->db->where('vw."ID"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_1"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_2"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_3"',$filters['unit_id_key']);	
				$this->db->or_where('vw."ESELON_4"',$filters['unit_id_key']);	
				$this->db->group_end();
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->like('upper(pegawai."NAMA")',strtoupper($filters['nama_key']),"BOTH");	
			}
			if($filters['nip']){
				$this->update_mandiri_model->like('upper("NIP_BARU")',strtoupper($filters['nip']),"BOTH");	
			}
			if($filters['data']){
				$this->update_mandiri_model->where('NAMA_KOLOM',$filters['data']);	
			}
			if($filters['status_cb'] != ''){
				if ($filters['status_cb'] == -1)
					$this->update_mandiri_model->where('STATUS is null');	
				else 
					$this->update_mandiri_model->where('STATUS',$filters['status_cb']);	
			}
			if($filters['nama_key']){
				$this->update_mandiri_model->where('upper("NAMA") LIKE \''.strtoupper($filters['nama_key']).'%\'');	
			}
			if($filters['golongan_key']){
				$this->update_mandiri_model->where('"GOL_ID"',strtoupper($filters['golongan_key']));	
			}
			if($filters['tingkat_pendidikan']){
				$this->update_mandiri_model->where('"TK_PENDIDIKAN"',strtoupper($filters['tingkat_pendidikan']));	
			}
			if($filters['level']){
				$this->update_mandiri_model->where("LEVEL_UPDATE",$filters['level']);	
			}
			
		}
		$kawasan_sdms = json_decode($this->auth->kawasan_sdms());
		
		if ($kawasan_sdms){
			if (in_array(1,json_decode($kawasan_sdms))){
				$this->db->group_start();
				$this->update_mandiri_model->where_in('pegawai.pengelola_sdm_kawasan_id',json_decode($kawasan_sdms));
				$this->update_mandiri_model->or_where('pegawai.pengelola_sdm_kawasan_id is null');
				$this->db->group_end();
			} else {
				$this->update_mandiri_model->where_in('pegawai.pengelola_sdm_kawasan_id',json_decode($kawasan_sdms));
			}
		} else {
			$this->update_mandiri_model->where_in('pegawai.kawasan_id',json_decode($this->auth->kawasans()));	
		}
		$output=array();
		$datapegwai = $this->update_mandiri_model->find_notif1();
        $this->load->library('LibOpenTbs');
        $template_name = trim($this->settings_lib->item('site.pathuploaded')).'template_update_mandiri.xlsx';
        $TBS = $this->libopentbs->TBS;
        $TBS->LoadTemplate($template_name, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
        $TBS->MergeBlock('r', $datapegwai);
        $TBS->MergeField('a', array(
            'tanggal'=>date("d M Y"),
        )); 

        $output_file_name = 'request_update_mandiri_pegawai.xlsx';
        $output_file_name = str_replace('.', '_'.date('Y-m-d').'.', $output_file_name);
        ob_end_clean();
        $TBS->Show(OPENTBS_DOWNLOAD, $output_file_name); // Also merges all [onshow] automatic fields.
	}
	public function listultah()
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('toolbar_title', "Pegawai Ulang Tahun");
		
        Template::render();
    }
	public function getdataultah(){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_ultah($this->UNOR_ID);

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_ultah($this->UNOR_ID);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_ultah($this->UNOR_ID);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbypendidikan($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Pegawai Pendidikan");
		Template::set_view('pegawai/kepegawaian/listpendidikan');
        Template::render();
    }
    public function getdatapendidikan($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_pendidikan($this->UNOR_ID,$tingkat);

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_all_pendidikan($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_pendidikan($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->NAMA_PENDIDIKAN;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbygolongan($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Pegawai Golongan");
		Template::set_view('pegawai/kepegawaian/listbygolongan');
        Template::render();
    }
    public function getdatabygolongan($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_golongan($this->UNOR_ID,$tingkat);

		/*Ketika dalam mode pencarian, berarti kita harus
		'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
		yang mengandung keyword tertentu
		*/
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_all_golongan($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_golongan($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->NAMA_GOLONGAN;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listpensiuntahun($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Pegawai Pensiun");
        Template::render();
    }
    public function getdatapensiuntahun($tahun = ""){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$output=array();
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_estimasi_pensiun_tahun($this->UNOR_ID,$tahun);
		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->pegawai_model->count_all_estimasi_pensiun_tahun($this->UNOR_ID,$tahun);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_estimasi_pensiun_tahun($this->UNOR_ID,$tahun);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='70%'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->NAMA_JABATAN." (".$record->PENSIUN.")";
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                 
                $row[] = implode(" ",$btn_actions);
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbyumur($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Pegawai Umur");
        Template::render();
    }
    public function getdatabyumur($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		 
		
		$output=array();
		

		/*Jika $search mengandung nilai, berarti user sedang telah 
		memasukan keyword didalam filed pencarian*/
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_umur($this->UNOR_ID,$tingkat);

		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_all_umur($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_umur($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->umur;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}

	private function simpanKepakaran($nip_baru, $kepakarans,$mandiri = null)
	{
		$this->load->model('pegawai/kepakaran_pegawai_model');
		$this->load->model('masters/bidang_kepakaran_model');
		$this->load->helper('aplikasi');
		// if (empty($kepakaran)){
			$this->kepakaran_pegawai_model->where('bidang_penelitian is NULL');
			$this->kepakaran_pegawai_model->delete_where(array('nip' => $nip_baru));
			log_activity($this->auth->user_id(), 'Hapus Semua Bidang Kepakaran NIP :'.$nip_baru, 'pegawai');
		// }
		foreach ($kepakarans as $kepakaran) {
			$pakar = $this->bidang_kepakaran_model->find_detail_by_code($kepakaran);
			$this->kepakaran_pegawai_model->where('bidang_penelitian is NULL');
			$cekdata = $this->kepakaran_pegawai_model->find_by(array('nip'=>$nip_baru, 'kodebidang' => $kepakaran));
			if ($pakar[0]){
				$data = array();
				$pakar = $pakar[0];
				$data['kodebidang'] = $kepakaran;
				$data['koderumpun'] = $pakar->kode_rumpun;
				$data['nip'] 		= $nip_baru;
				$data['bidang_kepakaran'] = $pakar->id;
				$data['rumpun_kepakaran'] = $pakar->id_rumpun;
				$data['nip'] 		= $nip_baru;
				if ($cekdata){			
					$perubahan = arrDiff($cekdata,$data);		
					$data['tanggalupdate'] = date('Y-m-d h:i:s');
					$this->kepakaran_pegawai_model->update($cekdata->id,$data);
					log_activity($this->auth->user_id(), 'Update Bidang Kepakaran NIP :'.$nip_baru.' perubahan '.json_encode($perubahan), 'pegawai');
				} else {
					$data['tanggalpengajuan'] = date('Y-m-d h:i:s');
					$this->kepakaran_pegawai_model->insert($data);
					log_activity($this->auth->user_id(), 'Tambah Bidang Kepakaran NIP :'.$nip_baru.' data '.json_encode($data), 'pegawai');
				}
			}
		}
	}
	public function listbykatjabatan($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Pegawai Jabatan");
        Template::render();
    }
    public function getdatabykatjabatan($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$output=array();
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_katjabatan($this->UNOR_ID,$tingkat);

		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_all_katjabatan($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_katjabatan($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->KATEGORI_JABATAN;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbykeljabatan($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Pegawai Jabatan");
        Template::render();
    }
    public function getdatabykeljabatan($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		$tingkat = urldecode($tingkat);
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$output=array();
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_keljabatan($this->UNOR_ID,$tingkat);

		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			//$this->pegawai_model->or_where('NIP_BARU',$search);
			$jum	= $this->pegawai_model->count_all_keljabatan($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_keljabatan($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->kel_jabatan;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbystatuspegawai($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Status Pegawai");
        Template::render();
    }
    public function getdatabystatus($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		$tingkat = urldecode($tingkat);
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$output=array();
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_status($this->UNOR_ID,$tingkat);

		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->pegawai_model->count_all_status($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_status($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->kel_jabatan;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbyagama($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Agama Pegawai");
        Template::render();
    }
    public function getdatabyagama($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		$tingkat = urldecode($tingkat);
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$output=array();
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_agama($this->UNOR_ID,$tingkat);

		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->pegawai_model->count_all_agama($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_agama($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->nama_agama;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	public function listbyjeniskelamin($tingkat = "")
    {	
    	$this->auth->restrict($this->permissionView);
    	Template::set('tingkat', $tingkat);
    	Template::set('toolbar_title', "Agama Pegawai");
        Template::render();
    }
    public function getdatabyjk($tingkat){
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		$tingkat = urldecode($tingkat);
		$length= $this->input->post('length');
		$start= $this->input->post('start');
		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$output=array();
		if($search!=""){
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
		}
		
		$this->pegawai_model->limit($length,$start);
		/*Urutkan dari alphabet paling terkahir*/
		$kolom = $iSortCol != "" ? $iSortCol : "NAMA";
		$sSortCol == "asc" ? "asc" : "desc";
		$this->pegawai_model->order_by($iSortCol,$sSortCol);
		$records=$this->pegawai_model->find_all_jenis_kelamin($this->UNOR_ID,$tingkat);

		if($search != "")
		{
			$this->pegawai_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
			$this->pegawai_model->or_where('upper("NIP_BARU") LIKE \''.strtoupper($search).'%\'');
			$jum	= $this->pegawai_model->count_all_jenis_kelamin($this->UNOR_ID,$tingkat);
			$output['recordsTotal']=$output['recordsFiltered']=$jum;
		}else{
			$total= $this->pegawai_model->count_all_jenis_kelamin($this->UNOR_ID,$tingkat);
			$output['draw']=$draw;
			$output['recordsTotal']= $output['recordsFiltered']=$total;
			$output['data']=array();

		}
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $foto_pegawai =  END_POINT_PHOTO.$record->username_intra;
                $row = array();
                $row []  = $nomor_urut.".";
                $row []  = "<img src='".$foto_pegawai."' width='90px'>";
                $row []  = $record->NIP_BARU;
                $row []  = $record->NAMA;
                $row []  = $record->TGL_LAHIR;
                $row []  = $record->JENIS_KELAMIN;
                $row []  = $record->NAMA_UNOR;
                
                $btn_actions = array();
                $btn_actions  [] = "<a href='".base_url()."admin/kepegawaian/pegawai/profilen/".$record->ref."' class='btn btn-sm btn-info'><i class='glyphicon glyphicon-user'></i> </a>"; 
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();

	}
	private function simpanPenelitian($nip_baru, $penelitians,$mandiri = null)
	{
		$this->load->model('pegawai/kepakaran_pegawai_model');
		$this->load->model('masters/bidang_penelitian_model');
		$this->load->helper('aplikasi');
		// if (empty($penelitians)){
			$this->kepakaran_pegawai_model->where('bidang_penelitian is not NULL');
			$this->kepakaran_pegawai_model->delete_where(array('nip' => $nip_baru));
			log_activity($this->auth->user_id(), 'Hapus Semua Bidang Penelitian NIP :'.$nip_baru, 'pegawai');
		// }
		foreach ($penelitians as $penelitian) {
			$pakar = $this->bidang_penelitian_model->find_detail_by_code($penelitian);
			$this->kepakaran_pegawai_model->where('bidang_penelitian is NULL');
			$cekdata = $this->kepakaran_pegawai_model->find_by(array('nip'=>$nip_baru, 'kodepenelitian' => $penelitian));
			if ($pakar[0]){
				$data = array();
				$pakar = $pakar[0];
				$data['kodepenelitian'] = $penelitian;
				$data['kodebidang'] = $pakar->kode_bidang_kepakaran;
				$data['koderumpun'] = $pakar->kode_rumpun;
				$data['nip'] 		= $nip_baru;
				$data['bidang_penelitian'] = $pakar->id;
				$data['bidang_kepakaran'] = $pakar->id_bidang_kepakaran;
				$data['rumpun_kepakaran'] = $pakar->id_rumpun;
				$data['nip'] 		= $nip_baru;
				if ($cekdata){			
					$perubahan = arrDiff($cekdata,$data);		
					$data['tanggalupdate'] = date('Y-m-d h:i:s');
					$this->kepakaran_pegawai_model->update($cekdata->id,$data);
					log_activity($this->auth->user_id(), 'Update Bidang Kepakaran NIP :'.$nip_baru.' perubahan '.json_encode($perubahan), 'pegawai');
				} else {
					$data['tanggalpengajuan'] = date('Y-m-d h:i:s');
					$this->kepakaran_pegawai_model->insert($data);
					log_activity($this->auth->user_id(), 'Tambah Bidang Kepakaran NIP :'.$nip_baru.' perubahan '.json_encode($data), 'pegawai');
				}
			}
		}
	}
	public function ajax_belum_diverif()
	{
		$this->auth->restrict($this->permissionPeremajaanView);
    	$this->load->model('pegawai/update_mandiri_model');
    	if (!$this->input->is_ajax_request()) {
   			Template::set_message("Hanya request ajax", 'error');
            redirect(SITE_AREA . '/kepegawaian/pegawai');
		}
		$draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
		
		$length= $this->input->post('length') != "" ? $this->input->post('length') : 10;
		$start= $this->input->post('start') != "" ? $this->input->post('start') : 0;
		$PNS_ID = $this->input->post('PNS_ID');
		$kolom = $this->input->post('DATA');
		$this->db->start_cache();
		$this->update_mandiri_model->where('STATUS is null');
		$this->update_mandiri_model->where('pegawai.PNS_ID',$PNS_ID);
		$this->update_mandiri_model->where('NAMA_KOLOM',$kolom);
		$this->db->stop_cache();
		$output=array();
		$output['draw']=$draw;
		$total= $this->update_mandiri_model->count_notif_pribadi();
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
		
		$this->update_mandiri_model->limit($length,$start);

		$records=$this->update_mandiri_model->find_notif_pribadi();
		$this->db->flush_cache();
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
				$row = array();
				if ($this->auth->has_permission('Pegawai.Pdm.Cancel') || $this->auth->username() == $record->NIP_BARU){
					$row []  = "<input name='pdm_id[]' class='mycheck' type='checkbox' value='".$record->ID."'>";
				}
                $row []  = $nomor_urut.".";
                $row []  = $record->UPDATE_TGL;
                $row []  = $record->NAMA_KOLOM;
                $btn_actions = array();
				if ($this->auth->has_permission('Pegawai.Pdm.Cancel') || $this->auth->username() == $record->NIP_BARU){
					$btn_actions  [] = "<a href='#' kode='".$record->ID."' class='btn btn-sm btn-danger btn-cancel-pdm' data-toggle='tooltip' title='Batalkan perubahan' tooltip='Batalkan perubahan' ><i class='fa fa-ban'></i> batalkan</a>";
				}
				if (trim($record->NAMA_KOLOM) == "RIWAYAT_PENDIDIKAN") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatpendidikan/verifikasi2/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KEPANGKATAN") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkepangkatan/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_JABATAN") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatjabatan/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "DATA_PASANGAN") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/data_keluarga/verifikasi_pasangan/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "DATA_ORANG_TUA") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/data_keluarga/verifikasi_ortu/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "DATA_ANAK") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/data_keluarga/verifikasi_anak/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "DIKLAT_STRUKTURAL") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/diklatstruktural/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "DIKLAT_FUNGSIONAL") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/diklatfungsional/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KURSUS") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkursus/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PPK") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatprestasikerja/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PPK") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatprestasikerja/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_AK") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayat_angka_kredit/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_PENGHARGAAN") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatpenghargaan/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_KGB") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayat_kgb/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_TUBEL") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayat_tugasbelajar/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_CUTI") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayat_cltn/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "KARYA_TULIS") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatkaryatulisilmiah/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "AKTIVITAS_ILMIAH") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayataktivitasilmiah/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "RIWAYAT_ORGANISASI") {
					$btn_actions[] = "<a href='" . base_url() . "pegawai/riwayatorganisasi/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info modal-custom-global pull-right'><i class='fa fa-search'></i> Detail</a>";
				} else if (trim($record->NAMA_KOLOM) == "ARSIP_DIGITAL") {
					$btn_actions[] = "<a href='" . base_url() . "arsip_digital/arsip/verifikasi/" . $record->ID . "/true' class='btn btn-sm btn-info show-modal pull-right'><i class='fa fa-search'></i> Detail</a>";
				}
                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
	}

	public function ajax_count_pdm()
	{
		if (!$this->input->is_ajax_request()) {
			Template::set_message("Hanya request ajax", 'error');
		 	redirect(SITE_AREA . '/kepegawaian/pegawai');
	 	}
		$this->load->model('pegawai/update_mandiri_model');
		$PNS_ID = $this->input->post('PNS_ID');
		$data = $this->update_mandiri_model->find_belum_diverifikasi($PNS_ID);
		echo json_encode($data);
	}
    
}

	
