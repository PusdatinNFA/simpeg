<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayat_pns extends Admin_Controller
{
    protected $permissionCreate = 'Riwayatpns.Kepegawaian.View';
    protected $permissionDelete = 'Riwayatpns.Kepegawaian.View.Delete';
    protected $permissionEdit   = 'Riwayatpns.Kepegawaian.View.Edit';
    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/Riwayat_pns_cpns_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');
    }
     
    
    public function save(){
         // Validate the data
        $this->form_validation->set_rules($this->Riwayat_pns_cpns_model->get_validation_rules());
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
		
		
        $data = $this->Riwayat_pns_cpns_model->prep_data($this->input->post());
       	$this->pegawai_model->where("PNS_ID",$this->input->post("PNS_ID"));
        $mandiri = $this->input->post('mandiri');
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data["PNS_NIP"]    = $pegawai_data->NIP_BARU;
        $data["PNS_ID"]     = $pegawai_data->PNS_ID;
        
        $data['TMT_CPNS']  = $this->input->post('TMT_CPNS') ? $this->input->post('TMT_CPNS') : null;
        $data['TGL_SK_CPNS']  = $this->input->post('TGL_SK_CPNS') ? $this->input->post('TGL_SK_CPNS') : null;

        $data['TGL_SPMT']  = $this->input->post('TGL_SPMT') ? $this->input->post('TGL_SPMT') : null;
        $data['TMT_PNS']  = $this->input->post('TMT_PNS') ? $this->input->post('TMT_PNS') : null;
        $data['TGL_SK_PNS']  = $this->input->post('TGL_SK_PNS') ? $this->input->post('TGL_SK_PNS') : null;

        $data['TGL_PERTEK_C2TH']  = $this->input->post('TGL_PERTEK_C2TH') ? $this->input->post('TGL_PERTEK_C2TH') : null;
        $data['TGL_KEP_HONORER_2TAHUN']  = $this->input->post('TGL_KEP_HONORER_2TAHUN') ? $this->input->post('TGL_KEP_HONORER_2TAHUN') : null;

        $data['TGL_STTPL']  = $this->input->post('TGL_STTPL') ? $this->input->post('TGL_STTPL') : null;
        $data['TGL_DOKTER']  = $this->input->post('TGL_DOKTER') ? $this->input->post('TGL_DOKTER') : null;

        $id_data = $this->input->post("ID_PNS_CPNS");
        // regular update
        if (!$mandiri){
            if(isset($id_data) && !empty($id_data)){
                $this->Riwayat_pns_cpns_model->update($id_data,$data);
                $ID = $id_data;
            }
            else {
                $ID = $this->Riwayat_pns_cpns_model->insert($data);
            }

            if($this->input->post('KARPEG') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["KARTU_PEGAWAI"] =   $this->input->post('KARPEG');
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'update karpeg from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $this->input->post('KARPEG') . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            if($this->input->post('KARIS_KARSU') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["kartu_istri"] =   $this->input->post('KARIS_KARSU');
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'update karis/karsu from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $this->input->post('KARPEG') . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            if($this->input->post('NO_SK_CPNS') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["NOMOR_SK_CPNS"] =   $data['NO_SK_CPNS'];
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'Update NO_SK_CPNS from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $data['NO_SK_CPNS'] . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            if($this->input->post('NO_SK_PNS') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["NO_SK_PNS"] =   $data['NO_SK_PNS'];
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'Update NO_SK_PNS from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $data['NO_SK_PNS'] . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            if($this->input->post('TMT_CPNS') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["TMT_CPNS"] =   $data['TMT_CPNS'];
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'Update TMT_CPNS from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $data['TMT_CPNS'] . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            if($this->input->post('TMT_PNS') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["TMT_PNS"] =   $data['TMT_PNS'];
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'Update TMT_PNS from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $data['TMT_PNS'] . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            if($this->input->post('TGL_SK_CPNS') != ""){
                $data_pegawai_update       = array();
                $data_pegawai_update["TGL_SK_CPNS"] =   $data['TGL_SK_CPNS'];
                if($this->pegawai_model->update_where("NIP_BARU",$pegawai_data->NIP_BARU, $data_pegawai_update))
                {
                    log_activity($this->auth->user_id(), 'Update TGL_SK_CPNS from rwt pns  nip : '.$pegawai_data->NIP_BARU.' : ' . $data['TGL_SK_CPNS'] . ' : ' . $this->input->ip_address(), 'pegawai');
                }   
            }
            $response ['success']= true;
            $response ['kode']= $ID;
            $response ['msg']= "Berhasil";
            echo json_encode($response);  
        } else {
            // self update
            if(isset($id_data) && !empty($id_data)){
                $current = $this->Riwayat_pns_cpns_model->find($id_data);
                $perubahan = arrDiff($current,$data,true);
                $data_update = array();
                if ($perubahan[0] != '[]'){
                    $data_update['PNS_ID']		= $pegawai_data->PNS_ID;
                    $data_update['KOLOM']		= 'RIWAYAT_PNS';
                    $data_update['DARI']		= $perubahan[0];
                    $data_update['PERUBAHAN']	= $perubahan[1];
                    $data_update['NAMA_KOLOM']	= 'RIWAYAT_PNS';
                    $data_update['LEVEL_UPDATE']= 3;
                    $data_update['UPDATE_TGL']	= date("Y-m-d");
                    $data_update['ID_TABEL']    = $id_data;
                    $data_update['UPDATED_BY']	= $this->auth->user_id();
                    $id_update = $this->update_mandiri_model->insert($data_update);
                }
            }
            else {
                $data_update = array();
                $data_update['PNS_ID']		= $pegawai_data->PNS_ID;
                $data_update['KOLOM']		= 'RIWAYAT_PNS';
                $data_update['DARI']		= null;
                $data_update['PERUBAHAN']	= json_encode($data);
                $data_update['NAMA_KOLOM']	= 'RIWAYAT_PNS';
                $data_update['LEVEL_UPDATE']= 3;
                $data_update['UPDATE_TGL']	= date("Y-m-d");
                $data_update['UPDATED_BY']	= $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }

            $perubahan = arrDiff($pegawai_data,$_POST,true);
            if ($perubahan[0] != '[]'){
                $data_update = array();
                $data_update['PNS_ID']		= $pegawai_data->PNS_ID;
                $data_update['KOLOM']		= 'DATA_PERSONAL';
                $data_update['DARI']		= $perubahan[0];
                $data_update['PERUBAHAN']	= $perubahan[1];
                $data_update['NAMA_KOLOM']	= 'DATA_PERSONAL';
                $data_update['LEVEL_UPDATE']= 3;
                $data_update['UPDATE_TGL']	= date("Y-m-d");
                $data_update['UPDATED_BY']	= $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }

            $response ['success']= true;
            $response ['msg']= "Berhasil";
            echo json_encode($response); 
        }  

    }
    public function saveimport(){
         // Validate the data
        $year = $this->input->post("TAHUN");
        $this->load->library('Apiserviceassesment');
        $Classassesment = new Apiserviceassesment();
        $dataassesment  = $Classassesment->getdataassesmentTahun(trim($year));
        //print_r($dataassesment);
        //die($year);
        $dataassesment = json_decode($dataassesment);
        $output['recordsTotal']= 0;
        $data = array();
        if($dataassesment->status == true){
           foreach($dataassesment->data as $mydata)
            {
                if($mydata->nip == "196712311990031011"){
                    //echo json_encode($mydata->saranpengembangan);
                    //die();
                }
                $record['PNS_NIP'] = $mydata->nip;
                $record['TAHUN'] = $mydata->tahun_penilaian_awal;
                $record['NILAI'] = $mydata->nilai_potensi;
                $record['NILAI_KINERJA'] = $mydata->nilai_kinerja;
                $record['TAHUN_PENILAIAN_ID'] = $mydata->tahun_penilaian_id;
                $record['TAHUN_PENILAIAN_TITLE'] = $mydata->tahun_penilaian_title;

                $record['FULLNAME'] = $mydata->fullname;
                $record['POSISI_ID'] = $mydata->posisi_id;
                $record['UNIT_ORG_ID'] = $mydata->unit_org_kd;
                $record['NAMA_UNOR'] = $mydata->nama_unor;
                $record['SARANPENGEMBANGAN'] = strip_tags(json_encode($mydata->saranpengembangan));
                $data[] = $record;
            }
            $this->db->insert_batch("rwt_assesmen",$data);
        }else{
            $response ['success']= false;
            $response ['msg']= "Data tidak ditemukan";
            echo json_encode($response);    
            exit();
        }
        

        
        $response ['success']= true;
        $response ['msg']= "Berhasil";
        echo json_encode($response);    

    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
        $this->load->helper('handle_upload');
        $datadetil = $this->riwayat_assesmen_model->find($record_id);
        $file_upload = $datadetil->FILE_UPLOAD != "" ? trim($datadetil->FILE_UPLOAD) : ""; 
        deletefile($file_upload,trim($this->settings_lib->item('site.pathassesment')));

		if ($this->riwayat_assesmen_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Kursus : ' . $record_id . ' : ' . $this->input->ip_address(), 'riwayat_assesmen');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function deletefile($record_id){
        $this->auth->restrict($this->permissionDelete);
        $this->load->helper('handle_upload');
        $datadetil = $this->riwayat_assesmen_model->find($record_id);
        $file_upload = $datadetil->FILE_UPLOAD != "" ? trim($datadetil->FILE_UPLOAD) : ""; 
        if(deletefile($file_upload,trim($this->settings_lib->item('site.pathassesment')))){
            echo "Sukses";
        }else{
            echo "Gagal";
        }
        exit();
    }
    public function index($PNS_ID='1552260645'){
        Template::set_view("kepegawaian/tab_pane_riwayat_assesmen");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    function uploadberkas(){
        $tahun = $this->input->post('tahun');
        $kode = $this->input->post('kode');
        $satker = $this->input->post('satker');
        $kolom = $this->input->post('kolom');
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error',
            'namafile'=>''
        );

        $this->load->helper('handle_upload');
         $uploadData = array();
         $upload = true;
         $id = "";
         $namafile = "";
         if (isset($_FILES['userfile']) && is_array($_FILES['userfile']) && $_FILES['userfile']['error'] != 4)
         {
            $tmp_name = pathinfo($_FILES['userfile']['name'], PATHINFO_FILENAME);
            $uploadData = handle_upload('userfile',trim($this->settings_lib->item('site.pathassesment')));
             if (isset($uploadData['error']) && !empty($uploadData['error']))
             {
                $tipefile=$_FILES['userfile']['type'];
                //$tipefile = $_FILES['userfile']['name'];
                 $upload = false;
                 log_activity($this->auth->user_id(), 'Gagal : '.$uploadData['error'].$tipefile.$this->input->ip_address(), 'pegawai');
             }else{
                
                $namafile = $uploadData['data']['file_name'];
             }
         }else{
            die("File tidak ditemukan");
            log_activity($this->auth->user_id(), 'File tidak ditemukan : ' . $this->input->ip_address(), 'pegawai');
         }  
        //print_r($uploadData);
        //die(trim($this->settings_lib->item('site.pathassesment')));
        echo $namafile;
        //echo json_encode($response);    
       exit();
    }
}
