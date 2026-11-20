<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatorganisasi extends Admin_Controller
{
    protected $permissionCreate = 'RiwayatOrganisasi.Kepegawaian.Create';
    protected $permissionDelete = 'RiwayatOrganisasi.Kepegawaian.Delete';
    protected $permissionEdit   = 'RiwayatOrganisasi.Kepegawaian.Edit';
    protected $permissionView   = 'RiwayatOrganisasi.Kepegawaian.View';
    protected $permissionUpdateMandiri   = 'RiwayatOrganisasi.Kepegawaian.UpdateMandiri';

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_organisasi_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('masters/country_model');
        $countries = $this->country_model->find_all();
		Template::set('countries', $countries);
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');		
    }
    public function ajax_list(){
        $this->load->library('convert');
 		$convert = new convert();
        $draw = $this->input->post('draw');
		$iSortCol = $this->input->post('iSortCol_1');
		$sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        $pegawai = $this->pegawai_model->find_by("PNS_ID",$PNS_ID);
       
		$length= $this->input->post('length');
		$start= $this->input->post('start');

		$search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
		$this->riwayat_organisasi_model->where("nip",$pegawai->NIP_BARU);
		$total= $this->riwayat_organisasi_model->count_all();;
		$output=array();
		$output['draw']=$draw;

		
		$output['recordsTotal']= $output['recordsFiltered']=$total;
		$output['data']=array();
        
		$this->riwayat_organisasi_model->where("nip",$pegawai->NIP_BARU); 
        if($search!=""){
			// $this->riwayat_organisasi_model->where('upper("nomor_sk") LIKE \''.strtoupper($search).'%\'');
		}
		$this->riwayat_organisasi_model->limit($length,$start);
        $records=$this->riwayat_organisasi_model->find_all();
		$this->load->helper('aplikasi');
		$nomor_urut=$start+1;
		if(isset($records) && is_array($records) && count($records)):
			foreach ($records as $record) {
                $tgl_selesai =  $record->tanggal_selesai?' s.d. '.getIndonesiaFormat($record->tanggal_selesai):' s.d. sekarang';
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->tanggal_mulai? (getIndonesiaFormat($record->tanggal_mulai).$tgl_selesai) : '-';
                $row []  = $record->nama_organisasi;
                $row []  = $record->posisi;
                $row []  = $record->lokasi;
                $row []  = $record->sponsor;
                $btn_actions = array();
                if($record->id_arsip != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionUpdateMandiri))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatorganisasi/editmandiri/".$pegawai->NIP_BARU.'/'.$record->id."' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data mandiri' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/riwayatorganisasi/edit/".$record->id."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->id' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-organisasi'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";
                

                $output['data'][] = $row;
				$nomor_urut++;
			}
		endif;
		echo json_encode($output);
		die();
    }
    public function show($NIP_BARU = "",$record_id='',$mandiri = null){
        Template::set('mandiri', $mandiri);
        if (empty($record_id)){
            if (!$mandiri)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_organisasi_crud");
            Template::set('NIP_BARU', $NIP_BARU);
            Template::set('toolbar_title', "Tambah Riwayat Organisasi");
            Template::render();
        } else {
            if (!$mandiri)
                $this->auth->restrict($this->permissionEdit);
            $this->riwayat_organisasi_model->where("id",$record_id);
            $tb_data = $this->riwayat_organisasi_model->first_row();  
            Template::set_view("kepegawaian/riwayat_organisasi_crud");
            Template::set('detail_riwayat', $tb_data);    
            Template::set('NIP_BARU', $tb_data->nip);
            Template::set('toolbar_title', "Ubah Riwayat Organisasi"); 
            Template::render();
        }
    }
     
    public function add($NIP_BARU = ""){
        $this->show($NIP_BARU);
    }
    public function addmandiri($NIP_BARU = ""){
        $this->show($NIP_BARU,'',1);
    }
    public function edit($ID=''){
        $this->show('',$ID);
    }
    public function editmandiri($NIP_BARU,$ID=''){
        $this->show($NIP_BARU,$ID,1);
    }
    public function verifikasi($ID,$read_only = false)
    {
        $data = $this->update_mandiri_model->find($ID);
        $perubahan = json_decode($data->PERUBAHAN);
        $PNS_ID = $data->PNS_ID;
        $pegawai = $this->pegawai_model->find_by("PNS_ID",$PNS_ID);
        $NIP_BARU = $pegawai->NIP_BARU;
        if (!$read_only)
                $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only',$read_only);
        Template::set('id_update_mandiri', $ID);
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }
        if (empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_organisasi_crud");
            Template::set('detail_riwayat', $perubahan); 
            Template::set('NIP_BARU', $NIP_BARU);
            Template::set('toolbar_title', "Tambah Riwayat Organisasi");
            Template::render();
        } else {
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            $this->riwayat_organisasi_model->where("id",$data->ID_TABEL);
            $tb_data = $this->riwayat_organisasi_model->first_row();  
            if (!$tb_data)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $datadetil = array_replace((array) $tb_data,(array) $perubahan);
            $datadetil = (object) $datadetil;
            Template::set_view("kepegawaian/riwayat_organisasi_crud");
            Template::set('detail_riwayat', $tb_data);    
            Template::set('NIP_BARU', $tb_data->nip);
            Template::set('toolbar_title', "Ubah Riwayat Organisasi"); 
            Template::render();
        }
    }
    public function save(){
        //Validate the data
        $this->form_validation->set_rules($this->riwayat_organisasi_model->get_validation_rules());
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
		
		
        $data = $this->riwayat_organisasi_model->prep_data($this->input->post());
        $mandiri = $this->input->post("mandiri");
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name  = $_FILES['file_dokumen']['name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];
            $keterangan = $this->input->post('scientific_activity_name');
            $content    = file_get_contents($file_tmp);
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($this->input->post('nip'),$file_name,153,$keterangan, $content,$file_name,!$mandiri);
            if ($id){
                $data['id_arsip'] = $id;
            } else {
                if (empty($data['id_arsip']))
                    unset($data['id_arsip']);
            }

            if(in_array(strtolower(end($file_ext)),$allowed_ext) === false)
            {
                $errors[]='Extension not allowed';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>Extension not allowed</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
            if($file_size > 50097152)
            {
                $errors[]= 'File size must be under 50mb';
                $response['msg'] = "
                <div class='alert alert-block alert-error fade in note note-danger'>
                    <a class='close' data-dismiss='alert'>&times;</a>
                    <h4 class='alert-heading'>
                        Ada kesalahan
                    </h4>
                    <p>File size must be under 50Mb</p>
                </div>
                ";
                echo json_encode($response);
                exit();
            }
        } else {
            if ($mandiri && empty($data['id_arsip'])){
                $response ['success']= false;
                $response ['msg']= "<div class='alert alert-block alert-error fade in note note-danger'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                Harap lampirkan bukti dukung
                </h4>
                </div>";
                echo json_encode($response);
                die;
            }
        }
        
        // end upload berkas
       	$this->pegawai_model->where("NIP_BARU",$this->input->post("nip"));
        $pegawai_data = $this->pegawai_model->find_first_row();  
        $data['pns_id'] = $pegawai_data->PNS_ID;
        $id_data = $this->input->post("id");
        if(empty($id_data)){
            foreach ($data as $key => $value) {
                if (empty($data[$key]))
                    unset($data[$key]);
            }
        } else {
            foreach ($data as $key => $value) {
                if ($data[$key] == "")
                    $data[$key] = null;
            }
        }
        if (!$mandiri){
            if(isset($id_data) && !empty($id_data)){
                $data['modified_date']    = date("Y-m-d");
                $data['modified_by']    = $this->auth->username();
                $current = $this->riwayat_organisasi_model->find($id_data);
                $perubahan = arrDiff($current,$data);
                $id = $this->riwayat_organisasi_model->update($id_data,$data);
                if ($id)
                log_activity($this->auth->user_id(), 'Update data Riwayat Organisasi : ' . $pegawai_data->NIP_BARU . ' perubahan : '.json_encode($perubahan).' ' . $this->input->ip_address(), 'pegawai');
            } else {
                $data['created_date']  = date("Y-m-d");
                $data['created_by']    = $this->auth->username();
                $id = $this->riwayat_organisasi_model->insert($data);
                if ($id)
                log_activity($this->auth->user_id(), 'Insert data Riwayat Organisasi : '  . $pegawai_data->NIP_BARU . ' data : '.json_encode($data).' ' . $this->input->ip_address(), 'pegawai');
            }
        } else {
            if(isset($id_data) && !empty($id_data)){
                $current = $this->riwayat_organisasi_model->find($id_data);
                $perubahan = arrDiff($current,$data, true);
                if ($perubahan[1] != '[]'){
                    $data_update = array();
                    $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
                    $data_update['KOLOM']       = "RIWAYAT_ORGANISASI";
                    $data_update['DARI']        = $perubahan[0];
                    $data_update['PERUBAHAN']   = $perubahan[1];
                    $data_update['NAMA_KOLOM']  = "RIWAYAT_ORGANISASI";
                    $data_update['LEVEL_UPDATE']= 3;
                    $data_update['ID_TABEL']    = $id_data;
                    $data_update['ID_ARSIP']    = $data['id_arsip'];
                    $data_update['UPDATE_TGL']  = date("Y-m-d");
                    $data_update['UPDATED_BY']  = $this->auth->user_id();
                    $id_update = $this->update_mandiri_model->insert($data_update);
                }
            }
            else{
                $data_update = array();
                $data_update['PNS_ID']      = $pegawai_data->PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_ORGANISASI";
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "RIWAYAT_ORGANISASI";
                $data_update['LEVEL_UPDATE']= 3;
                $data_update['ID_ARSIP']    = $data['id_arsip'];
                $data_update['UPDATE_TGL']  = date("Y-m-d");
                $data_update['UPDATED_BY']  = $this->auth->user_id();
                $id_update = $this->update_mandiri_model->insert($data_update);
            }
        }
        if ($id_update_mandiri){
            $data_update = array();
            $data_update['VERIFIKASI_BY']	= $this->auth->user_id();
            $data_update['VERIFIKASI_TGL']	= date("Y-m-d");
            $data_update['STATUS']			= 1;
            $this->update_mandiri_model->update($id_update_mandiri,$data_update);
            log_activity($this->auth->user_id(), 'Verifikasi update mandiri id : ' . $id_update_mandiri . ' STATUS : 1 ' . $this->input->ip_address(), 'pegawai');

            if ($data['id_arsip']){
                $this->load->model('arsip_digital/arsip_digital_model');
                $this->arsip_digital_model->update($data['id_arsip'], array('ISVALID'=>1));
            }
        }
        $msgtb = "";
        $response ['success']= true;
        $response ['msg']= "Berhasil ".$msgtb;
        echo json_encode($response);    

    }
    public function delete($record_id = null){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_organisasi_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat Organisasi : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($NIP_BARU=''){
        Template::set_view("kepegawaian/tab_pane_organisasi");
        Template::set('NIP_BARU', $NIP_BARU);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    private function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_organisasi_model->find($id);
        $this->load->model('arsip_digital/arsip_digital_model');
        $arsip = $this->arsip_digital_model->find($datadetil->id_arsip);
        if ($arsip){
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $aws->viewdoc($arsip->location.$arsip->name);
        }
        die();
    }
}
