<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Kepegawaian controller
 */
class Riwayatpenghargaan extends Admin_Controller
{
    protected $permissionCreate = 'Riwayatpenghargaan.Kepegawaian.Create';
    protected $permissionDelete = 'Riwayatpenghargaan.Kepegawaian.Delete';
    protected $permissionEdit   = 'Riwayatpenghargaan.Kepegawaian.Edit';
    protected $permissionView   = 'Riwayatpenghargaan.Kepegawaian.View';
    protected $permissionUpdmandiri   = 'Riwayatpenghargaan.Kepegawaian.UpdateMandiri';
    protected $permissionVerifikasi   = 'Riwayatpenghargaan.Kepegawaian.Verifikasi';
    

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pegawai/riwayat_penghargaan_model');
        $this->load->model('pegawai/pegawai_model');
        $this->load->model('pegawai/jenis_penghargaan_model');
        $this->load->model('pegawai/update_mandiri_model');
        $this->load->helper('aplikasi');
        
    }
    public function ajax_list(){
        $draw = $this->input->post('draw');
        $iSortCol = $this->input->post('iSortCol_1');
        $sSortCol = $this->input->post('sSortDir_1');
        $PNS_ID = $this->input->post('PNS_ID');
        
        $length= $this->input->post('length');
        $start= $this->input->post('start');

        $search = isset($_REQUEST['search']["value"]) ? $_REQUEST['search']["value"] : "";
        $this->riwayat_penghargaan_model->where("rwt_penghargaan.PNS_ID",$PNS_ID);
        $total= $this->riwayat_penghargaan_model->count_all();;
        $output=array();
        $output['draw']=$draw;

        
        $output['recordsTotal']= $output['recordsFiltered']=$total;
        $output['data']=array();


        /*Jika $search mengandung nilai, berarti user sedang telah 
        memasukan keyword didalam filed pencarian*/
        if($search!=""){
            $this->riwayat_penghargaan_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
        }
        $this->riwayat_penghargaan_model->limit($length,$start);
        /*Urutkan dari alphabet paling terkahir*/
        $kolom = $iSortCol != "" ? $iSortCol : "NAMA";
        $sSortCol == "asc" ? "asc" : "desc";
        $this->riwayat_penghargaan_model->order_by($iSortCol,$sSortCol);
        $this->riwayat_penghargaan_model->order_by("SK_TANGGAL","asc");
        $this->riwayat_penghargaan_model->where("rwt_penghargaan.PNS_ID",$PNS_ID);    
        $records=$this->riwayat_penghargaan_model->find_all();

        /*Ketika dalam mode pencarian, berarti kita harus
        'recordsTotal' dan 'recordsFiltered' sesuai dengan jumlah baris
        yang mengandung keyword tertentu
        */
        if($search != "")
        {
            $this->riwayat_penghargaan_model->where('upper("NAMA") LIKE \''.strtoupper($search).'%\'');
            $jum    = $this->riwayat_penghargaan_model->count_all();
            $output['recordsTotal']=$output['recordsFiltered']=$jum;
        }
        
        $nomor_urut=$start+1;
        if(isset($records) && is_array($records) && count($records)):
            foreach ($records as $record) {
                $row = array();
                $row []  = $nomor_urut;
                $row []  = $record->jenis_penghargaan_text;
                $row []  = $record->KETERANGAN;
                $row []  = $record->SK_NOMOR;
                $row []  = $record->SK_TANGGAL;
                // $row []  = $record->NOMOR_IJASAH;
                
                $btn_actions = array();
                if($record->id_arsip != ""){
                    $btn_actions  [] = "<a href='".base_url()."admin/arsip/arsip_digital/viewdoc2/".$record->id_arsip."' data-toggle='tooltip' title='Lihat Dokumen' tooltip='Lihat Dokumen' class='btn btn-sm btn-info modal-custom-global'><i class='glyphicon glyphicon-eye-open'></i> </a>";
                }
                /*
                $btn_actions  [] = "
                    <a class='show-modal-custom' href='".base_url()."pegawai/Riwayatpenghargaan/detil/".$PNS_ID."/".$record->ID."'  data-toggle='tooltip' title='Lihat detil'><span class='fa-stack'>
                        <i class='fa fa-square fa-stack-2x'></i>
                        <i class='fa fa-eye fa-stack-1x fa-inverse'></i>
                        </span>
                        </a>
                ";
                
                */
                // if($this->auth->has_permission($this->permissionVerifikasi))
                // {    
                //     $btn_actions  [] = "<a href='".base_url()."pegawai/Riwayatpenghargaan/verifikasi/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Verifikasi data' tooltip='Verifikasi data' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-edit'></i> </a>";
                // }
                if(!$record->sync_bkn && $this->auth->has_permission($this->permissionUploadBkn))
                {    
                    $btn_actions  [] = "<a href='#' kode='{$record->ID}' data-toggle='tooltip' title='Upload BKN' tooltip='Sync Bkn' class='btn btn-sm btn-light btn-upload-bkn'><i class='fa fa-upload'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionUpdmandiri))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/Riwayatpenghargaan/editmandiri/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data mandiri' tooltip='Ubah data mandiri' class='btn btn-sm btn-warning show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionEdit))
                {    
                    $btn_actions  [] = "<a href='".base_url()."pegawai/Riwayatpenghargaan/edit/".$PNS_ID."/".$record->ID."' data-toggle='tooltip' title='Ubah data' tooltip='Ubah data' class='btn btn-sm btn-success show-modal-custom'><i class='fa fa-pencil'></i> </a>";
                }
                if($this->auth->has_permission($this->permissionDelete))
                {    
                    $btn_actions  [] = "<a href='#' kode='$record->ID' data-toggle='tooltip' title='Hapus Data' class='btn btn-sm btn-danger btn-hapus-penghargaan'><i class='fa fa-trash-o'></i> </a>";
                }

                $row[] = "<div class='btn-group'>".implode(" ",$btn_actions)."</div>";

                 

                $output['data'][] = $row;
                $nomor_urut++;
            }
        endif;
        echo json_encode($output);
        die();
    }
    public function show($PNS_ID,$record_id='',$mandiri = null){
        $penghargaans = $this->jenis_penghargaan_model->find_all();
        Template::set('penghargaans', $penghargaans);
        Template::set('mandiri', $mandiri);
        //
        if(empty($record_id)){
            if (!$mandiri)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_penghargaan_add");
            Template::set('jenis_penghargaans', $penghargaans);
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Penghargaan");

            Template::render();
        }
        else {
            if (!$mandiri)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_penghargaan_add");
            $detail_riwayat = $this->riwayat_penghargaan_model->find($record_id);
            $selectedPendidikanID = $this->jenis_penghargaan_model->find(trim($detail_riwayat->ID));
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('selectedPenghargaanID',$this->jenis_penghargaan_model->find(trim($detail_riwayat->ID)));
            Template::set('jenis_penghargaans', $penghargaans);
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Penghargaan");

            Template::render();
        }
    }
    public function add($PNS_ID){
        $this->show($PNS_ID);
    }
    public function addmandiri($PNS_ID){
        $this->auth->restrict($this->permissionUpdmandiri);
        $this->show($PNS_ID,'',1);
    }
    public function edit($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id);
    }
    public function editmandiri($PNS_ID,$record_id=''){
        $this->show($PNS_ID,$record_id,1);
    }
    public function verifikasi($ID,$read_only = false){
        $data = $this->update_mandiri_model->find($ID);
        $perubahan = json_decode($data->PERUBAHAN);
        $PNS_ID = $data->PNS_ID;
        if (!$read_only)
                $read_only = $data->STATUS != '' && 
            ($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
            || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3'));
        Template::set('read_only',$read_only);
        $jenis_penghargaans = $this->jenis_penghargaan_model->find_all();
        Template::set('jenis_penghargaans', $jenis_penghargaans);
        
        $penghargaans = $this->jenis_penghargaan_model->find_all();
        Template::set('penghargaans', $penghargaans);
        Template::set('id_update_mandiri', $ID);
        // cek orang lain lihat arsip orang
        if ($read_only && !($this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate') 
        || $this->auth->has_permission('Pegawai.Kepegawaian.VerifikasiUpdate3')) && $this->auth->username()!=$data->NIP_BARU){
            die();
        }
        //
        if(empty($data->DARI)){
            if (!$read_only)
                $this->auth->restrict($this->permissionCreate);
            Template::set_view("kepegawaian/riwayat_penghargaan_add");
            
            Template::set('detail_riwayat', $perubahan);
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Tambah Riwayat Penghargaan");

            Template::render();
        }
        else {
            if (!$read_only)
                $this->auth->restrict($this->permissionEdit);
            Template::set_view("kepegawaian/riwayat_penghargaan_add");
            $detail_riwayat = $this->riwayat_penghargaan_model->find($data->ID_TABEL);
            if (!$detail_riwayat)
                Template::set_message('Data Tidak Ditemukan/Data Telah Dihapus', 'error');
            $detail_riwayat = array_replace((array) $detail_riwayat,(array) $perubahan);
            $detail_riwayat = (object) $detail_riwayat;
            Template::set('detail_riwayat', $detail_riwayat);    
            Template::set('PNS_ID', $PNS_ID);
            Template::set('toolbar_title', "Ubah Riwayat Penghargaan");

            Template::render();
        }
    }
    public function detil($PNS_ID,$record_id=''){
        // $tingkatpendidikans = $this->tingkatjenis_penghargaan_model->find_all();
		// Template::set('tk_pendidikans', $tingkatpendidikans);
		// Template::set_view("kepegawaian/detilpendidikan");
		// Template::set('detail_riwayat', $this->riwayat_penghargaan_model->find($record_id));    
		// Template::set('PNS_ID', $PNS_ID);
		// Template::set('toolbar_title', "Detil Riwayat Penghargaan");

		// Template::render();
    }
    public function save(){
        if (!$this->input->is_ajax_request()) {
            die("Only ajax request");
        }
         // Validate the data
        $this->form_validation->set_rules($this->riwayat_penghargaan_model->get_validation_rules());
        $response = array(
            'success'=>false,
            'msg'=>'Unknown error'
        );
        if ($this->form_validation->run() === false) {
            $response['msg'] = "
            <div class='alert alert-block alert-error fade in'>
                <a class='close' data-dismiss='alert'>&times;</a>
                <h4 class='alert-heading'>
                    Error validation
                </h4>
                ".validation_errors()."
            </div>
            ";
            echo json_encode($response);
            exit();
        }
        $data = $this->riwayat_penghargaan_model->prep_data($this->input->post());
        $mandiri = $this->input->post("mandiri");
        if (!$mandiri)
            $this->auth->restrict($this->permissionCreate);
        $id_update_mandiri = $this->input->post("id_update_mandiri");
        // Make sure we only pass in the fields we want
        $base64     = "";
        $file_ext   = "";
        $file_size  = "";
        $file_tmp   = "";
        $type       = "";
        $pegawai = $this->pegawai_model->find_by('PNS_ID',$this->input->post('PNS_ID'));
        $data['PNS_NIP'] = $pegawai->NIP_BARU;
        if (isset($_FILES['file_dokumen']) && $_FILES['file_dokumen']['name']) 
        {
            $errors=array();
            $allowed_ext = array('pdf');
            $file_name =$_FILES['file_dokumen']['name'];
            $file_ext   = explode('.',$file_name);
            $jmltitik   = count($file_ext);
            $file_size  = $_FILES['file_dokumen']['size'];
            $file_tmp   = $_FILES['file_dokumen']['tmp_name'];
            $type       = $_FILES['file_dokumen']['type'];

            $content    = file_get_contents($file_tmp);
            $keterangan = $data['KETERANGAN'];
            $this->load->library('Api_s3_aws');
            $aws = new Api_s3_aws;
            $id = $aws->insertArsip($pegawai->NIP_BARU,$data['SK_NOMOR'],49,$keterangan, $content,$file_name,!$mandiri);
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
        
        $id_data = $this->input->post("id_data");
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
                $this->auth->restrict($this->permissionEdit);
                $current = $this->riwayat_penghargaan_model->find($id_data);
                $perubahan = arrDiff($current,$data);
                $this->riwayat_penghargaan_model->update($id_data,$data);
                log_activity($this->auth->user_id(), 'Update data Riwayat penghargaan: ' . $pegawai->NIP_BARU . ' perubahan : '.json_encode($perubahan). ' '. $this->input->ip_address(), 'pegawai');  
                
            }
            else {
                $id = $this->riwayat_penghargaan_model->insert($data);
                log_activity($this->auth->user_id(), 'Insert data Riwayat penghargaan: ' . $pegawai->NIP_BARU . ' data : '.json_encode($data). '  ' . $this->input->ip_address(), 'pegawai');  
            }
        } else {
            if (isset($id_data) && !empty($id_data)) {
                $current = $this->riwayat_penghargaan_model->find($id_data);
                $perubahan = arrDiff($current,$data, true);
                if ($perubahan[1] != '[]'){
                    $data_update = array();
                    $data_update['PNS_ID']      = $pegawai->PNS_ID;
                    $data_update['KOLOM']       = "RIWAYAT_PENGHARGAAN";
                    $data_update['DARI']        = $perubahan[0];
                    $data_update['PERUBAHAN']   = $perubahan[1];
                    $data_update['NAMA_KOLOM']  = "RIWAYAT_PENGHARGAAN";
                    $data_update['LEVEL_UPDATE']= 3;
                    $data_update['ID_TABEL']    = $id_data;
                    $data_update['ID_ARSIP']    = $data['id_arsip'];
                    $data_update['UPDATE_TGL']  = date("Y-m-d");
                    $data_update['UPDATED_BY']  = $this->auth->user_id();
                    $id_update = $this->update_mandiri_model->insert($data_update);
                }
            } else {
                $data_update = array();
                $data_update['PNS_ID']      = $pegawai->PNS_ID;
                $data_update['KOLOM']       = "RIWAYAT_PENGHARGAAN";
                $data_update['PERUBAHAN']   = json_encode($data);
                $data_update['NAMA_KOLOM']  = "RIWAYAT_PENGHARGAAN";
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
        $response ['success']= true;
        $response ['msg']= "Transaksi berhasil";
        echo json_encode($response);    
    }
    public function delete($record_id){
        $this->auth->restrict($this->permissionDelete);
		if ($this->riwayat_penghargaan_model->delete($record_id)) {
			 log_activity($this->auth->user_id(), 'delete data Riwayat penghargaan : ' . $record_id . ' : ' . $this->input->ip_address(), 'pegawai');
			 Template::set_message("Sukses Hapus data", 'success');
			 echo "Sukses";
		}else{
			echo "Gagal";
		}

		exit();
    }
    public function index($PNS_ID=8){
        Template::set_view("kepegawaian/tab_pane_riwayat_penghargaan");
        Template::set('PNS_ID', $PNS_ID);
        Template::set('TAB_ID', uniqid("TAB_CONTOH"));
        Template::render();
    }
    private function viewdoc($id)
    {
        if (empty($id)) {
            Template::set_message("ID tidak ditemukan", 'error');
        }
        $datadetil = $this->riwayat_penghargaan_model->find($id);
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
